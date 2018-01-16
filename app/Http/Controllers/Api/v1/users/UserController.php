<?php

namespace App\Http\Controllers\Api\v1\users;

use App\Jobs\SendSamplePush;
use App\Rules\OlderThan;
use App\Rules\phone;
use App\Traits\ApiResponse;
use App\Models\User;
use App\Utils\AppConstant;
use App\Traits\EmojiRemover;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


/**
 * @resource Users
 *
 * The user controller resource ships with some of the basic functionality that user always have in any app like update password, fetching user profile, creating profile or updating profile.
 * */

class UserController extends Controller
{
	use ApiResponse;
	use EmojiRemover;

	/**
	 * Update Password
	 *
	 * Handles Update Password requests.
	 *
	 */

    public function updatePassword(Request $request)
    {
	    $oldPassword = $request->oldPassword;
	    $newPassword = $request->newPassword;

	    $validator = Validator::make([
		    'oldPassword' => $oldPassword,
		    'newPassword' => $newPassword
	    ], [
		    'oldPassword' => 'required',
		    'newPassword' => 'required|different:oldPassword'
	    ], [
		    'newPassword.different' => 'Current password and new password must be different.'
	    ]);

	    if ($validator->fails()) {
		    $this->setMeta('status', AppConstant::STATUS_FAIL);
		    $this->setMeta('message', $validator->messages()->first());
		    return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
	    }
	    $user = $request->user;
	    if (!Hash::check($oldPassword, $user->password)) {
		    $this->setMeta('status', AppConstant::STATUS_FAIL);
		    $this->setMeta('message', __('auth.old_password_invalid'));
		    return response()->json($this->setResponse(), AppConstant::UNAUTHORIZED);
	    }
	    $user->password = Hash::make($newPassword);
	    $user->save();

	    $this->setMeta('status', AppConstant::STATUS_OK);
	    $this->setMeta('message', __('auth.password_update_success'));
	    $this->setData('user', $user);
	    return response()->json($this->setResponse(), AppConstant::OK);
    }

	/**
	 * Create Profile
	 *
	 * Handles Create Profile requests.
	 *
	 */

    public function createProfile(Request $request)
    {
	    $validator = Validator::make($request->all(), [
		    'lastName' => 'nullable',
		    'mobileNo' => ['nullable', 'numeric', 'unique:users,mobileNo,' . $request->user["id"], new phone(10)],
		    'profilePic' => 'nullable|image|mimes:jpg,png,jpeg',
		    'address' => 'required|regex:/([- ,\/0-9a-zA-Z]+)/',
		    'dob' => ['nullable', 'date', 'date_format:d-m-Y', new olderThan(13)],
	    ],[
	    	'address.regex' => 'Address field has invalid characters'
	    ]);

	    if ($validator->fails()) {
		    $this->setMeta('status', AppConstant::STATUS_FAIL);
		    $this->setMeta('message', $validator->messages()->first());
		    return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
	    }
    	try{
	    	$user = $request->user;
	    	if($user)
		    {
		    	DB::beginTransaction();
			    if ( $request->filled( 'lastName' ) ) {
				    $user->lastName = $request->lastName;
			    }
			    if ( $request->filled( 'mobileNo' ) ) {
				    $user->mobileNo = $request->mobileNo;
			    }
			    if ( $request->file( 'profilePic' ) ) {
				    $pathInfo = $request->profilePic->storeAs(
					    'users/' . $user->id, uniqid("IMG_", 15) . "." . $request->profilePic->getClientOriginalExtension(),'public');
				    $user->profilePic = $pathInfo;
				    Log::info(asset('storage/'.$pathInfo));
			    }
			    $user->address = $this->removeEmoji($request->address);
			    if ( $request->filled( 'dob' ) ) {
				    $user->dob = $request->dob;
			    }
			    $user->save();
			    DB::commit();
		    }

	    } catch (QueryException $e)
	    {
	    	DB::rollback();
		    $this->setMeta('status', AppConstant::STATUS_FAIL);
		    $this->setMeta('message', __('auth.server_error'));
		    //$this->setMeta('message', $e);
		    return response()->json( $this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR );
	    }
	    $this->setMeta('status', AppConstant::STATUS_OK);
	    $this->setMeta('message', __('app.profile_created'));
	    $this->setData('user', $user);
	    return response()->json( $this->setResponse(), AppConstant::OK );
    }

