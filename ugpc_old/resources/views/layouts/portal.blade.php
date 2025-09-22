<!DOCTYPE html>
<html lang="{{config('app.locale')}}">

@php
use App\Models\Portal\Link\Link;
$links = Link::where('parent_id', null)->orderBy('position')->get();
@endphp

<head>
  <title>{{ config('app.name', 'Laravel') }} - {{ env('APP_CORE'), 'CORE NOT FOUND' }}</title>

  <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 11]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
  <!-- Meta -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="csrf-token" content="{{csrf_token()}}">





  <!-- Favicon icon -->
  <link rel="icon" href="{{asset('images/favicon.ico')}}" type="image/x-icon">
  <!-- fontawesome icon -->
  <link rel="stylesheet" href="{{asset('fonts/dattaAbel/fontawesome/css/fontawesome-all.min.css')}}">
  <!-- animation css -->
  <link rel="stylesheet" href="{{asset('plugins/animation/css/animate.min.css')}}">
  <!-- vendor css -->
  {{-- <link rel="stylesheet" href="{{asset('css/jquery.datetimepicker.min.css')}}"> --}}
  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" /> --}}
  <link rel="stylesheet" href="{{asset('plugins/data-tables/css/datatables.min.css')}}">
  <link rel="stylesheet" href="{{asset('plugins/ekko-lightbox/css/ekko-lightbox.min.css')}}">
  <link rel="stylesheet" href="{{asset('css/dattaAbel/style.css')}}">
</head>

