<?php

namespace App\Http\Controllers\Api\v1\users;

use App\Jobs\SendForgotPasswordEmail;
use App\Jobs\SendVerificationEmail;
use App\Models\LoginDetail;
use App\Models\User;
use App\Rules\ValidOSType;
use App\Traits\ApiResponse;
use App\Utils\AppConstant;
use App\Utils\GetTokens;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

/**
 * @resource User Authentication
 *
 * The user authentication resource will take care of user authentication cycle which consist of register, login,forgot password, change password, logout.
 * */
class AuthController extends Controller
{
    use ApiResponse;

    /* for custom claim jwt token*/

    public function customClaimJWT($claims)
    {
        // you need to pass claims like this
        // $claims = ['id' => $userId, 'user_type' => 'user' ....];
//        dd($claims);
        //$customClaims = [$claims];
        $customClaims = [$claims];
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
//			'fcmToken' => $request->fcmToken, // if required then set nullable to required
//			'deviceType' => $request->deviceType, // if required then set nullable to required
//			'deviceName' => $request->deviceName, // if required then set nullable to required
//			'ip' => $request->ip, // if required then set nullable to required
            // when required 'os' => ['required', new ValidOSType()],
        ], [
            'email' => 'required|email|unique:users|max:150',
            'password' => 'required'
//			'fcmToken' => 'nullable', // if required then set nullable to required
//			'deviceType' => 'nullable', // if required then set nullable to required
//			'deviceName' => 'nullable', // if required then set nullable to required
//			'ip' => 'nullable', // if required then set nullable to required
            // when required 'os' => ['required', new ValidOSType()],
        ]);
        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages());

            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }

        //transaction start

        DB::beginTransaction();
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
//
//            if (!$token = JWTAuth::attempt($credentials)) {
//
//                $this->setMeta('status', AppConstant::STATUS_FAIL);
//                $this->setMeta('message', __('jwt.jwt_not_got'));
//                return response()->json($this->setResponse(), AppConstant::BAD_REQUEST);
//            }

            /*if you need to generate custom tokens*/
//
            $claims = ['uuid' => $user->uuid, 'email' => $user->email];
            $token = JWTAuth::attempt($credentials);
            $token = $this->customClaimJWT($claims);
            //  dd($token1);
            //$token = JWTAuth::attempt($claims);
//
//            if (!$token = $this->customClaimJWT($claims)) {
////
//                $this->setMeta('status', AppConstant::STATUS_FAIL);
//                $this->setMeta('message', __('jwt.jwt_not_got'));
//                return response()->json($this->setResponse(), AppConstant::BAD_REQUEST);
//            }


            /*if you need to store login details you can create a separate table for that*/
//
//            $loginDetails = new LoginDetail();
//            $loginDetails->userId = $user->id;


            /*
             * add token if you have single access token mechanism or if it is required as per project
             * $user->accessToken = $token;
             * $user->fcmToken = $request->fcmToken; // when you have fcm token for single access token
             * or you can create a separate login_details table for that.
             *
               $loginDetails->accessToken = $token;

               //when you need fcm_tokens
               $loginDetails->fcmToken = $request->fcmToken;

              // when you have any of these
              $loginDetails->deviceType = $request->deviceType;
              $loginDetails->deviceName = $request->deviceName;
              $loginDetails->os = $request->os;
              $loginDetails->ip =$request->ip;

            */
//            $loginDetails->save();
            DB::commit();

        } catch (QueryException $e) {
            DB::rollBack();
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            //$this->setMeta( 'message', $e );

            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }


        $user = User::where('id', $user->id)->first();
//        $user->sdtrtoken = $token;
//        $user->ssdtoken = $token1;

        //echo($user->name);
