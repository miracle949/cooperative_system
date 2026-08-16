<div class="footer-parent">
    <div class="footer-nav {{ request()->routeIs('MemberPortal') ? 'active' : '' }}">
        <a href="{{ route('MemberPortal') }}" title="Dashboard">
            <i class="fa fa-home"></i>
            <span>Home</span>
        </a>
    </div>

    <div class="footer-nav {{ request()->routeIs('LoanApplication') ? 'active' : '' }}">
        <a href="{{ route('LoanApplication') }}" title="Loan Application">
            <i class="fa fa-file"></i>
            <span>Loan Application</span>
        </a>
    </div>

    <div class="footer-nav {{ request()->routeIs('LoanStatus') ? 'active' : '' }}">
        <a href="{{ route('LoanStatus') }}" title="Loan Status">
            <i class="fa fa-hand-holding-dollar"></i>
            <span>Repayments</span>
        </a>
    </div>

    <div class="footer-nav">
        <a href="#">
            <i class="fa fa-wallet"></i>
            <span>Financial</span>
        </a>
    </div>
</div>