	/**
	 * Get Profile
	 *
	 * Handles Get Profile of logged in user or requested user.
	 *
	 */

    public function getProfile(Request $request, $uuid=null)
    {
	    if($uuid)
	    {
	    	$user = User::where([
	    		'uuid'=> $uuid,
	    		'status'=> AppConstant::STATUS_ACTIVE,
		    ])->first();
	    } else {
		    $user = $request->user;
	    }
    	try{
    		if($user)
		    {
			    $this->setMeta('status', AppConstant::STATUS_OK);
			    $this->setMeta('message', __( 'app.profile_fetched' ) );
			    $this->setData( "user", $user );
			    return response()->json( $this->setResponse(), AppConstant::OK );
		    } else {
			    $this->setMeta('status', AppConstant::STATUS_FAIL);
			    $this->setMeta('message', __( 'auth.user_not_found' ) );
			    return response()->json( $this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST );
		    }

	    } catch (QueryException $e){
		    $this->setMeta('status', AppConstant::STATUS_FAIL);
		    $this->setMeta('message', __('auth.server_error'));
		    //$this->setMeta('message', $e);
		    return response()->json( $this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR );
	    }
    }

	/**
	 * Update Profile
	 *
	 * Handles Update Profile requests.
	 *
	 */

	public function updateProfile(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'firstName' => 'required',
			'lastName' => 'nullable',
			'mobileNo' => ['nullable', 'numeric', 'unique:users,mobileNo,' . $request->user["id"], new phone(10)],
			'profilePic' => 'nullable|image|mimes:jpg,png,jpeg',
			'address' => 'required|regex:/([- ,\/0-9a-zA-Z]+)/',
			'dob' => ['nullable', 'date', 'date_format:d-m-Y', new olderThan(13)],
		],[
			'address.regex' => 'Address field has invalid characters'
		]);

		if ($validator->fails()) {
			$this->setMeta('status', AppConstant::STATUS_FAIL);
			$this->setMeta('message', $validator->messages()->first());
			return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
		}
		try{
			$user = $request->user;
			if($user)
			{
				$user->firstName = $request->firstName;
				if ( $request->filled( 'lastName' ) ) {
					$user->lastName = $request->lastName;
				}
				if ( $request->filled( 'mobileNo' ) ) {
					$user->mobileNo = $request->mobileNo;
				}
				if ( $request->file( 'profilePic' ) ) {
					$pathInfo = $request->profilePic->storeAs(
						'users/' . $user->id, uniqid("IMG_", 15) . "." . $request->profilePic->getClientOriginalExtension(),'public');
					$user->profilePic = $pathInfo;
					Log::info(asset('storage/'.$pathInfo));
				}
				$user->address = $this->removeEmoji($request->address);
				if ( $request->filled( 'dob' ) ) {
					$user->dob = $request->dob;
				}
				$user->save();
				DB::commit();
			}
		} catch (QueryException $e)
		{
			DB::rollback();
			$this->setMeta('status', AppConstant::STATUS_FAIL);
			$this->setMeta('message', __('auth.server_error'));
			//$this->setMeta('message', $e);
			return response()->json( $this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR );
		}
		$this->setMeta('status', AppConstant::STATUS_OK);
		$this->setMeta('message', __('app.profile_updated'));
		$this->setData('user', $user);
		return response()->json( $this->setResponse(), AppConstant::OK );
	}

	/**
	 * Push Notification
	 *
	 * A Demo for push notification.
	 *
	 */
	 public function testPushNotification(Request $request)
	 {
		 $validator = Validator::make($request->all(), [
			 'uuid' => 'required',
		 ]);

		 if ($validator->fails()) {
			 $this->setMeta('status', AppConstant::STATUS_FAIL);
			 $this->setMeta('message', $validator->messages()->first());
			 return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
		 }
		 $user = $request->user;
		 /*send push notification code*/
		 $receiver = User::where('uuid',$request->uuid)->first();
		 $receiveruuid = $receiver->uuid;
                    $data = array(
	                    'uuid' => $user->uuid,
	                    'receiveruuid' => $receiveruuid,
                    );

                    dispatch(new SendSamplePush($data));

		 $this->setMeta('status', AppConstant::STATUS_OK);
		 $this->setMeta('message', __('app.push_send'));
		 return response()->json( $this->setResponse(), AppConstant::OK );
	 }
}
