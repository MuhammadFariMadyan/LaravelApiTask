@extends('admin.layouts.login')
@section('title')
    Forgot Password
@stop
@section('content')

    <form action="{{URL::to(\App\Utils\AppConstant::ADMIN_URL_PREFIX.'forgotPassword')}}" method="post" id="forgotpassword"
          autocomplete="off">
        <div class="panel panel-body login-form">
            <div class="text-center">
                <div>
                    <img width="100" height="100" src="{{URL::asset('images/ballerLogo.png')}}" alt="Baller">
                </div>
                <h5 class="content-group">Forgot Password?
                    <small class="display-block">Enter your registered email address below</small>
                </h5>
            </div>
        {{ csrf_field() }}
            <div class="form-group">
                <label>Enter Register Email Id</label>
                <input type="text" placeholder="Enter Register Email Id" class="form-control"
                       name="adminEmail" id="forgotemail"/>
                @if ($errors->has('adminEmail'))
                    <span class="help-block">
                                    <strong>{{ $errors->first('adminEmail') }}</strong>
                                </span>
                @endif
            </div>
            <div class="form-group">
                <button type="submit" class="btn bg-pink-400 btn-block">Submit <i
                            class="icon-circle-right2 position-right"></i></button>
            </div>
        </div>
    </form>
@stop