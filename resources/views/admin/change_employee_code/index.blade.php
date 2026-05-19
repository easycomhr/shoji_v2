@extends("layouts.app")
@section('title', $title)
@section("content")

    <div class="post d-flex flex-column-fluid">
        <div id="kt_content_container" class="container-fluid">
            <div class="row g-7 justify-content-center">
                <div class="col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div id="change-employee-code-form"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var lblPageTitle = "{{ __($title) }}";
        var URL_PROCESS  = "{{ route('admin.change_employee_code.process') }}";
        var CSRF_TOKEN   = "{{ csrf_token() }}";
    </script>

@endsection

@section("pagescript")
    <script src="{{ asset('js/admin/change_employee_code/index.js?t='.config('constant.app_version')) }}"></script>
@endsection