<body>
  <div id="app">

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
      <div class="loader-track">
        <div class="loader-fill"></div>
      </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar">
      <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
          <a href="{{ route('main') }}" class="b-brand">
            <div class="b-bg">
              D
            </div>
            <span class="b-title">{{ config('app.name', 'Laravel') }}</span>
          </a>
          <a class="mobile-menu on" id="mobile-collapse" href="javascript:"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">

          <ul class="nav pcoded-inner-navbar">
            {{-- [Links] start --}}
            <li class="nav-item pcoded-menu-caption">
              <label>Меню</label>
            </li>
            @foreach ($links as $link)
                      @if ((!Auth::check() && $link->auth == 'auth') || (Auth::check() && $link->auth == 'guest'))
                      @else
                          @if ((!Auth::check() && !$link->role) ||
                              (Auth::check() && (!$link->role || Auth::user()->checkRole($link->role->name))))
                              @php
                                  if (env('APP_CORE') != null && $link->app_core == env('APP_CORE')) {
                                      $full_uri = route($link->route ?? '');
                                  } else {
                                      if ($link->app_core == 'all') {
                                          $full_uri = route($link->route ?? '');
                                      } else {
                                          $full_uri = $link->full_url_portal;
                                      }
                                  }
                              @endphp
                              @if ($link->child->isEmpty())
                                  @switch($link->route)
                                      @case('logout')
                                          <hr>
                                          <li data-username="{{ $link->title }}"
                                              class="nav-item @if (Route::is($link->route)) {{ 'active' }} @endif">
                                              <a href="{{ $full_uri }}" class="nav-link"
                                                  target="{{ $link->target }}"
                                                  onclick="event.preventDefault();submitForm('#logout-form');">
                                                  <span class="pcoded-micon">
                                                      <i class="{{ $link->icon }}"></i>
                                                  </span>
                                                  <span class="pcoded-mtext">{{ $link->title }}</span>
                                              </a>
                                              <form id="logout-form" action="{{ route('logout') }}" method="GET"
                                                  style="display: none;">
                                              </form>
                                          </li>
                                      @break

                                      @default
                                          <li data-username="{{ $link->title }}"
                                              class="nav-item @if (Route::is($link->route)) {{ 'active' }} @endif">
                                              <a href="{{ $full_uri }}" class="nav-link"
                                                  target="{{ $link->target }}">
                                                  <span class="pcoded-micon">
                                                      <i class="{{ $link->icon }}"></i>
                                                  </span>
                                                  <span class="pcoded-mtext">{{ $link->title }}</span>
                                              </a>
                                          </li>
                                  @endswitch
                              @else
                                  <li data-username="{{ $link->title }}"
                                      class="nav-item pcoded-hasmenu  @if (Route::is($link->route . '*')) {{ 'pcoded-trigger' }} @endif">
                                      <a href="javascript:" class="nav-link ">
                                          <span class="pcoded-micon">
                                              <i class="{{ $link->icon }}"></i>
                                          </span>
                                          <span class="pcoded-mtext">{{ $link->title }}</span>
                                      </a>
                                      <ul class="pcoded-submenu"
                                          style="display: {{ Route::is($link->route . '*') ? 'block' : 'none' }};">
                                          @foreach ($link->child as $child)
                                              @if (!$child->role || Auth::user()->checkRole($child->role->name))
                                                  @php
                                                      if (env('APP_CORE') != null && $link->app_core == env('APP_CORE')) {
                                                          $full_uri = route($child->route ?? '');
                                                      } else {
                                                          if ($child->app_core == 'all') {
                                                              $full_uri = route($child->route ?? '');
                                                          } else {
                                                              $full_uri = $child->full_url_portal;
                                                          }
                                                      }
                                                  @endphp
                                                  <li
                                                      class="@if ($child->app_core == env('APP_CORE')) @if (Route::is($child->route)) {{ 'active' }} @endif @endif">
                                                      <a href="{{ $full_uri }}"
                                                          target="{{ $child->target }}">{{ $child->title }}</a>
                                                  </li>
                                              @endif
                                          @endforeach
                                      </ul>
                                  </li>
                              @endif
                          @endif
                      @endif
                  @endforeach
            {{-- [Links] end --}}

          </ul>
        </div>
      </div>
    </nav>
    <!-- [ navigation menu ] end -->

    <!-- [ Header ] start -->
    <header class="navbar pcoded-header navbar-expand-lg navbar-light">
      <div class="m-header">
        <a class="mobile-menu" id="mobile-collapse1" href="javascript:"><span></span></a>
        <a href="{{ route('main') }}" class=" b-brand">
          <div class="b-bg">
            D
          </div>
          <span class="b-title">{{ config('app.name', 'Laravel') }}</span>
        </a>
      </div>
      <a class="mobile-menu" id="mobile-header" href="javascript:">
        <i class="feather icon-more-horizontal"></i>
      </a>
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
          <li><a href="javascript:" class="full-screen" onclick="javascript:toggleFullScreen()"><i
                class="feather icon-maximize"></i></a></li>
          <li class="nav-item">
            <div class="main-search">
              <div class="input-group">
                <input type="text" id="m-search" class="form-control" placeholder="Search . . .">
                <a href="javascript:" class="input-group-append search-close">
                  <i class="feather icon-x input-group-text"></i>
                </a>
                <span class="input-group-append search-btn btn btn-primary">
                  <i class="feather icon-search input-group-text"></i>
                </span>
              </div>
            </div>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          @auth
            <li>
              <div class="dropdown">
                  <a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class="icon feather icon-bell"></i></a>
                  <div class="dropdown-menu dropdown-menu-right notification">
                      <div class="noti-head">
                          <h6 class="d-inline-block m-b-0">Уведомления</h6>
                          <div class="float-right">
                          </div>
                      </div>
                      <ul class="noti-body">

                      </ul>
                      <div class="noti-footer">
                          <!--<a href="#!">Показать все</a>-->
                      </div>
                  </div>
              </div>
          </li>
          <li>
            <div class="dropdown drp-user">
              <a href="javascript:" class="dropdown-toggle" data-toggle="dropdown">
                <i class="icon feather icon-settings"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right profile-notification">
                <div class="pro-head">
                  @if (Auth::user()->avatar)
                  <img src="{{asset(Storage::url(Auth::user()->avatar))}}" class="img-radius" alt="User-Profile-Image">
                  @else
                  <img src="{{asset('images/dattaAbel/user/avatar-1.jpg')}}" class="img-radius"
                    alt="User-Profile-Image">
                  @endif
                  <span>{{Auth::user()->name}}</span>
                  <form id="logout-form-2" action="{{ route('logout') }}" method="GET">
                    <button type="submit" class="dud-logout icon-btn" title="Logout">
                      <i class="feather icon-log-out"></i>
                    </button>
                  </form>
                </div>
                <ul class="pro-body">
                  <li><a href="#" class="dropdown-item"><i class="feather icon-user"></i> Профиль</a>
                  </li>
                </ul>
              </div>
            </div>
          </li>
          @endauth
        </ul>
      </div>
    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
      <div class="pcoded-wrapper">
        <div class="pcoded-content pt-0">
          <div class="pcoded-inner-content">
            @yield('content')
          </div>
        </div>
      </div>
    </div>
    <!-- [ Main Content ] end -->

    <!-- Warning Section Starts -->
    <!-- Older IE warning message -->
    <!--[if lt IE 11]>
              <div class="ie-warning">
                  <h1>Warning!!</h1>
                  <p>You are using an outdated version of Internet Explorer, please upgrade
                     <br/>to any of the following web browsers to access this website.
                  </p>
                  <div class="iew-container">
                      <ul class="iew-download">
                          <li>
                              <a href="http://www.google.com/chrome/">
                                  <img src="{{asset('images/browser/chrome.png')}}" alt="Chrome">
                                  <div>Chrome</div>
                              </a>
                          </li>
                          <li>
                              <a href="https://www.mozilla.org/en-US/firefox/new/">
                                  <img src="{{asset('images/browser/firefox.png')}}" alt="Firefox">
                                  <div>Firefox</div>
                              </a>
                          </li>
                          <li>
                              <a href="http://www.opera.com">
                                  <img src="{{asset('images/browser/opera.png')}}" alt="Opera">
                                  <div>Opera</div>
                              </a>
                          </li>
                          <li>
                              <a href="https://www.apple.com/safari/">
                                  <img src="{{asset('images/browser/safari.png')}}" alt="Safari">
                                  <div>Safari</div>
                              </a>
                          </li>
                          <li>
                              <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                                  <img src="{{asset('images/browser/ie.png')}}" alt="">
                                  <div>IE (11 & above)</div>
                              </a>
                          </li>
                      </ul>
                  </div>
                  <p>Sorry for the inconvenience!</p>
              </div>
          <![endif]-->
    <!-- Warning Section Ends -->
    @stack('modals')
  </div>

  <!-- Required Js -->
  <script src="{{asset('js/dattaAbel/vendor-all.min.js')}}"></script>
  <script src="{{asset('plugins/bootstrap/js/bootstrap.min.js')}}"></script>
  <script src="{{asset('js/jquery.datetimepicker.full.min.js')}}" defer></script>
  <script src="{{asset('js/dattaAbel/pcoded.min.js')}}"></script>
  <script src="{{asset('js/imagePreview.js')}}"></script>
  <script src="{{asset('js/helper.js')}}"></script>
  {{-- <script src="{{asset('js/dattaAbel/task-board.js')}}"></script> --}}
  {{-- <script src="{{asset('js/dattaAbel/pages/form-select-custom.js')}}"></script> --}}
  <script src="{{asset('plugins/data-tables/js/datatables.min.js')}}"></script>
  <script src="{{asset('js/dattaAbel/pages/tbl-datatable-custom.js')}}"></script>

  <script src="{{asset('plugins/ekko-lightbox/js/ekko-lightbox.min.js')}}"></script>
  <script src="{{asset('js/dattaAbel/pages/ac-lightbox.js')}}"></script>
  <script src="{{ asset('js/app.js') }}"></script>





  <script>
    const hiddenStatus = localStorage.getItem('navHidden');
    if(hiddenStatus === 'true'){
      $('.pcoded-navbar').toggleClass('navbar-collapsed');
      $('.mobile-menu').toggleClass('on');
    };

    $('.mobile-menu').click(() => {
      const exist = localStorage.getItem('navHidden');
      const hiddenStatus = exist !== null ? exist !== 'true' : true
      localStorage.setItem('navHidden', hiddenStatus);
    });
  </script>
  @stack('scripts')

</body>

</html>
