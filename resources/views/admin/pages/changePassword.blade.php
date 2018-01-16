@extends('admin.layouts.sidebar')
@section('title')
    Change Password
@stop

@section('content')
    <div class="row">
        <div class="col-md-6">
            @if(Session::has('password_success'))
                <div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span>
                    </button>
                    <span class="text-semibold"></span> {!! session('password_success') !!}
                </div>
            @endif
                @if(Session::has('password_fail'))
                    <div class="alert alert-danger alert-styled-left alert-arrow-left alert-bordered">
                        <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span>
                        </button>
                        <span class="text-semibold"></span> {!! session('password_fail') !!}
                    </div>
                @endif
            <form id="changepassword" action="{{ URL::to(\App\Utils\AppConstant::ADMIN_URL_PREFIX.'editPassword') }}" method="post">
                <div class="panel panel-flat">
                    {{ csrf_field() }}
                    <div class="panel-heading">
                        <h5 class="panel-title">Change Password</h5>
                    </div>

                    <div class="panel-body">
                        <div class="form-group has-feedback {{ $errors->has('currentPassword') ? ' has-error' : '' }}">
                            <label>Current Password <span style="color: #ff0000;">*</span></label>
                            <input type="password" name="currentPassword" class="form-control"
                                   placeholder="Enter Current Password" value="{{old('currentPassword')}}">
                            @if ($errors->has('currentPassword'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('currentPassword') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group has-feedback {{ $errors->has('newPassword') ? ' has-error' : '' }}">
                            <label>New Password <span style="color: #ff0000;">*</span></label>
                            <input type="password" id="newPassword" class="form-control" name="newPassword"
                                   placeholder="Enter New Password">
                            @if ($errors->has('newPassword'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('newPassword') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>Re-enter Password <span style="color: #ff0000;">*</span></label>
                            <input type="password" class="form-control" name="reenterNewPassword"
                                   placeholder="Re-enter New Password">
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Change Password</button>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /basic layout -->
        </div>
    </div>
@stop