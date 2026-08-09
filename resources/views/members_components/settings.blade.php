<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile</title>

    {{-- AOS animation link css --}}
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/settings.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    {{-- bootstrap and tailwind link --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- font awesome cdn link --}}
    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")

            <div class="main-parent">
                <h3>Settings</h3>
                <p>Manage how your account, notifications and security are configured</p>

                <div class="settings-parent">
                    <div class="settings-sidebar">
                        <a href="#preference" class="active" data-target="preference">
                            <i class="fa fa-sliders"></i>
                            Preference
                        </a>

                        <a href="#notifications" data-target="notifications">
                            <i class="fa fa-bell"></i>
                            Notifications
                        </a>

                        <a href="#security" data-target="security">
                            <i class="fa fa-shield-halved"></i>
                            Security
                        </a>

                        <a href="#sessions" data-target="sessions">
                            <i class="fa fa-desktop"></i>
                            Sessions
                        </a>

                        <a href="#danger-zone" data-target="danger-zone">
                            <i class="fa fa-triangle-exclamation"></i>
                            Danger Zone
                        </a>
                    </div>
                    <div class="settings-rightbar">
                        <div class="ledger-page" id="preference">
                            <div class="ledger-header">
                                <h4>Preference</h4>
                            </div>
                            <div class="ledger-body">
                                <div class="field">
                                    <span>Display Currency</span>
                                    <div class="pill-group">
                                        <div class="pill active">₱ PHP</div>
                                        <div class="pill">$ USD</div>
                                        <div class="pill">¥ JPY</div>
                                    </div>
                                </div>

                                <div class="field">
                                    <span>Language</span>
                                    <div class="pill-group">
                                        <div class="pill active">English</div>
                                        <div class="pill">Filipino</div>
                                    </div>
                                </div>

                                <div class="field">
                                    <span>Accent Color</span>
                                    <div class="pill-group">
                                        <div class="pill active">
                                            <div class="swatch" style="background-color: var(--blue)"></div>
                                            Blue
                                        </div>
                                        <div class="pill">
                                            <div class="swatch" style="background-color: var(--gold)"></div>
                                            Gold
                                        </div>
                                        <div class="pill">
                                            <div class="swatch" style="background-color: var(--mint)"></div>
                                            Mint
                                        </div>
                                        <div class="pill">
                                            <div class="swatch" style="background-color: var(--coral)"></div>
                                            Coral
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="ledger-page" id="notifications">
                            <div class="ledger-header">
                                <h4>Notifications</h4>
                            </div>
                            <div class="ledger-body">
                                <div class="opt-row">
                                    <div class="opt-icon">
                                        <i class="fa fa-hand-holding-dollar"></i>
                                    </div>
                                    <div class="opt-text">
                                        <strong>Loan Payment Reminders</strong>
                                        <span>Get notified 3 days before a due date</span>
                                    </div>
                                    <div class="switch on"></div>
                                </div>

                                <div class="opt-row">
                                    <div class="opt-icon gold">
                                        <i class="fa fa-piggy-bank"></i>
                                    </div>
                                    <div class="opt-text">
                                        <strong>Savings & Share Capital Updates</strong>
                                        <span>Deposit confirmations and dividend posts</span>
                                    </div>
                                    <div class="switch on"></div>
                                </div>

                                <div class="opt-row">
                                    <div class="opt-icon mint">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="opt-text">
                                        <strong>Email Digest</strong>
                                        <span>Weekly summary of your account activity</span>
                                    </div>
                                    <div class="switch"></div>
                                </div>

                                <div class="opt-row">
                                    <div class="opt-icon">
                                        <i class="fa fa-bullhorn"></i>
                                    </div>
                                    <div class="opt-text">
                                        <strong>Cooperative Announcements</strong>
                                        <span>Meetings, elections and general assembly notices</span>
                                    </div>
                                    <div class="switch on"></div>
                                </div>

                            </div>
                        </div>

                        <div class="ledger-page" id="security">
                            <div class="ledger-header">
                                <h4>Security</h4>
                            </div>
                            <div class="ledger-body">
                                <div class="opt-row">
                                    <div class="opt-icon">
                                        <i class="fa fa-key"></i>
                                    </div>
                                    <div class="opt-text">
                                        <strong>Password</strong>
                                        <span>Last changed 3 months ago</span>
                                    </div>
                                    <span class="link-btn">Change</span>
                                </div>

                                <div class="opt-row">
                                    <div class="opt-icon mint">
                                        <i class="fa fa-mobile-screen"></i>
                                    </div>
                                    <div class="opt-text">
                                        <strong>Two-Factor Authentication</strong>
                                        <span>SMS verification enabled</span>
                                    </div>
                                    <div class="switch on"></div>
                                </div>

                                <div class="opt-row">
                                    <div class="opt-icon mint">
                                        <i class="fa fa-mobile-screen"></i>
                                    </div>
                                    <div class="opt-text">
                                        <strong>Login alerts</strong>
                                        <span>Notify on new device sign-in</span>
                                    </div>
                                    <div class="switch on"></div>
                                </div>
                            </div>
                        </div>

                        <div class="ledger-page" id="sessions">
                            <div class="ledger-header">
                                <h4>Active Sessions</h4>
                            </div>
                            <div class="ledger-body">
                                <div class="session-row">
                                    <div class="session-icon">
                                        <i class="fa fa-desktop"></i>
                                    </div>
                                    <div class="session-text">
                                        <strong>Chrome on Windows · Imus City</strong>
                                        <span>127.0.0.1 · Active now</span>
                                    </div>
                                    <span class="session-tag">This device</span>
                                </div>

                                <div class="session-row">
                                    <div class="session-icon">
                                        <i class="fa fa-mobile-screen"></i>
                                    </div>
                                    <div class="session-text">
                                        <strong>KPMPCATS App · Android</strong>
                                        <span>Last active Jul 22, 2026, 8:14 PM</span>
                                    </div>
                                    <span class="link-btn">Sign out</span>
                                </div>

                                <div class="session-row">
                                    <div class="session-icon">
                                        <i class="fa fa-desktop"></i>
                                    </div>
                                    <div class="session-text">
                                        <strong>Safari on macOS · Dasmariñas</strong>
                                        <span>Last active Jul 18, 2026, 6:02 PM</span>
                                    </div>
                                    <span class="link-btn">Sign out</span>
                                </div>
                            </div>
                        </div>

                        <div class="ledger-page" id="danger-zone">
                            <div class="ledger-header">
                                <h4>Danger Zone</h4>
                            </div>
                            <div class="ledger-body">
                                <div class="danger-row">
                                    <div class="danger-text">
                                        <strong>Download my data</strong>
                                        <span>Export a copy of your membership records as PDF</span>
                                    </div>
                                    <button class="export">
                                        <i class="fa fa-download"></i>
                                        Export
                                    </button>
                                </div>
                                <div class="danger-row">
                                    <div class="danger-text">
                                        <strong>Request account deactivation</strong>
                                        <span>Temporarily disable access pending clearance of balances</span>
                                    </div>
                                    <button class="deactivate">
                                        <i class="fa fa-triangle-exclamation"></i>
                                        Request
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const sidebarLinks = document.querySelectorAll(".settings-sidebar a");
            const sections = Array.from(sidebarLinks).map(link =>
                document.getElementById(link.dataset.target)
            );
            const scrollContainer = document.querySelector(".main-parent");

            function setActiveLink(activeLink) {
                sidebarLinks.forEach(link => link.classList.remove("active"));
                activeLink.classList.add("active");
            }

            // Click to smooth-scroll to section
            sidebarLinks.forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    const target = document.getElementById(this.dataset.target);
                    if (target) {
                        scrollContainer.scrollTo({
                            top: target.offsetTop - 20,
                            behavior: "smooth"
                        });
                    }
                });
            });

            // Scrollspy: highlight link based on section in view
            function onScroll() {
                const scrollPos = scrollContainer.scrollTop + 100; // offset for header
                let currentIndex = 0;

                sections.forEach((section, i) => {
                    if (section && section.offsetTop <= scrollPos) {
                        currentIndex = i;
                    }
                });

                setActiveLink(sidebarLinks[currentIndex]);
            }

            scrollContainer.addEventListener("scroll", onScroll);
            onScroll(); // run once on load
        });
    </script>

</body>

</html>