//        dd($token);
        // dispatch(new SendVerificationEmail($user, $user->uuid));
        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.register_success'));
        $this->setData("user", $user);
        $this->setData("token", $token->get());
        // dd($user);
        return response()->json($this->setResponse(), AppConstant::CREATED);

    }

    /**
     * Multiple Device Login
     *
     * Handles Multiple Device Login requests.
     *
     */

    public function multipleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'fcmToken' => 'nullable', // if required then set nullable to required
            'deviceType' => 'nullable', // if required then set nullable to required
            'deviceName' => 'nullable', // if required then set nullable to required
            'ip' => 'nullable', // if required then set nullable to required
            //when required 'os' => ['required', new ValidOSType()],
        ]);
        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages()->first());

            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }
        try {
            $password = $request->password;
            $user = User::where('email', strtolower($request->email))
                ->first();
            if (!$user || !Hash::check($password, $user->password)) {
                $this->setMeta('status', AppConstant::STATUS_FAIL);
                $this->setMeta('message', __('auth.login_failed'));
                return response()->json($this->setResponse(), AppConstant::UNAUTHORIZED);
            }
            $token = null;
//            $credentials = $request->only('email', 'password');
//            if (!$token = JWTAuth::attempt($credentials)) {
//                $this->setMeta('status', AppConstant::STATUS_FAIL);
//                $this->setMeta('message', __('jwt.jwt_not_got'));
//                return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
//            }

            /*if you need to generate custom tokens*/

            /*if you need to generate custom tokens*/

            $claims = ['id' => $user->uuid, 'userType' => 'user'];

            $token = $this->customClaimJWT($claims);
//
//            $loginDetails = new LoginDetail();
//            $loginDetails->userId = $user->id;
//            $loginDetails->accessToken = $token;
            /*
              //when you need fcm_tokens
              $loginDetails->fcmToken = $request->fcmToken;

              //when you have any of these
              $loginDetails->deviceType = $request->deviceType;
              $loginDetails->deviceName = $request->deviceName;
              $loginDetails->os = $request->os;
              $loginDetails->ip =$request->ip;
            */
            //$loginDetails->save();

        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }
        $user->token = $token;
        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.login_success'));
        $this->setData('user', $user);
        return response()->json($this->setResponse(), AppConstant::OK);
    }

    /**
     * Single Device Login
     *
     * Handles Single Device Login requests.
     *
     */

    public function singleLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'fcmToken' => 'nullable', // if required then set nullable to required
            'deviceType' => 'nullable', // if required then set nullable to required
            'deviceName' => 'nullable', // if required then set nullable to required
            'ip' => 'nullable', // if required then set nullable to required
            //when required 'os' => ['required', new ValidOSType()],
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

            $status = false;
            $userToken = null;
            /*if token is stored in user table*/
            /*if($user){
                $userToken = $user->token;
                if($userToken !== null){
                    $status = $this->invalidateToken($userToken);
                }
            }*/

            /*if token is stored in login details(or other) table*/
            $checkLogin = LoginDetail::where('userId', $user->id)->first();
            if ($checkLogin) {
                $userToken = $checkLogin->accessToken;
                if ($userToken !== null) {
                    $status = $this->invalidateToken($userToken);
                }
            }
            /* condition ends**/

            if ($status == true || $userToken === null) {
                $token = null;
                $credentials = $request->only('email', 'password');
                if (!$token = JWTAuth::attempt($credentials)) {
                    $this->setMeta('status', AppConstant::STATUS_FAIL);
                    $this->setMeta('message', __('jwt.jwt_not_got'));

                    return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
                }

                /*if you need to generate custom tokens*/

                /*$claims =['id'=>$user->id,'userType' =>'user'];

                $token = $this->customClaimJWT($claims);*/

                /*
                 * if you have token field in the user table itself then update the token field's value with current token
                */
                /*User::where( 'email', $request->email )
                    ->update( [
                        'accessToken' => $token,
                        'fcmToken' => $request->fcmToken // when you have fcm token for single access token
                    ] );
                $user->token = $token;*/

                /*
                 * if you have separate table for login details then insert the current token into login table*/
                if (!$checkLogin) {
                    $loginDetails = new LoginDetail();
                    $loginDetails->userId = $user->id;


                    /*
                     * add token if it is required as per project
                     * $user->accessToken = $token;
                     * or you can create a separate login_details table for that.
                     *
                       $loginDetails->accessToken = $token;

                       //when you need fcm_tokens
                       $loginDetails->fcmToken = $request->fcmToken;

                      // when you have any of these
                      $loginDetails->deviceType = $request->deviceType;
                      $loginDetails->deviceName = $request->deviceName;
                      $loginDetails->os = $request->os;
                      $loginDetails->ip =$request->ip;

                    */
                    $loginDetails->save();
                } else {
                    $checkLogin->accessToken = $token;
                    $checkLogin->fcmToken = $request->fcmToken; //when you have fcm token for single access token
                    $checkLogin->save();
                }
            }
        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }
        $user->token = $token;
        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.login_success'));
        $this->setData('user', $user);
        return response()->json($this->setResponse(), AppConstant::OK);
    }


    /**
     * Forgot Password
     *
     * Handles Forgot Password requests.
     *
     */

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', $validator->messages()->first());
            return response()->json($this->setResponse(), AppConstant::UNPROCESSABLE_REQUEST);
        }
        try {
            $user = User::where([
                'email' => strtolower($request->email),
                'status' => AppConstant::STATUS_ACTIVE
            ])->first();
            if (!$user) {
                $this->setMeta('status', AppConstant::STATUS_FAIL);
                $this->setMeta('message', __('auth.user_not_found'));
                return response()->json($this->setResponse(), AppConstant::NOT_FOUND);
            }
            $getToken = new GetTokens();
            $token = $getToken->limit(10);
            $token = $token->token;
            $uuid = $user->uuid;
            $user->forgotPasswordCode = $token;
            $user->save();

            dispatch(new SendForgotPasswordEmail($user, $uuid));

        } catch (QueryException $e) {
            $this->setMeta('status', AppConstant::STATUS_FAIL);
            $this->setMeta('message', __('auth.server_error'));
            return response()->json($this->setResponse(), AppConstant::INTERNAL_SERVER_ERROR);
        }
        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('auth.forgot_password_mail_success'));
        return response()->json($this->setResponse(), AppConstant::OK);
    }

    /**
     *
     * Logout For single access token
     *  use two methods for this purpose first invalidateToken() method which will be used in 2 API's ie., login and logout in login this function will take care of deleting all the logged in tokens so that only one instance of user can be logged in
     *
     */

    public function invalidateToken($token)
    {
        $id = JWTAuth::getPayload($token)->get('sub');
        /*if you have access token in your user table*/
        /*User::where( 'id', $id )
            ->update( [
                'accessToken' => null,
                'fcmToken' => null
            ] );*/

        /*if you have access token in your login detail (or other) table*/

        LoginDetail::where('userId', $id)
            ->update([
                'accessToken' => null,
                //'fcmToken' => null // when you have fcm token
            ]);

        JWTAuth::invalidate($token);

        return true;
    }

    public function singleLogout()
    {
        $token = JWTAuth::getToken();
        $this->invalidateToken($token);
        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('jwt.logout_success'));
        return response()->json($this->setResponse(), AppConstant::OK);
    }

    /*Single Access Token ends*/

    /**
     *
     * Logout For Multiple access token
     *
     */

    public function logout()
    {
        $token = JWTAuth::getToken();
        JWTAuth::invalidate($token);
        /*if you have separate table for login details*/
        LoginDetail::where('accessToken', $token)->delete();
        $this->setMeta('status', AppConstant::STATUS_OK);
        $this->setMeta('message', __('jwt.logout_success'));
        return response()->json($this->setResponse(), AppConstant::OK);
    }


}
