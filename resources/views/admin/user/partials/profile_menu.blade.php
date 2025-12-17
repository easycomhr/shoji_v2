@php

    $routeName = request()->route()->getName();
@endphp
<ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
    <!--begin::Nav item-->
    <li class="nav-item mt-2">
        <a class="nav-link text-active-primary ms-0 me-10 py-5 {{ $page == "general" ? 'active' : '' }}" href="{{ route('admin.user.profile', ['page' => 'general', 'code' => $code]) }}">
            {{ __("General") }}
        </a>
    </li>
    <!--end::Nav item-->
    <!--begin::Nav item-->
    <li class="nav-item mt-2">
        <a class="nav-link text-active-primary ms-0 me-10 py-5  {{ $page == 'personal' ? 'active' : '' }}" href="{{ route('admin.user.profile', ['page' => 'personal', 'code' => $code]) }}">
            {{ __("Personal") }}
        </a>
    </li>
    <!--end::Nav item-->
    {{--    <!--begin::Nav item-->--}}
    {{--    <li class="nav-item mt-2">--}}
    {{--        <a class="nav-link text-active-primary ms-0 me-10 py-5 "--}}
    {{--           href="/metronic8/demo1/account/security.html">--}}
    {{--            Security </a>--}}
    {{--    </li>--}}
    {{--    <!--end::Nav item-->--}}
    {{--    <!--begin::Nav item-->--}}
    {{--    <li class="nav-item mt-2">--}}
    {{--        <a class="nav-link text-active-primary ms-0 me-10 py-5 "--}}
    {{--           href="/metronic8/demo1/account/activity.html">--}}
    {{--            Activity </a>--}}
    {{--    </li>--}}
    {{--    <!--end::Nav item-->--}}
    {{--    <!--begin::Nav item-->--}}
    {{--    <li class="nav-item mt-2">--}}
    {{--        <a class="nav-link text-active-primary ms-0 me-10 py-5 "--}}
    {{--           href="/metronic8/demo1/account/billing.html">--}}
    {{--            Billing </a>--}}
    {{--    </li>--}}
    {{--    <!--end::Nav item-->--}}
    {{--    <!--begin::Nav item-->--}}
    {{--    <li class="nav-item mt-2">--}}
    {{--        <a class="nav-link text-active-primary ms-0 me-10 py-5 "--}}
    {{--           href="/metronic8/demo1/account/statements.html">--}}
    {{--            Statements </a>--}}
    {{--    </li>--}}
    {{--    <!--end::Nav item-->--}}
    {{--    <!--begin::Nav item-->--}}
    {{--    <li class="nav-item mt-2">--}}
    {{--        <a class="nav-link text-active-primary ms-0 me-10 py-5 "--}}
    {{--           href="/metronic8/demo1/account/referrals.html">--}}
    {{--            Referrals </a>--}}
    {{--    </li>--}}
    {{--    <!--end::Nav item-->--}}
    {{--    <!--begin::Nav item-->--}}
    {{--    <li class="nav-item mt-2">--}}
    {{--        <a class="nav-link text-active-primary ms-0 me-10 py-5 "--}}
    {{--           href="/metronic8/demo1/account/api-keys.html">--}}
    {{--            API Keys </a>--}}
    {{--    </li>--}}
    {{--    <!--end::Nav item-->--}}
    {{--    <!--begin::Nav item-->--}}
    {{--    <li class="nav-item mt-2">--}}
    {{--        <a class="nav-link text-active-primary ms-0 me-10 py-5 "--}}
    {{--           href="/metronic8/demo1/account/logs.html">--}}
    {{--            Logs </a>--}}
    {{--    </li>--}}
    {{--    <!--end::Nav item-->--}}
</ul>
