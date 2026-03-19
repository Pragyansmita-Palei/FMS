<div class="sidebar" id="sidebar">
    <!-- HEADER -->
    <div class="sidebar-header">
        <div class="logo">
            <!-- Full Logo Text (shown when expanded) -->
            <span class="logo-text">Furnish<span>Pro</span></span>
            <!-- Abbreviated Logo (shown when collapsed) -->
            <span class="logo-abbr">F</span>
        </div>
    </div>

    <!-- SCROLLABLE MENU AREA -->
    <div class="sidebar-menu">
        <ul class="menu">

            {{-- ================= DASHBOARD ================= --}}
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('sales_associates'))
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
<i class="fa fa-chart-line gradient-icon"></i>
  <span class="menu-text">Dashboard</span>
                    </a>
                </li>
            @elseif(auth()->user()->hasRole('tailors'))
                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-chart-line"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
            @endif

            {{-- ================= STAFF ================= --}}
@if(auth()->user()->hasRole('staff'))
 <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}">
                        <i class="fa fa-chart-line"></i>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>
<li class="has-submenu {{ request()->routeIs('projects.*') || request()->routeIs('orders.*') ? 'open' : '' }}">
    <div class="menu-row">
        <i class="fa fa-box"></i>
        <span class="menu-text">Order</span>
    </div>

    <ul class="submenu wide-submenu">
        <li>
            <a href="">Orders</a>
        </li>

        <li>
            <a href="{{ route('projects.index') }}">Projects</a>
        </li>
    </ul>
</li>



@endif

            {{-- ================= ADMIN & SALES ================= --}}
            @if(auth()->user()->hasRole(['admin','sales_associates']))


               <li>
                    <a href="{{ route('sales_associates.index') }}">
                        <i class="fa fa-users"></i>
                        <span class="menu-text">Sales Associates</span>
                    </a>
                </li>

            {{-- ================= BUSINESS PARTNER ================= --}}
<li class="has-submenu
    {{ request()->routeIs('customers.*')
    || request()->routeIs('tailors.*')
    || request()->routeIs('interiors.*') ? 'open' : '' }}">

    <div class="menu-row">
        <i class="fa fa-handshake"></i>
        <span class="menu-text">Business Partner</span>
    </div>

    <ul class="submenu wide-submenu">
        <li>
            <a href="{{ route('customers.index') }}">
                 <i class="fas fa-user-alt"></i>
                <span>Customer</span>
            </a>
        </li>

        <li>
            <a href="{{ route('tailors.index') }}">
                <i class="fa fa-scissors"></i>
                <span>Tailors</span>
            </a>
        </li>

        <li>
  <a href="{{ route('interiors.index') }}">
                <i class="fa fa-couch"></i>
                <span>Interiors</span>
            </a>
         </li>
    </ul>
</li>
                <!-- <li class="has-submenu"data-menu="customer">
                    <div class="menu-row">
                        <i class="fas fa-user-alt"></i>
                        <span class="menu-text">Customer</span>
                    </div>
                    <ul class="submenu">
                        <li><a href="{{ route('customers.create') }}">Add Customer</a></li>
                        <li><a href="{{ route('customers.index') }}">Customer List</a></li>
                    </ul>
                </li> -->
                <!-- <li>
                    <a href="{{ route('customers.index') }}">
                        <i class="fas fa-user-alt"></i>
                        <span class="menu-text">Customer</span>
                    </a>
                </li> -->



                <!-- <li>
                    <a href="{{ route('tailors.index') }}">
                        <i class="fa fa-scissors"></i>
                        <span class="menu-text">Tailors</span>
                    </a>
                </li> -->



                {{-- MASTER --}}
<li class="has-submenu" data-menu="master">
      <div class="menu-row">
                        <i class="fa fa-shopping-bag"></i>
                        <span class="menu-text">Master</span>
                    </div>

                    <!-- WIDE SUBMENU -->
                    <ul class="submenu wide-submenu">
                        <li><a href="{{ route('stores.index') }}">Stores</a></li>
                        <li><a href="{{ route('products.index') }}">Products</a></li>
                        {{-- <li><a href="{{ route('product-groups.index') }}">Product Groups</a></li> --}}
                        <li><a href="{{ route('brands.index') }}">Brands</a></li>
                        <li><a href="{{ route('catalogues.index') }}">Catalogues</a></li>
                        <li><a href="{{ route('group-types.index') }}">Product Groups</a></li>
                        <li><a href="{{ route('selling-units.index') }}">Selling Units</a></li>
                    </ul>
                </li>
            @endif


 <li class="has-submenu {{ request()->routeIs('projects.*') || request()->routeIs('orders.*') ? 'open' : '' }}">
    <div class="menu-row">
        <i class="fa fa-box"></i>
        <span class="menu-text">Order</span>
    </div>

    <ul class="submenu wide-submenu">

         <li>
            <a href="{{ route('projects.index') }}">Projects</a>
        </li>
     <li>
    <a href="{{ route('orders.index') }}">Orders</a>
   </li>



    </ul>
