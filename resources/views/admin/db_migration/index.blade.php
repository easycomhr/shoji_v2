@extends("layouts.app")
@section('title', $title)
@section("content")

    <div class="post d-flex flex-column-fluid">
        <!--begin::Container-->
        <div id="kt_content_container" class="container-fluid">
            <div class="row g-7">
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-body card-scroll">
                            <div class="row">
                                <div class="col-md-12" id="main-gird"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Container-->
    </div>

    <script>
        var URL_PREVIEW      = "{{ route('admin.db-migration.preview') }}";
        var URL_SYNC         = "{{ route('admin.db-migration.sync') }}";
        var MIGRATION_TABLES = {!! json_encode($migrationTables) !!};
        var CSRF_TOKEN       = "{{ csrf_token() }}";
    </script>

@endsection

@section("pagescript")
    <script src="{{ asset('js/admin/db_migration/index.js?t='.config('constant.app_version')) }}"></script>
@endsection
