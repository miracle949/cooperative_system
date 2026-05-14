<div class="sidebar">
    <div class="sidebar-logo">
        <img src="images/logo2.png" alt="">
        <div class="side-text">
            <h3>KPMPCATS</h3>
            <p>Member Management</p>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul>
            <div class="navigation">
                <div class="menu" style="padding: 4px 10px 6px;">
                    <p>Main</p>
                </div>
                <li class="{{ request()->routeIs('MemberPortal') ? 'active' : '' }}">
                    <a href="{{ route('MemberPortal') }}">
                        <div class="nav-icon">
                            <i class="fa fa-home"></i>
                        </div>
                        <span>Dashboard</span>
                    </a>
                </li>
                    <!-- <div class="menu">
                    <p>Loan Management</p>
                </div> -->
                <li class="{{ request()->routeIs('LoanApplication') ? 'active' : '' }}">
                    <a href="{{ route('LoanApplication') }}">
                        <div class="nav-icon">
                            <i class="fa fa-file"></i>
                        </div>
                        <span>Loan Application</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('LoanStatus') ? 'active' : '' }}">
                    <a href="{{ route('LoanStatus') }}">
                        <div class="nav-icon">
                            <i class="fa fa-hand-holding-dollar"></i>
                        </div>
                        <span>Loan Status</span>
                    </a>
                </li>
                    <!-- <div class="menu">
                    <p>Member Finance</p>
                </div> -->
                <li class="{{ request()->routeIs('ShareCapitalMember') ? 'active' : '' }}">
                    <a href="{{ route('ShareCapitalMember') }}">
                        <div class="nav-icon">
                            <i class="fa fa-coins"></i>
                        </div>
                        <span>Share Capital</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('savings.index') ? 'active' : '' }}">
                    <a href="{{ route('savings.index') }}">
                        <div class="nav-icon">
                            <i class="fa fa-piggy-bank"></i>
                        </div>
                        <span>Savings</span>
                    </a>
                </li>

                <div class="sidebar-divider"></div>

                <div class="menu" style="padding: 10px 10px 8px;">
                    <p>Account</p>
                </div>

                <li>
                    <a href="#">
                        <div class="nav-icon">
                            <i class="fa fa-user"></i>
                        </div>
                        <span>My Profile</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <div class="nav-icon">
                            <i class="fa fa-receipt"></i>
                        </div>
                        <span>Transaction</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <div class="nav-icon">
                            <i class="fa fa-bell"></i>
                        </div>
                        <span>Notifications</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <div class="nav-icon">
                            <i class="fa fa-gear"></i>
                        </div>
                        <span>Settings</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('logout') }}">
                        <div class="nav-icon">
                            <i class="fa fa-sign-out"></i>
                        </div>
                        <span>Logout</span>
                    </a>
                </li>
            </div>

            {{-- <div class="logout">
                <li>
                    <a href="{{ route('logout') }}">
                        <i class="fa fa-sign-out"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </div> --}}

            <div class="account">
                <div class="first-last">
                    <p>{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}</p>
                </div>
                <div class="first-name">
                    <div class="fullname">
                        <p style="margin: 0">
                            {{ auth()->user()->first_name ?? '' }}
                        </p>
                        <p style="margin: 0">
                            {{ auth()->user()->last_name ?? '' }}
                        </p>
                    </div>
                    <span>Member #48291</span>
                </div>
            </div>
        </ul>
    </div>
</div>