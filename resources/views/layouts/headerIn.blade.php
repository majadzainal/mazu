<!DOCTYPE html>
<html>
<head>
    <title>{{ auth()->user()->store->store_name }} System</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
    <meta name="keywords" content="flat ui, admin Admin , Responsive, Landing, Bootstrap, App, Template, Mobile, iOS, Android, apple, creative app">
    <meta name="author" content="Phoenixcoded" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon icon -->
    <link rel="icon" href="{{ asset ('/assets/files/assets/images/favicon.ico') }}" type="image/x-icon">
    <!-- Google font-->     <link href="https://fonts.googleapis.com/css?family=Roboto:400,500" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/bootstrap/css/bootstrap.min.css') }}">
    <!-- waves.css -->
    <link rel="stylesheet" href="{{ asset ('/assets/files/assets/pages/waves/css/waves.min.css') }}" type="text/css" media="all">
    <!-- sweet alert framework -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/sweetalert/css/sweetalert.css') }}">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/assets/icon/themify-icons/themify-icons.css') }}">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/assets/icon/icofont/css/icofont.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/assets/icon/font-awesome/css/font-awesome.min.css') }}">
    <!-- Select 2 css -->
    <link rel="stylesheet" href="{{ asset ('/assets/files/bower_components/select2/css/select2.min.css') }}" />
    <!-- Data Table Css -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/assets/pages/data-table/css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}">
    <!-- Switch component css -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/switchery/css/switchery.min.css') }}">
    <!-- Tags css -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/bootstrap-tagsinput/css/bootstrap-tagsinput.css') }}">
    <!-- Calender css -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/fullcalendar/css/fullcalendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/fullcalendar/css/fullcalendar.print.css') }}" media='print'>
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/assets/css/jquery.mCustomScrollbar.css') }}">

    <!-- Custom Style.css -->
    <link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/assets/css/customStyle.css') }}">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js" integrity="sha512-jDEmOIskGs/j5S3wBWQAL4pOYy3S5a0y3Vav7BgXHnCVcUBXkf1OqzYS6njmDiKyqes22QEX8GSIZZ5pGk+9nA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>

