<div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasMenu1"
  aria-labelledby="offcanvasMenu1Label">
  <div class="offcanvas-header">
    <div class="d-flex justify-content-center align-items-center gap-3">
      <img src="images/logo2.png" alt="">
      <h5 class="offcanvas-title" id="offcanvasMenu2Label">Kingsland Pala-Pala Cooperative</h5>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="offcanvas-links">
      <ul>
        <li>
          <a href="{{ route("Landingpage") }}">Home</a>
        </li>

        <li>
          <a href="{{ route("AboutUs") }}">About</a>
        </li>

        <li>
          <a href="#">Services</a>
        </li>

        <li>
          <a href="{{ route("BlogsPage") }}">Blogs</a>
        </li>

        <li>
          <a href="{{ route("ContactPage") }}">Contacts</a>
        </li>
      </ul>
    </div>

    <div class="offcanvas-accounts">
      <ul>
        <li><a href="{{ route("LoginPage") }}">Login</a></li>
      </ul>
    </div>
  </div>
</div>

{{-- offcanvas 2 --}}

<div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-1" id="offcanvasMenu2"
  aria-labelledby="offcanvasMenu2Label">
  <div class="offcanvas-header">
    <div class="d-flex justify-content-center align-items-center gap-2">
      <img src="images/logo2.png" alt="">
      <h5 class="offcanvas-title" id="offcanvasMenu2Label">Member Cooperative Portal</h5>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <div class="offcanvas-links">
      <ul>
        <li>
          <a href="{{ route("MemberPortal") }}">Home</a>
        </li>

        <li>
          <a href="{{ route("LoanApplication") }}">Loan Application</a>
        </li>

        <li>
          <a href="{{ route("Savings") }}">Savings</a>
        </li>

        <li>
          <a href="{{ route("LoanStatus") }}">Loan Status</a>
        </li>
      </ul>
    </div>

    <div class="offcanvas-accounts">
      <ul>
        <li><a href="{{ route("LoginPage") }}">Login</a></li>
      </ul>
    </div>
  </div>
</div>