<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Financial</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    {{-- Both source stylesheets are loaded together. They share the same
    design-token names (--teal, --border, --muted, etc.) with matching
    values, so there is no visual conflict loading both. --}}
    <link rel="stylesheet" href="css_folder/share_capital.css">
    <link rel="stylesheet" href="css_folder/savings.css">
    <link rel="stylesheet" href="css_folder/savings_modal.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">

    <style>
        /* ── Tab switcher (Share Capital / Savings) ── */

        .main-sub-parent {
            width: 100%;
            height: calc(100vh - 80px);
            overflow-y: auto;
            background-color: #FBFBFD;
            padding: 2rem;
        }

        .fin-tabs {
            display: flex;
            gap: 6px;
            background-color: #ffffff;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            padding: 4px;
            border-radius: 10px;
            width: fit-content;
            margin: 1.5rem 0;
        }

        .fin-tab {
            padding: 8px 18px;
            border-radius: 7px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--muted, #6b7b74);
            text-decoration: none;
            transition: all 0.15s;
        }

        .fin-tab:hover {
            color: #1a1a1a;
        }

        .fin-tab.active {
            background-color: var(--teal);
            color: #ffffff;
        }

        .sc-journey-card-capital {
            padding: 18px 20px;
            background-color: #ffffff;
            border: 1px solid var(--border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            border-radius: 20px;
        }

        /* ── Receipt Modal Overlay (Share Capital) ── */
        #sc-receipt-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(4px);
            z-index: 99999;
            align-items: flex-start;
            justify-content: center;
            padding: 1.5rem 1rem;
            overflow-y: auto;
        }

        #sc-receipt-overlay.active {
            display: flex;
        }

        #sc-receipt-modal {
            background: #fff;
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 24px 60px rgba(0, 0, 0, 0.18);
            overflow: visible;
            margin: auto;
            animation: scModalIn 0.35s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes scModalIn {
            from {
                opacity: 0;
                transform: translateY(28px) scale(0.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .sc-receipt-header {
            background-color: #ffffff;
            padding: 1.5rem;
            text-align: center;
            border-radius: 20px 20px 0 0;
            border-bottom: 1px solid var(--line);
        }

        .sc-receipt-header .check-circle {
            width: 60px;
            height: 60px;
            background-color: var(--teal);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.8rem;
        }

        .sc-receipt-header .check-circle i {
            color: #fff;
            font-size: 26px;
        }

        .sc-receipt-header h2 {
            color: #1a1a1a;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 0.25rem;
        }

        .sc-receipt-header p {
            color: var(--muted);
            font-size: 0.82rem;
            margin: 0;
        }

        .sc-receipt-body {
            padding: 1.5rem;
        }

        .sc-receipt-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 0;
            border-bottom: 1px dashed #e8e8e8;
            font-size: 0.85rem;
        }

        .sc-receipt-row:last-child {
            border-bottom: none;
        }

        .sc-receipt-row .label {
            color: #888;
            font-weight: 500;
        }

        .sc-receipt-row .value {
            color: #1a1a1a;
            font-weight: 700;
            text-align: right;
        }

        .sc-receipt-row .value.highlight {
            color: #1a1a1a;
            font-size: 13.6px;
        }

        .sc-ref-badge {
            background: #f4f4f4;
            border-radius: 6px;
            padding: 0.2rem 0.6rem;
            letter-spacing: 0.5px;
            color: #333;
        }

        .sc-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff8e1;
            border: 1.5px solid #ffe082;
            color: #b8860b;
            border-radius: 20px;
            padding: 0.2rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .sc-status-badge.completed {
            background: #e8f5e9;
            border-color: #a5d6a7;
            color: #2e7d32;
        }

        .sc-status-badge .dot {
            width: 7px;
            height: 7px;
            background: #e6a817;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .sc-status-badge.completed .dot {
            background: #2e7d32;
        }

        .sc-receipt-footer {
            padding: 0 1.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.6rem;
            border-radius: 0 0 20px 20px;
            background: #fff;
        }

        .sc-btn-download {
            width: 100%;
            padding: 0.8rem;
            background-color: var(--teal);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: opacity 0.2s;
        }

        .sc-btn-download:hover {
            opacity: 0.88;
        }

        .sc-btn-close-modal {
            width: 100%;
            padding: 0.7rem;
            background: transparent;
            color: #888;
            border: 1.5px solid #e8e8e8;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }

        .sc-btn-close-modal:hover {
            background: #f5f5f5;
            color: #333;
        }

        .sc-inline-error {
            display: none;
            align-items: flex-start;
            gap: 8px;
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            border-radius: 10px;
            padding: 0.6rem 0.9rem;
            font-size: 12px;
            color: #b91c1c;
            margin-bottom: 0.85rem;
            line-height: 1.5;
        }

        .sc-inline-error.show {
            display: flex;
        }

        .sc-inline-error i {
            margin-top: 1px;
            flex-shrink: 0;
        }

        /* ── Unified Transaction Modal ── */
        .tx-dest-toggle {
            display: flex;
            gap: 8px;
            margin-bottom: 1.1rem;
        }

        .tx-dest-btn {
            flex: 1;
            padding: 10px 12px;
            border-radius: 10px;
            border: 1.5px solid #e0e0e0;
            background: #fff;
            color: #666;
            font-size: 13.5px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
        }

        /* .tx-dest-btn.active {
            background: var(--teal);
            border-color: var(--teal);
            color: #fff;
        } */

        .tx-qbtn.active {
            background: var(--teal) !important;
            color: #fff !important;
        }

        .tx-amt-qbtn {
            padding: 5px 13px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            background: #fff;
            color: #808080;
            border: 1.5px solid #ddd;
        }
    </style>
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("components.offcanvas")

        {{-- ═══════════════════════════════════════
        RECEIPT MODAL (Share Capital — shown after success)
        ═══════════════════════════════════════ --}}
        @if($activeTab === 'share_capital' && session('success'))
            <div id="sc-receipt-overlay" class="active">
                <div id="sc-receipt-modal">
                    <div class="sc-receipt-header">
                        <div class="check-circle"><i class="fa-solid fa-check"></i></div>
                        <h2>Request Submitted!</h2>
                        @if(session('sc_receipt_status') === 'Completed')
                            <p>Your deposit has been recorded successfully.</p>
                        @else
                            <p>Your withdrawal request is pending for approval.</p>
                        @endif
                    </div>

                    <div class="sc-receipt-body" id="sc-receipt-printable">
                        <div class="sc-receipt-row">
                            <span class="label">Organization</span>
                            <span class="value">KMPCATS</span>
                        </div>
                        <div class="sc-receipt-row">
                            <span class="label">Member</span>
                            <span class="value">{{ session('sc_receipt_member', Auth::user()->name ?? 'Member') }}</span>
                        </div>
                        <div class="sc-receipt-row">
                            <span class="label">Transaction Type</span>
                            <span class="value">{{ session('sc_receipt_type', 'Deposit') }}</span>
                        </div>
                        <div class="sc-receipt-row">
                            <span class="label">Shares</span>
                            <span class="value highlight">{{ session('sc_receipt_shares', '—') }} shares</span>
                        </div>
                        <div class="sc-receipt-row">
                            <span class="label">Amount</span>
                            <span class="value highlight">₱{{ number_format(session('sc_receipt_amount', 0), 0) }}</span>
                        </div>
                        <div class="sc-receipt-row">
                            <span class="label">Payment Method</span>
                            <span class="value">{{ session('sc_receipt_method', '—') }}</span>
                        </div>
                        <div class="sc-receipt-row">
                            <span class="label">Reference No.</span>
                            <span class="value"><span
                                    class="sc-ref-badge">{{ session('sc_receipt_ref', '—') }}</span></span>
                        </div>
                        <div class="sc-receipt-row">
                            <span class="label">Date</span>
                            <span class="value">{{ now()->timezone('Asia/Manila')->format('M d, Y · h:i A') }}</span>
                        </div>
                        <div class="sc-receipt-row">
                            <span class="label">Status</span>
                            <span class="value">
                                @if(session('sc_receipt_status') === 'Completed')
                                    <span class="sc-status-badge completed"><span class="dot"></span> Completed</span>
                                @else
                                    <span class="sc-status-badge"><span class="dot"></span> Pending Approval</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="sc-receipt-footer">
                        <button class="sc-btn-download" onclick="scDownloadReceipt()">
                            <i class="fa-solid fa-download"></i> Download Receipt
                        </button>
                        <button class="sc-btn-close-modal" onclick="scCloseModal()">Close</button>
                    </div>
                </div>
            </div>

            <div id="sc-receipt-data" data-member="{{ session('sc_receipt_member', Auth::user()->name ?? 'Member') }}"
                data-type="{{ session('sc_receipt_type', 'Deposit') }}"
                data-shares="{{ session('sc_receipt_shares', '—') }}"
                data-amount="{{ number_format(session('sc_receipt_amount', 0), 0) }}"
                data-method="{{ session('sc_receipt_method', '—') }}" data-ref="{{ session('sc_receipt_ref', '—') }}"
                data-date="{{ now()->timezone('Asia/Manila')->format('M d, Y · h:i A') }}"
                data-status="{{ session('sc_receipt_status', 'Pending') }}" style="display:none;">
            </div>
        @endif

        @include("components.sidebar")

        <div class="rightbar">
            @include("components.navbar2")

            <div class="main-sub-parent">
                <main>

                    {{-- ═══════════════════════════════════════
                    UNIFIED TRANSACTION MODAL (Share Capital + Savings, one entry point)
                    ═══════════════════════════════════════ --}}
                    <div class="modal fade" id="unifiedTxModal" tabindex="-1" aria-labelledby="unifiedTxModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content"
                                style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 24px 60px rgba(0,0,0,0.15);">

                                <div class="modal-header"
                                    style="background: #ffffff; border-bottom: 1px solid var(--line); padding: 1.25rem 1.5rem;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div
                                            style="width: 45px; height: 45px; background: var(--teal); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa fa-right-left" style="color: #ffffff; font-size: 16px;"></i>
                                        </div>
                                        <div>
                                            <h5 class="modal-title mb-0" id="unifiedTxModalLabel"
                                                style="color: #1a1a1a; font-size: 15px; font-weight: 600;">New
                                                Transaction</h5>
                                            <p style="margin: 0; color: var(--muted); font-size: 13.5px;">
                                                Deposit or withdraw from Share Capital or Savings</p>
                                        </div>
                                    </div>
                                    <button type="button" class="btn-close btn-close-dark m-0" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>

                                <div class="modal-body"
                                    style="padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem;">

                                    <form action="{{ route('share_capital.store') }}" method="POST" id="unified-tx-form"
                                        enctype="multipart/form-data" data-sc-route="{{ route('share_capital.store') }}"
                                        data-sv-deposit-route="{{ route('savings.deposit') }}"
                                        data-sv-withdraw-route="{{ route('savings.withdraw') }}">
                                        @csrf
                                        <input type="hidden" name="_form" id="tx-form-marker" value="share_capital">

                                        {{-- <div class="tx-dest-toggle">
                                            <button type="button" class="tx-dest-btn active" data-dest="share_capital">
                                                <i class="fa fa-coins"></i> Share Capital
                                            </button>
                                            <button type="button" class="tx-dest-btn" data-dest="savings">
                                                <i class="fa fa-piggy-bank"></i> Savings
                                            </button>
                                        </div> --}}

                                        <div
                                            style="border-radius: 10px; padding: 0.8rem 1.1rem; display: flex; align-items: center; border: 1px solid rgba(0, 0, 0, 0.1); margin: 0 0 1.1rem 0; justify-content: space-between;">
                                            <span style="font-size: 0.78rem; color: var(--muted);"
                                                id="tx-balance-label">Current
                                                Balance</span>
                                            <span id="tx-balance-value"
                                                style="font-size: 14.5px; font-weight: 600; color: #1a1a1a">
                                                ₱{{ number_format($currentBalance ?? 0, 0) }} ·
                                                {{ $currentShares ?? 0 }} shares
                                            </span>
                                        </div>

                                        <div class="sc-inline-error" id="tx-inline-error">
                                            <i class="fa fa-circle-exclamation"></i>
                                            <span id="tx-inline-error-text"></span>
                                        </div>

                                        {{-- SHARE CAPITAL FIELDS --}}
                                        <div id="tx-sc-fields">
                                            <p style="margin: 0 0 8px; font-size: 13px; color: #666;">Number of shares
                                            </p>
                                            <div
                                                style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                                <button type="button" id="tx-dec"
                                                    style="width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #ddd; background: #fff; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #333;">−</button>
                                                <input type="number" name="shares" id="tx-shares" value="1" min="1"
                                                    step="0.01"
                                                    style="width: 60px; text-align: center; font-size: 14.5px; font-weight: 600; color: var(--teal); border: 1.5px solid #ddd; border-radius: 10px; padding: 6px;">
                                                <button type="button" id="tx-inc"
                                                    style="width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #ddd; background: #fff; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #333;">+</button>
                                            </div>

                                            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 15px;">
                                                <button type="button" class="tx-qbtn active" data-v="1"
                                                    style="padding: 5px 13px; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; background: #fff; color: #808080; border: 1.5px solid #ddd;">1
                                                    share</button>
                                                <button type="button" class="tx-qbtn" data-v="6.25"
                                                    style="padding: 5px 13px; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; background: #fff; color: #808080; border: 1.5px solid #ddd;">6.25
                                                    shares</button>
                                                <button type="button" class="tx-qbtn" data-v="10"
                                                    style="padding: 5px 13px; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; background: #fff; color: #808080; border: 1.5px solid #ddd;">10
                                                    shares</button>
                                                <button type="button" class="tx-qbtn" data-v="12"
                                                    style="padding: 5px 13px; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; background: #fff; color: #808080; border: 1.5px solid #ddd;">12
                                                    shares</button>
                                                <button type="button" class="tx-qbtn" data-v="25"
                                                    style="padding: 5px 13px; border-radius: 7px; font-size: 12px; font-weight: 500; cursor: pointer; background: #fff; color: #808080; border: 1.5px solid #ddd;">25
                                                    shares</button>
                                            </div>

                                            <p style="font-size: 12px; color: #888888; margin-bottom: 1.2rem;">
                                                Cost: <strong id="tx-cost" style="color: var(--teal);">₱200</strong> ·
                                                ₱200 per Share (Par Value)
                                            </p>
                                        </div>

                                        {{-- SAVINGS FIELDS --}}
                                        <div id="tx-sv-fields" style="display:none;">
                                            <label class="sm-form-label" for="tx-amount"
                                                style="font-size: 13px; color: #666; display:block; margin-bottom:8px;">Amount</label>
                                            <div class="sm-amount-wrap" style="margin-bottom: 10px;">
                                                <span class="sm-amount-prefix">₱</span>
                                                <input class="sm-form-input" type="number" id="tx-amount" name="amount"
                                                    placeholder="0.00" min="1" step="0.01" disabled />
                                            </div>
                                            <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 15px;">
                                                <button type="button" class="tx-amt-qbtn" data-v="500">₱500</button>
                                                <button type="button" class="tx-amt-qbtn" data-v="1000">₱1,000</button>
                                                <button type="button" class="tx-amt-qbtn" data-v="1500">₱1,500</button>
                                                <button type="button" class="tx-amt-qbtn" data-v="2000">₱2,000</button>
                                                <button type="button" class="tx-amt-qbtn" data-v="5000">₱5,000</button>
                                            </div>
                                        </div>

                                        <div style="margin-bottom: 1.1rem;">
                                            <label
                                                style="font-size: 12px; text-transform: uppercase; color: #888888; font-weight: 600; display: block; margin-bottom: 6px;">Account</label>
                                            <select id="tx-dest-select" class="form-select" required
                                                style="border-radius: 10px;border: 1.5px solid #e0e0e0;height: 46px;font-size: 14px;color: #333;">
                                                <option value="">Select account</option>
                                                <option value="share_capital">🪙 Share Capital</option>
                                                <option value="savings">🐷 Savings</option>
                                            </select>
                                        </div>

                                        <div style="margin-bottom: 17.6px;">
                                            <label
                                                style="font-size: 12px; text-transform: uppercase; color: #888888; font-weight: 600; display: block; margin-bottom: 6px;">Type</label>
                                            <select name="type" class="form-select" id="tx-type" required
                                                style="border-radius: 10px;border: 1.5px solid #e0e0e0;height: 46px;font-size: 14px;color: #333;">
                                                <option value="">Select type..</option>
                                                <option value="Deposit">Deposit</option>
                                                <option value="Withdrawal">Withdrawal</option>
                                            </select>
                                        </div>

                                        <div id="tx-sc-withdrawal-notice"
                                            style="display:none; background:#fff8e1; border:1.5px solid #ffe082; border-radius:10px; padding:0.65rem 1rem; margin-bottom:0.9rem; font-size:12px; color:#856404; line-height:1.5;">
                                            <i class="fa fa-circle-info" style="margin-right:6px;"></i>
                                            Withdrawal requests are subject to admin approval. Your current share
                                            balance will <strong>not</strong> be reduced until the request is approved.
                                        </div>

                                        <div id="tx-sc-full-withdrawal-warning"
                                            style="display:none; background:#fef2f2; border:1.5px solid #fecaca; border-radius:10px; padding:0.65rem 1rem; margin-bottom:0.9rem; font-size:12px; color:#991b1b; line-height:1.5;">
                                            <i class="fa fa-circle-exclamation" style="margin-right:6px;"></i>
                                            <strong>Notice:</strong> Fully withdrawing your share capital is equivalent
                                            to resigning. This action will submit a resignation request and is subject
                                            to a 60-day holding period upon approval.
                                        </div>

                                        <div style="margin-bottom: 17.6px;">
                                            <label
                                                style="font-size: 12px; text-transform: uppercase; color: #888888; font-weight: 600; display: block; margin-bottom: 6px;">Payment
                                                Method</label>
                                            <select name="payment_method" class="form-select" id="tx-pay" required
                                                style="border-radius: 10px; border: 1.5px solid #e0e0e0; height: 46px; font-size: 14px; color: #333;">
                                                <option value="" disabled selected>Select payment method...</option>
                                                <option value="cash">Cash</option>
                                                <option value="gcash">GCash</option>
                                            </select>
                                        </div>

                                        <div id="tx-gcash-box" style="display: none; margin-top: 0.8rem;">
                                            @if($gcashPaymentMethod && $gcashPaymentMethod->has_qr_code && $gcashPaymentMethod->qr_code_image_path)
                                                <div
                                                    style="background: linear-gradient(135deg, #f0f7ff 0%, #e8f4ff 100%); border: 1.5px solid #c2deff; border-radius: 12px; padding: 1rem 1.2rem; text-align: center;">
                                                    <p
                                                        style="margin: 0 0 10px; font-size: 14px; font-weight: 700; color: #0056b3;">
                                                        <i class="fa-solid fa-mobile-screen-button"></i> Scan to Pay via
                                                        GCash
                                                    </p>
                                                    <img src="{{ asset('storage/' . $gcashPaymentMethod->qr_code_image_path) }}"
                                                        alt="GCash QR Code"
                                                        style="width: 220px; height: 220px; max-width: 100%; object-fit: contain; border-radius: 10px; border: 1px solid #c2deff; background: #fff; padding: 12px; display: block; margin: 0 auto;">
                                                    <p style="margin: 10px 0 0; font-size: 11px; color: #5a8ac4;">
                                                        Scan this using your GCash app, then upload your payment screenshot
                                                        below.
                                                    </p>
                                                    <p style="margin: 6px 0 0; font-size: 11px;">
                                                        <a href="#"
                                                            onclick="openQrLightbox('{{ asset('storage/' . $gcashPaymentMethod->qr_code_image_path) }}'); return false;"
                                                            style="color: #0056b3; font-weight: 600;">
                                                            <i class="fa fa-up-right-and-down-left-from-center"></i> View
                                                            full-size QR
                                                        </a>
                                                    </p>
                                                </div>
                                            @else
                                                <div
                                                    style="background: #fff3cd; border: 1.5px solid #ffe08a; border-radius: 12px; padding: 1rem 1.2rem;">
                                                    <p style="margin: 0; font-size: 13px; color: #856404;">
                                                        <i class="fa fa-triangle-exclamation"></i> No GCash QR code has been
                                                        set up yet. Please contact the admin.
                                                    </p>
                                                </div>
                                            @endif

                                            <div style="margin-top: 1rem;">
                                                <label
                                                    style="font-size: 12px; text-transform: uppercase; font-weight: 600; color: #888888; display: block; margin-bottom: 6px;">
                                                    Upload Payment Screenshot <span
                                                        style="font-size: 11px; color: #bbb;">(GCash proof)</span>
                                                </label>
                                                <input type="file" name="gcash_proof" id="tx-gcash-proof-input"
                                                    accept="image/png,image/jpeg,image/jpg"
                                                    style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #ddd; font-size: 14px; box-sizing: border-box;"
                                                    class="form-control">
                                                <div id="tx-gcash-proof-preview" style="display:none; margin-top:10px;">
                                                    <img id="tx-gcash-proof-preview-img"
                                                        style="width:100%; height:180px; object-fit:cover; border-radius:8px; border:1px solid #e0e0e0;">
                                                </div>
                                            </div>
                                        </div>

                                        <div style="margin-top: 17.6px;">
                                            <label
                                                style="font-size: 12px;text-transform: uppercase; font-weight: 600; color: #888888; display: block; margin-bottom: 6px;">
                                                Note <span style="font-size: 12px; color: #bbb;">(optional)</span>
                                            </label>
                                            <input type="text" name="note" id="tx-note"
                                                placeholder="e.g. Q2 CBU installment"
                                                style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #ddd; font-size: 14px; color: #333; box-sizing: border-box; height: 46px; ">
                                        </div>

                                    </form>

                                </div>

                                <div class="modal-footer"
                                    style="background: #f8f9fa; border-top: 1px solid rgba(0, 0, 0, 0.1); padding: 1rem 1.6rem;display: flex;justify-content: center;align-items: center; gap: 8px;">
                                    <button type="submit" form="unified-tx-form" id="tx-submit-btn"
                                        style="width: 100%; padding: 0.75rem; background: var(--teal); color: #fff; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                        <i class="fa fa-right-left"></i> Confirm Transaction
                                    </button>
                                    <button type="button" class="btn w-100 text-center" data-bs-dismiss="modal"
                                        style="border-radius: 8px;font-size: 14px; padding: 10px 18px; border: 1.5px solid #e0e0e0;color: var(--muted);">
                                        Cancel
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="main-parent">

                        {{-- ═══════════════════════════════════════
                        PAGE HEADER + TABS
                        ═══════════════════════════════════════ --}}
                        <div class="parent-main">
                            <div class="parent-text">
                                <h3>Financial</h3>
                                <p>Track your Share Capital contributions and Savings activity in one place.</p>
                            </div>
                            <div class="parent-download">
                                <div class="share">
                                    <button data-bs-toggle="modal" data-bs-target="#unifiedTxModal">
                                        <i class="fa fa-right-left"></i>
                                        <span>New Transaction</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="fin-tabs">
                            <a href="{{ route('Financial', ['tab' => 'share_capital']) }}"
                                class="fin-tab {{ $activeTab === 'share_capital' ? 'active' : '' }}">Share Capital</a>
                            <a href="{{ route('Financial', ['tab' => 'savings']) }}"
                                class="fin-tab {{ $activeTab === 'savings' ? 'active' : '' }}">Savings</a>
                        </div>

                        {{-- ═══════════════════════════════════════════════════════════
                        TAB: SHARE CAPITAL
                        ═══════════════════════════════════════════════════════════ --}}
                        @if($activeTab === 'share_capital')

                            {{-- STAT CARDS --}}
                            <div class="sc-stat-grid">

                                <div class="sc-stat-card" style="animation-delay:.05s">
                                    <div class="sc-stat-header">
                                        <div class="sc-stat-label">Subscribed Capital</div>
                                        <div class="sc-stat-icon blue"><i class="fa fa-bullseye"></i></div>
                                    </div>
                                    <div class="sc-stat-value green">₱{{ number_format($targetAmount, 0) }}</div>
                                    <div class="sc-stat-sub">Target · {{ $targetShares }} shares @ ₱{{ $parValue }} par
                                        value</div>
                                </div>

                                <div class="sc-stat-card" style="animation-delay:.08s">
                                    <div class="sc-stat-header">
                                        <div class="sc-stat-label">Paid-up Capital</div>
                                        <div class="sc-stat-icon green"><i class="fa fa-peso-sign"></i></div>
                                    </div>
                                    <div class="sc-stat-value green">₱{{ number_format($currentBalance, 0) }}</div>
                                    <div class="sc-stat-sub">{{ $currentShares }} shares actually contributed</div>
                                    <div class="sc-progress-track">
                                        <div class="sc-progress-fill" style="width:{{ $paidUpPercent }}%;"></div>
                                    </div>
                                </div>

                                <div class="sc-stat-card" style="animation-delay:.11s">
                                    <div class="sc-stat-header">
                                        <div class="sc-stat-label">CBU Progress</div>
                                        <div class="sc-stat-icon blue"><i class="fa fa-arrows-rotate"></i></div>
                                    </div>
                                    <div class="sc-stat-value blue">{{ $paidUpPercent }}%</div>
                                    <div class="sc-stat-sub {{ $remainingToTarget > 0 ? '' : 'unlocked-note' }}">
                                        @if($remainingToTarget > 0)
                                            ₱{{ number_format($remainingToTarget, 0) }} remaining to complete subscription
                                        @else
                                            Subscription complete
                                        @endif
                                    </div>
                                </div>

                                <div class="sc-stat-card" style="animation-delay:.14s">
                                    <div class="sc-stat-header">
                                        <div class="sc-stat-label">Share Certificate</div>
                                        <div class="sc-stat-icon {{ $certificateEligible ? 'gold' : 'locked' }}">
                                            <i class="fa {{ $certificateEligible ? 'fa-certificate' : 'fa-lock' }}"
                                                style="color:{{ $certificateEligible ? '#C9A84C' : '#999' }};"></i>
                                        </div>
                                    </div>
                                    <div class="sc-stat-value {{ $certificateEligible ? 'gold' : '' }}">
                                        {{ $certificateEligible ? 'Eligible' : 'Not Yet Eligible' }}
                                    </div>
                                    <div class="sc-stat-sub {{ $certificateEligible ? 'unlocked-note' : 'locked-note' }}">
                                        Unlocks automatically at ₱{{ number_format($targetAmount, 0) }} paid-up
                                    </div>
                                </div>

                            </div>

                            <div class="sc-journey-card-capital">
                                <div class="sc-journey-header">
                                    <div>
                                        <p class="sc-journey-title">
                                            2-Year Capital Build-Up Journey ·
                                            <span class="accent">
                                                @if($nextDueSlot)
                                                    {{ $nextDueSlot['sublabel'] }}, {{ $nextDueSlot['label'] }}
                                                    {{ $nextDueSlot['status'] === 'due' ? 'due now' : 'upcoming' }}
                                                @else
                                                    All installments complete
                                                @endif
                                            </span>
                                        </p>
                                        <p class="sc-journey-sub">Every member subscribes
                                            ₱{{ number_format($targetAmount, 0) }} in share capital, payable over 8 quarters
                                            (2 years). Miss a quarter and it simply shifts your certificate date — no
                                            penalty applies.</p>
                                    </div>
                                    <span class="sc-journey-target-chip"><i class="fa fa-bullseye"
                                            style="margin-right:5px;"></i>Target ₱{{ number_format($targetAmount, 0) }} by
                                        Q4 · Year 2</span>
                                </div>

                                <div class="sc-timeline-row">
                                    @php
                                        $paidCount = collect($installmentTimeline)->where('status', 'paid')->count();
                                        $progressPct = round(($paidCount / max(1, count($installmentTimeline) - 1)) * 92);
                                    @endphp
                                    <div class="sc-timeline-progress" style="width: {{ $progressPct }}%;"></div>
                                    @foreach($installmentTimeline as $slot)
                                        <div class="sc-tl-slot">
                                            <div class="sc-tl-dot {{ $slot['status'] }}"></div>
                                            <div class="sc-tl-year">{{ $slot['sublabel'] }}</div>
                                            <div class="sc-tl-q">{{ $slot['label'] }}</div>
                                            <div class="sc-tl-amount {{ $slot['status'] === 'due' ? 'due' : '' }}">
                                                @if($slot['status'] === 'paid')
                                                    ₱{{ number_format($slot['amount'], 0) }} paid
                                                @elseif($slot['status'] === 'due')
                                                    Due now
                                                @else
                                                    Upcoming
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="sc-journey-footer">
                                    <div class="sc-journey-eligibility">
                                        <div class="journey-icon">
                                            <i class="fa fa-award"></i>
                                        </div>
                                        <div>
                                            <strong>Share Certificate Eligibility</strong>
                                            Automatically issued once Paid-up Capital reaches
                                            ₱{{ number_format($targetAmount, 0) }}
                                            @if(!$certificateEligible && $nextDueSlot)
                                                — at your current pace, expected around
                                                {{ optional($nextDueSlot['date'])->format('M d, Y') }}.
                                            @endif
                                        </div>
                                    </div>
                                    <button type="button" class="sc-btn-pay-installment" data-bs-toggle="modal"
                                        data-bs-target="#shareCapital">
                                        <i class="fa fa-coins" style="margin-right:6px;"></i>
                                        @if($nextDueSlot)
                                            Pay {{ $nextDueSlot['label'] }} {{ $nextDueSlot['sublabel'] }} Installment
                                        @else
                                            Fully Subscribed
                                        @endif
                                    </button>
                                </div>

                                @if($advancePayment > 0)
                                    <div class="sc-advance-note">
                                        <i class="fa fa-circle-plus" style="margin-right:6px;"></i>
                                        <strong>₱{{ number_format($advancePayment, 0) }}</strong>
                                        in additional contributions beyond your 2-year plan window has been recorded and
                                        is included in your Paid-up Capital above.
                                    </div>
                                @endif
                            </div>

                            {{-- ═══════════════════════════════════════
                            DIVIDEND EARNINGS — 60/40 SPLIT
                            ═══════════════════════════════════════ --}}
                            <h3 class="sc-split-heading">
                                <!-- Dividend Earnings — The 60/40 Split
                                    <span class="sc-split-note">Distributed once a year, after audited annual surplus is
                                        approved by the General Assembly</span> -->
                                <div>
                                    <h4>Dividend Earnings — The 60/40 Split</h4>
                                    <p>Distributed once a year, after audited annual surplus is approved by the General
                                        Assembly</p>
                                </div>
                                {{-- <a href="{{ route('Financial', ['tab' => 'dividends']) }}"
                                    style="font-size:13px; font-weight:600; color:var(--teal, #1E2A4A); white-space:nowrap;">
                                    View full dividend history →
                                </a> --}}
                            </h3>
                            <div class="sc-split-grid">
                                <div class="sc-split-card isc">
                                    <div class="sc-split-tag">Capital-Based</div>
                                    <div class="sc-split-title">Interest on Share Capital (ISC)</div>
                                    <div class="sc-split-pct">
                                        {{ number_format(\App\Http\Controllers\ShareCapital::ISC_SPLIT * 100, 0) }}%
                                    </div>
                                    <div class="sc-split-desc">of allocated annual surplus. Computed on <strong>how much
                                            capital you hold</strong> — your Average Monthly Balance across the year,
                                        multiplied by the board-approved dividend rate. The more shares you build up, the
                                        larger your ISC share.</div>
                                    <div class="sc-split-est"><span>Est. ISC this
                                            cycle</span><strong>₱{{ number_format($iscAmount, 2) }}</strong></div>
                                </div>

                                <div class="sc-split-card patronage">
                                    <div class="sc-split-tag">Usage-Based</div>
                                    <div class="sc-split-title">Patronage Refund</div>
                                    <div class="sc-split-pct">
                                        {{ number_format(\App\Http\Controllers\ShareCapital::PATRONAGE_SPLIT * 100, 0) }}%
                                    </div>
                                    <div class="sc-split-desc">of allocated annual surplus. Computed on <strong>how much you
                                            transacted with the cooperative</strong> — loan interest paid, service fees, and
                                        other patronage — regardless of how many shares you hold. Rewards active members.
                                    </div>
                                    <div class="sc-split-est"><span>Est. Patronage this
                                            cycle</span><strong>₱{{ number_format($patronageAmount, 2) }}</strong></div>
                                </div>

                                <div class="sc-split-card transparent">
                                    <div class="sc-split-tag">Transparent Basis</div>
                                    <div class="sc-split-pct">₱{{ number_format($projectedNextDividend, 0) }}</div>
                                    <div class="sc-split-desc">Your Average Monthly Balance (AMB) — the running average of
                                        your paid-up capital across the 12-month period. This is the figure your ISC is
                                        computed from.</div>
                                    <div class="sc-split-formula">
                                        ISC = AMB × <strong>{{ $dividendRate }}% p.a.</strong> × 60%<br>
                                        Patronage = Loan interest paid × allocation rate × 40%
                                    </div>
                                </div>
                            </div>

                            {{-- 2-YEAR CAPITAL BUILD-UP JOURNEY --}}
                            <div class="sc-journey-card">


                                {{-- NOTICES --}}
                                <div class="sc-notice-grid">
                                    <div class="sc-notice-box warn">
                                        <div class="sc-notice-icon"><i class="fa fa-lock"></i></div>
                                        <div>
                                            <strong class="title">Non-Withdrawable Fund<span class="tag">INVESTMENT /
                                                    SOSYO</span></strong><br>
                                            Share capital is your <strong>investment (sosyo)</strong> in the cooperative,
                                            not a
                                            savings account. It cannot be withdrawn on demand — it earns dividends annually
                                            and
                                            builds your ownership stake instead of sitting as liquid, on-call funds.
                                        </div>
                                    </div>
                                    <div class="sc-notice-box danger">
                                        <div class="sc-notice-icon"><i class="fa fa-triangle-exclamation"></i></div>
                                        <div>
                                            <strong class="title">Applied to Outstanding Loans on Resignation</strong><br>
                                            If you resign or your membership is terminated, your share capital balance will
                                            first be used to <strong>offset any outstanding loan balance</strong> before the
                                            remainder — if any — is refunded to you, per cooperative bylaws.
                                        </div>
                                    </div>
                                </div>

                                {{-- CONTRIBUTION HISTORY --}}
                                <div class="contribution-parent">
                                    <div class="contribution-text">
                                        <h3>Contribution History</h3>
                                        <p>View your contribution history breakdown</p>
                                    </div>
                                    <div class="contribution-header">
                                        <div class="contribution-search">
                                            <div class="sc-search-box">
                                                <i class="fa fa-search" style="color:#aaa; font-size:13px;"></i>
                                                <input type="text" id="sc-search-input"
                                                    placeholder="Search reference no., type...">
                                            </div>
                                        </div>
                                        <div class="sc-filter-bar">
                                            <div class="sc-filter-group">
                                                <input type="date" id="sc-filter-date" class="form-control">
                                            </div>
                                            <div class="sc-filter-stats">
                                                <div class="sc-filter-group">
                                                    <select id="sc-filter-type" class="form-select">
                                                        <option value="">All</option>
                                                        <option value="Deposit">Deposit</option>
                                                        <option value="Withdrawal">Withdrawal</option>
                                                    </select>
                                                </div>
                                                <div class="sc-filter-group">
                                                    <select id="sc-filter-status" class="form-select">
                                                        <option value="">All</option>
                                                        <option value="posted">Completed</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="failed">Failed</option>
                                                    </select>
                                                </div>
                                                <button type="button" id="sc-filter-clear">Clear filter</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="parent-table">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Reference No.</th>
                                                    <th>Shares Purchased</th>
                                                    <th>Amount</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody id="sc-contribution-tbody">
                                                @forelse($contributions as $row)
                                                    @php
                                                        $method = strtolower($row->payment_method ?? '');
                                                        $channelClass = match (true) {
                                                            str_contains($method, 'gcash') => 'gcash',
                                                            str_contains($method, 'maya') => 'maya',
                                                            str_contains($method, 'otc') || str_contains($method, 'counter') => 'otc',
                                                            str_contains($method, 'transfer') => 'transfer',
                                                            default => 'cash',
                                                        };
                                                        $channelLabel = match ($channelClass) {
                                                            'gcash' => 'GCash',
                                                            'maya' => 'Maya',
                                                            'otc' => 'Over-the-Counter',
                                                            'transfer' => 'Transfer',
                                                            default => 'Cash',
                                                        };
                                                        $isPending = strtolower($row->status ?? '') === 'pending';
                                                        $statusKey = ($row->status === 'Completed' || $row->status === null)
                                                            ? 'posted'
                                                            : ($isPending ? 'pending' : 'failed');
                                                    @endphp
                                                    <tr data-date="{{ \Carbon\Carbon::parse($row->transaction_date)->format('Y-m-d') }}"
                                                        data-type="{{ $row->type }}" data-status="{{ $statusKey }}">
                                                        <td>{{ \Carbon\Carbon::parse($row->transaction_date)->format('M d, Y') }}
                                                        </td>
                                                        <td>{{ $row->type }}</td>
                                                        <td>
                                                            <span
                                                                class="sc-channel-badge {{ $channelClass }}">{{ $channelLabel }}</span>
                                                            <span class="sc-ref-text">
                                                                {{ $isPending && empty($row->reference_no) ? 'Awaiting reference' : ('Ref# ' . ($row->reference_no ?? '—')) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $row->shares ? number_format((float) $row->shares, 2) . ' shares' : '— shares' }}
                                                        </td>
                                                        <td>₱{{ number_format($row->total_amount, 2) }}</td>
                                                        <td>
                                                            @if($row->status === 'Completed' || $row->status === null)
                                                                <span class="sc-status-pill posted">Completed</span>
                                                            @elseif($isPending)
                                                                <span class="sc-status-pill pending">Pending</span>
                                                            @else
                                                                <span class="sc-status-pill failed">{{ $row->status }}</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="6"
                                                            style="text-align: center; color: #aaa; padding: 2rem; font-size: 13px;">
                                                            <i class="fa fa-inbox"
                                                                style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                                                            No contributions yet.<br>
                                                            <span style="font-size: 11px;">Start by subscribing to share
                                                                capital.</span>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($contributions->isNotEmpty())
                                        <div class="sc-table-footer">
                                            <span id="sc-table-footer-count">Showing {{ $contributions->count() }} of
                                                {{ $contributions->count() }}
                                                contributions</span>
                                            <div class="sc-pagination" id="sc-pagination"></div>
                                        </div>
                                    @endif
                                </div>

                        @endif

                            {{-- ═══════════════════════════════════════════════════════════
                            TAB: SAVINGS
                            ═══════════════════════════════════════════════════════════ --}}
                            @if($activeTab === 'savings')

                                        <main class="main">
                                            <div class="card-box-parent">
                                                <div class="card-box-text">
                                                    <h3>My Savings Balance</h3>
                                                    <h2>₱ <b>{{ number_format($totalSavingsBalance, 2) }}</b></h2>
                                                    <div class="hero-sub">
                                                        Last updated {{ $lastUpdated }} ·
                                                        {{ $monthsActive == 0 ? 'Less than a month' : $monthsActive . ' ' . ($monthsActive == 1 ? 'month' : 'months') }}
                                                        active
                                                    </div>
                                                </div>
                                            </div>
                                        </main>

                                        <div class="{{ !$hasShareCapital ? 'gated' : '' }}">
                                            <section id="section1">
                                                <div class="main-card-box">
                                                    <div class="card-box tw:bg-white">
                                                        <div class="card-header-icon">
                                                            <p>Interest Accrued</p>
                                                            <div class="card-icon d-flex justify-content-center align-items-center">
                                                                <i class="fa-solid fa-percent"></i>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4>₱ {{ number_format($estimatedQuarterInterest, 2) }}</h4>
                                                            <span>{{ number_format($regularSavingsRate, 2) }}% p.a. · credited
                                                                {{ $regularSavingsFrequency }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="card-box tw:bg-white">
                                                        <div class="card-header-icon">
                                                            <p>Monthly Average</p>
                                                            <div class="card-icon d-flex justify-content-center align-items-center">
                                                                <i class="fa-solid fa-arrow-trend-up"></i>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4>₱ {{ number_format($monthlyAverage, 2) }}</h4>
                                                            <span>Per month average</span>
                                                        </div>
                                                    </div>

                                                    <div class="card-box tw:bg-white">
                                                        <div class="card-header-icon">
                                                            <p>Total Months</p>
                                                            <div class="card-icon d-flex justify-content-center align-items-center">
                                                                <i class="fa-solid fa-calendar-days"></i>
                                                            </div>
                                                        </div>
                                                        <div class="card-body">
                                                            <h4>{{ $totalMonths }} Months</h4>
                                                            <span>Months saving</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>

                                            @if(!$hasShareCapital)
                                                <div class="gate-shield">
                                                    <div class="gate-lock"><i class="fa-solid fa-lock"></i></div>
                                                    <div class="gate-msg">Savings stats are locked</div>
                                                    <div class="gate-sub">
                                                        Please subscribe to Share Capital first to unlock your savings stats — use the
                                                        <a href="{{ route('Financial', ['tab' => 'share_capital']) }}">Share Capital
                                                            tab</a> above.
                                                    </div>
                                                </div>
                                            @endif
                                        </div>



                                        <div class="ask-box">
                                    <div class="ask-body">
                                        <div class="ask-card">
                                            <div class="ask-card-text">
                                                <h3>Secure your savings with Time Deposit</h3>
                                                <p>Grow your money with higher returns and guaranteed earnings over a fixed term.
                                                </p>
                                            </div>
                                            <div class="{{ !$hasShareCapital ? 'gated' : '' }}">
                                                <div
                                                    class="ask-card-button {{ request()->routeIs('TimeDeposit') ? 'active' : '' }}">
                                                    <a href="{{ route("TimeDeposit") }}">

                                                        Time Deposit
                                                        <i class="fa fa-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- ══ BREAKDOWN + GROWTH GRAPH — its own gated/hover-lock block ══ --}}
                                <div class="{{ !$hasShareCapital ? 'gated' : '' }}">
                                    <div class="parent-panel">
                                        <div class="panel">
                                            <div class="panel-head">
                                                <div class="panel-text">
                                                    <h3>Time Deposit Accounts</h3>
                                                    <p>Time Deposit history</p>
                                                </div>
                                                <div class="panel-view">
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#tdHistoryModal">
                                                        View all
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="panel-body">
                                                @forelse ($tdHistory as $td)
                                                    <div class="panel-card">
                                                        <div class="panel-icon">
                                                            @if ($td->display_status === 'completed')
                                                                <i class="fa fa-circle-check"></i>
                                                            @elseif ($td->display_status === 'matured')
                                                                <i class="fa fa-hourglass-end"></i>
                                                            @elseif ($td->display_status === 'goal_reached')
                                                                <i class="fa fa-bullseye"></i>
                                                            @else
                                                                <i class="fa fa-lock"></i>
                                                            @endif
                                                        </div>
                                                        <div class="panel-text">
                                                            <div class="text">
                                                                <h4>₱{{ number_format($td->goal_amount, 2) }} Goal</h4>
                                                                <p>
                                                                    Opened {{ \Carbon\Carbon::parse($td->opened_at)->format('M d, Y') }}
                                                                    · {{ number_format($td->interest_rate, 2) }}% p.a.
                                                                </p>
                                                            </div>
                                                            <div class="price">
                                                                <h4>₱{{ number_format($td->display_balance, 2) }}</h4>
                                                                @if ($td->display_status === 'completed')
                                                                    <p style="color:var(--green);font-weight:700;">Completed</p>
                                                                @elseif ($td->display_status === 'matured')
                                                                    <p style="color:var(--green);font-weight:700;">Ready to Claim</p>
                                                                @elseif ($td->display_status === 'goal_reached')
                                                                    <p style="color:var(--blue, #1e56a0);font-weight:700;">Fully Funded</p>
                                                                @else
                                                                    <p style="color:#AB7817;font-weight:700;">In Progress</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div style="text-align:center; padding:2.5rem 1rem;">
                                                        <i class="fa-solid fa-piggy-bank fa-2x"
                                                            style="color:var(--muted); opacity:.4;"></i>
                                                        <p style="color:var(--muted); margin-top:0.75rem; font-size:13.5px;">
                                                            No Time Deposits opened yet.
                                                        </p>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>

                                        <div class="panel graph">
                                            <div class="panel-head"
                                                style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
                                                <div>
                                                    <h3>Savings Growth</h3>
                                                    <p>{{ $growthYear === now()->year ? 'Net deposits over the last 6 months' : "Net deposits for {$growthYear}" }}
                                                    </p>
                                                </div>
                                                <select class="sm-filter-select" id="growthYearSelect"
                                                    onchange="changeGrowthYear(this.value)">
                                                    @foreach($availableGrowthYears as $y)
                                                        <option value="{{ $y }}" {{ $growthYear == $y ? 'selected' : '' }}>{{ $y }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="panel-body">
                                                <div class="chart-wrap">
                                                    @foreach ($savingsGrowth as $month)
                                                        <div class="bar-col {{ $month['is_current'] ? 'active' : '' }}">
                                                            <div class="bar" style="height:{{ $month['height_percent'] }}%">
                                                                <div class="bar-tooltip">
                                                                    <div class="bar-tooltip-title">{{ $month['label'] }}</div>
                                                                    <div class="bar-tooltip-row">
                                                                        <span
                                                                            class="bar-tooltip-dot {{ $month['is_current'] ? 'dot-gold' : 'dot-blue' }}"></span>
                                                                        <span class="bar-tooltip-label">Net Savings:</span>
                                                                        <span class="bar-tooltip-value">
                                                                            {{ $month['net'] >= 0 ? '₱' : '-₱' }}{{ number_format(abs($month['net']), 2) }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span class="bar-month">{{ $month['label'] }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="chart-legend">
                                                    <div class="legend-item"><span class="legend-dot"
                                                            style="background:var(--blue);"></span>Prior months</div>
                                                    <div class="legend-item"><span class="legend-dot"
                                                            style="background:var(--gold);"></span>Current month</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(!$hasShareCapital)
                                        <div class="gate-shield">
                                            <div class="gate-lock"><i class="fa-solid fa-lock"></i></div>
                                            <div class="gate-msg">Savings breakdown is locked</div>
                                            <div class="gate-sub">
                                                Please <a href="{{ route('ShareCapitalMember') }}">subscribe to Share Capital</a>
                                                first to unlock your breakdown and growth chart.
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                            <div class="{{ !$hasShareCapital ? 'gated' : '' }}">
                                                <section id="section2">
                                                    <div class="card-box-parent">
                                                        <div class="d-flex justify-content-between align-items-center card-box-title">
                                                            <div class="title">
                                                                <h3>Transaction History</h3>
                                                                <p>View your monthly transactions breakdown</p>
                                                            </div>
                                                            <div class="gap-3 print">
                                                                <button class="py-2 px-3 tw:text-white" style="border-radius: 10px">
                                                                    <i class="fa-solid fa-download"></i> CSV
                                                                </button>
                                                                <button class="py-2 px-3 tw:text-white" style="border-radius: 10px">
                                                                    <i class="fa fa-solid fa-download"></i> PDF
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="sm-tab-group">
                                                            <a href="{{ route('Financial', array_merge(request()->except('type', 'page'), ['tab' => 'savings', 'type' => 'all'])) }}"
                                                                class="sm-tab {{ $type === 'all' ? 'active' : '' }}">All</a>
                                                            <a href="{{ route('Financial', array_merge(request()->except('type', 'page'), ['tab' => 'savings', 'type' => 'deposit'])) }}"
                                                                class="sm-tab {{ $type === 'deposit' ? 'active' : '' }}">Deposits</a>
                                                            <a href="{{ route('Financial', array_merge(request()->except('type', 'page'), ['tab' => 'savings', 'type' => 'withdrawal'])) }}"
                                                                class="sm-tab {{ $type === 'withdrawal' ? 'active' : '' }}">Withdrawals</a>
                                                        </div>

                                                        <form method="GET" action="{{ route('Financial') }}" class="sm-tx-toolbar"
                                                            id="sm-tx-filter-form">
                                                            <input type="hidden" name="tab" value="savings">
                                                            <input type="hidden" name="type" value="{{ $type }}">
                                                            <div class="sm-search-box">
                                                                <i class="fa-solid fa-magnifying-glass"></i>
                                                                <input type="text" name="ref" value="{{ $ref }}"
                                                                    placeholder="Search by reference no.">
                                                            </div>
                                                            <input type="date" class="sm-filter-select" name="date" value="{{ $date }}"
                                                                onchange="document.getElementById('sm-tx-filter-form').submit()">

                                                            <select name="status" class="sm-filter-select"
                                                                onchange="this.form.submit()">
                                                                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status
                                                                </option>
                                                                @foreach($availableStatuses as $s)
                                                                    <option value="{{ strtolower($s) }}" {{ $status === strtolower($s) ? 'selected' : '' }}>{{ $s }}</option>
                                                                @endforeach
                                                            </select>

                                                            @if($ref !== '' || $date !== '' || $status !== 'all')
                                                                <a href="{{ route('Financial', ['tab' => 'savings', 'type' => $type]) }}"
                                                                    class="sm-filter-clear">Clear filters</a>
                                                            @endif
                                                        </form>

                                                        <div class="card-box-savings">
                                                            <div class="overflow-x-auto">
                                                                <table class="table table-scroll m-0">
                                                                    <thead>
                                                                        <tr style="border-bottom: 1px solid rgba(0,0,0,0.2);">
                                                                            <th class="text-start">Type</th>
                                                                            <th class="text-start">Reference No.</th>
                                                                            <th class="text-start">Date</th>
                                                                            <th class="text-start">Amount</th>
                                                                            <th class="text-start">Status</th>
                                                                            <th class="text-start">Receipt</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse ($transactions as $tx)
                                                                            <tr>
                                                                                <td class="text-start">
                                                                                    @if($tx->type === 'deposit' && str_starts_with($tx->reference_no ?? '', 'DISB'))
                                                                                        <div class="deposit">Loan Disbursement</div>
                                                                                    @elseif($tx->type === 'deposit' && str_starts_with($tx->reference_no ?? '', 'PAT'))
                                                                                        <div class="deposit">Patronage Refund</div>
                                                                                    @elseif($tx->type === 'deposit')
                                                                                        <div class="deposit">Deposit</div>
                                                                                    @elseif($tx->type === 'td_release')
                                                                                        <div class="deposit">Time Deposit Claimed</div>
                                                                                    @elseif(str_starts_with($tx->reference_no ?? '', 'LNPAY'))
                                                                                        <div class="withdraw">Loan Repay</div>
                                                                                    @else
                                                                                        <div class="withdraw">Withdrawal</div>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-start">
                                                                                    @if ($tx->reference_no)
                                                                                        <span class="tx-ref">{{ $tx->reference_no }}</span>
                                                                                    @else
                                                                                        <span style="color:#000000;font-size:0.78rem">—</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-start">
                                                                                    {{ \Carbon\Carbon::parse($tx->transaction_date)->format('m/d/Y') }}
                                                                                </td>
                                                                                <td class="text-start"
                                                                                    style="font-weight:700; color:{{ $tx->type === 'withdrawal' ? 'var(--red)' : 'var(--green)' }}">
                                                                                    {{ $tx->type === 'withdrawal' ? '-' : '+' }} ₱
                                                                                    {{ number_format($tx->amount, 2) }}
                                                                                </td>
                                                                                <td>
                                                                                    @php
                                                                                        $displayStatus = $tx->status ?? 'completed';
                                                                                    @endphp

                                                                                    @if ($displayStatus === 'pending')
                                                                                        <span class="status pending">Pending</span>
                                                                                    @elseif (in_array($displayStatus, ['approved', 'completed']))
                                                                                        <span
                                                                                            class="status approved">{{ ucfirst($displayStatus) }}</span>
                                                                                    @elseif ($displayStatus === 'released')
                                                                                        <span class="status released">Released</span>
                                                                                    @elseif ($displayStatus === 'deducted')
                                                                                        <span class="status deducted">Deducted</span>
                                                                                    @elseif ($displayStatus === 'rejected')
                                                                                        <span class="status rejected">Rejected</span>
                                                                                    @elseif ($displayStatus === 'credited')
                                                                                        <span class="status credited">Credited</span>
                                                                                    @elseif ($displayStatus === 'locked')
                                                                                        <span class="status locked">Locked</span>
                                                                                    @else
                                                                                        <span
                                                                                            class="status">{{ ucfirst($displayStatus) }}</span>
                                                                                    @endif
                                                                                </td>
                                                                                <td class="text-start">
                                                                                    @if ($tx->reference_no && in_array($tx->type, ['deposit', 'withdrawal']))
                                                                                        <a href="{{ route('savings.receipt', $tx->reference_no) }}"
                                                                                            title="Download Receipt"
                                                                                            style="color: var(--teal);font-size: 18px;">
                                                                                            <i class="fa-solid fa-file-arrow-down"></i>
                                                                                        </a>
                                                                                    @else
                                                                                        <span style="color:#c4c4c4;font-size:0.78rem">—</span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr>
                                                                                <td colspan="6" class="text-center py-5">
                                                                                    <i class="fa-solid fa-folder-open fa-2x mb-3"
                                                                                        style="color: var(--muted);"></i>
                                                                                    <p style="color:var(--muted);margin-top:0.5rem;">No
                                                                                        transactions yet.</p>
                                                                                </td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>

                                                            @if ($transactions->total() > 0)
                                                                <div class="sm-pagination-wrap">
                                                                    <div class="sm-pagination-info">
                                                                        Showing <b>{{ $transactions->lastItem() }}</b> of
                                                                        <b>{{ $transactions->total() }}</b> transactions
                                                                    </div>

                                                                    @if ($transactions->hasPages())
                                                                        <div class="sm-pagination">
                                                                            @if ($transactions->onFirstPage())
                                                                                <span class="sm-page-btn disabled"><i
                                                                                        class="fa-solid fa-chevron-left"></i></span>
                                                                            @else
                                                                                <a href="{{ $transactions->previousPageUrl() }}"
                                                                                    class="sm-page-btn">
                                                                                    <i class="fa-solid fa-chevron-left"></i>
                                                                                </a>
                                                                            @endif

                                                                            @for ($i = 1; $i <= $transactions->lastPage(); $i++)
                                                                                <a href="{{ $transactions->url($i) }}"
                                                                                    class="sm-page-btn {{ $i == $transactions->currentPage() ? 'active' : '' }}">
                                                                                    {{ $i }}
                                                                                </a>
                                                                            @endfor

                                                                            @if ($transactions->hasMorePages())
                                                                                <a href="{{ $transactions->nextPageUrl() }}" class="sm-page-btn">
                                                                                    <i class="fa-solid fa-chevron-right"></i>
                                                                                </a>
                                                                            @else
                                                                                <span class="sm-page-btn disabled"><i
                                                                                        class="fa-solid fa-chevron-right"></i></span>
                                                                            @endif
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </section>

                                                @if(!$hasShareCapital)
                                                    <div class="gate-shield">
                                                        <div class="gate-lock"><i class="fa-solid fa-lock"></i></div>
                                                        <div class="gate-msg">Transaction history is locked</div>
                                                        <div class="gate-sub">
                                                            Please subscribe to Share Capital first to unlock your transaction history —
                                                            use the <a href="{{ route('Financial', ['tab' => 'share_capital']) }}">Share
                                                                Capital tab</a> above.
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                            @endif

                        </div>

                </main>
            </div>
        </div>

        {{-- ═══════════════════════════════════════
        ERROR / WARNING TOASTS (shared)
        ═══════════════════════════════════════ --}}
        @if(session('error'))
            <div
                style="position: fixed; top: 1.2rem; right: 1.2rem; z-index: 9999;
                                                            background: #fff; border: 1.5px solid #f5c6c6; border-radius: 14px;
                                                            padding: 1rem 1.25rem; box-shadow: 0 8px 30px rgba(0,0,0,0.12);
                                                            display: flex; align-items: center; gap: 12px; max-width: 360px;">
                <div
                    style="width: 36px; height: 36px; background: #fef0f0; border-radius: 50%;
                                                                display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fa fa-times" style="color: #e03131; font-size: 15px;"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 13px; font-weight: 700; color: #1a1a1a;">Error</p>
                    <p style="margin: 0; font-size: 12px; color: #888;">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()"
                    style="background: none; border: none; color: #bbb; font-size: 18px; cursor: pointer; margin-left: auto; line-height: 1;">×</button>
            </div>
        @endif

        @if(session('warning'))
            <div
                style="position: fixed; top: 1.2rem; right: 1.2rem; z-index: 9999;
                                                            background: #fff; border: 1.5px solid #ffe082; border-radius: 14px;
                                                            padding: 1rem 1.25rem; box-shadow: 0 8px 30px rgba(0,0,0,0.12);
                                                            display: flex; align-items: center; gap: 12px; max-width: 380px;">
                <div
                    style="width: 36px; height: 36px; background: #fff8e1; border-radius: 50%;
                                                                display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fa fa-triangle-exclamation" style="color: #b8860b; font-size: 15px;"></i>
                </div>
                <div>
                    <p style="margin: 0; font-size: 13px; font-weight: 700; color: #1a1a1a;">Notice</p>
                    <p style="margin: 0; font-size: 12px; color: #888;">{{ session('warning') }}</p>
                </div>
                <button onclick="this.parentElement.remove()"
                    style="background: none; border: none; color: #bbb; font-size: 18px; cursor: pointer; margin-left: auto; line-height: 1;">×</button>
            </div>
        @endif

    </div><!-- end .container-fluid -->

    {{-- ═══════════════════════════════════════
    SAVINGS TAB — modals (success / TD history)
    ═══════════════════════════════════════ --}}
    @if($activeTab === 'savings')

        <div class="modal fade" id="depositSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content sm-modal-content">
                    <div class="modal-body sm-success-body">

                        <div class="sm-success-icon sm-success-green">
                            <i class="fa-solid fa-check"></i>
                        </div>

                        <h5 class="sm-success-title">Deposit Successful!</h5>

                        <p class="sm-success-msg">
                            Your deposit of
                            <strong>₱
                                {{ session('deposit_amount') ? number_format(session('deposit_amount'), 2) : '0.00' }}</strong>
                            has been added to your savings account.
                        </p>

                        @if (session('deposit_reference'))
                            <div class="sm-ref-pill">
                                <span class="sm-ref-label">Reference No.</span>
                                <span class="sm-ref-value" id="deposit-ref-no">{{ session('deposit_reference') }}</span>
                                <button class="sm-copy-btn" onclick="copyRef('deposit-ref-no')" title="Copy">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        @endif

                        <div class="sm-success-balance-pill">
                            <span>New Balance</span>
                            <span>₱ {{ number_format($savingsAccount->balance, 2) }}</span>
                        </div>

                        @if (session('deposit_reference'))
                            <a href="{{ route('savings.receipt', session('deposit_reference')) }}" class="sm-btn-download">
                                <i class="fa-solid fa-file-arrow-down"></i> Download Receipt
                            </a>
                        @endif

                        <button type="button" class="sm-btn-confirm sm-deposit-confirm w-100 mt-3" data-bs-dismiss="modal">
                            <i class="fa-solid fa-check"></i> Done
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="withdrawSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content sm-modal-content">
                    <div class="modal-body sm-success-body">

                        <div class="sm-success-icon sm-success-red">
                            <i class="fa-solid fa-check"></i>
                        </div>

                        <h5 class="sm-success-title">Withdraw Successful!</h5>

                        <p class="sm-success-msg">
                            Your withdrawal of
                            <strong>₱
                                {{ session('withdraw_amount') ? number_format(session('withdraw_amount'), 2) : '0.00' }}</strong>
                            has been deducted from your savings account.
                        </p>

                        @if (session('withdraw_reference'))
                            <div class="sm-ref-pill">
                                <span class="sm-ref-label">Reference No.</span>
                                <span class="sm-ref-value" id="withdraw-ref-no">{{ session('withdraw_reference') }}</span>
                                <button class="sm-copy-btn" onclick="copyRef('withdraw-ref-no')" title="Copy">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        @endif

                        <div class="sm-success-balance-pill">
                            <span>New Balance</span>
                            <span>₱ {{ number_format($savingsAccount->balance, 2) }}</span>
                        </div>

                        @if (session('withdraw_reference'))
                            <a href="{{ route('savings.receipt', session('withdraw_reference')) }}" class="sm-btn-download">
                                <i class="fa-solid fa-file-arrow-down"></i> Download Receipt
                            </a>
                        @endif

                        <button type="button" class="sm-btn-confirm sm-withdraw-confirm w-100 mt-3" data-bs-dismiss="modal">
                            <i class="fa-solid fa-check"></i> Done
                        </button>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tdHistoryModal" tabindex="-1" aria-labelledby="tdHistoryModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content sm-modal-content">

                    <div class="modal-header sm-modal-header" style="padding: 24px 20px;">
                        <div class="modal-text">
                            <div class="sm-modal-icon sm-deposit-icon">
                                <i class="fa-solid fa-lock" style="color:#fff;"></i>
                            </div>
                            <div class="sm-modal-text">
                                <h1 class="modal-title sm-modal-title" id="tdHistoryModalLabel">Time Deposit Accounts
                                </h1>
                                <p class="sm-modal-subtitle">All your Time Deposit history</p>
                            </div>
                        </div>
                        <button type="button" class="sm-modal-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <div class="modal-body sm-modal-body" style="padding: 1.25rem 1.5rem;">

                        <div class="sm-tx-toolbar" style="margin:0 0 1rem;">
                            <div class="sm-search-box">
                                <i class="fa-solid fa-magnifying-glass"></i>
                                <input type="text" id="tdSearchInput" placeholder="Search by reference no. or goal amount">
                            </div>
                            <input type="date" class="sm-filter-select" id="tdDateFilter">
                            <select class="sm-filter-select" id="tdStatusFilter">
                                <option value="all">All Status</option>
                                <option value="completed">Completed</option>
                                <option value="matured">Ready to Claim</option>
                                <option value="goal_reached">Fully Funded</option>
                                <option value="in_progress">In Progress</option>
                            </select>
                            <a href="#" id="tdClearFilters" class="sm-filter-clear">Clear filters</a>
                        </div>

                        <div id="tdHistoryList">
                            @forelse ($tdHistory as $td)
                                <div class="td-modal-row" data-ref="{{ strtolower($td->reference_no ?? '') }}"
                                    data-goal="{{ $td->goal_amount }}"
                                    data-date="{{ \Carbon\Carbon::parse($td->opened_at)->format('Y-m-d') }}"
                                    data-status="{{ $td->display_status }}">
                                    <div class="td-modal-icon td-icon-{{ $td->display_status }}">
                                        @if ($td->display_status === 'completed')
                                            <i class="fa fa-circle-check"></i>
                                        @elseif ($td->display_status === 'matured')
                                            <i class="fa fa-hourglass-end"></i>
                                        @elseif ($td->display_status === 'goal_reached')
                                            <i class="fa fa-bullseye"></i>
                                        @else
                                            <i class="fa fa-lock"></i>
                                        @endif
                                    </div>
                                    <div class="td-modal-info">
                                        <h4>₱{{ number_format($td->goal_amount, 2) }} Goal</h4>
                                        <p>
                                            Ref: {{ $td->reference_no ?? '—' }} ·
                                            Opened {{ \Carbon\Carbon::parse($td->opened_at)->format('M d, Y') }}
                                            · {{ number_format($td->interest_rate, 2) }}% p.a.
                                        </p>
                                    </div>
                                    <div class="td-modal-amount">
                                        <h4>₱{{ number_format($td->display_balance, 2) }}</h4>
                                        @if ($td->display_status === 'completed')
                                            <span class="td-status-badge td-status-completed">Completed</span>
                                        @elseif ($td->display_status === 'matured')
                                            <span class="td-status-badge td-status-matured">Ready to Claim</span>
                                        @elseif ($td->display_status === 'goal_reached')
                                            <span class="td-status-badge td-status-goal_reached">Fully Funded</span>
                                        @else
                                            <span class="td-status-badge td-status-in_progress">In Progress</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center; padding:2.5rem 1rem;">
                                    <i class="fa-solid fa-piggy-bank fa-2x" style="color:var(--muted); opacity:.4;"></i>
                                    <p style="color:var(--muted); margin-top:0.75rem; font-size:13.5px;">
                                        No Time Deposits opened yet.
                                    </p>
                                </div>
                            @endforelse

                            <div id="tdNoResults" style="display:none; text-align:center; padding:2.5rem 1rem;">
                                <i class="fa-solid fa-magnifying-glass fa-2x" style="color:var(--muted); opacity:.4;"></i>
                                <p style="color:var(--muted); margin-top:0.75rem; font-size:13.5px;">
                                    No matching Time Deposits found.
                                </p>
                            </div>
                        </div>

                        @if($tdHistory->count() > 0)
                            <div class="td-pagination-wrap">
                                <div class="sm-pagination-info" id="tdPaginationInfo"></div>
                                <div class="sm-pagination" id="tdPaginationBtns"></div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="tdClaimSuccessModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content sm-modal-content">
                    <div class="modal-body sm-success-body">
                        <div class="sm-success-icon sm-success-green">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h5 class="sm-success-title">Time Deposit Claimed!</h5>
                        <p class="sm-success-msg">
                            <strong>₱{{ session('td_claim_amount') ? number_format(session('td_claim_amount'), 2) : '0.00' }}</strong>
                            (principal + interest) has been added to your Regular Savings.
                        </p>
                        @if (session('td_claim_reference'))
                            <div class="sm-ref-pill">
                                <span class="sm-ref-label">Reference No.</span>
                                <span class="sm-ref-value" id="tdclaim-ref-no">{{ session('td_claim_reference') }}</span>
                                <button class="sm-copy-btn" onclick="copyRef('tdclaim-ref-no')" title="Copy">
                                    <i class="fa-regular fa-copy"></i>
                                </button>
                            </div>
                        @endif
                        <div class="sm-success-balance-pill">
                            <span>New Regular Savings Balance</span>
                            <span>₱ {{ number_format($savingsAccount->balance, 2) }}</span>
                        </div>
                        <button type="button" class="sm-btn-confirm sm-deposit-confirm w-100 mt-3" data-bs-dismiss="modal">
                            <i class="fa-solid fa-check"></i> Done
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <button id="triggerDepositSuccess" data-bs-toggle="modal" data-bs-target="#depositSuccessModal"
            style="display:none;"></button>
        <button id="triggerWithdrawSuccess" data-bs-toggle="modal" data-bs-target="#withdrawSuccessModal"
            style="display:none;"></button>
        <button id="triggerTdClaimSuccess" data-bs-toggle="modal" data-bs-target="#tdClaimSuccessModal"
            style="display:none;"></button>

    @endif

    <button id="triggerUnifiedTx" data-bs-toggle="modal" data-bs-target="#unifiedTxModal"
        style="display:none;"></button>

    {{-- QR Lightbox (shared by both tabs) --}}
    <div id="qr-lightbox-overlay"
        style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.8); z-index:100000; align-items:center; justify-content:center;">
        <button type="button" onclick="closeQrLightbox()"
            style="position:absolute; top:20px; right:24px; background:#fff; border:none; width:40px; height:40px; border-radius:50%; font-size:20px; color:#333; cursor:pointer; display:flex; align-items:center; justify-content:center;">
            <i class="fa fa-times"></i>
        </button>
        <img id="qr-lightbox-img" src="" alt="GCash QR Code"
            style="max-width:90%; max-height:85vh; border-radius:12px;">
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init();

        function openQrLightbox(src) {
            document.getElementById('qr-lightbox-img').src = src;
            document.getElementById('qr-lightbox-overlay').style.display = 'flex';
        }

        function closeQrLightbox() {
            document.getElementById('qr-lightbox-overlay').style.display = 'none';
        }

        document.getElementById('qr-lightbox-overlay')?.addEventListener('click', function (e) {
            if (e.target === this) closeQrLightbox();
        });
    </script>

    {{-- ═══════════════════════════════════════
    UNIFIED TRANSACTION MODAL — script (always loaded, both tabs supply the
    balance figures needed since the Financial controller computes both
    Share Capital and Savings data on every request)
    ═══════════════════════════════════════ --}}
    <script>
        (function () {
            const form = document.getElementById('unified-tx-form');
            if (!form) return;

            const PRICE = 200;
            const SC_BALANCE = {{ $currentBalance ?? 0 }};
            const SC_SHARES = {{ $currentShares ?? 0 }};
            const SV_BALANCE = {{ $totalSavingsBalance ?? 0 }};

            const destSelect = document.getElementById('tx-dest-select');
            const scFields = document.getElementById('tx-sc-fields');
            const svFields = document.getElementById('tx-sv-fields');
            const typeEl = document.getElementById('tx-type');
            const pay = document.getElementById('tx-pay');
            const gcashBox = document.getElementById('tx-gcash-box');
            const balanceLabel = document.getElementById('tx-balance-label');
            const balanceValue = document.getElementById('tx-balance-value');
            const sharesInput = document.getElementById('tx-shares');
            const amountInput = document.getElementById('tx-amount');
            const costEl = document.getElementById('tx-cost');
            const submitBtn = document.getElementById('tx-submit-btn');
            const withdrawalNotice = document.getElementById('tx-sc-withdrawal-notice');
            const fullWithdrawalWarning = document.getElementById('tx-sc-full-withdrawal-warning');
            const inlineError = document.getElementById('tx-inline-error');
            const inlineErrorText = document.getElementById('tx-inline-error-text');
            const formMarker = document.getElementById('tx-form-marker');

            let dest = '';

            function showInlineError(msg) {
                inlineErrorText.textContent = msg;
                inlineError.classList.add('show');
                inlineError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }

            function clearInlineError() {
                inlineError.classList.remove('show');
                inlineErrorText.textContent = '';
            }

            function updateFormAction() {
                if (dest === 'share_capital') {
                    form.action = form.dataset.scRoute;
                    formMarker.value = 'share_capital';
                } else if (typeEl.value === 'Withdrawal') {
                    form.action = form.dataset.svWithdrawRoute;
                    formMarker.value = 'withdraw';
                } else {
                    form.action = form.dataset.svDepositRoute;
                    formMarker.value = 'deposit';
                }
            }

            function toggleFullWithdrawalWarning(cost) {
                fullWithdrawalWarning.style.display = (SC_BALANCE > 0 && cost >= SC_BALANCE) ? 'block' : 'none';
            }

            function validateScWithdrawal(cost) {
                if (SC_BALANCE <= 0) {
                    showInlineError('You cannot withdraw because your current share capital balance is ₱0.');
                    return false;
                }
                if (cost > SC_BALANCE) {
                    showInlineError(
                        'Withdrawal amount (₱' + cost.toLocaleString() +
                        ') exceeds your current share capital balance (₱' + SC_BALANCE.toLocaleString() + ').'
                    );
                    return false;
                }
                clearInlineError();
                toggleFullWithdrawalWarning(cost);
                return true;
            }

            function validateSvWithdrawal(amount) {
                if (SV_BALANCE <= 0) {
                    showInlineError('You cannot withdraw because your current savings balance is ₱0.');
                    return false;
                }
                if (amount > SV_BALANCE) {
                    showInlineError(
                        'Withdrawal amount (₱' + amount.toLocaleString() +
                        ') exceeds your current savings balance (₱' + SV_BALANCE.toLocaleString() + ').'
                    );
                    return false;
                }
                clearInlineError();
                return true;
            }

            function setDest(d) {
                dest = d;
                destSelect.value = d;
                clearInlineError();
                withdrawalNotice.style.display = 'none';
                fullWithdrawalWarning.style.display = 'none';

                if (d === 'share_capital') {
                    scFields.style.display = '';
                    svFields.style.display = 'none';
                    sharesInput.disabled = false;
                    amountInput.disabled = true;
                    balanceLabel.textContent = 'Current Balance';
                    balanceValue.textContent = '₱' + SC_BALANCE.toLocaleString() + ' · ' + SC_SHARES + ' shares';
                    if (typeEl.value === 'Withdrawal') {
                        withdrawalNotice.style.display = 'block';
                        validateScWithdrawal(+sharesInput.value * PRICE);
                    }
                } else if (d === 'savings') {
                    scFields.style.display = 'none';
                    svFields.style.display = '';
                    sharesInput.disabled = true;
                    amountInput.disabled = false;
                    balanceLabel.textContent = 'My Savings Balance';
                    balanceValue.textContent = '₱' + SV_BALANCE.toLocaleString(undefined, { minimumFractionDigits: 2 });
                    if (typeEl.value === 'Withdrawal') {
                        validateSvWithdrawal(parseFloat(amountInput.value || 0));
                    }
                } else {
                    // Nothing selected yet
                    scFields.style.display = 'none';
                    svFields.style.display = 'none';
                    balanceLabel.textContent = 'Current Balance';
                    balanceValue.textContent = 'Select an account';
                }
                updateFormAction();
            }

            function setShares(v) {
                v = parseFloat(v);
                if (isNaN(v) || v < 1) return;
                v = Math.round(v * 100) / 100; // avoid float drift like 12.000000000000002
                sharesInput.value = v;
                costEl.textContent = '₱' + (v * PRICE).toLocaleString();
                document.querySelectorAll('.tx-qbtn').forEach(b => {
                    b.classList.toggle('active', parseFloat(b.dataset.v) === v);
                });
                if (dest === 'share_capital' && typeEl.value === 'Withdrawal') {
                    validateScWithdrawal(v * PRICE);
                }
            }

            destSelect.addEventListener('change', () => setDest(destSelect.value));

            document.getElementById('tx-dec').onclick = () => setShares((parseFloat(sharesInput.value) || 1) - 0.25);
            document.getElementById('tx-inc').onclick = () => setShares((parseFloat(sharesInput.value) || 1) + 0.25);

            sharesInput.addEventListener('input', () => {
                const raw = sharesInput.value;
                const v = parseFloat(raw);
                if (!isNaN(v) && v > 0) {
                    costEl.textContent = '₱' + (v * PRICE).toLocaleString();
                    document.querySelectorAll('.tx-qbtn').forEach(b => {
                        b.classList.toggle('active', parseFloat(b.dataset.v) === v);
                    });
                    if (dest === 'share_capital' && typeEl.value === 'Withdrawal') {
                        validateScWithdrawal(v * PRICE);
                    }
                }
            });

            sharesInput.addEventListener('blur', () => setShares(sharesInput.value || 1));
            document.querySelectorAll('.tx-qbtn').forEach(b => b.onclick = () => setShares(+b.dataset.v));

            document.querySelectorAll('.tx-amt-qbtn').forEach(b => b.onclick = () => {
                amountInput.value = b.dataset.v;
                if (typeEl.value === 'Withdrawal') validateSvWithdrawal(parseFloat(amountInput.value || 0));
            });

            typeEl.onchange = function () {
                clearInlineError();
                fullWithdrawalWarning.style.display = 'none';

                if (dest === 'share_capital') {
                    withdrawalNotice.style.display = (this.value === 'Withdrawal') ? 'block' : 'none';
                    if (this.value === 'Withdrawal') validateScWithdrawal(+sharesInput.value * PRICE);
                } else if (dest === 'savings') {
                    withdrawalNotice.style.display = 'none';
                    if (this.value === 'Withdrawal') validateSvWithdrawal(parseFloat(amountInput.value || 0));
                }
                updateFormAction();
            };

            pay.onchange = function () {
                const isGcash = this.value === 'gcash';
                gcashBox.style.display = isGcash ? 'block' : 'none';
                document.getElementById('tx-gcash-proof-input').required = isGcash;
                submitBtn.style.display = 'flex';
            };

            document.getElementById('tx-gcash-proof-input')?.addEventListener('change', function () {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        document.getElementById('tx-gcash-proof-preview-img').src = e.target.result;
                        document.getElementById('tx-gcash-proof-preview').style.display = 'block';
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });

            document.getElementById('unifiedTxModal').addEventListener('show.bs.modal', () => {
                setDest('');
                setShares(1);
                amountInput.value = '';
                pay.value = '';
                typeEl.value = '';
                gcashBox.style.display = 'none';
                submitBtn.style.display = 'flex';
                withdrawalNotice.style.display = 'none';
                fullWithdrawalWarning.style.display = 'none';
                document.getElementById('tx-note').value = '';
                clearInlineError();
            });

            form.addEventListener('submit', function (e) {
                if (!dest) {
                    e.preventDefault();
                    showInlineError('Please select an account (Share Capital or Savings).');
                    return;
                }
                if (typeEl.value === 'Withdrawal') {
                    if (dest === 'share_capital' && !validateScWithdrawal(+sharesInput.value * PRICE)) {
                        e.preventDefault();
                        return;
                    }
                    if (dest === 'savings' && !validateSvWithdrawal(parseFloat(amountInput.value || 0))) {
                        e.preventDefault();
                        return;
                    }
                }
                updateFormAction();
            });

            updateFormAction();

            @if ($errors->any() && in_array(old('_form'), ['share_capital', 'deposit', 'withdraw']))
                document.getElementById('triggerUnifiedTx').click();
            @endif
        })();
    </script>

    {{-- RECEIPT MODAL script (Share Capital) --}}
    @if($activeTab === 'share_capital')
        <script>
            function scCloseModal() {
                const overlay = document.getElementById('sc-receipt-overlay');
                if (overlay) overlay.remove();
            }

            document.getElementById('sc-receipt-overlay')?.addEventListener('click', function (e) {
                if (e.target === this) scCloseModal();
            });

            function scDownloadReceipt() {
                const d = document.getElementById('sc-receipt-data')?.dataset;
                if (!d) return;

                const isCompleted = d.status === 'Completed';

                const wrapper = document.createElement('div');
                wrapper.style.cssText = `
                                                        position: fixed; left: -9999px; top: 0;
                                                        width: 400px; background: #fff;
                                                        border-radius: 20px; overflow: hidden;
                                                        box-shadow: 0 8px 40px rgba(0,0,0,0.15);
                                                    `;

                wrapper.innerHTML = `
                                                        <div style="background:linear-gradient(135deg,#1a4a3a,#2d6a4f);padding:2rem 1.5rem 1.2rem;text-align:center;">
                                                            <div style="width:56px;height:56px;background:rgba(255,255,255,0.15);border:3px solid rgba(255,255,255,0.6);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 0.8rem;">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                            </div>
                                                            <div style="color:#fff;font-size:1.2rem;font-weight:800;margin-bottom:4px;">Request Submitted!</div>
                                                            <div style="color:rgba(255,255,255,0.75);font-size:0.8rem;">
                                                                ${isCompleted ? 'Your deposit has been recorded successfully.' : 'Your withdrawal request is pending for approval.'}
                                                            </div>
                                                        </div>
                                                        <div style="height:16px;background:linear-gradient(135deg,#1a4a3a,#2d6a4f);position:relative;">
                                                            <svg viewBox="0 0 400 16" xmlns="http://www.w3.org/2000/svg" style="display:block;width:100%;height:16px;">
                                                                <polygon fill="#ffffff" points="0,16 10,0 20,16 30,0 40,16 50,0 60,16 70,0 80,16 90,0 100,16 110,0 120,16 130,0 140,16 150,0 160,16 170,0 180,16 190,0 200,16 210,0 220,16 230,0 240,16 250,0 260,16 270,0 280,16 290,0 300,16 310,0 320,16 330,0 340,16 350,0 360,16 370,0 380,16 390,0 400,16"/>
                                                            </svg>
                                                        </div>
                                                        <div style="padding:1.2rem 1.5rem;">
                                                            <table style="width:100%;border-collapse:collapse;font-size:0.84rem;">
                                                                ${scReceiptRow('Organization', 'KMPCATS')}
                                                                ${scReceiptRow('Member', d.member)}
                                                                ${scReceiptRow('Transaction Type', '<strong>' + d.type + '</strong>')}
                                                                ${scReceiptRow('Shares', '<strong style="color:#1a4a3a">' + d.shares + ' shares</strong>')}
                                                                ${scReceiptRow('Amount', '<strong style="color:#1a4a3a">&#8369;' + d.amount + '</strong>')}
                                                                ${scReceiptRow('Payment Method', d.method)}
                                                                ${scReceiptRow('Reference No.', '<span style="font-size:0.76rem;">' + d.ref + '</span>')}
                                                                ${scReceiptRow('Date & Time', d.date)}
                                                                ${scReceiptRow('Status', isCompleted
                    ? '<span style="color:#2e7d32;font-weight:700;font-size:0.72rem;">✓ Completed</span>'
                    : '<span style="color:#b8860b;font-weight:700;font-size:0.72rem;">• Pending Approval</span>')}
                                                            </table>
                                                        </div>
                                                        <div style="padding:0.8rem 1.5rem 1.2rem;text-align:center;border-top:1px dashed #e8e8e8;">
                                                            <div style="color:#aaa;font-size:0.72rem;">This is an official transaction receipt from KMPCATS.</div>
                                                            <div style="color:#bbb;font-size:0.68rem;margin-top:2px;">Keep this for your records.</div>
                                                        </div>
                                                    `;

                document.body.appendChild(wrapper);

                html2canvas(wrapper, { scale: 2, useCORS: true, backgroundColor: null }).then(canvas => {
                    document.body.removeChild(wrapper);
                    const link = document.createElement('a');
                    link.download = `KMPCATS-Receipt-${d.ref}.png`;
                    link.href = canvas.toDataURL('image/png');
                    link.click();
                });
            }

            function scReceiptRow(label, value) {
                return `
                                                        <tr style="border-bottom:1px dashed #ebebeb;">
                                                            <td style="color:#888;font-weight:500;padding:0.55rem 0.5rem 0.55rem 0;vertical-align:middle;white-space:nowrap;">${label}</td>
                                                            <td style="color:#1a1a1a;font-weight:600;text-align:right;padding:0.55rem 0 0.55rem 0.5rem;vertical-align:middle;">${value}</td>
                                                        </tr>
                                                    `;
            }

            /* CONTRIBUTION HISTORY — SEARCH + FILTERS + PAGINATION */
            (function () {
                const searchInp = document.getElementById('sc-search-input');
                const dateInp = document.getElementById('sc-filter-date');
                const typeInp = document.getElementById('sc-filter-type');
                const statusInp = document.getElementById('sc-filter-status');
                const clearBtn = document.getElementById('sc-filter-clear');
                if (!dateInp) return;

                const tbody = document.getElementById('sc-contribution-tbody');
                const rows = Array.from(tbody.querySelectorAll('tr[data-date]'));
                const footerCount = document.getElementById('sc-table-footer-count');
                const pagination = document.getElementById('sc-pagination');
                const PAGE_SIZE = 10;
                let emptyRow = null;
                let currentPage = 1;

                function getFilteredRows() {
                    const search = (searchInp?.value || '').trim().toLowerCase().replace(/,/g, '');
                    const date = dateInp.value;
                    const type = typeInp?.value || '';
                    const status = statusInp?.value || '';

                    return rows.filter(row => {
                        const matchesDate = !date || row.dataset.date === date;
                        const matchesType = !type || row.dataset.type === type;
                        const matchesStatus = !status || row.dataset.status === status;
                        const rowText = row.textContent.toLowerCase().replace(/,/g, '');
                        const matchesSearch = !search || rowText.includes(search);
                        return matchesDate && matchesType && matchesStatus && matchesSearch;
                    });
                }

                function renderPagination(filteredCount, totalPages) {
                    if (!pagination) return;
                    pagination.innerHTML = '';

                    const prevBtn = document.createElement('button');
                    prevBtn.type = 'button';
                    prevBtn.className = 'sc-page-btn nav';
                    prevBtn.innerHTML = '<i class="fa fa-chevron-left"></i>';
                    prevBtn.disabled = currentPage === 1;
                    prevBtn.addEventListener('click', () => { currentPage--; applyFilter(); });
                    pagination.appendChild(prevBtn);

                    for (let p = 1; p <= totalPages; p++) {
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'sc-page-btn' + (p === currentPage ? ' active' : '');
                        btn.textContent = p;
                        btn.addEventListener('click', () => { currentPage = p; applyFilter(); });
                        pagination.appendChild(btn);
                    }

                    const nextBtn = document.createElement('button');
                    nextBtn.type = 'button';
                    nextBtn.className = 'sc-page-btn nav';
                    nextBtn.innerHTML = '<i class="fa fa-chevron-right"></i>';
                    nextBtn.disabled = currentPage === totalPages;
                    nextBtn.addEventListener('click', () => { currentPage++; applyFilter(); });
                    pagination.appendChild(nextBtn);
                }

                function applyFilter() {
                    const filtered = getFilteredRows();
                    const filteredCount = filtered.length;
                    const totalPages = Math.max(1, Math.ceil(filteredCount / PAGE_SIZE));

                    if (currentPage > totalPages) currentPage = totalPages;
                    if (currentPage < 1) currentPage = 1;

                    const startIdx = (currentPage - 1) * PAGE_SIZE;
                    const endIdx = startIdx + PAGE_SIZE;

                    rows.forEach(row => { row.style.display = 'none'; });
                    filtered.slice(startIdx, endIdx).forEach(row => { row.style.display = ''; });

                    if (footerCount) {
                        const shownCount = Math.min(endIdx, filteredCount) - startIdx;
                        footerCount.textContent = `Showing ${Math.max(shownCount, 0)} of ${filteredCount} contributions`;
                    }

                    if (filteredCount === 0) {
                        if (!emptyRow) {
                            emptyRow = document.createElement('tr');
                            emptyRow.id = 'sc-filter-empty-row';
                            emptyRow.innerHTML = `<td colspan="6" style="text-align:center;color:#aaa;padding:2rem;font-size:13px;">
                                                        <i class="fa fa-filter-circle-xmark" style="font-size:24px;display:block;margin-bottom:8px;"></i>
                                                        No contributions match your filters.
                                                    </td>`;
                            tbody.appendChild(emptyRow);
                        }
                        emptyRow.style.display = '';
                    } else if (emptyRow) {
                        emptyRow.style.display = 'none';
                    }

                    renderPagination(filteredCount, totalPages);
                }

                searchInp?.addEventListener('input', () => { currentPage = 1; applyFilter(); });
                dateInp.addEventListener('change', () => { currentPage = 1; applyFilter(); });
                typeInp?.addEventListener('change', () => { currentPage = 1; applyFilter(); });
                statusInp?.addEventListener('change', () => { currentPage = 1; applyFilter(); });
                clearBtn.addEventListener('click', () => {
                    if (searchInp) searchInp.value = '';
                    dateInp.value = '';
                    if (typeInp) typeInp.value = '';
                    if (statusInp) statusInp.value = '';
                    currentPage = 1;
                    applyFilter();
                });

                applyFilter();
            })();
        </script>
    @endif

    {{-- ═══════════════════════════════════════
    SAVINGS TAB — scripts
    ═══════════════════════════════════════ --}}
    @if($activeTab === 'savings')
        <script>
            const smRefInput = document.querySelector('.sm-search-box input[name="ref"]');
            const smFilterForm = document.getElementById('sm-tx-filter-form');
            let smSearchDebounce;

            if (smRefInput) {
                smRefInput.addEventListener('input', function () {
                    clearTimeout(smSearchDebounce);
                    smSearchDebounce = setTimeout(() => smFilterForm.submit(), 500);
                });

                if (smRefInput.value) {
                    smRefInput.focus();
                    const val = smRefInput.value;
                    smRefInput.value = '';
                    smRefInput.value = val;
                }
            }

            function changeGrowthYear(year) {
                const url = new URL(window.location.href);
                url.searchParams.set('growth_year', year);
                window.location.href = url.toString();
            }
        </script>

        <script>
            function copyRef(elementId) {
                const text = document.getElementById(elementId).textContent.trim();
                navigator.clipboard.writeText(text).then(() => {
                    const btn = event.currentTarget;
                    btn.innerHTML = '<i class="fa-solid fa-check"></i>';
                    setTimeout(() => { btn.innerHTML = '<i class="fa-regular fa-copy"></i>'; }, 1500);
                });
            }

            window.addEventListener('DOMContentLoaded', function () {

                @if (session('deposit_success'))
                    document.getElementById('triggerDepositSuccess').click();
                @endif

                @if (session('withdraw_success'))
                    document.getElementById('triggerWithdrawSuccess').click();
                @endif

                @if (session('td_claim_success'))
                    document.getElementById('triggerTdClaimSuccess').click();
                @endif

                                                });

            document.querySelectorAll('.parent-panel .panel-body').forEach(el => el.scrollTop = 0);

            // Time Deposit modal — search, filter & pagination (10 per page)
            (function () {
                const searchInput = document.getElementById('tdSearchInput');
                const dateFilter = document.getElementById('tdDateFilter');
                const statusFilter = document.getElementById('tdStatusFilter');
                const clearBtn = document.getElementById('tdClearFilters');
                const noResults = document.getElementById('tdNoResults');
                const paginationInfo = document.getElementById('tdPaginationInfo');
                const paginationBtns = document.getElementById('tdPaginationBtns');
                const allRows = Array.from(document.querySelectorAll('.td-modal-row'));
                const PAGE_SIZE = 10;
                let currentPage = 1;

                function getFilteredRows() {
                    const q = (searchInput?.value || '').trim().toLowerCase();
                    const date = dateFilter?.value || '';
                    const status = statusFilter?.value || 'all';

                    return allRows.filter(row => {
                        const matchesSearch = !q || row.dataset.ref.includes(q) || row.dataset.goal.includes(q);
                        const matchesDate = !date || row.dataset.date === date;
                        const matchesStatus = status === 'all' || row.dataset.status === status;
                        return matchesSearch && matchesDate && matchesStatus;
                    });
                }

                function renderPage() {
                    const filtered = getFilteredRows();
                    const totalPages = Math.max(1, Math.ceil(filtered.length / PAGE_SIZE));
                    currentPage = Math.min(currentPage, totalPages);

                    allRows.forEach(row => row.style.display = 'none');

                    const start = (currentPage - 1) * PAGE_SIZE;
                    const pageRows = filtered.slice(start, start + PAGE_SIZE);
                    pageRows.forEach(row => row.style.display = 'flex');

                    if (noResults) noResults.style.display = filtered.length === 0 ? 'block' : 'none';

                    if (paginationInfo) {
                        paginationInfo.innerHTML = filtered.length === 0
                            ? ''
                            : `Showing <b>${start + 1}–${Math.min(start + PAGE_SIZE, filtered.length)}</b> of <b>${filtered.length}</b> Time Deposits`;
                    }

                    if (paginationBtns) {
                        paginationBtns.innerHTML = '';
                        if (filtered.length > PAGE_SIZE) {
                            const prevBtn = document.createElement('span');
                            prevBtn.className = 'sm-page-btn' + (currentPage === 1 ? ' disabled' : '');
                            prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
                            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderPage(); } };
                            paginationBtns.appendChild(prevBtn);

                            for (let i = 1; i <= totalPages; i++) {
                                const btn = document.createElement('a');
                                btn.href = '#';
                                btn.className = 'sm-page-btn' + (i === currentPage ? ' active' : '');
                                btn.textContent = i;
                                btn.onclick = (e) => { e.preventDefault(); currentPage = i; renderPage(); };
                                paginationBtns.appendChild(btn);
                            }

                            const nextBtn = document.createElement('span');
                            nextBtn.className = 'sm-page-btn' + (currentPage === totalPages ? ' disabled' : '');
                            nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
                            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderPage(); } };
                            paginationBtns.appendChild(nextBtn);
                        }
                    }
                }

                function applyTdFilters() {
                    currentPage = 1;
                    renderPage();
                }

                searchInput?.addEventListener('input', applyTdFilters);
                dateFilter?.addEventListener('change', applyTdFilters);
                statusFilter?.addEventListener('change', applyTdFilters);
                clearBtn?.addEventListener('click', function (e) {
                    e.preventDefault();
                    searchInput.value = '';
                    dateFilter.value = '';
                    statusFilter.value = 'all';
                    applyTdFilters();
                });

                document.getElementById('tdHistoryModal')?.addEventListener('show.bs.modal', () => {
                    currentPage = 1;
                    renderPage();
                });

                renderPage();
            })();
        </script>
    @endif

</body>

</html>