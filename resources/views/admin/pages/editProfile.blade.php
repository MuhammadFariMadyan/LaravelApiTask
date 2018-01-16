@extends('admin.layouts.sidebar')
@section('title')
    Edit Profile
@stop
@section('content')
    <div class="row">
        <div class="col-md-6">
            @if(Session::has('update_success'))
                <div class="alert alert-success alert-styled-left alert-arrow-left alert-bordered">
                    <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span>
                    </button>
                    <span class="text-semibold"></span> {!! session('update_success') !!}
                </div>
            @endif
            <form action="{{ URL::to(\App\Utils\AppConstant::ADMIN_URL_PREFIX.'updateProfile') }}"
                  method="post" id="editProfileForm">
                <div class="panel panel-flat">
                    {{ csrf_field() }}
                    <div class="panel-heading">
                        <h5 class="panel-title">Edit Profile</h5>
                    </div>

                    <div class="panel-body">
                        <div class="form-group has-feedback {{ $errors->has('adminName') ? ' has-error' : '' }}">
                            <label>Name</label>
                            <input type="text" name="adminName" class="form-control"
                                   placeholder="Enter First Name" value="{{$data['admin']->adminName}}">
                            @if ($errors->has('adminName'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('adminName') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>E-mail</label>
                            <input type="text" class="form-control" name="emailId"
                                   placeholder="Email" value="{{$data['admin']->adminEmail}}" disabled>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop