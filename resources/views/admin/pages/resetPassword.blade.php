@extends('admin.layouts.login')
@section('title')
    Reset Password
@stop
@section('content')
    <form action={{URL::to(\App\Utils\AppConstant::ADMIN_URL_PREFIX.'updatePassword')}} method="post" id="formForgot">
        {{ csrf_field() }}
        <div class="panel panel-body login-form">
            <div class="text-center">
                <div>
                    <img width="100" height="100" src="{{URL::asset('/images/ballerLogo.png')}}" alt="Baller">
                </div>
                <h5 class="content-group">Reset your account
                    <small class="display-block">Enter your new password below</small>
                </h5>
            </div>
            <div class="form-group has-feedback has-feedback-left {{ $errors->has('password') ? ' has-error' : '' }}">
                {{csrf_field()}}
                <input type="hidden" value="{{$data['adminId']}}" name="adminId">
                <input type="password" class="form-control" placeholder="New Password" name="password" id="newPassword">
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
                @if ($errors->has('password'))
                    <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                @endif
            </div>
            <div class="form-group has-feedback has-feedback-left">
                <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation">
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
            </div>

            <div class="form-group">
                <button type="submit" class="btn bg-pink-400 btn-block">Reset Password <i
                            class="icon-circle-right2 position-right"></i></button>
            </div>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function () {

            var formRules = {
                password: {
                    required: true
                },
                confirmPassword: {
                    required: true,
                    equalTo: "#password"
                }
            };
            validateForm('#formForgot',formRules);
        });
    </script>
@stop