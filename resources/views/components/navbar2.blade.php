<nav id="dashboard-nav"
    class="tw:flex justify-content-between align-items-center tw:w-[100%] tw:h-[80px] tw:bg-[#ffffff]">
    <div class="nav-logo">
        {{-- <h2 class="m-0" style="font-size: 25px">LOGO</h2> --}}
        <!-- <img src="images/logo2.png" width="50px" height="50px" style="border-radius: 50%" alt="">
        <h3>KPMPCATS</h3> -->
        {{-- <h2 class="mw-100 m-0" style="font-size: 14px; width: 200px;">Kingsland Pala-Pala MPC & Transport Service
        </h2> --}}

        <!-- <div class="nav-menu">
            <i class="fa fa-bars" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu1"
                aria-controls="staticBackdrop"></i>
        </div> -->

        <!-- <i class="fa fa-bars" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu1"
                aria-controls="staticBackdrop"></i> -->
        <button class="collapse-button" onclick="toggleSidebar()">
            <i class="fa fa-bars"></i>
        </button>

        <!-- <h5>Good day, {{ $username }} <span>Here's your overview</span></h5> -->
    </div>

    <!-- <div class="nav-list nav-list2">
        <ul class="tw:flex tw:gap-x-[4rem] m-0 p-0">
            <li class="tw:list-none">
                <a href="{{ route("MemberPortal") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none">Home</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("LoanApplication") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none">Loan Application</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("LoanStatus") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none">Loan
                    Status</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("ShareCapitalMember") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none">Share Capital</a>
            </li>

            <li class="tw:list-none">
                <a href="{{ route("savings.index") }}"
                    class="tw:no-underline tw:text-[15.5px] text-decoration-none">Savings</a>
            </li>
        </ul>
    </div> -->

    <div class="nav-menu">
        <i class="fa fa-bars" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu2"
            aria-controls="staticBackdrop"></i>
    </div>

    <div class="nav-acc2" id="nav-acc2">
        <ul class="m-0 p-0">
            <div class="nb-notif-wrap">
                <button type="button" onclick="toggleNavNotif(event)"
                    style="background:none; border:none; cursor:pointer; position:relative; padding:4px; display:flex; align-items:center;">
                    <i class="fa-solid fa-bell" style="font-size: 17px; color: var(--muted);"></i>
                    @if(($navUnreadCount ?? 0) > 0)
                        <span
                            style="position:absolute; top:-4px; right:-4px; background:#dc2626; color:#fff; font-size:10px; font-weight:700; min-width:16px; height:16px; border-radius:50%; display:flex; align-items:center; justify-content:center; padding:0 3px; line-height:1;">
                            {{ $navUnreadCount ?? 0 }}
                        </span>
                    @endif
                </button>

                <div id="nb-notif-panel">
                    <div
                        style="padding:14px 16px; border-bottom:1px solid #f0f0f0; font-weight:700; font-size:14px; color:#1a1a1a;">
                        Notifications
                    </div>
                    <div style="max-height:360px; overflow-y: scroll;">
                        @forelse($navNotifications as $n)
                            <a href="{{ $n['url'] }}" class="nb-notif-item" data-key="{{ $n['key'] }}"
                                onclick="return handleNotifClick(event, this)"
                                style="display:flex; gap:10px; padding:12px 16px; border-bottom:1px solid #f5f5f5; text-decoration:none; color:inherit; {{ $n['is_read'] ? 'opacity:0.55;' : '' }}">
                                <div
                                    style="width:32px; height:32px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; background: {{ $n['color'] === 'red' ? '#fee2e2' : ($n['color'] === 'gold' ? '#fef3c7' : '#d1fae5') }};">
                                    <i class="fa-solid {{ $n['icon'] }}"
                                        style="font-size:13px; color: {{ $n['color'] === 'red' ? '#dc2626' : ($n['color'] === 'gold' ? '#b45309' : '#059669') }};"></i>
                                </div>
                                <div style="flex:1; min-width:0;">
                                    <p style="margin:0; font-size:13px; font-weight:600; color:#1a1a1a;">{{ $n['title'] }}
                                    </p>
                                    <p style="margin:2px 0 0; font-size:12px; color:#666; line-height:1.4;">
                                        {{ $n['message'] }}
                                    </p>
                                    <p style="margin:4px 0 0; font-size:11px; color:#999;">{{ $n['time'] }}</p>
                                </div>
                            </a>
                        @empty
                            <div style="padding:30px 16px; text-align:center; color:#999; font-size:13px;">
                                <i class="fa-regular fa-bell-slash"
                                    style="font-size:20px; display:block; margin-bottom:8px; opacity:0.5;"></i>
                                No notifications right now.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <script>
                function toggleNavNotif(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const panel = document.getElementById('nb-notif-panel');
                    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
                }

                document.addEventListener('click', function (e) {
                    const panel = document.getElementById('nb-notif-panel');
                    const wrap = document.querySelector('.nb-notif-wrap');
                    if (panel && wrap && !wrap.contains(e.target) && panel.style.display === 'block') {
                        panel.style.display = 'none';
                    }
                });
            </script>
            <li>
                <a href="#" onclick="toggleDropdown(event)"
                    class="tw:flex tw:justify-center tw:items-center tw:gap-x-[0.7rem] position-relative">
                    <div class="first-last">
                        <p>{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}</p>
                    </div>
                    <div class="name-email">
                        <p>
                            {{ auth()->user()->first_name ?? '' }}
                            <!-- {{ auth()->user()->last_name ?? '' }} -->
                        </p>
                        <!-- <p>
                            {{ auth()->user()->email ?? '' }}
                        </p> -->
                    </div>

                </a>
                <!-- @if ($username)
                    @php
                        $userId = Auth::id();
                        $navOtherinfo = \App\Models\Otherinfo_tbl::where('user_id', $userId)->first();
                        $navMembergovernIds = \App\Models\Membergovern_ids_tbl::where('user_id', $userId)->first();
                        $navMissingCount = 0;
                        if ($navOtherinfo && empty($navOtherinfo->contact_no))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->present_address))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->permanent_address))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->date_of_birth))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->place_of_birth))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->sex))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->civil_status))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->citizenship))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->blood_type))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->height))
                            $navMissingCount++;
                        if ($navOtherinfo && empty($navOtherinfo->weight))
                            $navMissingCount++;
                        if ($navMembergovernIds && empty($navMembergovernIds->sss_id))
                            $navMissingCount++;
                        if ($navMembergovernIds && empty($navMembergovernIds->philhealth_id))
                            $navMissingCount++;
                        if ($navMembergovernIds && empty($navMembergovernIds->pagibig_id))
                            $navMissingCount++;
                        if ($navMembergovernIds && empty($navMembergovernIds->tin_id))
                            $navMissingCount++;
                    @endphp
                    <a href="#" onclick="toggleDropdown(event)"
                        class="tw:flex tw:justify-center tw:items-center tw:gap-x-[0.7rem] position-relative">
                        <div class="first-last">
                            <p>{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}</p>
                        </div>
                        <p style="margin: 0">
                            {{ auth()->user()->first_name ?? '' }}
                        </p>
                        @if($navMissingCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 12px; padding: 4px 8px;">
                                {{ $navMissingCount }}
                            </span>
                        @endif
                        <i class="fa fa-chevron-down"></i>
                    </a>
                @endif -->

                <ul>
                    @if($email)
                        <li>
                            <div class="card-icon">
                                <p>{{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}</p>
                            </div>
                            <p>{{ $email }}</p>
                        </li>
                    @endif
                    <hr>
                    <li style="position: relative;">
                        <div class="card-icon"><i class="fa fa-user"></i></div>
                        <a href="{{ route('ProfileMember') }}">Profile</a>
                        @if($navMissingCount > 0)
                            <span class="start-50 translate-middle badge rounded-pill bg-danger"
                                style="font-size: 12px; padding: 4px 8px;">
                                {{ $navMissingCount }}
                            </span>
                        @endif
                    </li>
                    <li>
                        <div class="card-icon"><i class="fa fa-lock"></i></div>
                        <a href="#">Reset Password</a>
                    </li>
                    <li>
                        <div class="card-icon"><i class="fa fa-sign-out"></i></div>
                        <a href="{{ route('logout') }}">Logout</a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>

    <script>
        function toggleDropdown(e) {
            e.preventDefault();
            e.stopPropagation();
            document.getElementById('nav-acc2').classList.toggle('open');
        }

        // document.addEventListener('click', function (e) {
        //     const wrap = document.getElementById('nav-acc2');
        //     if (!wrap.contains(e.target)) {
        //         wrap.classList.remove('open');
        //     }
        // });
    </script>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const rightbar = document.querySelector('.rightbar');

            sidebar.classList.toggle('collapsed');
            rightbar.classList.toggle('expanded');
        }
    </script>

    <script>
        function handleNotifClick(e, el) {
            e.preventDefault();
            const key = el.dataset.key;
            const url = el.getAttribute('href');

            const csrfTag = document.querySelector('meta[name="csrf-token"]');
            const csrfToken = csrfTag ? csrfTag.content : '';

            if (!csrfToken) {
                console.warn('CSRF meta tag not found — skipping mark-as-read request.');
            }

            const finish = () => {
                const badge = document.querySelector('.nb-notif-wrap span');
                if (badge && el.dataset.wasRead !== '1') {
                    const current = parseInt(badge.textContent.trim() || '0', 10);
                    const next = Math.max(0, current - 1);
                    if (next === 0) badge.remove(); else badge.textContent = next;
                }
                if (url && url !== '#') {
                    window.location.href = url;
                }
            };

            if (!csrfToken) {
                finish();
                return false;
            }

            fetch("{{ route('notifications.markRead') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ key })
            }).finally(finish);

            return false;
        }
    </script>

</nav>