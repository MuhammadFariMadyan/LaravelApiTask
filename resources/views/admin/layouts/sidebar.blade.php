<!DOCTYPE html>
<html>
<head>
    @include('admin.includes.head')
</head>
<body>
<header>
    @include('admin.includes.header')
</header>


<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">
        @include('admin.includes.sidebar')

<div class="content-wrapper">

    <!-- Page header -->
    <div class="page-header">
        <div class="breadcrumb-line breadcrumb-line-component"><a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>
            <ul class="breadcrumb">
                <li>
                    <a href="{{ URL::to(\App\Utils\AppConstant::ADMIN_URL_PREFIX.'dashboard')}}">
                        <i class="icon-home2 position-left"></i>
                        Dashboard</a>
                </li>
                @yield('breadcrumb')
            </ul>
        </div>
    </div>
    <!-- /page header -->

    <!-- Content area -->
    <div class="content">
        @yield('content')
    </div>
    <!-- /content area -->
</div>
<!-- /main content -->
</div>
<!-- /page content -->
</div>
<!-- /page container -->
</body>
</html>
