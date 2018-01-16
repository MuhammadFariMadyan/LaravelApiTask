
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel |  @yield('title')</title>

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
    <link href="{{ URL::asset('assets/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/bootstrap/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('css/core.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('css/components.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ URL::asset('assets/colors/colors.css') }}" rel="stylesheet" type="text/css">
    @yield('pagecss')
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="{{ URL::asset('assets/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/bootstrap/js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/loaders/blockui.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/loaders/pace.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/ripple/ripple.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/jquery_validate/validate.min.js') }}"></script>
    <!-- /core JS files -->

    <script type="text/javascript" src="{{ URL::asset('js/validation.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/apps.js') }}"></script>

    <!-- /theme JS files -->
    @yield('pagejs')