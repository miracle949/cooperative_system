<div class="sidebar">
    <div class="sidebar-logo">
        <img src="images/logo2.png" alt="">
        <div class="side-text">
            <h3><b>KPMP</b>CATS</h3>
            <p>Member Management</p>
        </div>
    </div>
    <div class="sidebar-menu">
        <ul>
            <div class="navigation">
                <div class="menu" style="padding: 4px 10px 8px;">
                    <p>Navigation</p>
                </div>
                <li class="{{ request()->routeIs('MemberPortal') ? 'active' : '' }}">
                    <a href="{{ route('MemberPortal') }}">
                        <i class="fa fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <!-- <div class="menu">
                <p>Loan Management</p>
            </div> -->
                <li class="{{ request()->routeIs('LoanApplication') ? 'active' : '' }}">
                    <a href="{{ route('LoanApplication') }}">
                        <i class="fa fa-file"></i>
                        <span>Loan Application</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('LoanStatus') ? 'active' : '' }}">
                    <a href="{{ route('LoanStatus') }}">
                        <i class="fa fa-hand-holding-dollar"></i>
                        <span>Loan Status</span>
                    </a>
                </li>
                <!-- <div class="menu">
                <p>Member Finance</p>
            </div> -->
                <li class="{{ request()->routeIs('ShareCapitalMember') ? 'active' : '' }}">
                    <a href="{{ route('ShareCapitalMember') }}">
                        <i class="fa fa-coins"></i>
                        <span>Share Capital</span>
                    </a>
                </li>
                <li class="{{ request()->routeIs('savings.index') ? 'active' : '' }}">
                    <a href="{{ route('savings.index') }}">
                        <i class="fa fa-piggy-bank"></i>
                        <span>Savings</span>
                    </a>
                </li>

                <div class="sidebar-divider"></div>

                <div class="menu" style="padding: 10px 10px 8px;">
                    <p>Account</p>
                </div>

                <li>
                    <a href="#">
                        <i class="fa fa-user"></i>
                        <span>My Profile</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="fa fa-receipt"></i>
                        <span>Transaction</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="fa fa-bell"></i>
                        <span>Notifications</span>
                    </a>
                </li>

                <li>
                    <a href="#">
                        <i class="fa fa-gear"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </div>

            <div class="logout">
                <li>
                    <a href="{{ route('logout') }}">
                        <i class="fa fa-sign-out"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </div>
        </ul>
    </div>
</div>