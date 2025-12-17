<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: MetronicProduct Version: 8.2.6
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->
<head>
    <title>HRMS - The World's #1 Human Resource Management by EASYCOM</title>
    <meta charset="utf-8" />
    <meta name="description" content="The most advanced Bootstrap 5 Admin Theme with 40 unique prebuilt layouts on Themeforest trusted by 100,000 beginners and professionals. Multi-demo, Dark Mode, RTL support and complete React, Angular, Vue, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel versions. Grab your copy now and get life-time updates for free." />
    <meta name="keywords" content="metronic, bootstrap, bootstrap 5, angular, VueJs, React, Asp.Net Core, Rails, Spring, Blazor, Django, Express.js, Node.js, Flask, Symfony & Laravel starter kits, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Metronic - The World's #1 Selling Bootstrap Admin Template by KeenThemes" />
    <meta property="og:url" content="https://keenthemes.com/metronic" />
    <meta property="og:site_name" content="Metronic by Keenthemes" />

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="canonical" href="http://preview.keenthemes.comindex.html" />
    <link rel="shortcut icon" href="{{  asset('assets/templates/metronic20/assets/media/logos/favicon.ico' ) }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{  asset('assets/templates/metronic20/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css' ) }}" rel="stylesheet" type="text/css" />
    <link href="{{  asset('assets/templates/metronic20/assets/plugins/custom/datatables/datatables.bundle.css' ) }}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{  asset('assets/templates/metronic20/assets/plugins/global/plugins.bundle.css' ) }}" rel="stylesheet" type="text/css" />
    <link href="{{  asset('assets/templates/metronic20/assets/css/style.bundle.css' ) }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->


    <link href="{{  asset('assets/plugins/extjs/css/css-neptune/classic/resources/Admin-all.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{  asset('assets/libraries/toastr/toastr.min.css' ) }}">
    <link href="{{  asset('css/custom-admin.css') }}" rel="stylesheet" type="text/css" />
    <script src="{{  asset('assets/plugins/extjs/ext/ext-all.js') }}"></script>

    <script>

        const screenWidth = window.screen.width;
        const screenHeight = window.screen.height;
        const centerX = screenWidth / 2;
        const centerY = screenHeight / 2;

        var APP = {};
        var _token = "{{ csrf_token() }}";
        APP.TimeOut = 9999999;
        APP.timeFormat = "H:i:s";
        APP.itemsPerPage = 10;
        APP.centerX = centerX;
        APP.centerY = centerY;
        var currentDate = "{{ date('d/m/Y') }}";
        var loading_image_path = "{{ asset('images/preloader.gif') }}";
    </script>

    @include('admin.elements.include_variables')

</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_body" class="header-extended header-fixed header-tablet-and-mobile-fixed">
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
<!--end::Theme mode setup on page load-->
<!--begin::Main-->
<!--begin::Root-->
<div class="d-flex flex-column flex-root">
    <!--begin::Page-->
    <div class="page d-flex flex-row flex-column-fluid">
        <!--begin::Wrapper-->
        <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
            <!--begin::Header-->
            @include('layouts.metronic20.header')
            <!--end::Header-->
            <!--begin::Toolbar-->

            <!--end::Toolbar-->
            <!--begin::Container-->
            <div id="kt_content_container" class="d-flex flex-column-fluid align-items-start container-xxl">
                <!--begin::Post-->
                <div class="content flex-row-fluid" id="kt_content">
                    @yield('content')
                </div>
                <!--end::Post-->
            </div>
            <!--end::Container-->
            <!--begin::Footer-->
            @include('layouts.metronic20.footer')
            <!--end::Footer-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
<!--end::Root-->

<!--end::Main-->
<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up">
        <span class="path1"></span>
        <span class="path2"></span>
    </i>
</div>
<!--end::Scrolltop-->
<!--begin::Modals-->

<!--end::Modals-->
<!--begin::Javascript-->
<script>var hostUrl = "{{ url('home_page') }}";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{  asset('assets/templates/metronic20/assets/plugins/global/plugins.bundle.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic20/assets/js/scripts.bundle.js' ) }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{  asset('assets/templates/metronic20/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js' ) }}"></script>

<script src="{{  asset('assets/templates/metronic20/assets/plugins/custom/datatables/datatables.bundle.js' ) }}"></script>
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="{{  asset('assets/templates/metronic20/assets/js/widgets.bundle.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic20/assets/js/custom/widgets.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic20/assets/js/custom/apps/chat/chat.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic20/assets/js/custom/utilities/modals/upgrade-plan.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic20/assets/js/custom/utilities/modals/create-campaign.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic20/assets/js/custom/utilities/modals/create-app.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic20/assets/js/custom/utilities/modals/users-search.js' ) }}"></script>

<script src="{{ asset('assets/plugins/jquery-number/jquery.number.js?t=1') }}"></script>
<script src="{{  asset('assets/plugins/tagify/tagify.min.js') }}"></script>
<link rel="stylesheet" href="{{  asset('assets/plugins/tagify/tagify.css') }}">

<script src="{{  asset('assets/libraries/toastr/toastr.min.js' ) }}"></script>
<script src="{{  asset('js/notification.js' ) }}"></script>
<script src="{{  asset('js/admin/common.js?t=1') }}"></script>

<script type="text/javascript">



    $(function () {
        $('input.input-number').number( true, 0 );
        $(".tooltips").tooltip();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });

    $(document).ready(function (e){



        document.querySelectorAll('.area-date-dominus').forEach(container => {
            const datepicker = new tempusDominus.TempusDominus(container, {
                localization: {
                    locale: "vi",
                    startOfTheWeek: 1,
                    format: "dd/MM/yyyy"
                },
                display: {
                    components: {
                        clock: false,
                        hours: false,
                        minutes: false,
                        seconds: false
                    }
                }
            });

            const input = container.querySelector('.input-date-dominus');

            // Kích hoạt khi click vào input
            input.addEventListener('click', function (e) {
                e.preventDefault();
                datepicker.toggle();
            });
        });


    });


</script>

@if(session('success'))
    <script>
        $(function(){
            Notification.showSuccess("{{ session('success') }}");
        });
    </script>
@endif

@if(session('error'))
    <script>
        $(function(){
            Notification.showError("{{ session('error') }}");
        });
    </script>
@endif

@yield('pagescript')

<!--end::Custom Javascript-->
<!--end::Javascript-->
</body>
<!--end::Body-->
</html>
