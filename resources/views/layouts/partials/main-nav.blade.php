<div class="main-nav">
    <!-- Sidebar Logo -->
    <div class="logo-box">
        <a href="{{ route('second', [ 'dashboards' , 'index']) }}" class="logo-dark">
            <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
            <img src="/images/logo-dark.png" class="logo-lg" alt="logo dark">
        </a>

        <a href="{{ route('second', [ 'dashboards' , 'index']) }}" class="logo-light">
            <img src="/images/logo-sm.png" class="logo-sm" alt="logo sm">
            <img src="/images/logo-light.png" class="logo-lg" alt="logo light">
        </a>
    </div>

    <!-- Menu Toggle Button (sm-hover) -->
    <button type="button" class="button-sm-hover" aria-label="Show Full Sidebar">
        <iconify-icon icon="solar:double-alt-arrow-right-bold-duotone" class="button-sm-hover-icon"></iconify-icon>
    </button>

    <div class="scrollbar" data-simplebar>
        <ul class="navbar-nav" id="navbar-nav">

            <li class="menu-title">{{ __('messages.general') }}</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', [ 'dashboards' , 'index']) }}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:widget-5-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.dashboard') }} </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarProducts" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarProducts">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:t-shirt-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.products') }} </span>
                </a>
                <div class="collapse" id="sidebarProducts">
                    <ul class="nav sub-navbar-nav">
                    <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'products', 'list'])}}">{{ __('messages.list') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'products', 'grid'])}}">{{ __('messages.grid') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'products', 'detail'])}}">{{ __('messages.details') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'products', 'edit'])}}">{{ __('messages.edit') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'products', 'create'])}}">{{ __('messages.create') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCategory" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCategory">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:clipboard-list-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.category') }} </span>
                </a>
                <div class="collapse" id="sidebarCategory">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'category', 'list'])}}">{{ __('messages.list') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'category', 'edit'])}}">{{ __('messages.edit') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'category', 'create'])}}">{{ __('messages.create') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarInventory" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarInventory">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:box-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.inventory') }} </span>
                </a>
                <div class="collapse" id="sidebarInventory">
                    <ul class="nav sub-navbar-nav">

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'inventory', 'warehouse'])}}">{{ __('messages.warehouse') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'inventory', 'received-orders'])}}">{{ __('messages.received_orders') }}</a>
                        </li>

                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarOrders" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarOrders">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:bag-smile-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.orders') }} </span>
                </a>
                <div class="collapse" id="sidebarOrders">
                    <ul class="nav sub-navbar-nav">

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'orders', 'list'])}}">{{ __('messages.list') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'orders', 'details'])}}">{{ __('messages.details') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'orders', 'cart'])}}">{{ __('messages.cart') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'orders', 'checkout'])}}">{{ __('messages.checkout') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarPurchases" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarPurchases">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:card-send-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.purchases') }} </span>
                </a>
                <div class="collapse" id="sidebarPurchases">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'purchase', 'list'])}}">{{ __('messages.list') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'purchase', 'order'])}}">{{ __('messages.order') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'purchase', 'return'])}}">{{ __('messages.return') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAttributes" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarAttributes">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:confetti-minimalistic-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.attributes') }} </span>
                </a>
                <div class="collapse" id="sidebarAttributes">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'attributes', 'list'])}}">{{ __('messages.list') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'attributes', 'edit'])}}">{{ __('messages.edit') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'attributes', 'create'])}}">{{ __('messages.create') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarInvoice" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarInvoice">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:bill-list-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.invoices') }} </span>
                </a>
                <div class="collapse" id="sidebarInvoice">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'invoice', 'list'])}}">{{ __('messages.list') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['general', 'invoice', 'details'])}}">{{ __('messages.details') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{route('third', ['general', 'invoice', 'create'])}}">{{ __('messages.create') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['general', 'settings'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:settings-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.settings') }} </span>
                </a>
            </li>

            <li class="menu-title mt-2">{{ __('messages.users') }}</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['users', 'pages-profile'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.profile') }} </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarRoles" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarRoles">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:user-speak-rounded-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.roles') }} </span>
                </a>
                <div class="collapse" id="sidebarRoles">
                    <ul class="nav sub-navbar-nav">
                        <ul class="nav sub-navbar-nav">
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{ route('third', ['users', 'role', 'list'])}}">{{ __('messages.list') }}</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{ route('third', ['users', 'role', 'edit'])}}">{{ __('messages.edit') }}</a>
                            </li>
                            <li class="sub-nav-item">
                                <a class="sub-nav-link" href="{{ route('third', ['users', 'role', 'create'])}}">{{ __('messages.create') }}</a>
                            </li>
                        </ul>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['users', 'pages-permission'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:checklist-minimalistic-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.permissions') }} </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCustomers" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCustomers">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:users-group-two-rounded-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.customers') }} </span>
                </a>
                <div class="collapse" id="sidebarCustomers">
                    <ul class="nav sub-navbar-nav">

                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['users', 'customer', 'list'])}}">{{ __('messages.list') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['users', 'customer',  'details'])}}">{{ __('messages.details') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarSellers" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarSellers">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:shop-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.sellers') }} </span>
                </a>
                <div class="collapse" id="sidebarSellers">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['users', 'seller', 'list'])}}">{{ __('messages.list') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['users', 'seller', 'details'])}}">{{ __('messages.details') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['users', 'seller', 'edit'])}}">{{ __('messages.edit') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['users', 'seller', 'create'])}}">{{ __('messages.create') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="menu-title mt-2">{{ __('messages.other') }}</li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCoupons" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCoupons">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:leaf-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.coupons') }} </span>
                </a>
                <div class="collapse" id="sidebarCoupons">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('second', ['other', 'coupons-list'])}}">{{ __('messages.list') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('second', ['other', 'coupons-add'])}}">{{ __('messages.add') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['other', 'pages-review'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:chat-square-like-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.reviews') }} </span>
                </a>
            </li>

            <li class="menu-title mt-2">{{ __('messages.other_apps') }}</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['apps', 'chat'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:chat-round-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.chat') }} </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['apps', 'email'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:mailbox-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.email') }} </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['apps', 'calendar'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:calendar-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.calendar') }} </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['apps', 'todo'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:checklist-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.todo') }} </span>
                </a>
            </li>

            <li class="menu-title mt-2">{{ __('messages.support') }}</li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['support', 'help-center'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:help-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.help_center') }} </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['support', 'faqs'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:question-circle-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.faqs') }} </span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['support', 'privacy-policy'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:document-text-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.privacy_policy') }} </span>
                </a>
            </li>

            <li class="menu-title mt-2">{{ __('messages.custom') }}</li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarPages" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarPages">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:gift-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.pages') }} </span>
                </a>
                <div class="collapse" id="sidebarPages">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['custom', 'pages', 'starter'])}}">{{ __('messages.welcome_page') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['custom', 'pages', 'coming-soon'])}}">{{ __('messages.coming_soon') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['custom', 'pages', 'timeline'])}}">{{ __('messages.timeline') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['custom', 'pages', 'pricing'])}}">{{ __('messages.pricing') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['custom', 'pages', 'maintenance'])}}">{{ __('messages.maintenance') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['custom', 'pages', 'error-404'])}}">{{ __('messages.error_404') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['custom', 'pages', 'error-404-alt'])}}">{{ __('messages.error_404') }}</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarAuthentication" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarAuthentication">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:lock-keyhole-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.authentication') }} </span>
                </a>
                <div class="collapse" id="sidebarAuthentication">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('second', [ 'auth' , 'login']) }}">{{ __('messages.sign_in') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('second', [ 'auth' , 'register']) }}">{{ __('messages.sign_up') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('second', [ 'auth' , 'reset-password']) }}">{{ __('messages.reset_password') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('second', [ 'auth' , 'lock-screen']) }}">Lock
                                Screen</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('second', ['custom', 'widgets'])}}">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:atom-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.widgets') }} </span>
                    <span class="badge bg-info badge-pill text-end">9+</span>
                </a>
            </li>

            <li class="menu-title mt-2">{{ __('messages.components') }}</li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarBaseUI" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarBaseUI">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:bookmark-square-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.base_ui') }} </span>
                </a>
                <div class="collapse" id="sidebarBaseUI">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'accordion'])}}">Accordion</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'alerts'])}}">Alerts</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'avatar'])}}">Avatar</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'badge'])}}">Badge</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'breadcrumb'])}}">Breadcrumb</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'buttons'])}}">Buttons</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'cards'])}}">Card</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'carousel'])}}">Carousel</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'collapse'])}}">Collapse</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'dropdown'])}}">Dropdown</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'list-group'])}}">List Group</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'modal'])}}">Modal</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'tabs'])}}">Tabs</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'offcanvas'])}}">Offcanvas</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'pagination'])}}">Pagination</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'placeholder'])}}">Placeholders</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'popovers'])}}">Popovers</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'progress'])}}">Progress</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'scrollspy'])}}">Scrollspy</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'spinners'])}}">Spinners</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'toasts'])}}">Toasts</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'ui', 'tooltips'])}}">Tooltips</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarExtendedUI" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarExtendedUI">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:case-round-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.advanced_ui') }} </span>
                </a>
                <div class="collapse" id="sidebarExtendedUI">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'advanced', 'rating'])}}">Ratings</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'advanced', 'sweet-alerts'])}}">Sweet Alert</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'advanced', 'swiper-slider'])}}">Swiper Slider</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'advanced', 'scrollbar'])}}">Scrollbar</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'advanced', 'toastify'])}}">Toastify</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarCharts" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarCharts">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:pie-chart-2-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.charts') }} </span>
                </a>
                <div class="collapse" id="sidebarCharts">
                    <ul class="nav sub-navbar-nav">
                       <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-area'])}}">Area</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-bar'])}}">Bar</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts','apex-bubble'])}}">Bubble</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts','apex-candlestick'])}}">Candlestick</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts','apex-column'])}}">Column</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts','apex-heatmap'])}}">Heatmap</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-line'])}}">Line</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-mixed'])}}">Mixed</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-timeline'])}}">{{ __('messages.timeline') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-boxplot'])}}">Boxplot</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-treemap'])}}">Treemap</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-pie'])}}">Pie</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components',  'charts', 'apex-radar'])}}">Radar</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-radialbar'])}}">RadialBar</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-scatter'])}}">Scatter</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'charts', 'apex-polar-area'])}}">Polar Area</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarForms" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarForms">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:book-bookmark-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.forms') }} </span>
                </a>
                <div class="collapse" id="sidebarForms">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components' ,'forms', 'basic'])}}">Basic Elements</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components' ,'forms', 'checkbox-radio'])}}">Checkbox &amp; Radio</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components' ,'forms', 'choice-select'])}}">Choice Select</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components' ,'forms', 'clipboard'])}}">Clipboard</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components' ,'forms', 'flatepicker'])}}">Flatepicker</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components' ,'forms', 'validation'])}}">Validation</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'forms', 'wizard'])}}">Wizard</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'forms', 'file-upload'])}}">File Upload</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'forms', 'editors'])}}">Editors</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'forms', 'input-mask'])}}">Input Mask</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'forms', 'range-slider'])}}">Slider</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarTables" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarTables">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:tuning-2-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.tables') }} </span>
                </a>
                <div class="collapse" id="sidebarTables">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'tables', 'basic'])}}">Basic Tables</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'tables', 'gridjs'])}}">Grid Js</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarIcons" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarIcons">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:ufo-2-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.icons') }} </span>
                </a>
                <div class="collapse" id="sidebarIcons">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'icons', 'boxicons'])}}">Boxicons</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'icons', 'solar'])}}">Solar Icons</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarMaps" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarMaps">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:streets-map-point-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.maps') }} </span>
                </a>
                <div class="collapse" id="sidebarMaps">
                    <ul class="nav sub-navbar-nav">
                         <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'maps', 'google'])}}">Google Maps</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="{{ route('third', ['components', 'maps', 'vector'])}}">Vector Maps</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="javascript:void(0);">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:volleyball-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text">{{ __('messages.badge_menu') }}</span>
                    <span class="badge bg-danger badge-pill text-end">1</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link menu-arrow" href="#sidebarMultiLevelDemo" data-bs-toggle="collapse" role="button"
                   aria-expanded="false" aria-controls="sidebarMultiLevelDemo">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:share-circle-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.menu_item') }} </span>
                </a>
                <div class="collapse" id="sidebarMultiLevelDemo">
                    <ul class="nav sub-navbar-nav">
                        <li class="sub-nav-item">
                            <a class="sub-nav-link" href="javascript:void(0);">{{ __('messages.menu_item_1') }}</a>
                        </li>
                        <li class="sub-nav-item">
                            <a class="sub-nav-link  menu-arrow" href="#sidebarItemDemoSubItem" data-bs-toggle="collapse"
                               role="button" aria-expanded="false" aria-controls="sidebarItemDemoSubItem">
                                <span> {{ __('messages.menu_item_2') }} </span>
                            </a>
                            <div class="collapse" id="sidebarItemDemoSubItem">
                                <ul class="nav sub-navbar-nav">
                                    <li class="sub-nav-item">
                                        <a class="sub-nav-link" href="javascript:void(0);">{{ __('messages.menu_sub_item') }}</a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link disabled" href="javascript:void(0);">
                         <span class="nav-icon">
                              <iconify-icon icon="solar:user-block-rounded-bold-duotone"></iconify-icon>
                         </span>
                    <span class="nav-text"> {{ __('messages.disable_item') }} </span>
                </a>
            </li>
        </ul>
    </div>
</div>
