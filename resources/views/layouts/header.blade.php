<header class="clearFix">
    <h1>
        <a href="{{ route('admin.base') }}"><img src="{{asset('images/site_id.gif')}}" width="117" height="43" alt="管理画面"></a>
    </h1>
    {{-- Todo --}}
    <p class="info_msg">
        @foreach($listNotifyHeader as $notify)
            @if(auth()->guard('bb_admin')->user()?->level == config('constants.CONST_LEVEL_AFFILIATE'))
                @continue($notify->view_agent != 1)
            @elseif((auth()->guard('bb_admin')->user()?->level != config('constants.CONST_LEVEL_AFFILIATE')))
                @continue($notify->view_admin != 1)
            @endif
            <a href="{{ route('admin.notify.detail', $notify->id) }}">{{ date('Y/m/d', strtotime($notify->day)) }}({{ timestamp_with_weekname(strtotime($notify->day)) }})　{{ $notify->title }}</a><br>
        @endforeach
    </p>
    <nav id="header_navi">
        <ul>
            <li class="logout">
                <a href="{{route('logout')}}"><span>ログアウト</span></a>
            </li>
            <li class="info">
                <span><a href="{{ route('admin.notify.index') }}">お知らせ一覧</a></span>
            </li>
            <li class="info">
                <span>
                    <a href="{{ route('admin.information.index') }}">通知一覧</a>
                    <a href="{{ route('admin.information.index') }}" class="info_count" id="info_count">0</a>
                </span>
            </li>
        </ul>
    </nav>
</header>
