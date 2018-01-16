<!-- Main sidebar -->
<div class="sidebar sidebar-main">
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user-material">
            <div class="category-content">
                <div class="sidebar-user-material-content">
                    <a href="{{URL::to('admin/dashboard')}}"><img src="{{URL::asset('/images/ballerLogo.png')}}" class="img-circle img-responsive" alt=""></a>
                    <h6>{{ Auth::guard(\App\Utils\AppConstant::GUARD_ADMIN)->user()->adminName }}</h6>
                </div>

                <div class="sidebar-user-material-menu">
                    <a href="#user-nav" data-toggle="collapse"><span>My account</span> <i class="caret"></i></a>
                </div>
            </div>

            <div class="navigation-wrapper collapse" id="user-nav">
                <ul class="navigation">
                    <li><a href="{{URL::to('admin/editProfile')}}"><i class="icon-cog5"></i> <span>Profile Settings</span></a></li>
                    <li><a href="{{URL::to('admin/changePassword')}}"><i class="icon-cog5"></i> <span>Change Password</span></a></li>
                    <li>{{--<a href="{{URL::to('admin/logout')}}"><i class="icon-switch2"></i> <span>Logout</span></a>--}}
                        <a href="{{ url(\App\Utils\AppConstant::ADMIN_URL_PREFIX.'logout') }}"
                           onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            <i class="icon-switch2"></i> <span>Logout</span>
                        </a>

                        <form id="logout-form" action="{{ url(\App\Utils\AppConstant::ADMIN_URL_PREFIX.'logout') }}"
                              method="POST"
                              style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /user menu -->


        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">

                    <!-- Main -->
                    <li class="active"><a href="{{ URL::to('admin/dashboard')}}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                    <li class="active"><a href="{{ URL::to('admin/users')}}"><i class="icon-users"></i> <span>App Users</span></a></li>
                    <li class="active"><a href="{{ URL::to('horizon')}}" target="_blank"><i class="icon-yin-yang"></i> <span>Horizon</span></a></li>
                    <!-- /main -->


                </ul>
            </div>
        </div>
        <!-- /main navigation -->

    </div>
</div>
<!-- /main sidebar -->