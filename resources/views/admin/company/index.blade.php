@extends("layouts.app")
@section('title', $title)
@section("content")

    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
        <!--begin::Header-->
        <!--end::Header-->
        <!--begin::Wrapper-->
        <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
            <!--begin::Main-->
            <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
                <!--begin::Content wrapper-->
                <div class="d-flex flex-column flex-column-fluid">
                    <div id="kt_app_content" class="app-content flex-column-fluid">
                        <!--begin::Content container-->
                        <div id="kt_app_content_container" class="app-container container-xxl">

                            @include('admin.company.partials.profile_general')

                        </div>
                    </div>

                </div>
                <!--end::Content wrapper-->
                <!--begin::Footer-->
{{--                @include('layouts.metronic.footer')--}}
                <!--end::Footer-->
            </div>
            <!--end:::Main-->
        </div>
        <!--end::Wrapper-->
    </div>



    <script>



        {{--var mainGird;--}}
        {{--var lblPageTitle = "{{ __($title) }}";--}}
        {{--var lblCode = "{{ __('') }}";--}}
        {{--var lblName = "{{ __('') }}";--}}
        {{--var URL_DATA = "{{ route('admin.user.search') }}";--}}
        {{--var URL_STORE = "{{ route('admin.user.store') }}";--}}
        {{--var URL_DELETE = "{{ route('admin.user.destroy') }}";--}}
    </script>

@endsection

@section("pagescript")

    <script src="{{asset('js/admin/user/profile.js?t='.config('constants.app_version'))}}"></script>
@endsection


