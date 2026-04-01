<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Share Capital</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">

    {{-- css link --}}
    <link rel="stylesheet" href="css_folder/share_capital.css">
    <link rel="stylesheet" href="css_folder/loading.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="font-awesome-icon/css/all.min.css">

    <style>
        /* ── Receipt Modal Overlay ── */
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
            background: linear-gradient(135deg, #1a4a3a 0%, #2d6a4f 100%);
            padding: 2rem 1.5rem 1.5rem;
            text-align: center;
            border-radius: 20px 20px 0 0;
        }

        .sc-receipt-header .check-circle {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.15);
            border: 3px solid rgba(255, 255, 255, 0.6);
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
            color: #fff;
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0 0 0.25rem;
        }

        .sc-receipt-header p {
            color: rgba(255, 255, 255, 0.75);
            font-size: 0.82rem;
            margin: 0;
        }

        .sc-receipt-tear {
            background: linear-gradient(135deg, #1a4a3a 0%, #2d6a4f 100%);
            height: 20px;
            position: relative;
            margin-bottom: -1px;
        }

        .sc-receipt-tear::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            background: #fff;
            clip-path: polygon(0% 100%, 2.5% 0%, 5% 100%, 7.5% 0%, 10% 100%,
                    12.5% 0%, 15% 100%, 17.5% 0%, 20% 100%,
                    22.5% 0%, 25% 100%, 27.5% 0%, 30% 100%,
                    32.5% 0%, 35% 100%, 37.5% 0%, 40% 100%,
                    42.5% 0%, 45% 100%, 47.5% 0%, 50% 100%,
                    52.5% 0%, 55% 100%, 57.5% 0%, 60% 100%,
                    62.5% 0%, 65% 100%, 67.5% 0%, 70% 100%,
                    72.5% 0%, 75% 100%, 77.5% 0%, 80% 100%,
                    82.5% 0%, 85% 100%, 87.5% 0%, 90% 100%,
                    92.5% 0%, 95% 100%, 97.5% 0%, 100% 100%);
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
            color: #1a4a3a;
            font-size: 14.5px;
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

        .sc-status-badge .dot {
            width: 7px;
            height: 7px;
            background: #e6a817;
            border-radius: 50%;
            flex-shrink: 0;
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
            background: linear-gradient(135deg, #1a4a3a, #2d6a4f);
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
    </style>
</head>

<body>

    <div class="container-fluid p-0 m-0">
        @include("components.navbar2")
        @include("components.offcanvas")

        {{-- ═══════════════════════════════════════
        RECEIPT MODAL (shown after success)
        ═══════════════════════════════════════ --}}
        @if(session('success'))
            <div id="sc-receipt-overlay" class="active">
                <div id="sc-receipt-modal">

                    {{-- Header --}}
                    <div class="sc-receipt-header">
                        <div class="check-circle">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <h2>Request Submitted!</h2>
                        <p>Your share capital request is pending for approval.</p>
                    </div>
                    <div class="sc-receipt-tear"></div>

                    {{-- Body --}}
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
                                <span class="sc-status-badge"><span class="dot"></span> Pending Approval</span>
                            </span>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="sc-receipt-footer">
                        <button class="sc-btn-download" onclick="scDownloadReceipt()">
                            <i class="fa-solid fa-download"></i> Download Receipt
                        </button>
                        <button class="sc-btn-close-modal" onclick="scCloseModal()">Close</button>
                    </div>

                </div>
            </div>

            {{-- Hidden data attrs for JS receipt builder --}}
            <div id="sc-receipt-data" data-member="{{ session('sc_receipt_member', Auth::user()->name ?? 'Member') }}"
                data-type="{{ session('sc_receipt_type', 'Deposit') }}"
                data-shares="{{ session('sc_receipt_shares', '—') }}"
                data-amount="{{ number_format(session('sc_receipt_amount', 0), 0) }}"
                data-method="{{ session('sc_receipt_method', '—') }}" data-ref="{{ session('sc_receipt_ref', '—') }}"
                data-date="{{ now()->timezone('Asia/Manila')->format('M d, Y · h:i A') }}" style="display:none;">
            </div>
        @endif

        <main>
            {{-- Add Share Capital Modal --}}
            <div class="modal fade" id="shareCapital" tabindex="-1" aria-labelledby="shareCapitalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content"
                        style="border-radius: 16px; overflow: hidden; border: none; box-shadow: 0 24px 60px rgba(0,0,0,0.15);">

                        {{-- Header --}}
                        <div class="modal-header" style="background: #1a4a3a; border: none; padding: 1.25rem 1.5rem;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div
                                    style="width: 36px; height: 36px; background: rgba(255,255,255,0.15); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                                    <i class="fa fa-coins" style="color: #fff; font-size: 15px;"></i>
                                </div>
                                <div>
                                    <h5 class="modal-title mb-0" style="color: #fff; font-size: 15px; font-weight: 600;"
                                        id="shareCapitalLabel">Add Share Capital</h5>
                                    <p style="margin: 0; color: rgba(255,255,255,0.65); font-size: 12px;">Purchase
                                        additional shares</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close btn-close-white m-0" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        {{-- Body --}}
                        <div class="modal-body"
                            style="padding: 1.25rem 1.5rem; display: flex; flex-direction: column; gap: 1rem;">

                            <form action="{{ route('share_capital.store') }}" method="POST" id="modal-sc-form">
                                @csrf

                                {{-- Balance --}}
                                <div
                                    style="background: #f5f5f5; border-radius: 10px; padding: 0.75rem 1rem; display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                    <span style="font-size: 13px; color: #888;">Current Balance</span>
                                    <span
                                        style="font-size: 13px; font-weight: 600; color: #1a1a1a;">₱{{ number_format($currentBalance ?? 0, 0) }}
                                        · {{ $currentShares ?? 0 }} shares</span>
                                </div>

                                {{-- Shares counter --}}
                                <p style="margin: 0 0 8px; font-size: 13px; color: #666;">Number of shares to add</p>
                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                    <button type="button" id="modal-dec"
                                        style="width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #ddd; background: #fff; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #333;">−</button>
                                    <input type="number" name="shares" id="modal-shares" value="1" min="1" readonly
                                        style="width: 60px; text-align: center; font-size: 14.5px; font-weight: 600; color: #1a4a3a; border: 1.5px solid #ddd; border-radius: 10px; padding: 6px;">
                                    <button type="button" id="modal-inc"
                                        style="width: 36px; height: 36px; border-radius: 50%; border: 1.5px solid #ddd; background: #fff; font-size: 18px; cursor: pointer; display: flex; align-items: center; justify-content: center; color: #333;">+</button>
                                </div>

                                {{-- Quick select --}}
                                <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 8px;">
                                    <button type="button" class="modal-qbtn active" data-v="1"
                                        style="padding: 5px 13px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; background: #1a4a3a; color: #fff; border: 1.5px solid #1a4a3a;">1
                                        share</button>
                                    <button type="button" class="modal-qbtn" data-v="5"
                                        style="padding: 5px 13px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1.5px solid #ddd;">5
                                        shares</button>
                                    <button type="button" class="modal-qbtn" data-v="10"
                                        style="padding: 5px 13px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1.5px solid #ddd;">10
                                        shares</button>
                                    <button type="button" class="modal-qbtn" data-v="25"
                                        style="padding: 5px 13px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; background: #fff; color: #555; border: 1.5px solid #ddd;">25
                                        shares</button>
                                </div>
                                <p style="font-size: 12px; color: #888; margin-bottom: 1rem;">Cost: <strong
                                        id="modal-cost" style="color: #1a4a3a;">₱1,000</strong> · ₱1,000 per share</p>

                                <hr style="border-color: #eee; margin: 0 0 1rem;">

                                {{-- Type --}}
                                <div style="margin-bottom: 0.9rem;">
                                    <label
                                        style="font-size: 13px; color: #666; display: block; margin-bottom: 6px;">Type</label>
                                    <select name="type" id="modal-type"
                                        style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #ddd; font-size: 14px; color: #333; background: #fff;"
                                        required>
                                        <option value="">Select type..</option>
                                        <option value="Deposit">Deposit</option>
                                        <option value="Withdrawal">Withdrawal</option>
                                    </select>
                                </div>

                                {{-- Withdrawal notice (hidden by default) --}}
                                <div id="modal-withdrawal-notice"
                                    style="display:none; background:#fff8e1; border:1.5px solid #ffe082; border-radius:10px; padding:0.65rem 1rem; margin-bottom:0.9rem; font-size:12px; color:#856404; line-height:1.5;">
                                    <i class="fa fa-circle-info" style="margin-right:6px;"></i>
                                    Withdrawal requests are subject to admin approval. Your current share balance will
                                    <strong>not</strong> be reduced until the request is approved.
                                </div>

                                {{-- Payment Method --}}
                                <div style="margin-bottom: 0.9rem;">
                                    <label
                                        style="font-size: 13px; color: #666; display: block; margin-bottom: 6px;">Payment
                                        Method</label>
                                    <select class="select-form" name="payment_method" id="modal-pay"
                                        style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #ddd; font-size: 14px; color: #333; background: #fff;"
                                        required>
                                        <option value="" disabled selected>Select payment method...</option>
                                        <option value="cash">Cash</option>
                                        <option value="gcash">GCash</option>
                                    </select>
                                </div>

                                {{-- Note --}}
                                <div style="margin-bottom: 0.5rem;">
                                    <label
                                        style="font-size: 13px; color: #666; display: block; margin-bottom: 6px;">Note
                                        <span style="font-size: 11px; color: #bbb;">(optional)</span></label>
                                    <input type="text" name="note" id="modal-note"
                                        placeholder="e.g. Monthly contribution"
                                        style="width: 100%; padding: 8px 10px; border-radius: 10px; border: 1.5px solid #ddd; font-size: 14px; color: #333; box-sizing: border-box;">
                                </div>

                                {{-- GCash box --}}
                                <div id="modal-gcash-box"
                                    style="display: none; margin-top: 0.8rem; background: #f0f7ff; border: 1.5px solid #c2deff; border-radius: 12px; padding: 0.75rem 1rem; align-items: center; justify-content: space-between; gap: 10px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div
                                            style="width: 32px; height: 32px; background: #007DFF; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <i class="fa-solid fa-mobile-screen-button"
                                                style="color: #fff; font-size: 14px;"></i>
                                        </div>
                                        <div>
                                            <p style="margin: 0; font-size: 13px; font-weight: 700; color: #0056b3;">Pay
                                                via GCash</p>
                                            <p style="margin: 0; font-size: 11px; color: #5a8ac4;">Fast & secure payment
                                            </p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="submitModalGcash()"
                                        style="background: #007DFF; color: #fff; border: none; border-radius: 8px; padding: 6px 14px; font-size: 12px; font-weight: 600; cursor: pointer; white-space: nowrap;">Pay
                                        Now</button>
                                </div>

                            </form>

                            {{-- GCash redirect form — includes type so session saves correctly --}}
                            <form id="modal-gcash-form" action="{{ route('share_capital.gcash') }}" method="POST"
                                style="display:none;">
                                @csrf
                                <input type="hidden" name="shares" id="modal-gcash-shares">
                                <input type="hidden" name="note" id="modal-gcash-note">
                                {{-- ✅ type is passed so GCash success redirect goes to the right page --}}
                                <input type="hidden" name="type" id="modal-gcash-type">
                            </form>

                        </div>

                        {{-- Footer --}}
                        <div class="modal-footer"
                            style="border: none; padding: 0 1.5rem 1.25rem; flex-direction: column; gap: 8px;">
                            <button type="submit" form="modal-sc-form" id="modal-submit-btn"
                                style="width: 100%; padding: 0.75rem; background: #1a4a3a; color: #fff; border: none; border-radius: 12px; font-size: 14px; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <i class="fa fa-coins"></i> Add Share Capital
                            </button>
                            <button type="button" data-bs-dismiss="modal"
                                style="width: 100%; padding: 0.7rem; background: transparent; color: #888; border: 1.5px solid #eee; border-radius: 12px; font-size: 14px; cursor: pointer;">
                                Cancel
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="parent-main">
                <div class="download">
                    <button>
                        <i class="fa fa-arrow-down"></i>
                        <span>Download Statement</span>
                    </button>
                </div>

                <div class="share">
                    <button data-bs-toggle="modal" data-bs-target="#shareCapital">
                        <i class="fa fa-plus"></i>
                        <span>Add Share Capital</span>
                    </button>
                </div>
            </div>

            <div class="parent-share">
                <div class="card">
                    <div class="card-accent"></div>
                    <div class="card-icon">
                        <div class="fa fa-dollar-sign"></div>
                    </div>
                    <span>Current Balance</span>
                    <h4>₱{{ number_format($currentBalance, 0) }}</h4>
                    <article>{{ $currentShares }} shares</article>
                </div>

                <div class="card">
                    <div class="card-accent"></div>
                    <div class="card-icon">
                        <div class="fa fa-arrow-trend-up"></div>
                    </div>
                    <span>Dividend Rate</span>
                    <h4>8.5% p.a</h4>
                    <article>Annual return</article>
                </div>

                <div class="card">
                    <div class="card-accent"></div>
                    <div class="card-icon">
                        <div class="fa fa-chart-pie"></div>
                    </div>
                    <span>Last Dividend</span>
                    <h4>P2,125</h4>
                    <article>Dec 2024</article>
                </div>

                <div class="card">
                    <div class="card-accent"></div>
                    <div class="card-icon">
                        <div class="fa fa-calendar-days"></div>
                    </div>
                    <span>Next Dividend</span>
                    <h4>June 15, 2025</h4>
                    <article>Expected date</article>
                </div>
            </div>

            <div class="contribution-parent">
                <h3>Contribution History</h3>

                <div class="parent-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Reference No.</th>
                                <th>Shares</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contributions as $row)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($row->transaction_date)->format('M d, Y') }}</td>
                                    <td>{{ $row->type }}</td>
                                    <td>{{ $row->reference_no }}</td>
                                    <td>{{ (int) $row->shares }}</td>
                                    <td>₱{{ number_format($row->total_amount, 0) }}</td>
                                    <td>
                                        @if($row->status === 'Completed')
                                            <i class="fa fa-check-circle" style="color: #1a4a3a;"></i>
                                            <span>Completed</span>
                                        @elseif($row->status === 'Pending')
                                            <i class="fa fa-clock" style="color: #e6a817;"></i>
                                            <span>Pending</span>
                                        @else
                                            <i class="fa fa-times-circle" style="color: #e03131;"></i>
                                            <span>{{ $row->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6"
                                        style="text-align: center; color: #aaa; padding: 2rem; font-size: 13px;">
                                        <i class="fa fa-inbox"
                                            style="font-size: 24px; display: block; margin-bottom: 8px;"></i>
                                        No contributions yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="dividend-parent">
                <h3>Dividend History</h3>

                <div class="parent-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Dividend Rate</th>
                                <th>Share Capital</th>
                                <th>Dividend Amount</th>
                                <th>Date Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>2nd Semester 2024</td>
                                <td>8.5%</td>
                                <td>P25,000</td>
                                <td>P2,125</td>
                                <td>Dec 15,2024</td>
                            </tr>
                            <tr>
                                <td>1st Semester 2024</td>
                                <td>8.5%</td>
                                <td>P25,000</td>
                                <td>P2,125</td>
                                <td>Dec 15,2024</td>
                            </tr>
                            <tr>
                                <td>2nd Semester 2023</td>
                                <td>8.5%</td>
                                <td>P25,000</td>
                                <td>P2,125</td>
                                <td>Dec 15,2024</td>
                            </tr>
                            <tr>
                                <td>1st Semester 2023</td>
                                <td>8.5%</td>
                                <td>P25,000</td>
                                <td>P2,125</td>
                                <td>Dec 15,2024</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        @if(session('error'))
            <div style="position: fixed; top: 1.2rem; right: 1.2rem; z-index: 9999;
                            background: #fff; border: 1.5px solid #f5c6c6; border-radius: 14px;
                            padding: 1rem 1.25rem; box-shadow: 0 8px 30px rgba(0,0,0,0.12);
                            display: flex; align-items: center; gap: 12px; max-width: 360px;">
                <div style="width: 36px; height: 36px; background: #fef0f0; border-radius: 50%;
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
    </div>

    {{-- html2canvas for receipt download --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        /* ══════════════════════════════════════
            MODAL LOGIC
        ══════════════════════════════════════ */
        (function () {
            const PRICE = 1000;
            const inp = document.getElementById('modal-shares');
            const costEl = document.getElementById('modal-cost');
            const pay = document.getElementById('modal-pay');
            const typeEl = document.getElementById('modal-type');
            const gcashBox = document.getElementById('modal-gcash-box');
            const submitBtn = document.getElementById('modal-submit-btn');
            const withdrawalNotice = document.getElementById('modal-withdrawal-notice');

            if (!inp) return;

            function setShares(v) {
                if (v < 1) return;
                inp.value = v;
                costEl.textContent = '₱' + (v * PRICE).toLocaleString();
                document.querySelectorAll('.modal-qbtn').forEach(b => {
                    const on = parseInt(b.dataset.v) === v;
                    b.style.background = on ? '#1a4a3a' : '#fff';
                    b.style.color = on ? '#fff' : '#555';
                    b.style.borderColor = on ? '#1a4a3a' : '#ddd';
                });
            }

            document.getElementById('modal-dec').onclick = () => setShares(+inp.value - 1);
            document.getElementById('modal-inc').onclick = () => setShares(+inp.value + 1);
            document.querySelectorAll('.modal-qbtn').forEach(b => b.onclick = () => setShares(+b.dataset.v));

            // Show/hide withdrawal notice
            typeEl.onchange = function () {
                withdrawalNotice.style.display = (this.value === 'Withdrawal') ? 'block' : 'none';
            };

            pay.onchange = function () {
                if (this.value === 'gcash') {
                    gcashBox.style.display = 'flex';
                    submitBtn.style.display = 'none';
                } else {
                    gcashBox.style.display = 'none';
                    submitBtn.style.display = 'flex';
                }
            };

            // Reset modal on open
            document.getElementById('shareCapital').addEventListener('show.bs.modal', () => {
                setShares(1);
                pay.value = '';
                typeEl.value = '';
                gcashBox.style.display = 'none';
                submitBtn.style.display = 'flex';
                withdrawalNotice.style.display = 'none';
                document.getElementById('modal-note').value = '';
            });
        })();

        // GCash submit — passes type so the controller session saves it correctly
        function submitModalGcash() {
            const type = document.getElementById('modal-type').value;
            if (!type) {
                alert('Please select a transaction type first.');
                return;
            }
            document.getElementById('modal-gcash-shares').value = document.getElementById('modal-shares').value;
            document.getElementById('modal-gcash-note').value = document.getElementById('modal-note').value;
            document.getElementById('modal-gcash-type').value = type;
            document.getElementById('modal-gcash-form').submit();
        }

        /* ══════════════════════════════════════
            RECEIPT MODAL LOGIC
        ══════════════════════════════════════ */
        function scCloseModal() {
            // Remove overlay from DOM cleanly (clears flash session state visually)
            const overlay = document.getElementById('sc-receipt-overlay');
            if (overlay) overlay.remove();
        }

        // Close on backdrop click
        document.getElementById('sc-receipt-overlay')?.addEventListener('click', function (e) {
            if (e.target === this) scCloseModal();
        });

        /* ══════════════════════════════════════
            DOWNLOAD RECEIPT
        ══════════════════════════════════════ */
        function scDownloadReceipt() {
            const d = document.getElementById('sc-receipt-data')?.dataset;
            if (!d) return;

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
                    <div style="color:rgba(255,255,255,0.75);font-size:0.8rem;">Your share capital request is pending for approval.</div>
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
                        ${scReceiptRow('Transaction Type', `<strong>${d.type}</strong>`)}
                        ${scReceiptRow('Shares', `<strong style="color:#1a4a3a">${d.shares} shares</strong>`)}
                        ${scReceiptRow('Amount', `<strong style="color:#1a4a3a">&#8369;${d.amount}</strong>`)}
                        ${scReceiptRow('Payment Method', d.method)}
                        ${scReceiptRow('Reference No.', `<span style="font-size:0.76rem;">${d.ref}</span>`)}
                        ${scReceiptRow('Date & Time', d.date)}
                        ${scReceiptRow('Status', `<span style="color:#b8860b;font-weight:700;font-size:0.72rem;">• Pending Approval</span>`)}
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
    </script>
</body>

</html>