<?php

namespace App\Utils;

class AppConstant
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const STATUS_FAIL = 'fail';
    const STATUS_OK = 'ok';

    // API status codes
    const OK = 200;
    const CREATED = 201;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const UNPROCESSABLE_REQUEST = 422;
    const INTERNAL_SERVER_ERROR = 500;
    const TOKEN_INVALID = 503;

    const BASE_URL = 'http://localhost:8000';

    const OS_TYPE = ['android', 'ios'];

    const OS_ANDROID = "android";
    const OS_IOS = "ios";

    //Push Notification

	const REQ_PUSH = 1;

	//admin

	CONST ADMIN_URL_PREFIX ="/admin/";
	CONST GUARD_ADMIN = "admin";
	CONST ADMIN_MAIL = "php4.webmob@gmail.com";


}