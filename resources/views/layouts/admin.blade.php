<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/AdminLTE.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/css/skins/_all-skins.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{
            --ink:#16243b;
            --muted:#6b7690;
            --line:#e8edf4;
            --bg:#f5f7fb;
            --blue:#4a6cf7;
            --mint:#12b886;
            --sidebar-bg:#101b2d;
            --sidebar-bg-deep:#0d1727;
            --sidebar-hover:#1e3053;
            --header-h:64px;
        }
        *{box-sizing:border-box}
        body{font-family:'DM Sans',sans-serif!important;background:var(--bg)!important;color:var(--ink)}

        /* ===== Header ===== */
        .main-header{box-shadow:0 1px 0 var(--line)}
        .main-header .logo{
            height:var(--header-h);background:var(--sidebar-bg)!important;border-bottom:0;
            font-family:'Manrope',sans-serif;font-weight:800;letter-spacing:.02em;
            display:flex!important;align-items:center;justify-content:center;color:#fff!important;
            transition:width .3s ease;
        }
        .admin-brand-logo{width:32px;height:32px;object-fit:contain;background:#fff;border-radius:8px;padding:3px;margin-right:10px;flex-shrink:0}
        .main-header .logo .logo-lg{display:flex;align-items:center;line-height:1}

        .main-header .navbar{
            min-height:var(--header-h)!important;background:#fff!important;box-shadow:none;
            display:flex;align-items:stretch;
        }
        .main-header .navbar-custom-menu,.main-header .navbar-right{float:none!important}
        .main-header .navbar > .sidebar-toggle{
            height:var(--header-h);display:flex;align-items:center;justify-content:center;
            padding:0 20px;color:#526074!important;transition:background .15s,color .15s;
        }
        .main-header .navbar > .sidebar-toggle:hover{background:#f4f6fa!important;color:var(--blue)!important}

        /* Flex row that holds toggle + clock + spacer + right menus, all vertically centered */
        .navbar-align-row{display:flex;align-items:stretch;flex:1 1 auto;min-width:0}
        .navbar-spacer{flex:1 1 auto}

        .admin-clock{
            height:var(--header-h);display:flex;align-items:center;gap:11px;padding:0 20px;
            color:var(--muted);font-size:12px;border-left:1px solid var(--line);white-space:nowrap;
        }
        .admin-clock i{font-size:17px;color:var(--blue)}
        .admin-clock strong{display:block;color:var(--ink);font-size:13px;line-height:1.3;font-weight:700}
        .admin-clock span{font-size:11px;color:var(--muted)}

        .main-header .navbar-nav{display:flex;align-items:stretch;height:var(--header-h);margin:0}
        .main-header .navbar-nav>li{display:flex;align-items:stretch}
        .navbar-nav>.notifications-menu>.dropdown-toggle,
        .navbar-nav>.user-menu>.dropdown-toggle{
            height:var(--header-h)!important;display:flex!important;align-items:center;
            padding:0 18px!important;color:#526074!important;position:relative;
            transition:background .15s,color .15s;
        }
        .navbar-nav>.notifications-menu>.dropdown-toggle:hover,
        .navbar-nav>.user-menu>.dropdown-toggle:hover{background:#f4f6fa!important;color:var(--blue)!important}
        .navbar-nav>.notifications-menu>.dropdown-toggle{font-size:17px}
        .navbar-nav>.notifications-menu>.dropdown-toggle .label{
            position:absolute;top:14px;right:10px;font-size:10px;font-weight:700;
            padding:2px 5px;border-radius:20px;min-width:16px;line-height:1.3;
        }

        .admin-avatar{width:32px;height:32px;object-fit:cover;border-radius:50%;border:2px solid #dce6ff;margin-right:9px;flex-shrink:0}
        .profile-fallback{display:inline-flex;align-items:center;justify-content:center;background:var(--blue);color:#fff;font-weight:700}
        .navbar-nav>.user-menu>.dropdown-toggle .hidden-xs{display:inline-flex!important;align-items:center;gap:6px;font-weight:600;font-size:13px;color:var(--ink)}

        .navbar-nav>.user-menu>.dropdown-menu{
            width:236px;border:0;border-radius:12px;box-shadow:0 18px 40px rgba(16,24,40,.16);
            padding:8px;margin-top:0;
        }
        .admin-profile-menu{padding:12px 10px;border-bottom:1px solid var(--line);margin-bottom:6px}
        .admin-profile-menu strong{display:block;font-size:13.5px;color:var(--ink)}
        .admin-profile-menu small{color:var(--muted)}
        .navbar-nav>.user-menu>.dropdown-menu a{border-radius:7px;padding:10px 12px;color:#46556b;display:flex;align-items:center}
        .navbar-nav>.user-menu>.dropdown-menu a:hover{background:#f1f5ff;color:var(--blue)}

        .notifications-menu .dropdown-menu{border:0;border-radius:12px;box-shadow:0 18px 40px rgba(16,24,40,.16);padding:6px;margin-top:0}
        .notifications-menu .dropdown-menu .menu{max-height:280px}
        .notifications-menu .dropdown-menu .menu>li>a{border-radius:7px;padding:10px 12px;white-space:normal;color:#46556b}
        .notifications-menu .dropdown-menu .menu>li>a:hover{background:#f1f5ff;color:var(--blue)}

        /* ===== Sidebar ===== */
        .main-sidebar{background:var(--sidebar-bg)!important;padding-top:14px}
        .sidebar-menu>li>a{
            color:#b9c6dc!important;border-radius:8px;margin:3px 12px;padding:12px 14px;
            font-weight:600;font-size:13.5px;display:flex;align-items:center;transition:background .15s,color .15s;
        }
        .sidebar-menu>li>a>i{width:22px;text-align:center;margin-right:2px}
        .sidebar-menu>li:hover>a,.sidebar-menu>li.active>a,.sidebar-menu>li.menu-open>a{
            background:var(--sidebar-hover)!important;color:#fff!important;
            border-left:3px solid #6e8cff;padding-left:11px;
        }
        .sidebar-menu .treeview-menu{background:var(--sidebar-bg-deep)!important;padding:5px 0 8px;margin:0 12px;border-radius:0 0 8px 8px}
        .sidebar-menu .treeview-menu>li>a{color:#9eacc2!important;padding:9px 10px 9px 40px;display:flex;align-items:center;font-size:13px}
        .sidebar-menu .treeview-menu>li.active>a,.sidebar-menu .treeview-menu>li>a:hover{color:#fff!important;background:transparent}

        .content-wrapper{background:var(--bg)!important;min-height:calc(100vh - var(--header-h))!important}
        .content{padding:26px!important}

        .main-footer{
            background:#fff;border-top:1px solid var(--line);color:var(--muted);
            padding:16px;text-align:center;font-size:13px;
        }
        .main-footer strong{color:var(--ink)}

        .alert{border:0;border-radius:12px;box-shadow:0 4px 14px rgba(16,24,40,.05)}

        @media(max-width:767px){
            .admin-clock{display:none}
            .main-header .logo{width:60px!important}
            .main-header .logo .logo-lg{display:none}
            .content{padding:16px!important}
            .navbar-nav>.user-menu>.dropdown-toggle .hidden-xs{display:none!important}
            .navbar-nav>.user-menu>.dropdown-menu{right:6px;left:auto}
        }
    </style>
    @yield('styles')
</head>

<body class="sidebar-mini skin-purple" style="height: auto; min-height: 100%;">
    <div class="wrapper" style="height: auto; min-height: 100%;">
        <header class="main-header">
            <a href="{{ route('admin.home') }}" class="logo">
                <span class="logo-mini"><b>MSV</b></span>
                <span class="logo-lg"><img src="{{ asset('asset/img/msv-logo.png') }}" class="admin-brand-logo" alt="MSV">MSV Admin</span>
            </a>

            <nav class="navbar navbar-static-top">
                <div class="navbar-align-row">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">{{ trans('global.toggleNavigation') }}</span>
                    </a>

                    <div class="admin-clock hidden-xs">
                        <i class="fa fa-calendar-o"></i>
                        <div><strong id="adminLiveTime">--:--:--</strong><span id="adminLiveDate">Loading date…</span></div>
                    </div>

                    <div class="navbar-spacer"></div>

                    @if(count(config('panel.available_languages', [])) > 1)
                        <ul class="nav navbar-nav">
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    {{ strtoupper(app()->getLocale()) }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <ul class="menu">
                                            @foreach(config('panel.available_languages') as $langLocale => $langName)
                                                <li>
                                                    <a href="{{ url()->current() }}?change_language={{ $langLocale }}">{{ strtoupper($langLocale) }} ({{ $langName }})</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    @endif

                    <ul class="nav navbar-nav">
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-bell-o"></i>
                                @php($alertsCount = \Auth::user()->userUserAlerts()->where('read', false)->count())
                                    @if($alertsCount > 0)
                                        <span class="label label-warning">
                                            {{ $alertsCount }}
                                        </span>
                                    @endif
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <div class="slimScrollDiv" style="position: relative;">
                                        <ul class="menu">
                                            @if(count($alerts = \Auth::user()->userUserAlerts()->withPivot('read')->limit(10)->orderBy('created_at', 'ASC')->get()->reverse()) > 0)
                                                @foreach($alerts as $alert)
                                                    <li>
                                                        <a href="{{ $alert->alert_link ? $alert->alert_link : "#" }}" target="_blank" rel="noopener noreferrer">
                                                            @if($alert->pivot->read === 0) <strong> @endif
                                                                {{ $alert->alert_text }}
                                                                @if($alert->pivot->read === 0) </strong> @endif
                                                        </a>
                                                    </li>
                                                @endforeach
                                            @else
                                                <li style="text-align:center;">
                                                    {{ trans('global.no_alerts') }}
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        @php($adminPhoto = Auth::user()->getFirstMediaUrl('profile_photo', 'preview'))
                        <li class="dropdown user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                @if($adminPhoto)
                                    <img src="{{ $adminPhoto }}" class="admin-avatar" alt="{{ Auth::user()->name }}">
                                @else
                                    <span class="admin-avatar profile-fallback">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                                @endif
                                <span class="hidden-xs">{{ Auth::user()->name }} <i class="fa fa-angle-down"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="admin-profile-menu"><strong>{{ Auth::user()->name }}</strong><small>{{ Auth::user()->email }}</small></li>
                                <li><a href="{{ route('profile.password.edit') }}"><i class="fa fa-user-circle-o fa-fw"></i> Profile settings</a></li>
                                <li><a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        @include('partials.menu')

        <div class="content-wrapper" style="min-height: 960px;">
            @if(session('message'))
                <div class="row" style='padding:20px 20px 0 20px;'>
                    <div class="col-lg-12">
                        <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                    </div>
                </div>
            @endif
            @if($errors->count() > 0)
                <div class="row" style='padding:20px 20px 0 20px;'>
                    <div class="col-lg-12">
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            @yield('content')
        </div>
        <footer class="main-footer">
            <strong>{{ trans('panel.site_title') }} &copy;</strong> {{ trans('global.allRightsReserved') }}
        </footer>

        <form id="logoutform" action="{{ route('logout') }}" method="POST" style="display: none;">
            {{ csrf_field() }}
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.3/js/adminlte.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.19/js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        (function () {
            var time = document.getElementById('adminLiveTime');
            var date = document.getElementById('adminLiveDate');
            if (!time || !date) return;
            function updateClock() {
                var now = new Date();
                time.textContent = now.toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
                date.textContent = now.toLocaleDateString('en-IN', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' });
            }
            updateClock(); setInterval(updateClock, 1000);
        })();
    </script>
    <script>
        $(function() {
  let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
  let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
  let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
  let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
  let printButtonTrans = '{{ trans('global.datatables.print') }}'
  let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
  let selectAllButtonTrans = '{{ trans('global.select_all') }}'
  let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

  let languages = {
    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
  };

  $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
  $.extend(true, $.fn.dataTable.defaults, {
    language: {
      url: languages['{{ app()->getLocale() }}']
    },
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }, {
        orderable: false,
        searchable: false,
        targets: -1
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [],
    scrollX: true,
    pageLength: 100,
    dom: 'lBfrtip<"actions">',
    buttons: [
      {
        extend: 'selectAll',
        className: 'btn-primary',
        text: selectAllButtonTrans,
        exportOptions: {
          columns: ':visible'
        },
        action: function(e, dt) {
          e.preventDefault()
          dt.rows().deselect();
          dt.rows({ search: 'applied' }).select();
        }
      },
      {
        extend: 'selectNone',
        className: 'btn-primary',
        text: selectNoneButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'copy',
        className: 'btn-default',
        text: copyButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'csv',
        className: 'btn-default',
        text: csvButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'excel',
        className: 'btn-default',
        text: excelButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'pdf',
        className: 'btn-default',
        text: pdfButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'print',
        className: 'btn-default',
        text: printButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'colvis',
        className: 'btn-default',
        text: colvisButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
  });

  $.fn.dataTable.ext.classes.sPageButton = '';
});

    </script>
    <script>
        $(document).ready(function () {
    $(".notifications-menu").on('click', function () {
        if (!$(this).hasClass('open')) {
            $('.notifications-menu .label-warning').hide();
            $.get('/admin/user-alerts/read');
        }
    });
});

    </script>
    @yield('scripts')
</body>

</html>