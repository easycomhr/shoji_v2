@extends("layouts.app")
@section('title', $title ?? 'User Lists')
@section("content")

    <div class="card mb-5 mb-xl-8">
        <!--begin::Header-->
        <div class="card-header border-0 pt-5">
            <h3 class="card-title align-items-start flex-column">
                <span class="card-label fw-bold fs-3 mb-1">{{ $title ?? '' }}</span>
            </h3>
            <div class="card-toolbar">
                <a href="{{ route('admin.user.import') }}" class="btn btn-sm btn-light-info mx-2">
                    <i class="fas fa-upload"></i> Upload Excel
                </a>

                <a href="{{ route('admin.user.export_excel') }}" class="btn btn-sm btn-light-info mx-2">
                    <i class="fas fa-download"></i> Export Excel
                </a>

                <a href="{{ route('admin.user.add') }}" class="btn btn-sm btn-light-primary">
                    <i class="ki-duotone ki-plus fs-2"></i>New User
                </a>
            </div>
        </div>
        <!--end::Header-->
        <!--begin::Body-->
        <div class="card-body py-3">

            @include('admin.elements._notification')

            <!--begin::Table container-->
            <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-bordered">
                    <!--begin::Table head-->
                    <thead>
                    <tr class="fw-bold text-muted bg-light">
                        <th class="ps-4 min-w-125px rounded-start text-center">Avatar</th>
                        <th class="ps-4 min-w-325px rounded-start">Name</th>
                        <th class="min-w-200px">Role</th>
                        <th class="min-w-100px text-center">Login</th>
                        <th class="min-w-150px text-center">Lasted Login</th>
                        <th class="min-w-100px text-center">Action</th>
                    </tr>
                    </thead>
                    <!--end::Table head-->
                    <!--begin::Table body-->
                    <tbody>

                    @foreach($list as $key => $item)

                        <tr>
                            <td class="text-center">
                                @if(!empty($item->avatar))
                                    <div class="symbol symbol-50px me-5">
                                        <img src="{{ asset('assets/avatars/' . $item->avatar) }}" class="" alt="" />
                                    </div>
                                @else
                                    <div class="symbol symbol-45px me-5">
                                        <span class="symbol-label bg-light-danger text-danger fw-bold">U</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex justify-content-start flex-column">
                                        <a href="#" class="text-gray-900 fw-bold text-hover-primary mb-1 fs-6">{{ $item->name ?? '' }}</a>
                                        <span class="text-muted fw-semibold text-muted d-block fs-7">{{ $item->email ?? '' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                {{ $item->role_name ?? '' }}
                            </td>

                            <td class="text-center">
                                <a href="javascript:;"
                                   data-id="{{ $item->id }}"
                                   class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm me-1 js-on-change-login {{ $item->is_login ? 'active' : '' }}">
                                    <i class="ki-duotone ki-switch fs-2">
                                        <span class="path1"></span>
                                        <span class="path2"></span>
                                    </i>
                                </a>
                            </td>

                            <td class="text-center">
                                {{ $item->lasted_login ?? '' }}
                            </td>

                            <td class="text-center">

                                <a href="{{ route('admin.user.edit', $item->id) }}" class="btn btn-icon btn-light-primary btn-sm me-1">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="javascript:;" class="btn btn-icon btn-light-danger btn-sm js-on-delete" data-id="{{ $item->id }}">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                    <!--end::Table body-->
                </table>
                <!--end::Table-->
            </div>

            @if($list)
                <div class="pagination-custom">
                    {!! $list->appends(request()->input())->links('pagination::bootstrap-4') !!}
                </div>
            @endif

            <!--end::Table container-->
        </div>
        <!--begin::Body-->
    </div>

    @include('admin.elements.modal_confirm')
    @include('admin.user.elements.modal_confirm_login')
@endsection

<script>
    const DELETE_URL = "{{ route('admin.user.destroy') }}";
</script>

@section("pagescript")
    <script src="{{ asset('js/admin/user/index.js?t='.config('constants.app_version') )}}"></script>
@endsection


