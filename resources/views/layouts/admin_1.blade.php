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
    <title>{{ $title ?? 'Admin MGT' }}</title>

    @include('elements.meta_data')

    <link rel="shortcut icon" href="{{  asset('assets/templates/metronic/assets/media/logos/favicon.ico' ) }}" />
    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{  asset('assets/templates/metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css' ) }}" rel="stylesheet" type="text/css" />
    <link href="{{  asset('assets/templates/metronic/assets/plugins/custom/datatables/datatables.bundle.css' ) }}" rel="stylesheet" type="text/css" />
    <!--end::Vendor Stylesheets-->
    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{  asset('assets/templates/metronic/assets/plugins/global/plugins.bundle.css' ) }}" rel="stylesheet" type="text/css" />
    <link href="{{  asset('assets/templates/metronic/assets/css/style.bundle.css' ) }}" rel="stylesheet" type="text/css" />
    <link href="{{  asset('css/custom.css' ) }}" rel="stylesheet" type="text/css" />
    <link href="{{  asset('assets/fonts/awesome/css/all.css' ) }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    <!-- Popperjs -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha256-BRqBN7dYgABqtY9Hd4ynE+1slnEw+roEPFzQ7TRRfcg=" crossorigin="anonymous"></script>
    <!-- Tempus Dominus JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/js/tempus-dominus.min.js" crossorigin="anonymous"></script>

    <!-- Tempus Dominus Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.9.4/dist/css/tempus-dominus.min.css" crossorigin="anonymous">
    <!-- Bootstrap Fileinput CSS -->

</head>
<!--end::Head-->
<!--begin::Body-->
<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
<!--begin::Theme mode setup on page load-->
<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
<!--end::Theme mode setup on page load-->
<!--begin::App-->
<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Page-->
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        <!--begin::Header-->
        @include('layouts.metronic.header')
        <!--end::Header-->
        <!--begin::Wrapper-->
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            <!--begin::Sidebar-->
            @include('layouts.metronic.menu')
            <!--end::Sidebar-->
            <!--begin::Main-->
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <!--begin::Content wrapper-->
                <div class="d-flex flex-column flex-column-fluid">
                    <div id="kt_app_content" class="app-content flex-column-fluid">
                        <!--begin::Content container-->
                        <div id="kt_app_content_container" class="app-container container-xxl">
                            @yield('content')
                        </div>
                    </div>

                </div>
                <!--end::Content wrapper-->
                <!--begin::Footer-->
                @include('layouts.metronic.footer')
                <!--end::Footer-->
            </div>
            <!--end:::Main-->
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Page-->
</div>
<!--end::App-->
<!--begin::Drawers-->

<!--end::Drawers-->
<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up">
        <span class="path1"></span>
        <span class="path2"></span>
    </i>
</div>
<!--end::Scrolltop-->
<!--begin::Javascript-->
<script>var hostUrl = "{{ url('/') }}";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{  asset('assets/templates/metronic/assets/plugins/global/plugins.bundle.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic/assets/js/scripts.bundle.js' ) }}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Vendors Javascript(used for this page only)-->
<script src="{{  asset('assets/templates/metronic/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic/assets/plugins/custom/datatables/datatables.bundle.js' ) }}"></script>
<!--end::Vendors Javascript-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="{{  asset('assets/templates/metronic/assets/js/widgets.bundle.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic/assets/js/custom/widgets.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic/assets/js/custom/apps/chat/chat.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic/assets/js/custom/utilities/modals/upgrade-plan.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic/assets/js/custom/utilities/modals/create-app.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic/assets/js/custom/utilities/modals/new-target.js' ) }}"></script>
<script src="{{  asset('assets/templates/metronic/assets/js/custom/utilities/modals/users-search.js' ) }}"></script>
<script src="{{  asset('assets/templates/adminlte3/plugins/jquery/jquery.min.js' ) }}"></script>
<script src="{{  asset('assets/fonts/awesome/js/all.js' ) }}"></script>

<!-- Bootstrap Fileinput JS -->
<!--end::Custom Javascript-->
<!--end::Javascript-->
@yield('pagescript')

<script>
    $(document).ready(function (e) {
        $(document).on('click', '.js-input-date', function () {
            $(this).siblings('.input-group-text').find('i').trigger('click');
        });
    });
</script>

</body>
<!--end::Body-->
</html>
