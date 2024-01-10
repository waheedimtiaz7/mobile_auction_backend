<aside class="main-sidebar elevation-4" style="background-color: rgb(255, 255, 255)">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <img src="{{ asset('admin-assets/img/logo.png')}}" alt="logo" class="brand-image img-circle elevation-4" style="opacity: .8">
        <span class="brand-text font-weight-dark">Mobile Auction</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                    with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item ">
                    <a href="{{route('users.index')}}" class="nav-link {{ request()->is('admin/users/*')?'active':'' }}">
                        <i class="nav-icon  fas fa-users"></i>
                        <p>Users</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('employees.index')}}" class="nav-link {{ request()->is('admin/employees/*')?'active':'' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Employees</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('devices.index')}}" class="nav-link {{ request()->is('admin/devices/*')?'active':'' }}">
                        <i class="nav-icon fas fa-tablet-alt"></i>
                        <p>Devices</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
 </aside>
