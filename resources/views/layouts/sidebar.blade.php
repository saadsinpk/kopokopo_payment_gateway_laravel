<aside id="sidebar-wrapper sidebar-{{setting('theme_contrast')}}-{{setting('theme_color')}}">
    <div class="sidebar-brand {{setting('logo_bg_color','bg-white')}}" style="white-space: nowrap">
        <img class="navbar-brand-full app-header-logo" src="{{$app_logo}}" height="45"
             alt="{{setting('app_name')}} Logo">
        <a href="{{ url('/') }}">{{setting('app_name')}}</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
        <a href="{{ url('/') }}" class="small-sidebar-text">
            <img class="navbar-brand-full" src="{{$app_logo}}" width="45px" alt=""/>
        </a>
    </div>
    <ul class="sidebar-menu">
        @include('layouts.menu')
    </ul>
</aside>
