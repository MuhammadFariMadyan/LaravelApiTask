@extends('admin.layouts.login')
@section('title')
    Login
@stop
@section('content')
    @if(Session::has('email_sent'))
        <div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span>
            </button>
            <span class="text-semibold"></span> {!! session('email_sent') !!}
        </div>
    @endif
    @if(Session::has('email_fail'))
        <div class="alert alert-danger alert-styled-left alert-arrow-left alert-bordered">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span>
            </button>
            <span class="text-semibold"></span> {!! session('email_fail') !!}
        </div>
    @endif
    <form action={{URL::to(\App\Utils\AppConstant::ADMIN_URL_PREFIX.'login')}} method="post">
        {{ csrf_field() }}
        <div class="panel panel-body login-form">
            <div class="text-center">
                <div>
                    <img width="100" height="100" src="{{URL::asset('/images/ballerLogo.png')}}" alt="Baller">
                </div>
                <h5 class="content-group">Login to your account
                    <small class="display-block">Enter your credentials below</small>
                </h5>
            </div>

            <div class="form-group has-feedback has-feedback-left {{ $errors->has('adminEmail') ? ' has-error' : '' }}">
                <input type="text" class="form-control" placeholder="E-mail" name="adminEmail">
                <div class="form-control-feedback">
                    <i class="icon-user text-muted"></i>
                </div>
                @if ($errors->has('adminEmail'))
                    <span class="help-block">
                                    <strong>{{ $errors->first('adminEmail') }}</strong>
                                </span>
                @endif
            </div>

            <div class="form-group has-feedback has-feedback-left {{ $errors->has('adminPassword') ? ' has-error' : '' }}">
                <input type="password" class="form-control" placeholder="Password" name="adminPassword">
                <div class="form-control-feedback">
                    <i class="icon-lock2 text-muted"></i>
                </div>
                @if ($errors->has('adminPassword'))
                    <span class="help-block">
                                    <strong>{{ $errors->first('adminPassword') }}</strong>
                                </span>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="btn bg-pink-400 btn-block">Sign in <i
                            class="icon-circle-right2 position-right"></i></button>
            </div>

            <div class="text-center">
                <a href="{{URL::to('admin/forgotPasswordPage')}}">Forgot password?</a>
            </div>
        </div>
    </form>
@stop
