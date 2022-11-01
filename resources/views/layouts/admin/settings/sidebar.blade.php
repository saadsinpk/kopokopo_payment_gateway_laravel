<div class="card ">
    <div class="card-header">
        <h4 class="card-title text-black-50">{{__("Settings Menu")}}</h4>
    </div>
    <div class="card-body p-0">
        <ul class="nav nav-pills flex-column">
            @can('admin.settings.general')
                <li class="nav-item"><a href="{{route('admin.settings.general')}}" class="nav-link {{ Request::is('admin/settings/general') ? 'active' : '' }}"><i class="fas fa-cog"></i> {{__('General Settings')}}</a></li>
            @endcan
            @can('admin.settings.app')
                <li class="nav-item"><a href="{{route('admin.settings.app')}}" class="nav-link {{ Request::is('admin/settings/app') ? 'active' : '' }}"><i class="fas fa-mobile"></i> {{__('App Settings')}}</a></li>
            @endcan
            @can('admin.settings.pricing')
                <li class="nav-item"><a href="{{route('admin.settings.pricing')}}" class="nav-link {{ Request::is('admin/settings/pricing') ? 'active' : '' }}"><i class="fas fa-money-bill"></i> {{__('Pricing')}}</a></li>
            @endcan
            @can('admin.settings.social_login')
                <li class="nav-item"><a href="{{route('admin.settings.social_login')}}" class="nav-link {{ Request::is('admin/settings/social_login') ? 'active' : '' }}"><i class="fas fa-share-alt"></i> {{__('Social Login')}}</a></li>
            @endcan
            @can('admin.settings.payments_api')
                <li class="nav-item"><a href="{{route('admin.settings.payments_api')}}" class="nav-link {{ Request::is('admin/settings/payments_api') ? 'active' : '' }}"><i class="fas fa-credit-card"></i> {{__('Payments API')}}</a></li>
            @endcan
            @can('admin.settings.notifications')
                <li class="nav-item"><a href="{{route('admin.settings.notifications')}}" class="nav-link {{ Request::is('admin/settings/notifications') ? 'active' : '' }}"><i class="fas fa-envelope"></i> {{__('Notifications')}}</a></li>
            @endcan
            @can('admin.roles.index')
                <li class="nav-item"><a href="{{route('admin.roles.index')}}" class="nav-link {{ Request::is('admin/settings/roles*') ? 'active' : '' }}"><i class="fas fa-lock"></i> {{__('Roles')}}</a></li>
            @endcan
            @can('admin.permissions.index')
                <li class="nav-item"><a href="{{route('admin.permissions.index')}}" class="nav-link {{ Request::is('admin/settings/permissions*') ? 'active' : '' }}"><i class="fas fa-user-lock"></i> {{__('Permissions')}}</a></li>
            @endcan
            @can('admin.settings.currency')
                <li class="nav-item"><a href="{{route('admin.settings.currency')}}" class="nav-link {{ Request::is('admin/settings/currency') ? 'active' : '' }}"><i class="fas fa-coins"></i> {{__('Currency')}}</a></li>
            @endcan
            @can('admin.offlinePaymentMethods.index')
                <li class="nav-item"><a href="{{route('admin.offlinePaymentMethods.index')}}" class="nav-link {{ Request::is('admin/settings/offlinePaymentMethods*') ? 'active' : '' }}"><i class="fas fa-dollar-sign"></i> {{__('Offline Payment Methods')}}</a></li>
            @endcan
            @can('admin.settings.legal')
                <li class="nav-item"><a href="{{route('admin.settings.legal')}}" class="nav-link {{ Request::is('admin/settings/legal') ? 'active' : '' }}"><i class="fas fa-gavel"></i> {{__('Legal')}}</a></li>
            @endcan
        </ul>
    </div>
</div>
