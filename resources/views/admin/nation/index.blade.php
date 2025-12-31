@extends("layouts.app")
@section('title', $title)
@section("content")

    <div class="post d-flex flex-column-fluid">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-fluid">
            <!--begin::Contacts App- Add New Contact-->
            <div class="row g-7">

                <!--begin::Search-->
                <div class="col-lg-12">
                    <!--begin::Contacts-->
                    <div class="card shadow-sm">

                        <div class="card-body card-scroll">
                            <div class="row">
                                <div class="col-md-12 table-responsive-lg" id="main-gird">

                                </div>
                            </div>
                        </div>

                    </div>
                    <!--end::Contacts-->
                </div>
                <!--end::Search-->

            </div>
            <!--end::Contacts App- Add New Contact-->
        </div>
        <!--end::Container-->
    </div>

    <script>
        var mainGird;
        var lblPageTitle = "{{ __($title) }}";
        var lblCode = "{{ __('Code') }}";
        var lblName = "{{ __('Name') }}";
        var URL_DATA = "{{ route('admin.nation.search') }}";
        var URL_STORE = "{{ route('admin.nation.store') }}";
        var URL_DELETE = "{{ route('admin.nation.destroy') }}";
    </script>

@endsection

@section("pagescript")
    <script src="{{asset('js/admin/nation/index.js?t='.config('constant.app_version'))}}"></script>
@endsection


