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
                    <div class="card shadow-sm" style="width: 650px; margin: 0 auto;">
                        <div class="card-body card-scroll">
                            <div class="row">
                                <div class="col-md-12 table-responsive-lg" id="import-form">

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
        var lblPageTitle = "{{ __($title) }}";
        var BACK_URL = "{{ route('admin.leave.index') }}";
        var IMPORT_URL = "{{ route('admin.leave.import_data') }}";
        var EXAMPLE_FILE_URL = "{{ asset('assets/samples/sample_import_user_leave.xlsx') }}";
    </script>

@endsection

@section("pagescript")
    <script src="{{asset('js/admin/leave/import.js?t='.config('constant.app_version'))}}"></script>
@endsection


