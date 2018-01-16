<!DOCTYPE html>
<html>
<head>
    @include('admin.includes.head')
</head>
<body class="login-container">
<header>
    @include('admin.includes.header')
</header>
    <!-- Page container -->
    <div class="page-container">

        <!-- Page content -->
        <div class="page-content">

            <!-- Main content -->
            <div class="content-wrapper">

                <!-- Content area -->
                <div class="content">
                    @yield('content')

                    <footer class="row">
                        @include('admin.includes.footer')
                    </footer>

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
