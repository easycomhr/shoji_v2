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
        var URL_DATA = "{{ route('admin.labour_contracts.search') }}";
        var URL_STORE = "{{ route('admin.labour_contracts.store') }}";
        var URL_DELETE = "{{ route('admin.labour_contracts.destroy') }}";
        var URL_EXPORT = "{{ route('admin.labour_contracts.export') }}";
        var CSRF_TOKEN = "{{ csrf_token() }}";
    </script>

@endsection

@section("pagescript")
    <script src="{{asset('js/admin/labour_contract/index.js?t='.config('constant.app_version'))}}"></script>
@endsection
