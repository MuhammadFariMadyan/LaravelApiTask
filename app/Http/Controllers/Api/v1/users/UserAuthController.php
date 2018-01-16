<?php
/**
 * Created by PhpStorm.
 * User: php7
 * Date: 10/1/18
 * Time: 7:04 PM
 */

namespace App\Http\Controllers\Api\v1\users;

use App\Models\User;
use App\Models\UserEmail;
use App\Models\UserMobileNo;
use App\Traits\ApiResponse;
use App\Utils\AppConstant;
use App\Utils\GetTokens;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class UserAuthController
{

    use ApiResponse;

    /* for custom claim jwt token*/

    public function customClaimJWT($claims)
    {
        // you need to pass claims like this
        // $claims = ['id' => $userId, 'user_type' => 'user' ....];
        $customClaims = $claims;
        $payload = JWTFactory::make($customClaims);
        $token = JWTAuth::encode($payload);
        return $token;
    }

    /**
     * Registration
     *
     * Handles User Registration requests.
     *
     */

    public function register(Request $request)
    {

        $validator = Validator::make([
            'email' => $request->email,
            'password' => $request->password,
        ], [
            'email' => 'required|email|unique:users|max:150',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages());

            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }

        //transaction start
        try {

            $password = $request->password;
            $getToken = new GetTokens();
            $emailToken = $getToken->limit(10);
            $emailToken = $emailToken->token;
            $uuid4 = Uuid::uuid4();
            $uuid = $uuid4->toString();

            $user = new User();
            $user->uuid = $uuid;
            $user->email = strtolower($request->email);
            $user->password = Hash::make($password);
            $user->save();

            // generating jwt token
            $token = null;

            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {

                $this->setMeta('status', AppConstant::STATUS_FAIL);
                $this->setMeta('message', __('jwt.jwt_not_got'));
                return response()->json($this->setResponse(), AppConstant::BAD_REQUEST);
            }
        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));

            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }


        $user = User::where('id', $user->id)->first();
        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.register_success'));
        $this->setData("user", $user);
        $this->setData("token", $token);
        // dd($user);
        return response()->json($this->setResponse(), AppConstant::CREATED);

    }

    /**
     * Login
     *
     * Handles User Login requests.
     *
     */
    public function singleLogin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages()->first());

            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }

        try {

            $user = User::where('email', strtolower($request->email))->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                $this->setMeta('status', AppConstant::STATUS_FAIL);
                $this->setMeta('message', __('auth.login_failed'));
                return response()->json($this->setResponse(), AppConstant::UNAUTHORIZED);
            }

            $token = null;
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                $this->setMeta('status', AppConstant::STATUS_FAIL);
                $this->setMeta('message', __('jwt.jwt_not_got'));

                return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
            }

        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }


        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.login_success'));
        $this->setData("user", $user);
        $this->setData("token", $token);
        return response()->json($this->setResponse(), AppConstant::OK);
    }

    /**
     * Alternatic Emails Add
     *
     * Handles User Multiple emails requests.
     *
     */

    public function addEmailOnly(Request $request)
    {
        $user = $request->user;
        $userId = $user->id;

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'alterEmail.*' => 'required|email'
            ], [
                'email.required' => "Emaildasd id required",
                'email.email' => "Please enter valid email in formate",
                'alterEmail.*.email' => "Please enter valid email in formate",
            ]);

        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages()->first());

            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }

        try {
            //            Save EmailData in Table
            foreach ($request->alterEmail as $altEmail) {

                $matchThese = ['user_id' => $userId, 'email' => $altEmail];
                $userEmail = UserEmail::where($matchThese)->first();

                if ($userEmail != null) {
                    $this->setMeta('status', AppConstant::STATUS_FAIL);
                    $this->setMeta('message', __('auth.user_email_exist'));
                    $this->setData("mobileNo", $userEmail->email);
                    return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
                }

                $emailModel = new UserEmail();
                $emailModel->email = $altEmail;
                $emailModel->user_id = $userId;
                $emailModel->save();

            }

        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }

        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.alternate_emails_success'));
        $this->setData("user", $user);
        return response()->json($this->setResponse(), AppConstant::OK);

    }

    /**
     * Alternatic Mobile Add
     *
     * Handles Update User Multiple mobile requests.
     *
     */

    public function addMobileOnly(Request $request)
    {
        $user = $request->user;
        $userId = $user->id;

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'alterMobile.*' => 'required|numeric|digits_between:10,10'
            ], [
                'email.required' => "Email id required",
                'email.email' => "Please enter valid email in formate",
                'alterMobile.*.numeric' => "Please enter only Numeric value",
                'alterMobile.*.digits_between' => "Please enter 10 digit mobile number"
            ]);


        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages()->first());

            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }

        try {
            //            Save MobileData in Table
            foreach ($request->alterMobile as $altMobile) {

                $matchThese = ['user_id' => $userId, 'mobile_no' => $altMobile];

                $userMobile = UserMobileNo::where($matchThese)->first();


                if ($userMobile != null) {
                    $this->setMeta('status', AppConstant::STATUS_FAIL);
                    $this->setMeta('message', __('auth.user_mobile_exist'));
                    $this->setData("mobileNo", $userMobile->mobile_no);
                    return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
                }

                $mobileModel = new UserMobileNo();
                $mobileModel->mobile_no = $altMobile;
                $mobileModel->user_id = $userId;
                $mobileModel->save();

            }

        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }

        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.alternate_mobile_success'));
        $this->setData("user", $user);
        return response()->json($this->setResponse(), AppConstant::OK);
    }

    /**
     * Alternatic Mobile Edit
     *
     * Handles Update User Single mobile requests.
     *
     */
    public function updateEmailOnly(Request $request)
    {

        $user = $request->user;
        $userId = $user->id;

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'oldemail' => 'required|email|exists:user_email,email',
                'newemail' => 'required|email|different:oldemail'
            ], [
                'required' => "Email id required",
                'email' => "Please enter valid email in formate",
                'different' => "New Email id must Different from old Email id",
                'exists' => "Old Email ID is not exist"
            ]);

        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages()->first());
            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }


        try {

            //check new email id already assign to current user
            $checkNewPwd = ['user_id' => $userId, 'email' => $request->newemail];
            $newEmailModel = UserEmail::where($checkNewPwd)->first();

            if ($newEmailModel != null) {
                $this->setMeta('status', AppConstant::STATUS_FAIL);
                $this->setMeta('message', __('auth.user_new_email_exist'));
                return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
            }

            //update EmailData in Table
            $checkOldPwd = ['user_id' => $user->id, 'email' => $request->oldemail];

            $emailModel = UserEmail::where($checkOldPwd)->first();
            $emailModel->email = $request->newemail;
            $emailModel->save();


        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
//            $this->setMeta('message', $e->getMessage());
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }

        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.user_email_updated'));
        $this->setData("user", $user);
        return response()->json($this->setResponse(), AppConstant::OK);

    }


    /**
     * Alternatic Mobile Edit
     *
     * Handles Update User Single mobile requests.
     *
     */
    public function updateMobileOnly(Request $request)
    {

        $user = $request->user;
        $userId = $user->id;

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'oldmobile' => 'required|numeric|digits_between:10,10|exists:user_mobile,mobile_no',
                'newmobile' => 'required|numeric|digits_between:10,10|different:oldmobile'
            ], [
                'required' => "Field required",
                'email' => "Please enter valid email in formate",
                'different' => "New Mobile must Different from old Mobile",
                'exists' => "Old Mobile is not exist",
                'digits_between' => "Please enter 10 digit number"
            ]);

        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages()->first());
            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }


        try {
            //check new mobile id already assign to current user
            $checkNewMobile = ['user_id' => $userId, 'mobile_no' => $request->newmobile];
            $newMobileModel = UserMobileNo::where($checkNewMobile)->first();

            if ($newMobileModel != null) {
                $this->setMeta('status', AppConstant::STATUS_FAIL);
                $this->setMeta('message', __('auth.user_new_mobile_exist'));
                return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
            }

            //update EmailData in Table
            $checkOldMob = ['user_id' => $userId, 'mobile_no' => $request->oldmobile];

            $mobileModel = UserMobileNo::where($checkOldMob)->first();
            $mobileModel->mobile_no = $request->newmobile;
            $mobileModel->save();

        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }

        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.user_mobile_updated'));
        $this->setData("user", $user);
        return response()->json($this->setResponse(), AppConstant::OK);


    }

    /**
     * Alternatic Mobile Delete
     *
     * Handles Delete User Single mobile requests.
     *
     */

    public function deleteMobileOnly(Request $request)
    {

        $user = $request->user;
        $userId = $user->id;

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'mobile' => 'required|numeric|digits_between:10,10|exists:user_mobile,mobile_no',
            ], [
                'required' => "Field required",
                'email' => "Please enter valid email in formate",
                'exists' => "Mobile is not exist",
                'digits_between' => "Please enter 10 digit number"
            ]);

        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages()->first());
            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }

        try {
            //delete Mobile in Table
            $checkmobile = ['user_id' => $userId, 'mobile_no' => $request->mobile];
            UserMobileNo::where($checkmobile)->delete();

        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }

        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.user_mobile_delete'));
        $this->setData("user", $user);
        return response()->json($this->setResponse(), AppConstant::OK);
    }


    /**
     * Alternatic Email Delete
     *
     * Handles Delete User Single Email requests.
     *
     */

    public function deleteEmailOnly(Request $request)
    {

        $user = $request->user;
        $userId = $user->id;

        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'deleteEmail' => 'required|email|exists:user_email,email',
            ], [
                'required' => "Field required",
                'email' => "Please enter valid email in formate",
                'exists' => "Email is not exist"
            ]);

        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages()->first());
            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }


        try {
            //delete Mobile in Table
            $checkEmail = ['user_id' => $userId, 'email' => $request->deleteEmail];
            UserEmail::where($checkEmail)->delete();

        } catch (QueryException $e) {
            echo $e->getMessage();
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }

        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.user_email_delete'));
        $this->setData("user", $user);
        return response()->json($this->setResponse(), AppConstant::OK);


    }

    /**
     * Alternatic get User
     *
     * Handles get user with email and mobile requests.
     *
     */

    public function getUser(Request $request)
    {
        try {

            $userreq = $request->user;
            $userId = $userreq->id;
            $userData = User::find($userId);
            $userData->mobile = User::find($userId)->mobile_no;
            $userData->email = User::find($userId)->emails;

        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }
        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.user_get_success'));
        $this->setData('user', $userData);
        return response()->json($this->setResponse(), AppConstant::OK);

    }


}