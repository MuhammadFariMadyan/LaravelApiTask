@extends('admin.layouts.sidebar')
@section('title')
    Users
@stop
@section('pagecss')
    <link href="{{ URL::asset('assets/datatables/datatables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/forms/bootstrap_select/bootstrap_select.css') }}" rel="stylesheet"
          type="text/css">
@endsection

@section('breadcrumb')
    <li class="active">App Users</li>
@endsection

@section('content')
    @if(Session::has('serverError'))
        <div class="alert alert-danger alert-styled-left alert-arrow-left alert-bordered">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span class="sr-only">Close</span>
            </button>
            <span class="text-semibold"></span> {!! session('serverError') !!}
        </div>
    @endif
    <div class="row">
        <div class="col-md-12 row-center">
            <div class="panel panel-flat">
                <div class="panel-heading">
                    <h5 class="panel-title">
                        App Users
                    </h5>
                    <div class="heading-elements">
                        <!--    <ul class="icons-list no-margin">
                                <li>
                                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                                            data-target="#modalForm">
                                        <i class="icon-plus2"></i> Add
                                    </button>
                                </li>
                                        <li><a data-action="reload"></a></li>
                            </ul> -->
                    </div>
                </div>
                <table class="table datatable-basic" id="users">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email Id</th>
                        <th>Phone No.</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $no=0;
                    @endphp
                    @foreach ($userList as $user)
                        @php
                            $no++;
                        @endphp
                    <tr>
                        <td>{{$no}}</td>
                        <td>
                            {{ $user->firstName." ".$user->lastName }}
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                        <td>
                            {{ $user->phoneNumber }}
                        </td>
                        <td>
                             @php
                                 switch ($user->userStatus) {
                                 case 0:
                                 echo '<span class="label label-danger">Inactive</span>';
                                 break;
                                 case 1:
                                 echo '<span class="label label-success">Active</span>';
                                 break;
                                 default:
                                 break;
                                 }
                             @endphp
                        </td>
                        <td class="text-center">
                            <ul class="icons-list">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li>
                                            <a onclick="return confirm('{{ (new \App\Helpers\Helper)->setAlert($user->userStatus) }}');"
                                               href="{{ URL::to
                                            (\App\Utils\AppConstant::ADMIN_URL_PREFIX.'users/userStatus/'.$user->uuid.'/'.$user->userStatus) }}">
                                                <i class="icon-bin2"></i> Active / Inactive
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection

@section('pagejs')
    <script type="text/javascript" src="{{ URL::asset('assets/datatables/datatables.min.js') }}"></script>
    <script type="text/javascript"
            src="{{ URL::asset('assets/forms/bootstrap_select/bootstrap_select.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/jquery_validate/validate.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/handlebar/handlebars.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/pagejs/datatables.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/validation.js') }}"></script>
    <script>

        $(document).ready(function () {
            var table = $('#users').DataTable();
            $('.dataTables_filter input[type="search"]').attr('placeholder','Search users').css({'width':'250px','display':'inline-block'});
            $('.dataTables_filter input').unbind().bind('keyup', function() {
                var colIndex = 1;
                table.column( colIndex).search( this.value ).draw();
            });
        });
    </script>
@endsection
