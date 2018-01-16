@extends('admin.layouts.sidebar')
@section('title')
    Dashboard
@stop
@section('content')
    <div class="row">
            <div class="col-lg-4">
                <a href="{{ URL::to('admin/users')}}">
                <!-- Members online -->
                <div class="panel bg-teal-400">
                    <div class="panel-body">
                        <div class="heading-elements">
                            <i class="icon-users" style="font-size: 60px"></i>
                        </div>

                        <h3 class="no-margin">{{$data['users']}}</h3>
                        Total Users
                    </div>

                </div>
                <!-- /members online -->
                </a>
            </div>
    </div>
@stop