<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="loader-track">
            <div class="preloader-wrapper">
                <div class="spinner-layer spinner-blue">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <nav class="navbar header-navbar pcoded-header iscollapsed" header-theme="theme6" pcoded-header-position="fixed">
                <div class="navbar-wrapper">
                    <div class="navbar-logo">
                        <a class="mobile-menu waves-effect waves-light" id="mobile-collapse" href="#!">
                            <i class="ti-menu"></i>
                        </a>
                        <div class="mobile-search waves-effect waves-light">
                            <div class="header-search">
                                <div class="main-search morphsearch-search">
                                    <div class="input-group">
                                        <span class="input-group-prepend search-close"><i class="ti-close input-group-text"></i></span>
                                        <input type="text" class="form-control" placeholder="Enter Keyword">
                                        <span class="input-group-append search-btn"><i class="ti-search input-group-text"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="#">
                            {{ auth()->user()->store->store_name }} System
                        </a>
                        <a class="mobile-options waves-effect waves-light">
                            <i class="ti-more"></i>
                        </a>
                    </div>

                    <div class="navbar-container container-fluid">
                        <ul class="nav-left">
                            <li>
                                <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a></div>
                            </li>
                            <li>
                                <a href="#!" onclick="javascript:toggleFullScreen()" class="waves-effect waves-light">
                                    <i class="ti-fullscreen"></i>
                                </a>
                            </li>
                        </ul>
                        <ul class="nav-right">
                            <li class="user-profile header-notification">
                                <a href="#!" class="waves-effect waves-light">
                                    <img src="/uploads/{{ getLogo()}}" class="img-radius" alt="User-Profile-Image">
                                    <span>{{ auth()->user()->employee->employee_name }}</span>
                                    <i class="ti-angle-down"></i>
                                </a>
                                <ul class="show-notification profile-notification">
                                    <li class="waves-effect waves-light" data-toggle="modal" data-target="#large-Modal-view-profile">
                                        <a href="#!">
                                            <i class="ti-user"></i> Profile
                                        </a>
                                    </li>
                                    <li class="waves-effect waves-light" data-toggle="modal" data-target="#default-Modal-update-password">
                                        <a href="#!">
                                            <i class="ti-settings"></i> Change Password
                                        </a>
                                    </li>
                                    <li class="waves-effect waves-light">
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                                            <i class="ti-layout-sidebar-left"></i> Logout
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <nav class="pcoded-navbar">
                        <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                        <div class="pcoded-inner-navbar main-menu">
                            <div class="">
                                <div class="main-menu-header">
                                    <img class="img-80 img-radius" src="/uploads/{{ getLogo()}}" alt="User-Profile-Image">
                                    <div class="user-details">
                                        <span id="more-details">{{ auth()->user()->employee->employee_name }}<i class="fa fa-caret-down"></i></span>
                                    </div>
                                </div>

                                <div class="main-menu-content">
                                    <ul>
                                        <li class="more-details">
                                            <a href="#!" data-toggle="modal" data-target="#large-Modal-view-profile"><i class="ti-user"></i>View Profile</a>
                                            <a href="#!" data-toggle="modal" data-target="#default-Modal-update-password"><i class="ti-settings"></i>Change Password</a>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                                    <i class="ti-layout-sidebar-left"></i>Logout
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="pcoded-navigation-label">Menu</div>
                            <ul class="pcoded-item pcoded-left-item">
                                @php
                                    $user = Auth::user();
                                    $menu = getMenu();
                                    $menu_before = "";
                                @endphp

                                @foreach($menu as $ls)
                                    @if ($ls->menu->is_superuser != $user->is_superuser)
                                        @continue
                                    @endif

                                    @if($ls->menu->menu_parents == null)
                                        @php
                                            $classActive = $ls->menu->menu_id === $MenuID ? 'active' : '';
                                        @endphp

                                    <li class="{{$classActive}}">
                                        <a href="{{ $ls->menu->uri }}" class="waves-effect waves-dark">
                                            <span class="pcoded-micon"><i class="{{ $ls->menu->class }}"></i><b>{{ $ls->menu->alias }}</b></span>
                                            <span class="pcoded-mtext">{{ $ls->menu->menu_name }}</span>
                                            <span class="pcoded-mcaret"></span>
                                        </a>
                                    </li>
                                    @else
                                        @php
                                            $parentActive = '';
                                            $displayBlok = 'none';
                                            if(substr($ls->menu->menu_parents->menu_id, 0, 3) === substr($MenuID, 0, 3)){
                                                $parentActive = 'active';
                                                $displayBlok = 'block';
                                            }
                                            // if($ls->menu->menu_parents->menu_id === $MenuID){
                                            //     $parentActive = 'active';
                                            //     $displayBlok = 'block';
                                            // }
                                        @endphp
                                        @if( $menu_before != $ls->menu->menu_parents->menu_id)
                                        <li class="pcoded-hasmenu {{$parentActive}}">
                                            <a href="javascript:void(0)" class="waves-effect waves-dark">
                                                <span class="pcoded-micon"><i class="{{ $ls->menu->menu_parents->class }}"></i><b>{{ $ls->menu->menu_parents->alias }}</b></span>
                                                <span class="pcoded-mtext">{{ $ls->menu->menu_parents->menu_name }}</span>
                                                <span class="pcoded-mcaret"></span>
                                            </a>
                                            <ul class="pcoded-submenu" style="display:{{$displayBlok}};">
                                                @foreach($menu->where('menu.parent_menu', $ls->menu->menu_parents->menu_id) as $subls)
                                                @if ($subls->menu->is_superuser != $user->is_superuser)
                                                    @continue
                                                @endif
                                                @php
                                                    $classActive = $subls->menu->menu_id === $MenuID ? 'active' : '';
                                                @endphp
                                                <li class="{{$classActive}}">
                                                    <a href="{{ $subls->menu->uri }}" class="waves-effect waves-dark">
                                                        <span class="pcoded-micon"><i class="ti-angle-right"></i></span>
                                                        <span class="pcoded-mtext">{{ $subls->menu->menu_name }}</span>
                                                        <span class="pcoded-mcaret"></span>
                                                    </a>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                        @endif
                                        @php $menu_before = $ls->menu->menu_parents->menu_id @endphp
                                    @endif

                                @endforeach
                            </ul>
                        </div>
                    </nav>
                    @yield('content');


                </div><!--end pcode-wrapper-->
                <!--modal view profile-->
                <div class="modal fade" id="large-Modal-view-profile"  role="dialog">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color:lightblue">
                                <h4 class="modal-title" id="defaultModalLabel" >View Profile</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="/users/update-profile" method="post" enctype="multipart/form-data" id="profileForm">
                                @csrf
                                <input type="hidden" name="id" id="id" value="{{ auth()->user()->user_id }}">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="name" id="name" value="{{ auth()->user()->employee->employee_name }}" class="form-control">
                                        </div>
                                    </div>
                                    {{-- <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Divisi</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="divisi" id="divisi" value="{{ auth()->user()->employee->divisi->divisi_name }}" readonly class="form-control">
                                        </div>
                                    </div> --}}
                                    {{-- <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Location</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="location" id="location" value="{{ auth()->user()->employee->location->location_name }}" readonly class="form-control">
                                        </div>
                                    </div> --}}
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="email" id="email" value="{{ auth()->user()->employee->email }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Phone</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="phone" id="phone" value="{{ auth()->user()->employee->phone }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Username</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="username" id="username" value="{{ auth()->user()->username }}" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Role</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="role" id="role" value="{{ auth()->user()->roles->role_name }}" readonly class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" id="closeModalProfile" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInitProfile('#profileForm')">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end modal view profile-->

                <!--modal update password-->
                <div class="modal fade" id="default-Modal-update-password"  role="dialog">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header" style="background-color:lightblue">
                                <h4 class="modal-title" id="defaultModalLabel" >View Profile</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="/users/update-password" method="post" enctype="multipart/form-data" id="updatePasswordForm">
                                @csrf
                                <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->user_id }}">
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" name="password" id="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Re-Type Password</label>
                                        <div class="col-sm-8">
                                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" id="closeModalPassword" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInitProfile('#updatePasswordForm')">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end modal update passowrd-->
            </div>
        </div>
    </div>
    <script>
        function saveInitProfile(form){
            saveData(form, function() {
                loadData();
                $("#closeModalProfile").click();
                $("#closeModalPassword").click();
            });
        }
    </script>

</body>

</html>