</li>
            @if(auth()->user()->hasRole(['admin','sales_associates','tailors']))
                <li>
                    <a href="{{ route('tasks.index') }}">
                        <i class="fa fa-tasks"></i>
                        <span class="menu-text">Tasks</span>
                    </a>
                </li>
            @endif
                @if(auth()->user()->hasRole(['admin','sales_associates']))
                <li>
                    <a href="{{ route('labours.index') }}">
    <i class="fa fa-industry"></i>
                    <span class="menu-text">labours</span>
                    </a>
                </li>
             <!-- <li>
    <a href="{{ route('interiors.index') }}">
        <i class="fa fa-couch"></i>
        <span class="menu-text">Interiors</span>
    </a>
</li> -->
            @endif
            {{-- ================= USER MASTER ================= --}}
@if(auth()->user()->hasRole(['admin','sales_associates']))
<li class="has-submenu {{ request()->is('admin/roles*') || request()->routeIs('tailors.*') || request()->routeIs('sales_associates.*') ? 'open' : '' }}">
    <div class="menu-row">
        <i class="fa fa-users-cog"></i>
        <span class="menu-text">User Master</span>
    </div>

    <ul class="submenu wide-submenu">
          <li>
            <a href="{{ route('users.index') }}">User</a>
        </li>
        <li>
            <a href="{{ route('admin.roles.index') }}">Role</a>
        </li>
  <li>
            <a href="{{ route('admin.permissions.index') }}">Permission</a>
        </li>
         {{-- <li>
            <a href="{{ route('admin.terms.index') }}">Terms & Condition</a>
        </li> --}}
        <!-- <li>
            <a href="{{ route('tailors.index') }}">Tailors</a>
        </li> -->

        <!-- <li>
            <a href="{{ route('sales_associates.index') }}">Sales Associates</a>
        </li> -->
    </ul>
</li>
@endif

            @if(auth()->user()->hasRole('admin'))
                <!-- <li>
                    <a href="{{ route('users.index') }}">
                        <i class="fa fa-users"></i>
                        <span class="menu-text">Users</span>
                    </a>
                </li> -->

                {{-- <li>
                    <a href="{{ route('payment.details') }}">
                        <i class="fa fa-wallet"></i>
                        <span class="menu-text">Payments & Billing</span>
                    </a>
                </li> --}}
            @endif
            {{-- ================= REPORT ================= --}}
<li class="has-submenu"data-menu="report">
    <div class="menu-row">
        <i class="fa fa-chart-bar"></i>
        <span class="menu-text">Report</span>
    </div>

    <ul class="submenu wide-submenu">
        {{-- <li><a href="">Sales Report</a></li>
        <li><a href="">Order Report</a></li> --}}
        <li>
    <a href="{{ route('payment.details') }}">
        <i class="fa fa-wallet"></i>
        <span>Payment Report</span>
    </a>
</li>

        {{-- <li><a href="">Task Report</a></li>
        <li><a href="">Tailor Performance</a></li> --}}
    </ul>
</li>

        </ul>
    </div>

    <!-- FIXED ADMIN USER -->
    <div class="admin-user">
        <div class="admin-left">
            <div class="admin-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <span class="admin-name">{{ auth()->user()->name }}</span>
        </div>

        <button class="admin-menu-btn" id="adminMenuBtn">
            <i class="fas fa-ellipsis-v"></i>
        </button>

        <div class="admin-dropdown" id="adminDropdown">
  <a href="{{ route('admin.terms.index') }}" class="admin-dropdown-item">
     <i class="fas fa-user"></i> Terms & Condtion
 </a>
          <a href="{{ route('profile') }}" class="admin-dropdown-item">
    <i class="fas fa-user"></i> Profile
</a>
           <a href="/admin/roles" class="admin-dropdown-item">
    <i class="fas fa-cog"></i> Role
</a>
            <div class="admin-divider"></div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="admin-dropdown-item logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </div>
</div>
