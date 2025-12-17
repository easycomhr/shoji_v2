<!--begin: Pic-->
<div class="me-7 mb-4">
    <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
        <img src="{{ $employee->avatar_path ?? asset('images/no_avatar.svg') }}" alt="image">
        <div
                class="position-absolute translate-middle bottom-0 start-100 mb-6 {{ $employee->is_terminate ? 'bg-danger' : 'bg-success' }} rounded-circle border border-4 border-body h-20px w-20px"></div>
    </div>
</div>
<!--end::Pic-->

<!--begin::Info-->
<div class="flex-grow-1">
    <!--begin::Title-->
    <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
        <!--begin::User-->
        <div class="d-flex flex-column">
            <!--begin::Name-->
            <div class="d-flex align-items-center mb-2">
                <a href="javascript:;" class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ $employee->name ?? '' }}</a>
                <a href="javascript:;">
                    <i class="ki-duotone ki-verify fs-1 text-primary"><span
                                class="path1"></span><span class="path2" ></span>
                    </i>
                </a>
            </div>
            <!--end::Name-->

            <!--begin::Info-->
            <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                @if(!empty($employee->code))
                    <a href="javascript:;"
                       class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                        <i class="fas fa-address-card"></i> <span class="ml-2" style="margin-left: 5px;">{{ $employee->code ?? '-' }}</span>
                    </a>
                @endif
                    @if(!empty($employee->phone))
                        <a href="javascript:;"
                           class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                            <i class="fas fa-mobile"></i> <span class="ml-2" style="margin-left: 5px;">{{ $employee->phone ?? '-' }}</span>
                        </a>
                    @endif
                @if(!empty($employee->email))
                    <a href="javascript:;" class="d-flex align-items-center text-gray-500 text-hover-primary me-5 mb-2">
                        <i class="fas fa-envelope"></i> <span class="ml-2" style="margin-left: 5px;">{{ $employee->email ?? '-' }}</span>
                    </a>
                @endif


            </div>
            <!--end::Info-->
        </div>
        <!--end::User-->

        <!--begin::Actions-->
        <div class="d-flex my-4">
            <a href="{{ $employee->is_terminate ? route('admin.user.terminate') : route('admin.user.index') }}" class="btn btn-sm btn-primary me-3" >
                <i class="fas fa-chevron-left"></i> {{ __("Back") }}
            </a>

            <!--begin::Menu-->

            <!--end::Menu-->
        </div>
        <!--end::Actions-->
    </div>
    <!--end::Title-->

    <!--begin::Stats-->
    <div class="d-flex flex-wrap flex-stack">
        <!--begin::Wrapper-->
        <div class="d-flex flex-column flex-grow-1 pe-8">
            <!--begin::Stats-->
            <div class="d-flex flex-wrap">
                <!--begin::Stat-->
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3" title="{{ __("Department") }}">
                    <!--begin::Label-->
                    <div class="fw-semibold fs-6 text-gray-500"><i class="fas fa-building"></i> {{ $employee?->department?->name ?? '-' }}</div>
                    <!--end::Label-->
                </div>
                <!--end::Stat-->

                <!--begin::Stat-->
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3" title="{{ __("Position") }}">
                    <!--begin::Label-->
                    <div class="fw-semibold fs-6 text-gray-500"><i class="fas fa-user-tie"></i> {{ $employee?->position?->name ?? '-' }}</div>
                    <!--end::Label-->
                </div>
                <!--end::Stat-->

                <!--begin::Stat-->
                <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3" title="{{ __("Join date") }}">
                    <!--begin::Label-->
                    <div class="fw-semibold fs-6 text-gray-500"><i class="fas fa-handshake-alt"></i> {{ $employee?->join_date ?? '-' }}</div>
                    <!--end::Label-->
                </div>
                <!--end::Stat-->


            </div>
            <!--end::Stats-->
        </div>
        <!--end::Wrapper-->


    </div>
    <!--end::Stats-->

</div>
<!--end::Info-->