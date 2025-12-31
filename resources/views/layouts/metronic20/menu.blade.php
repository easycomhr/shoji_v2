<div class="tab-content" data-kt-scroll="true" data-kt-scroll-activate="{default: true, lg: false}" data-kt-scroll-height="auto" data-kt-scroll-offset="70px">
    <!--begin::Tab panel-->

    @foreach(config('menus.hrms') as $key => $menu)
        @php

            $currentRouteName = \Illuminate\Support\Facades\Route::currentRouteName();
            // Lấy parent code từ route hiện tại
            $currentParentCode = config("menus.route_to_parent")[$currentRouteName] ?? '';

            // Check active: so sánh menu code với parent code từ route
            $active_tab = ($menu['code'] === $currentParentCode) ? 'active' : '';

            // Fallback: nếu không tìm thấy mapping, active tab đầu tiên
            if (empty($currentParentCode) && $key == 0) {
                $active_tab = 'active';
            }

        @endphp
        <div class="tab-pane fade {{ $active_tab ? 'active show' : '' }}" id="kt_header_navs_tab_{{ $menu['code'] }}" >
            <!--begin::Menu wrapper-->
            <div class="header-menu flex-column align-items-stretch flex-lg-row">
                <!--begin::Menu-->
                <div class="menu menu-rounded menu-column menu-lg-row menu-root-here-bg-desktop menu-active-bg menu-title-gray-700 menu-state-primary menu-arrow-gray-500 fw-semibold align-items-stretch flex-grow-1 px-2 px-lg-0" id="#kt_header_menu" data-kt-menu="true">

                    @foreach($menu['children'] as $submenu)
                        <!--begin:Menu item-->
                        <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start" class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">

                            @if(!empty($submenu['route_name']))
                                <a class="menu-link py-1 {{ $submenu['route_name'] == \Illuminate\Support\Facades\Route::currentRouteName() ? 'active' : '' }}" href="{{ route($submenu['route_name']) }}"   data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                                    <span class="menu-icon">
                                                        <i class="ki-duotone ki-abstract-26 fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                    <span class="menu-title">{{ __($submenu['name'] ?? '') }}</span>
                                </a>
                            @else
                                <!--begin:Menu link-->
                                <span class="menu-link py-3">
                                                    <span class="menu-icon">
                                                        <i class="ki-duotone ki-abstract-26 fs-2">
                                                            <span class="path1"></span>
                                                            <span class="path2"></span>
                                                        </i>
                                                    </span>
                                                    <span class="menu-title">{{ __($submenu['name'] ?? '') }}</span>
                                                    <span class="menu-arrow d-lg-none"></span>
                                                </span>
                                <!--end:Menu link-->
                                <!--begin:Menu sub-->
                                <div class="menu-sub menu-sub-lg-down-accordion menu-sub-lg-dropdown px-lg-1 py-lg-1 w-lg-300px">

                                    @foreach($submenu['children'] as $child)
                                        <!--begin:Menu item-->
                                        <div class="menu-item">
                                            <!--begin:Menu link-->
                                            <a class="menu-link py-1" href="{{ !empty($child['route_name']) ? route($child['route_name']) : 'javascript:;' }}"   data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss="click" data-bs-placement="right">
                                                                <span class="menu-icon">
                                                                    <i class="ki-duotone ki-abstract-26 fs-2">
                                                                        <span class="path1"></span>
                                                                        <span class="path2"></span>
                                                                    </i>
                                                                </span>
                                                <span class="menu-title">{{ __($child['name'] ?? '') }}</span>
                                            </a>
                                            <!--end:Menu link-->
                                        </div>
                                        <!--end:Menu item-->
                                    @endforeach


                                </div>
                                <!--end:Menu sub-->
                            @endif


                        </div>
                        <!--end:Menu item-->
                    @endforeach


                </div>
                <!--end::Menu-->
            </div>
            <!--end::Menu wrapper-->
        </div>
    @endforeach


    <!--begin::Tab panel-->
    <div class="tab-pane fade" id="kt_header_navs_tab_5">
        <!--begin::Wrapper-->
        <div class="d-flex flex-column flex-lg-row flex-lg-stack flex-wrap gap-2 px-4 px-lg-0">
            <div class="d-flex flex-column flex-lg-row gap-2">
                <a class="btn btn-sm btn-light-primary fw-bold" href="https://preview.keenthemes.com/html/metronic/docs">Documentation</a>
                <a class="btn btn-sm btn-light-success fw-bold" href="documentation/getting-started/video-tutorials.html">Video Tutorials</a>
                <a class="btn btn-sm btn-light-danger fw-bold" href="https://preview.keenthemes.com/metronic8/demo20/layout-builder.html">Layout Builder</a>
            </div>
            <div class="d-flex flex-column flex-lg-row gap-2">
                <a class="btn btn-sm btn-light-info fw-bold" href="documentation/getting-started/changelog.html">Changelog</a>
            </div>
        </div>
        <!--end::Wrapper-->
    </div>
    <!--end::Tab panel-->
</div>