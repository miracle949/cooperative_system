<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Loan Application — KPMPCATS</title>
    <link rel="icon" href="images/websitelogo.png" type="image/png">
    <link rel="stylesheet" href="css_folder/loan_application.css">
    <link rel="stylesheet" href="css_folder/loading.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="../font-awesome-icon/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400&family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --bg: #f2f4f8;
            --teal: #1E2A4A;
            --teal-dark: #131a30;
            --teal-mid: #263359;
            --teal-dark: #131a30;
            --teal-mid: #263359;
            --teal-pale: #F0F4FF;
            --gold: #F5A623;
            --blue: #4F7FFA;
            --blue-light: #95B3FC;
            --blue-pale: #D9E5FE;
            --blue-mist: #EEF3FF;
            --coral: #FF6B6B;
            --mint: #22C993;
            --cream: #F0F4FF;
            --ink: #1E2A4A;
            --muted: #6b7a99;
            --line: rgba(30, 42, 74, .1);
            --sidebar-width: 250px;
            --border: #E5EAF3;
            --border-hover: #D9E5FE;
            --sidebar-width: 250px;
            --accent: #2563eb;
            --mint: #22C993;
            --coral: #FF6B6B;
            --semi-white: #FAF9F6;
            --green: #1e9e6b;
            --green-bg: #e6f7f1;
            --savings: #1560c0;
            --savings-bg: #e4edff;
            --shadow: 0 2px 12px rgba(30, 42, 74, 0.08);

            --ice-blue: #E8F4FD;
            --gold-pale: #FFF8EC;
            --mint-pale: #EDFAF4;
            --coral-pale: #FFF0F0;
            --lavender-tint: #F7F8FC;
            --navy-soft: #2D3F6B;
        }

        .loan-modal-overlay {
            position: fixed;
            inset: 0;
            z-index: 1050;
            background: rgba(13, 15, 20, .6);
            backdrop-filter: blur(7px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s ease;
        }

        .loan-modal-overlay.open {
            opacity: 1;
            pointer-events: all;
        }

        .loan-modal {
            background: #fff;
            border-radius: 24px;
            width: 100%;
            max-width: 820px;
            max-height: 94vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 32px 80px rgba(0, 0, 0, .28), 0 8px 24px rgba(0, 0, 0, .12);
            transform: translateY(28px) scale(.97);
            transition: transform .38s cubic-bezier(.22, 1, .36, 1);
            position: relative;
            font-family: 'Space Grotesk', sans-serif;
        }

        .loan-modal-overlay.open .loan-modal {
            transform: translateY(0) scale(1);
        }

        /* .modal-accent-bar {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--blue) 0%, var(--blue) 50%, var(--blue) 100%);
            border-radius: 24px 24px 0 0;
        } */

        .modal-layout {
            display: flex;
            flex: 1;
            overflow: hidden;
            min-height: 0;
        }

        .modal-invoice-sidebar {
            width: 250px;
            flex-shrink: 0;
            background: linear-gradient(160deg, var(--teal) 0%, var(--teal) 60%, var(--teal) 100%);
            /* background-color: #ffffff; */
            border-right: 1px solid var(--border);
            padding: 26px 18px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            position: relative;
            overflow: hidden;
        }

        .modal-invoice-sidebar::before {
            content: '';
            position: absolute;
            top: -60px;
            right: -60px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(184, 148, 63, .12);
            /* background-color: var(--blue-mist); */
        }

        .modal-invoice-sidebar::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: -40px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: rgba(184, 148, 63, .07);
            /* background-color: var(--blue-mist); */
        }

        .mis-eyebrow {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            /* color: rgba(184, 148, 63, .8); */
            color: var(--muted);
            position: relative;
            z-index: 1;
        }

        .mis-title {
            font-size: 16px;
            font-weight: 700;
            /* color: var(--teal); */
            color: #ffffff;
            position: relative;
            z-index: 1;
            margin-bottom: 1px;
            /* font-family: "Playfair Display"; */
        }

        .mis-sub {
            font-size: 10.5px;
            /* color: rgba(255, 255, 255, .45); */
            color: var(--muted);
            position: relative;
            z-index: 1;
        }

        .mis-amount-box {
            background: rgba(255, 255, 255, .07);
            /* background-color: var(--blue-mist); */
            border: 1px solid rgba(255, 255, 255, .12);
            /* border: 1px solid var(--border); */
            border-radius: 12px;
            padding: 14px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .mis-amount-lbl {
            font-size: 8.5px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .5);
            /* color: #ffffff; */
            /* color: var(--blue); */
            margin-bottom: 6px;
        }

        .mis-amount-val {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            /* color: var(--blue); */
            line-height: 1;
        }

        .mis-amount-val.dim {
            /* color: rgba(255, 255, 255, .3); */
            /* color: #ffffff; */
            /* color: var(--blue); */
            font-family: 'Space Grotesk', sans-serif;
            font-size: 20px;
        }

        .mis-amount-hint {
            font-size: 9.5px;
            color: rgba(255, 255, 255, .35);
            /* color: #ffffff; */
            /* color: var(--blue); */
            margin-top: 5px;
        }

        .mis-rows {
            display: flex;
            flex-direction: column;
            position: relative;
            z-index: 1;
        }

        .mis-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .07);
            font-size: 12.5px;
        }

        .mis-row:last-child {
            border-bottom: none;
        }

        .mis-lbl {
            color: rgba(255, 255, 255, .5);
            /* color: var(--muted); */
            font-weight: 600;
        }

        .mis-val {
            /* color: var(--teal); */
            color: #ffffff;
            font-weight: 700;
            font-size: 12px;
        }

        .mis-val.dim {
            color: rgba(255, 255, 255, .3);
            /* color: var(--teal); */
            font-weight: 400;
        }

        .mis-divider {
            height: 1px;
            background: rgba(255, 255, 255, .1);
            margin: 4px 0;
            position: relative;
            z-index: 1;
        }

        .mis-total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            position: relative;
            z-index: 1;
        }

        .mis-total-lbl {
            font-size: 12px;
            font-weight: 700;
            color: rgba(255, 255, 255, .5);
            /* color: var(--muted); */
        }

        .mis-total-val {
            font-size: 12px;
            font-weight: 700;
            /* color: var(--teal); */
            color: #ffffff;
        }

        .mis-total-val.dim {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 12px;
            font-weight: 400;
            color: rgba(255, 255, 255, .3);
        }

        .modal-main-area {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .modal-steps-bar {
            display: flex;
            align-items: center;
            padding: 16px 24px;
            border-bottom: 1px solid var(--border);
            /* background: #fafafa; */
            background-color: #ffffff;
            flex-shrink: 0;
        }

        .m-step {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
        }

        .m-step-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            flex-shrink: 0;
            transition: .3s;
        }

        .m-step.active .m-step-circle {
            background: var(--blue);
            /* color: var(--forest); */
            color: #fff;
            /* box-shadow: 0 0 0 3px rgba(184, 148, 63, .2); */
            box-shadow: 0 0 0 3px rgba(79, 127, 250, .12);
        }

        .m-step.done .m-step-circle {
            background: var(--blue);
            color: #fff;
        }

        .m-step.pending .m-step-circle {
            background: #ebebeb;
            color: var(--light);
        }

        .m-step-info .m-step-num {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: .6px;
            text-transform: uppercase;
            color: var(--light);
        }

        .m-step.active .m-step-info .m-step-num {
            color: var(--blue);
        }

        .m-step.done .m-step-info .m-step-num {
            color: var(--forest);
        }

        .m-step-info .m-step-name {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--muted);
            margin-top: 1px;
        }

        .m-step.active .m-step-info .m-step-name {
            color: var(--ink);
        }

        .m-connector {
            flex: 0 0 28px;
            height: 2px;
            background: var(--border);
            margin: 0 4px;
            border-radius: 2px;
            transition: .4s;
        }

        .m-connector.done {
            background: var(--forest);
        }

        .modal-panel {
            display: none;
            padding: 24px 28px;
            animation: panelIn .3s ease both;
            /* background-color: var(--bg); */
        }

        .modal-panel.active {
            display: block;
        }

        .modal-panel.back {
            animation: panelBack .3s ease both;
        }

        @keyframes panelIn {
            from {
                opacity: 0;
                transform: translateX(16px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        @keyframes panelBack {
            from {
                opacity: 0;
                transform: translateX(-16px)
            }

            to {
                opacity: 1;
                transform: translateX(0)
            }
        }

        .panel-sec-hd {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--blue);
            margin-bottom: 14px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .panel-sec-hd::before {
            content: '';
            width: 2.5px;
            height: 10px;
            border-radius: 2px;
            background: var(--blue);
            display: block;
        }

        .p-form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-bottom: 14px;
        }

        .p-form-row.single {
            grid-template-columns: 1fr;
        }

        .p-field {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .p-field label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink);
            display: flex;
            align-items: center;
            gap: 6px;
            flex-wrap: wrap;
        }

        .req {
            color: var(--danger);
        }

        .max-pill {
            font-size: 9.5px;
            font-weight: 700;
            padding: 1px 7px;
            border-radius: 8px;
            background: var(--blue-mist);
            color: var(--blue);
        }

        .p-inp-wrap {
            position: relative;
        }

        .p-inp-ico {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
        }

        .p-inp-ico svg {
            width: 13px;
            height: 13px;
        }

        .p-input,
        .p-select,
        .p-textarea {
            width: 100%;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13px;
            color: var(--ink);
            background: #fff;
            border: 1.5px solid var(--border);
            border-radius: 8px;
            padding: 10px 12px 10px 34px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            appearance: none;
        }

        .p-input.np,
        .p-select.np {
            padding-left: 12px;
        }

        .p-input::placeholder,
        .p-textarea::placeholder {
            color: #c4c0b8;
        }

        .p-input:focus,
        .p-select:focus,
        .p-textarea:focus {
            border-color: var(--blue);
            /* box-shadow: 0 0 0 3px rgba(184, 148, 63, .12); */
            box-shadow: 0 0 0 3px rgba(79, 127, 250, .12);
        }

        /* ── Field error state ── */
        .p-input.field-error,
        .p-select.field-error,
        .p-textarea.field-error {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, .1) !important;
        }

        /* ── Inline field error message ── */
        .p-field-error {
            display: none;
            font-size: 11px;
            color: #dc2626;
            font-weight: 600;
            margin-top: 3px;
            align-items: center;
            gap: 4px;
        }

        .p-field-error.show {
            display: flex;
        }

        .p-sel-wrap {
            position: relative;
        }

        .p-sel-wrap::after {
            content: '';
            position: absolute;
            right: 11px;
            top: 50%;
            transform: translateY(-50%);
            width: 0;
            height: 0;
            pointer-events: none;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 5px solid var(--muted);
        }

        .p-textarea {
            padding-left: 12px;
            resize: vertical;
            min-height: 78px;
            line-height: 1.6;
        }

        .p-hint {
            font-size: 11px;
            color: var(--muted);
        }

        .p-warn {
            display: none;
            margin-top: 6px;
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            border-radius: 8px;
            padding: 6px 10px;
            font-size: 12px;
            color: #dc2626;
            font-weight: 500;
        }

        .sc-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #fdf9f0;
            border: 1.5px solid var(--warn-b);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }

        .sc-alert-ico {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: rgba(232, 213, 163, .25);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .sc-alert-ico svg {
            width: 16px;
            height: 16px;
            color: #b8862a;
        }

        .sc-alert-title {
            font-size: 13px;
            font-weight: 700;
            color: #7a5010;
            margin-bottom: 3px;
        }

        .sc-alert-text {
            font-size: 12px;
            color: #8a6020;
            line-height: 1.6;
        }

        .docs-section {
            display: none;
            margin-top: 8px;
        }

        .docs-heading {
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--blue);
            padding-bottom: 6px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 14px;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .docs-heading::before {
            content: '';
            width: 2.5px;
            height: 10px;
            border-radius: 2px;
            background: var(--blue);
            display: block;
        }

        .upload-grid-modal {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .upload-card-modal {
            position: relative;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 14px;
            display: flex;
            flex-direction: column;
            gap: 3px;
            cursor: pointer;
            transition: .2s;
            overflow: hidden;
        }

        .upload-card-modal:hover {
            /* border-color: var(--forest);
            background: var(--forest-pale); */
            border-color: var(--blue);
            /* background-color: var(--blue-mist); */
            background-color: #ffffff;
        }

        .upload-card-modal.has-file {
            border-color: var(--blue);
            /* background: rgba(30, 122, 78, .04); */
            /* background-color: var(--blue-mist); */
            background-color: #ffffff;
        }

        .upload-card-modal input[type=file] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .uc-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(27, 61, 47, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 6px;
        }

        .uc-icon svg {
            width: 14px;
            height: 14px;
            color: var(--forest);
        }

        .uc-label {
            font-size: 12.5px;
            font-weight: 600;
            color: var(--ink);
        }

        .uc-sub {
            font-size: 10.5px;
            color: var(--muted);
        }

        .uc-badge {
            display: inline-block;
            margin-top: 3px;
            font-size: 9.5px;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 6px;
        }

        .uc-badge.required {
            background: rgba(201, 68, 68, .1);
            /* color: var(--danger); */
            color: #dc2626;
        }

        .uc-badge.uploaded {
            /* background: rgba(34, 201, 147, .12); */
            background-color: var(--blue-mist);
            color: var(--blue);
        }

        .uc-badge.optional {
            background: var(--blue-mist);
            color: var(--blue);
        }

        .uc-filename {
            font-size: 10.5px;
            color: var(--success);
            font-weight: 600;
            margin-top: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .uc-error {
            font-size: 11px;
            color: var(--danger);
            font-weight: 600;
            margin-top: 2px;
            display: none;
        }

        .loan-status-banner {
            border-radius: 10px;
            padding: .85rem 1rem;
            margin-top: 1rem;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 12.5px;
        }

        .lsb-danger {
            background: #fef2f2;
            border: 1.5px solid #fca5a5;
            color: #dc2626;
        }

        .lsb-warn {
            background: #fff8e1;
            border: 1.5px solid #ffe082;
            color: #856404;
        }

        .lsb-ok {
            background: #f0fdf4;
            border: 1.5px solid #86efac;
            color: #166534;
        }

        .lsb-ico {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .lsb-ico.danger-ico {
            background: #fee2e2;
        }

        .lsb-ico.warn-ico {
            background: #fff3cd;
        }

        .lsb-title {
            font-size: 13px;
            font-weight: 700;
            color: var(--ink);
            margin-bottom: 3px;
        }

        .breakdown-grid-modal {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 18px;
        }

        .b-box-m {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 13px;
            transition: .2s;
        }

        .b-box-m.hl {
            background: var(--forest-pale);
            border-color: rgba(27, 61, 47, .15);
        }

        .b-ico-m {
            width: 30px;
            height: 30px;
            border-radius: 7px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .b-ico-m svg {
            width: 14px;
            height: 14px;
        }

        .bi-g {
            background: rgba(27, 61, 47, .1);
        }

        .bi-g svg {
            color: var(--forest);
        }

        .bi-o {
            background: rgba(184, 148, 63, .12);
        }

        .bi-o svg {
            color: var(--gold);
        }

        .bi-r {
            background: rgba(201, 68, 68, .1);
        }

        .bi-r svg {
            color: var(--danger);
        }

        .bi-b {
            background: rgba(59, 130, 246, .1);
        }

        .bi-b svg {
            color: #3b82f6;
        }

        .bi-p {
            background: rgba(139, 92, 246, .1);
        }

        .bi-p svg {
            color: #8b5cf6;
        }

        .bi-t {
            background: rgba(20, 184, 166, .1);
        }

        .bi-t svg {
            color: #14b8a6;
        }

        .b-lbl-m {
            font-size: 9.5px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: var(--muted);
            margin-bottom: 2px;
        }

        .b-val-m {
            font-size: 17px;
            font-weight: 700;
            color: var(--ink);
        }

        .b-box-m.hl .b-val-m {
            color: var(--forest);
        }

        .b-hint-m {
            font-size: 10.5px;
            color: var(--muted);
            margin-top: 2px;
        }

        .amort-hd-m {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 8px;
            display: none;
            align-items: center;
            gap: 6px;
        }

        .amort-hd-m::before {
            content: '';
            width: 2.5px;
            height: 10px;
            border-radius: 2px;
            background: var(--gold);
            display: block;
        }

        .amort-wrap-m {
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
            display: none;
        }

        .amort-scroll-m {
            max-height: 200px;
            overflow-y: auto;
        }

        .amort-tbl {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .amort-tbl th {
            background: #f5f5f2;
            padding: 7px 10px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: var(--muted);
            text-align: left;
            border-bottom: 2px solid var(--border);
        }

        .amort-tbl td {
            padding: 8px 10px;
            border-bottom: 1px solid var(--border);
        }

        .amort-tbl tr:last-child td {
            border-bottom: none;
        }

        .amort-tbl tr:hover td {
            background: #fafaf8;
        }

        .mo-n {
            font-weight: 700;
            color: var(--forest);
        }

        .int-n {
            color: var(--danger);
            font-weight: 600;
        }

        .bal-n {
            color: var(--muted);
        }

        .confirm-hero-m {
            text-align: center;
            padding: 8px 0 18px;
        }

        .confirm-ring-m {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--blue);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }

        .confirm-ring-m .fa {
            color: #ffffff;
            font-size: 20px;
        }

        .confirm-ring-m svg {
            width: 22px;
            height: 22px;
            color: var(--forest);
        }

        .confirm-title-m {
            font-size: 21px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .confirm-sub-m {
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.65;
        }

        .sum-card-m {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 10px;
            overflow: hidden;
            margin: 14px 0;
        }

        .sum-head-m {
            background: var(--blue);
            padding: 10px 14px;
            font-size: 9.5px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            /* color: rgba(255, 255, 255, .6); */
            color: #ffffff;
        }

        .sum-row-m {
            display: flex;
            justify-content: space-between;
            padding: 10px 14px;
            border-bottom: 1px solid var(--border);
            font-size: 12.5px;
        }

        .sum-row-m:last-child {
            border-bottom: none;
        }

        .sum-row-m.total {
            /* background: #f0f4f2; */
            /* background-color: var(--blue); */
            background-color: #ffffff;
            /* background-color: #fafafa; */
        }

        .sum-lbl-m {
            color: var(--muted);
        }

        .sum-lbl-m.bold {
            color: var(--teal);
            font-weight: 700;
        }

        .sum-val-m {
            font-weight: 700;
            color: var(--teal);
        }

        .sum-val-m.green {
            color: var(--forest);
        }

        .sum-val-m.gold {
            color: var(--teal);
        }

        .sum-val-m.bigf {
            font-size: 14.5px;
            color: var(--teal);
            /* color: #ffffff; */
        }

        .cb-row-m {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            background: var(--surface);
            border: 1.5px solid var(--border);
            border-radius: 8px;
            margin-bottom: 4px;
            cursor: pointer;
            transition: border-color .2s;
        }

        .cb-row-m.cb-error {
            border-color: #dc2626;
            background: #fef2f2;
        }

        .cb-row-m input[type=checkbox] {
            width: 15px;
            height: 15px;
            margin-top: 2px;
            accent-color: var(--forest);
            flex-shrink: 0;
            cursor: pointer;
            padding: 0;
        }

        .cb-row-m label {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.65;
            cursor: pointer;
        }

        .cb-row-m label strong {
            color: var(--ink);
        }

        /* ── Checkbox required message ── */
        .cb-required-msg {
            display: none;
            font-size: 11px;
            color: #dc2626;
            font-weight: 600;
            margin-top: 4px;
            align-items: center;
            gap: 4px;
        }

        .cb-required-msg.show {
            display: flex;
        }

        .modal-footer-bar {
            padding: 14px 20px;
            border-top: 1px solid var(--line);
            background: #fafafa;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            flex-shrink: 0;
        }

        .mf-note {
            font-size: 11px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .mf-note svg {
            width: 12px;
            height: 12px;
            color: var(--forest);
        }

        .mf-btns {
            display: flex;
            gap: 8px;
        }

        .m-btn {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s;
            white-space: nowrap;
        }

        .m-btn svg {
            width: 13px;
            height: 13px;
        }

        .m-btn-outline {
            background: transparent;
            color: var(--blue);
            /* border: 1.5px solid var(--blue); */
            border: 1.5px solid var(--line)
        }

        .m-btn-outline:hover {
            /* background: var(--blue); */
            border: 1.5px solid var(--blue)
        }

        .m-btn-primary {
            background: linear-gradient(135deg, var(--blue), var(--blue));
            color: #fff;
            box-shadow: 0 4px 14px rgba(27, 61, 47, .22);
        }

        .m-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(27, 61, 47, .3);
        }

        .m-btn-gold {
            background: linear-gradient(135deg, var(--blue), var(--blue));
            /* color: var(--forest); */
            color: #fff;
            /* box-shadow: 0 4px 14px rgba(184, 148, 63, .3); */
            box-shadow: 0 4px 14px rgba(27, 61, 47, .22);
        }

        .m-btn-gold:hover {
            transform: translateY(-1px);
        }

        .m-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
            transform: none !important;
        }

        .success-screen-m {
            display: none;
            padding: 44px 32px;
            text-align: center;
            animation: fadeUp .5s ease both;
        }

        .success-screen-m.show {
            display: block;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px)
            }

            to {
                opacity: 1;
                transform: translateY(0)
            }
        }

        .success-ring-m {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(30, 122, 78, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
        }

        .success-ring-m svg {
            width: 30px;
            height: 30px;
            color: var(--success);
        }

        .success-title-m {
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .success-sub-m {
            font-size: 13px;
            color: var(--muted);
            line-height: 1.7;
            max-width: 360px;
            margin: 0 auto 20px;
        }

        .ref-pill-m {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--forest-pale);
            border: 1px solid rgba(27, 61, 47, .15);
            border-radius: 8px;
            padding: 9px 18px;
            font-size: 13px;
            font-weight: 700;
            color: var(--forest);
            margin-bottom: 22px;
        }

        .ref-pill-m svg {
            width: 14px;
            height: 14px;
        }

        @media (max-width: 768px) {
            .modal-invoice-sidebar {
                display: none;
            }

            .loan-modal {
                max-width: 560px;
            }

            .breakdown-grid-modal {
                grid-template-columns: 1fr 1fr;
            }

            .upload-grid-modal {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 500px) {
            .p-form-row {
                grid-template-columns: 1fr;
            }
        }

        .form-disabled-overlay {
            position: absolute;
            inset: 0;
            background: rgba(255, 255, 255, .7);
            border-radius: 24px;
            z-index: 5;
            cursor: not-allowed;
        }
    </style>

    <script>
        function closeSuccessModal() {
            document.getElementById('success-modal').style.display = 'none';
        }
    </script>
</head>

<body>
    <div class="container-fluid m-0 p-0">
        @include("components.offcanvas")
        @include("components.sidebar")

        <!-- Interest Rates Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="modal-text">
                            <h1>Interest Rates</h1>
                            <p>Per lending type - monthly basis</p>
                        </div>
                        <button type="button" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="reminders">
                            <p>💡 Rates are indicative. Final terms subject to credit evaluation and cooperative
                                approval.</p>
                        </div>
                        <div class="lending-parent-box">
                            <div class="lending-parent">
                                <div class="lending-icon">
                                    <p>Personal Loan</p><span>General personal expenses & needs</span>
                                </div>
                                <p>2% / mo</p>
                            </div>
                            <div class="lending-parent">
                                <div class="lending-icon">
                                    <p>Business Loan</p><span>Livelihood & enterprise capital</span>
                                </div>
                                <p>2% / mo</p>
                            </div>
                            <div class="lending-parent">
                                <div class="lending-icon">
                                    <p>Emergency Loan</p><span>Urgent medical, calamity & crisis needs</span>
                                </div>
                                <p>2% / mo</p>
                            </div>
                            <div class="lending-parent">
                                <div class="lending-icon">
                                    <p>Educational Loan</p><span>Tuition, school fees & supplies</span>
                                </div>
                                <p>2% / mo</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-bs-dismiss="modal">Got it, Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="rightbar">
            @include("components.navbar2")
            <div class="main-sub-parent">
                <main>
                    <div class="main-parent">
                        {{-- <div class="main-header">
                            <div class="main-badge">
                                <a href="#">Home</a>
                                <span>></span>
                                <span>Loan Application</span>
                            </div>
                            <h2>Loan Application</h2>
                            <p>Manage your loan applications and track their status.</p>
                        </div> --}}

                        @if(!$canApplyLoan)
                            <div
                                style="display:flex;align-items:flex-start;gap:12px;background:#fdf9f0;border:1.5px solid #e8d5a3;border-radius:10px;padding:14px 18px; margin-bottom:25px;">
                                <div
                                    style="width:36px;height:36px;border-radius:50%;background:rgba(232,213,163,.25);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fa fa-circle-info" style="color:#b8862a;font-size:15px;"></i>
                                </div>
                                <div>
                                    <div style="font-size:13.5px;font-weight:700;color:#7a5010;margin-bottom:3px;">Share
                                        Capital Requirement</div>
                                    <p style="font-size:12.5px;color:#8a6020;line-height:1.6;margin:0;">
                                        You need at least <strong>10 shares</strong> of Share Capital to apply for a loan.
                                        You currently have <strong>{{ $currentShares }} share(s)</strong> —
                                        you need <strong>{{ 10 - $currentShares }} more</strong> to become eligible.
                                    </p>
                                </div>
                            </div>
                        @endif

                        <div class="card-box-parent">
                            <div class="card-box">
                                <div class="card-header">
                                    <h5>Due Today</h5>
                                    <i class="fa fa-circle-exclamation"></i>
                                </div>
                                <div class="card-body">
                                    <p>{{ $dueTodayCount }}</p>
                                    <span>Borrowers with payments due today</span>
                                </div>
                            </div>

                            <div class="card-box">
                                <div class="card-header">
                                    <h5>Due This Week</h5>
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <div class="card-body">
                                    <p>{{ $dueThisWeekCount }}</p>
                                    <span>Upcoming loan repayments</span>
                                </div>
                            </div>

                            <div class="card-box">
                                <div class="card-header">
                                    <h5>Overdue</h5>
                                    <i class="fa fa-triangle-exclamation"></i>
                                </div>
                                <div class="card-body">
                                    <p>{{ $overdueCount }}</p>
                                    <span>Missed or unpaid loan payments</span>
                                </div>
                            </div>
                        </div>

                        <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-home-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-home" type="button" role="tab" aria-controls="nav-home"
                                    aria-selected="true">
                                    All Loans
                                    @if(isset($allLoansCount) && $allLoansCount > 0)
                                        <span
                                            style="background:#e0e7ff;color:#3730a3;font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:4px;">{{ $allLoansCount }}</span>
                                    @endif
                                </button>
                                <button class="nav-link" id="nav-profile-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-profile" type="button" role="tab" aria-controls="nav-profile"
                                    aria-selected="false">
                                    Due Today
                                    @if(isset($dueTodayCount) && $dueTodayCount > 0)
                                        <span
                                            style="background:#fff3cd;color:#856404;font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:4px;">{{ $dueTodayCount }}</span>
                                    @endif
                                </button>
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">
                                    Due This Week
                                    @if(isset($dueThisWeekCount) && $dueThisWeekCount > 0)
                                        <span
                                            style="background:#fff3cd;color:#856404;font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:4px;">{{ $dueThisWeekCount }}</span>
                                    @endif
                                </button>
                                <button class="nav-link" id="nav-disabled-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-disabled" type="button" role="tab" aria-controls="nav-disabled"
                                    aria-selected="false">
                                    Overdue
                                    @if(isset($overdueCount) && $overdueCount > 0)
                                        <span
                                            style="background:#fef2f2;color:#dc2626;font-size:10px;font-weight:700;padding:1px 7px;border-radius:20px;margin-left:4px;">{{ $overdueCount }}</span>
                                    @endif
                                </button>
                            </div>
                        </nav>

                        <div class="tab-content" id="nav-tabContent">

                            {{-- ══ TAB 1: ALL LOANS ══ --}}
                            <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                aria-labelledby="nav-home-tab" tabindex="0">
                                <div class="table-header">
                                    <div class="main-text">
                                        <h3>Loans ({{ $allLoansCount ?? 0 }}) <span>- Manage All Loan
                                                Applications</span></h3>
                                    </div>
                                    <div class="main-button">
                                        <button onclick="openLoanModal()" @if(!$canApplyLoan) disabled
                                        style="opacity:.5;cursor:not-allowed;" @endif>
                                            <i class="fa fa-plus"></i>
                                            <span>Apply for a Loan</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="table-parent">
                                    <div class="table-filter">
                                        <div class="filter search-parent">
                                            <i class="fa fa-search"></i>
                                            <input type="search" id="search-all"
                                                oninput="filterTable('all-loans-tbody', this.value)"
                                                placeholder="Search by reference, type, purpose">
                                        </div>
                                    </div>
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>Reference</th>
                                                <th>Date Applied</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Term</th>
                                                <th>Purpose</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="all-loans-tbody">
                                            @forelse($allLoans ?? [] as $loan)
                                                <tr>
                                                    <td style="font-weight:600;color:#1e2a4a;">{{ $loan->reference_no }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('Y-m-d') }}</td>
                                                    <td>{{ $loan->lending_type }}</td>
                                                    <td style="font-weight:600;">
                                                        ₱{{ number_format($loan->lending_amount, 2) }}</td>
                                                    <td>{{ $loan->lending_type_term }}</td>
                                                    <td>{{ $loan->purpose_loan }}</td>
                                                    <td>
                                                        @php
                                                            $s = $loan->status;
                                                            $sc = match ($s) {
                                                                'Approved' => 'background:#e6f7f1;color:#1e9e6b;',
                                                                'Pending' => 'background:#fff8e1;color:#856404;',
                                                                'Rejected' => 'background:#fef2f2;color:#dc2626;',
                                                                'Completed' => 'background:#e0e7ff;color:#3730a3;',
                                                                default => 'background:#f3f4f6;color:#6b7280;',
                                                            };
                                                        @endphp
                                                        <span
                                                            style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700; border: 1px solid var(--border);{{ $sc }}">{{ $s }}</span>
                                                    </td>
                                                    <td>
                                                        <button
                                                            style="background:none;border:1px solid var(--border);cursor:pointer;color:#4f7ffa;"
                                                            title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8"
                                                        style="text-align:center;color:#6b7a99;padding:32px;font-size:13px;">
                                                        <i class="fa fa-folder-open"
                                                            style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                                                        No loan applications yet.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="parent-pagination">
                                        <p>Showing <span>{{ count($allLoans ?? []) }}</span> of
                                            <span>{{ $allLoansCount ?? 0 }}</span> applications</p>
                                    </div>
                                </div>
                            </div>

                            {{-- ══ TAB 2: DUE TODAY ══ --}}
                            <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                aria-labelledby="nav-profile-tab" tabindex="0">
                                <div class="table-header">
                                    <div class="main-text">
                                        <h3>Due Today ({{ $dueTodayCount ?? 0 }}) <span>- Payments Due Today</span></h3>
                                    </div>
                                </div>
                                <div class="table-parent">
                                    <div class="table-filter">
                                        <div class="filter search-parent">
                                            <i class="fa fa-search"></i>
                                            <input type="search" oninput="filterTable('due-today-tbody', this.value)"
                                                placeholder="Search by reference, type, purpose">
                                        </div>
                                    </div>
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>Reference</th>
                                                <th>Date Applied</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Term</th>
                                                <th>Purpose</th>
                                                <th>Due Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="due-today-tbody">
                                            @forelse($dueTodayLoans ?? [] as $loan)
                                                <tr>
                                                    <td style="font-weight:600;color:#1e2a4a;">{{ $loan->reference_no }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('Y-m-d') }}</td>
                                                    <td>{{ $loan->lending_type }}</td>
                                                    <td style="font-weight:600;">
                                                        ₱{{ number_format($loan->lending_amount, 2) }}</td>
                                                    <td>{{ $loan->lending_type_term }}</td>
                                                    <td>{{ $loan->purpose_loan }}</td>
                                                    <td>
                                                        <span
                                                            style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#fff8e1;color:#856404;">
                                                            {{ $loan->due_date }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button
                                                            style="background:none; border: 1px solid var(--border); cursor:pointer;color:#4f7ffa;"
                                                            title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8"
                                                        style="text-align:center;color:#6b7a99;padding:32px;font-size:13px;">
                                                        <i class="fa fa-calendar-check"
                                                            style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                                                        No payments due today.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="parent-pagination">
                                        <p>Showing <span>{{ count($dueTodayLoans ?? []) }}</span> of
                                            <span>{{ $dueTodayCount ?? 0 }}</span> records</p>
                                    </div>
                                </div>
                            </div>

                            {{-- ══ TAB 3: DUE THIS WEEK ══ --}}
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                aria-labelledby="nav-contact-tab" tabindex="0">
                                <div class="table-header">
                                    <div class="main-text">
                                        <h3>Due This Week ({{ $dueThisWeekCount ?? 0 }}) <span>- Upcoming
                                                Repayments</span></h3>
                                    </div>
                                </div>
                                <div class="table-parent">
                                    <div class="table-filter">
                                        <div class="filter search-parent">
                                            <i class="fa fa-search"></i>
                                            <input type="search" oninput="filterTable('due-week-tbody', this.value)"
                                                placeholder="Search by reference, type, purpose">
                                        </div>
                                    </div>
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>Reference</th>
                                                <th>Date Applied</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Term</th>
                                                <th>Purpose</th>
                                                <th>Due Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="due-week-tbody">
                                            @forelse($dueThisWeekLoans ?? [] as $loan)
                                                <tr>
                                                    <td style="font-weight:600;color:#1e2a4a;">{{ $loan->reference_no }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('Y-m-d') }}</td>
                                                    <td>{{ $loan->lending_type }}</td>
                                                    <td style="font-weight:600;">
                                                        ₱{{ number_format($loan->lending_amount, 2) }}</td>
                                                    <td>{{ $loan->lending_type_term }}</td>
                                                    <td>{{ $loan->purpose_loan }}</td>
                                                    <td>
                                                        <span
                                                            style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#fff8e1;color:#856404;">
                                                            {{ $loan->due_date }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button
                                                            style="background:none;border:none;cursor:pointer;color:#4f7ffa;"
                                                            title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8"
                                                        style="text-align:center;color:#6b7a99;padding:32px;font-size:13px;">
                                                        <i class="fa fa-calendar"
                                                            style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;"></i>
                                                        No upcoming payments this week.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="parent-pagination">
                                        <p>Showing <span>{{ count($dueThisWeekLoans ?? []) }}</span> of
                                            <span>{{ $dueThisWeekCount ?? 0 }}</span> records</p>
                                    </div>
                                </div>
                            </div>

                            {{-- ══ TAB 4: OVERDUE ══ --}}
                            <div class="tab-pane fade" id="nav-disabled" role="tabpanel"
                                aria-labelledby="nav-disabled-tab" tabindex="0">
                                <div class="table-header">
                                    <div class="main-text">
                                        <h3>Overdue ({{ $overdueCount ?? 0 }}) <span>- Missed or Unpaid Payments</span>
                                        </h3>
                                    </div>
                                </div>
                                <div class="table-parent">
                                    <div class="table-filter">
                                        <div class="filter search-parent">
                                            <i class="fa fa-search"></i>
                                            <input type="search" oninput="filterTable('overdue-tbody', this.value)"
                                                placeholder="Search by reference, type, purpose">
                                        </div>
                                    </div>
                                    <table class="table m-0">
                                        <thead>
                                            <tr>
                                                <th>Reference</th>
                                                <th>Date Applied</th>
                                                <th>Type</th>
                                                <th>Amount</th>
                                                <th>Term</th>
                                                <th>Purpose</th>
                                                <th>Due Date</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody id="overdue-tbody">
                                            @forelse($overdueLoans ?? [] as $loan)
                                                <tr>
                                                    <td style="font-weight:600;color:#1e2a4a;">{{ $loan->reference_no }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('Y-m-d') }}</td>
                                                    <td>{{ $loan->lending_type }}</td>
                                                    <td style="font-weight:600;">
                                                        ₱{{ number_format($loan->lending_amount, 2) }}</td>
                                                    <td>{{ $loan->lending_type_term }}</td>
                                                    <td>{{ $loan->purpose_loan }}</td>
                                                    <td>
                                                        <span
                                                            style="padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;background:#fef2f2;color:#dc2626;">
                                                            {{ $loan->due_date }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button
                                                            style="background:none;border:none;cursor:pointer;color:#4f7ffa;"
                                                            title="View">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="8"
                                                        style="text-align:center;color:#6b7a99;padding:32px;font-size:13px;">
                                                        <i class="fa fa-circle-check"
                                                            style="font-size:24px;display:block;margin-bottom:8px;opacity:.4;color:#1e9e6b;"></i>
                                                        No overdue payments. Great job!
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    <div class="parent-pagination">
                                        <p>Showing <span>{{ count($overdueLoans ?? []) }}</span> of
                                            <span>{{ $overdueCount ?? 0 }}</span> records</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        {{-- Search filter JS --}}
                        <script>
                            function filterTable(tbodyId, query) {
                                const tbody = document.getElementById(tbodyId);
                                if (!tbody) return;
                                const q = query.toLowerCase().trim();
                                tbody.querySelectorAll('tr').forEach(row => {
                                    row.style.display = q === '' || row.textContent.toLowerCase().includes(q) ? '' : 'none';
                                });
                            }
                        </script>
                    </div>
                </main>
            </div>
        </div>

        <!-- LOAN APPLICATION MODAL -->
        <div class="loan-modal-overlay" id="loanModalOverlay" onclick="maybeCloseLoanModal(event)">
            <div class="loan-modal" id="loanModal">
                <div class="modal-accent-bar"></div>

                <div class="modal-layout">
                    <!-- Live Invoice Sidebar -->
                    <div class="modal-invoice-sidebar">
                        <div style="position:relative;z-index:1;" class="d-flex flex-column align-items-start gap-1">
                            <div class="mis-eyebrow">Live Preview</div>
                            <div class="mis-title">Loan Invoice</div>
                            <div class="mis-sub">Updates as you fill</div>
                        </div>
                        <div class="mis-amount-box">
                            <div class="mis-amount-lbl">Monthly Payment</div>
                            <div class="mis-amount-val dim" id="mis-mo">—</div>
                            <div class="mis-amount-hint" id="mis-hint">Enter details to compute</div>
                        </div>
                        <div class="mis-rows">
                            <div class="mis-row"><span class="mis-lbl">Type</span><span class="mis-val dim"
                                    id="mis-type">—</span></div>
                            <div class="mis-row"><span class="mis-lbl">Amount</span><span class="mis-val dim"
                                    id="mis-amount">—</span></div>
                            <div class="mis-row"><span class="mis-lbl">Rate</span><span class="mis-val dim"
                                    id="mis-rate">—</span></div>
                            <div class="mis-row"><span class="mis-lbl">Term</span><span class="mis-val dim"
                                    id="mis-term">—</span></div>
                            <div class="mis-row"><span class="mis-lbl">Interest</span><span class="mis-val dim"
                                    id="mis-int">—</span></div>
                        </div>
                        <div class="mis-divider"></div>
                        <div class="mis-total-row">
                            <span class="mis-total-lbl">Total Payable</span>
                            <span class="mis-total-val dim" id="mis-total">—</span>
                        </div>
                    </div>

                    <!-- Main Modal Content -->
                    <div class="modal-main-area">

                        <!-- Step Indicator -->
                        <div class="modal-steps-bar" id="modalStepsBar">
                            <div class="m-step active" id="mst1">
                                <div class="m-step-circle" id="msc1">1</div>
                                <div class="m-step-info">
                                    <div class="m-step-num">Step 1</div>
                                    <div class="m-step-name">Loan Details</div>
                                </div>
                            </div>
                            <div class="m-step pending" id="mst2">
                                <div class="m-step-circle" id="msc2">2</div>
                                <div class="m-step-info">
                                    <div class="m-step-num">Step 2</div>
                                    <div class="m-step-name">Breakdown</div>
                                </div>
                            </div>
                            <div class="m-step pending" id="mst3">
                                <div class="m-step-circle" id="msc3">3</div>
                                <div class="m-step-info">
                                    <div class="m-step-num">Step 3</div>
                                    <div class="m-step-name">Review & Submit</div>
                                </div>
                            </div>
                        </div>

                        <!-- PANEL 1 — Loan Details -->
                        <div class="modal-panel active" id="mp1">

                            @if(!$canApplyLoan)
                                <div class="sc-alert">
                                    <div class="sc-alert-ico">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" y1="8" x2="12" y2="12" />
                                            <line x1="12" y1="16" x2="12.01" y2="16" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="sc-alert-title">Share Capital Requirement</div>
                                        <p class="sc-alert-text">You need at least <strong>10 shares</strong> of Share
                                            Capital to apply. You currently have <strong>{{ $currentShares }}
                                                share(s)</strong> — you need <strong>{{ 10 - $currentShares }}
                                                more</strong>.</p>
                                    </div>
                                </div>
                            @endif

                            <form id="loan-form" action="{{ route('lendingProgram') }}" method="post"
                                enctype="multipart/form-data" {{ !$canApplyLoan ? 'onsubmit=return false' : '' }}>
                                @csrf

                                <div class="panel-sec-hd">Loan Information</div>

                                <div class="p-form-row">
                                    <div class="p-field">
                                        <label>Loan Type <span class="req">*</span></label>
                                        <div class="p-sel-wrap">
                                            <select class="p-select np" name="lending_type" id="lending_type"
                                                onchange="mUpdateTermOptions(); mCompute(); mClearError(this);" {{ !$canApplyLoan ? 'disabled' : '' }} required>
                                                <option value="">Select type</option>
                                                <option value="Personal Loan"
                                                    data-rate="{{ $loanSettings['Personal Loan'] ?? 0.02 }}">Personal
                                                    Loan</option>
                                                <option value="Business Loan"
                                                    data-rate="{{ $loanSettings['Business Loan'] ?? 0.02 }}">Business
                                                    Loan</option>
                                                <option value="Emergency Loan"
                                                    data-rate="{{ $loanSettings['Emergency Loan'] ?? 0.02 }}">Emergency
                                                    Loan</option>
                                                <option value="Education Loan"
                                                    data-rate="{{ $loanSettings['Education Loan'] ?? 0.02 }}">Education
                                                    Loan</option>
                                            </select>
                                        </div>
                                        <div class="p-field-error" id="err-lending_type">
                                            <i class="fa fa-circle-exclamation"></i> Loan Type is required.
                                        </div>
                                    </div>
                                    <div class="p-field">
                                        <label>
                                            Loan Amount (₱) <span class="req">*</span>
                                            <span class="max-pill">Max
                                                ₱{{ number_format($remainingLoanable, 0) }}</span>
                                        </label>
                                        <div class="p-inp-wrap">
                                            <span class="p-inp-ico" style="font-size:13px;font-weight:700;">₱</span>
                                            <input type="number" class="p-input" name="lending_amount" id="mLoanAmount"
                                                placeholder="e.g. 15000" min="1" max="{{ $remainingLoanable }}"
                                                oninput="let v=parseFloat(this.value);if(v>25000)this.value=25000;mCheckLimit(this);mCompute();mClearError(this);"
                                                onkeydown="if(['e','E','+','-'].includes(event.key))event.preventDefault();"
                                                {{ !$canApplyLoan || $hasFullyLoaned ? 'disabled' : '' }} required>
                                        </div>
                                        <div class="p-warn" id="loan-limit-warning">
                                            <i class="fa fa-circle-exclamation" style="margin-right:5px;"></i>
                                            You can only borrow up to
                                            <strong>₱{{ number_format($remainingLoanable, 2) }}</strong> more.
                                        </div>
                                        <div class="p-field-error" id="err-mLoanAmount">
                                            <i class="fa fa-circle-exclamation"></i> Loan Amount is required.
                                        </div>
                                    </div>
                                </div>

                                <div class="p-form-row">
                                    <div class="p-field">
                                        <label>Loan Term <span class="req">*</span></label>
                                        <div class="p-sel-wrap">
                                            <select class="p-select np" name="lending_type_term_nonbusiness"
                                                id="lending_type_term_nonbusiness"
                                                onchange="mSyncTerm();mCompute();mClearError(this);" {{ !$canApplyLoan ? 'disabled' : '' }}>
                                                <option value="">Select term</option>
                                                <option value="6 months">6 months</option>
                                            </select>
                                        </div>
                                        <div style="display:none" id="term-business-wrap">
                                            <div class="p-sel-wrap">
                                                <select class="p-select np" name="lending_type_term_business"
                                                    id="lending_type_term_business"
                                                    onchange="mSyncTerm();mCompute();mClearError(this);">
                                                    <option value="">Select term</option>
                                                    <option value="6 months">6 months</option>
                                                    <option value="12 months">12 months</option>
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="lending_type_term" id="lending_type_term">
                                        <div class="p-field-error" id="err-lending_type_term">
                                            <i class="fa fa-circle-exclamation"></i> Loan Term is required.
                                        </div>
                                    </div>
                                    <div class="p-field">
                                        <label>Monthly Income (₱) <span class="req">*</span></label>
                                        <div class="p-inp-wrap">
                                            <span class="p-inp-ico"><svg viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <rect x="2" y="7" width="20" height="14" rx="2" />
                                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                                                </svg></span>
                                            <input type="number" class="p-input" name="monthly_income"
                                                id="mMonthlyIncome" placeholder="Monthly income"
                                                oninput="if(this.value.length>6)this.value=this.value.slice(0,6);mClearError(this);"
                                                onkeydown="if(['e','E','+','-'].includes(event.key))event.preventDefault();"
                                                {{ !$canApplyLoan ? 'disabled' : '' }} required>
                                        </div>
                                        <div class="p-field-error" id="err-mMonthlyIncome">
                                            <i class="fa fa-circle-exclamation"></i> Monthly Income is required.
                                        </div>
                                    </div>
                                </div>

                                <div class="panel-sec-hd">Purpose & Details</div>

                                <div class="p-form-row single">
                                    <div class="p-field">
                                        <label>Purpose of Loan <span class="req">*</span></label>
                                        <div class="p-sel-wrap">
                                            <select class="p-select np" name="purpose_loan" id="purpose_loan_select"
                                                onchange="mHandlePurpose(this);mClearError(this);" {{ !$canApplyLoan ? 'disabled' : '' }} required>
                                                <option value="" disabled selected>Select purpose</option>
                                                <option value="Medical Expenses">Medical Expenses</option>
                                                <option value="Education">Education</option>
                                                <option value="Business Capital">Business Capital</option>
                                                <option value="Emergency Needs">Emergency Needs</option>
                                                <option value="Home Improvement">Home Improvement</option>
                                                <option value="Debt Consolidation">Debt Consolidation</option>
                                                <option value="Transportation">Transportation</option>
                                                <option value="Daily Expenses">Daily Expenses</option>
                                                <option value="Travel">Travel</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                        <div class="p-field-error" id="err-purpose_loan_select">
                                            <i class="fa fa-circle-exclamation"></i> Purpose of Loan is required.
                                        </div>
                                    </div>
                                </div>

                                <div id="others-textarea-wrapper" style="display:none;margin-bottom:14px;">
                                    <div class="p-field">
                                        <label>Describe the Purpose <span class="req">*</span></label>
                                        <textarea class="p-textarea" name="purpose_loan_others"
                                            id="purpose_loan_textarea"
                                            placeholder="Describe the purpose of your loan..."
                                            oninput="mClearError(this);" {{ !$canApplyLoan ? 'disabled' : '' }}></textarea>
                                        <span class="p-hint">Additional details help with faster approval.</span>
                                        <div class="p-field-error" id="err-purpose_loan_textarea">
                                            <i class="fa fa-circle-exclamation"></i> Please describe the purpose.
                                        </div>
                                    </div>
                                </div>

                                <!-- Supporting Documents -->
                                <div id="docs-wrapper-modal" style="display:none;">
                                    <div class="docs-heading">Supporting Documents</div>

                                    <!-- Personal -->
                                    <div id="docs-personal-modal" class="docs-section">
                                        <div class="upload-grid-modal">
                                            <div class="upload-card-modal" id="mcard-vid-personal">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Valid ID</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-vid-personal"></div>
                                                <input type="file" name="personal_valid_id"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-vid-personal','mname-vid-personal')">
                                            </div>
                                            <div class="upload-card-modal" id="mcard-poi-personal">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Proof of Income</div>
                                                <div class="uc-sub">Payslip / COE · PDF or image</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-poi-personal"></div>
                                                <input type="file" name="personal_proof_of_income"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-poi-personal','mname-poi-personal')">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Emergency -->
                                    <div id="docs-emergency-modal" class="docs-section">
                                        <div class="upload-grid-modal">
                                            <div class="upload-card-modal" id="mcard-vid-emergency">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Valid ID</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-vid-emergency"></div>
                                                <input type="file" name="emergency_valid_id"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-vid-emergency','mname-vid-emergency')">
                                            </div>
                                            <div class="upload-card-modal" id="mcard-poi-emergency">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Proof of Income</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-poi-emergency"></div>
                                                <input type="file" name="emergency_proof_of_income"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-poi-emergency','mname-poi-emergency')">
                                            </div>
                                            <div class="upload-card-modal" id="mcard-poe-emergency">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Proof of Emergency</div>
                                                <div class="uc-sub">Medical Cert, Hospital Bill, Police Report</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-poe-emergency"></div>
                                                <input type="file" name="proof_of_emergency"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-poe-emergency','mname-poe-emergency')">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Business -->
                                    <div id="docs-business-modal" class="docs-section">
                                        <div class="upload-grid-modal">
                                            <div class="upload-card-modal" id="mcard-vid-business">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Valid ID</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-vid-business"></div>
                                                <input type="file" name="business_valid_id"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-vid-business','mname-vid-business')">
                                            </div>
                                            <div class="upload-card-modal" id="mcard-poi-business">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Proof of Income</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-poi-business"></div>
                                                <input type="file" name="business_proof_of_income"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-poi-business','mname-poi-business')">
                                            </div>
                                            <div class="upload-card-modal" id="mcard-bp-business">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Business Permit / DTI</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-bp-business"></div>
                                                <input type="file" name="business_permit" accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-bp-business','mname-bp-business')">
                                            </div>
                                            <div class="upload-card-modal" id="mcard-fs-business">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Financial Statement</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge optional">Optional</span>
                                                <div class="uc-filename" id="mname-fs-business"></div>
                                                <input type="file" name="financial_statement"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-fs-business','mname-fs-business')">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Education -->
                                    <div id="docs-education-modal" class="docs-section">
                                        <div class="upload-grid-modal">
                                            <div class="upload-card-modal" id="mcard-sid-education">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">School ID</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-sid-education"></div>
                                                <input type="file" name="school_id" accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-sid-education','mname-sid-education')">
                                            </div>
                                            <div class="upload-card-modal" id="mcard-cor-education">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">COR (Certificate of Registration)</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-cor-education"></div>
                                                <input type="file" name="cor" accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-cor-education','mname-cor-education')">
                                            </div>
                                            <div class="upload-card-modal" id="mcard-vid-education">
                                                <div class="uc-icon"><svg viewBox="0 0 24 24" fill="none"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                                        <polyline points="17 8 12 3 7 8" />
                                                        <line x1="12" y1="3" x2="12" y2="15" />
                                                    </svg></div>
                                                <div class="uc-label">Valid ID</div>
                                                <div class="uc-sub">PDF, JPG or PNG · max 5MB</div>
                                                <span class="uc-badge required">Required</span>
                                                <div class="uc-filename" id="mname-vid-education"></div>
                                                <input type="file" name="education_valid_id"
                                                    accept=".jpg,.jpeg,.png,.pdf"
                                                    onchange="mOnFileSelected(this,'mcard-vid-education','mname-vid-education')">
                                            </div>
                                        </div>
                                    </div>

                                    @if($hasFullyLoaned)
                                        <div class="loan-status-banner lsb-danger" style="margin-top:14px;">
                                            <div class="lsb-ico danger-ico"><i class="fa fa-ban"
                                                    style="color:#dc2626;font-size:15px;"></i></div>
                                            <div>
                                                <div class="lsb-title">Loan Limit Reached</div>
                                                <p style="margin:0;font-size:12.5px;line-height:1.5;">You have an active
                                                    loan of <strong>₱{{ number_format($totalActiveLoan, 2) }}</strong> — the
                                                    max is <strong>₱25,000.00</strong>. Please repay before applying again.
                                                </p>
                                            </div>
                                        </div>
                                    @elseif($totalActiveLoan > 0)
                                        <div class="loan-status-banner lsb-warn" style="margin-top:14px;">
                                            <div class="lsb-ico warn-ico"><i class="fa fa-circle-info"
                                                    style="color:#e6a817;font-size:15px;"></i></div>
                                            <div style="width:100%;">
                                                <div class="lsb-title">Remaining Loanable Amount</div>
                                                <p style="margin:0 0 8px;font-size:12.5px;line-height:1.5;">You have an
                                                    active loan of
                                                    <strong>₱{{ number_format($totalActiveLoan, 2) }}</strong>. You may
                                                    still borrow up to:
                                                </p>
                                                <div
                                                    style="background:#f5f5f5;border-radius:20px;height:8px;overflow:hidden;margin-bottom:6px;">
                                                    <div
                                                        style="height:8px;border-radius:20px;background:linear-gradient(90deg,#e6a817,#f59e0b);width:{{ min(100, ($totalActiveLoan / 25000) * 100) }}%;">
                                                    </div>
                                                </div>
                                                <div
                                                    style="display:flex;justify-content:space-between;font-size:11px;color:#999;margin-bottom:8px;">
                                                    <span>Used: ₱{{ number_format($totalActiveLoan, 2) }}</span>
                                                    <span>Limit: ₱25,000.00</span>
                                                </div>
                                                <div
                                                    style="background:#fff;border:1.5px solid #ffe082;border-radius:8px;padding:7px 12px;display:inline-flex;align-items:center;gap:7px;font-size:13px;font-weight:700;color:#1a4a3a;">
                                                    <i class="fa fa-coins"></i>
                                                    Available: ₱{{ number_format($remainingLoanable, 2) }}
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="loan-status-banner lsb-ok" style="margin-top:14px;">
                                            <i class="fa fa-circle-info" style="margin-top:2px;flex-shrink:0;"></i>
                                            <span>You may borrow up to <strong>₱25,000.00</strong>. Applications exceeding
                                                this limit will not be processed.</span>
                                        </div>
                                    @endif
                                </div>

                                <input type="hidden" name="monthly_payment" id="hidden-monthly">
                                <input type="hidden" name="total_payment" id="hidden-total">
                                <input type="hidden" name="total_interest" id="hidden-interest">

                            </form>

                            <div class="modal-footer-bar">
                                <div class="mf-note">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                    Your data is encrypted
                                </div>
                                <div class="mf-btns">
                                    <button class="m-btn m-btn-outline" onclick="closeLoanModal()">Cancel</button>
                                    <button class="m-btn m-btn-primary" onclick="mGoStep2()" {{ !$canApplyLoan ? 'disabled' : '' }}>
                                        View Breakdown
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="9 18 15 12 9 6" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /Panel 1 -->

                        <!-- PANEL 2 — Charges & Breakdown -->
                        <div class="modal-panel" id="mp2">
                            <div class="panel-sec-hd">Charges & Breakdown</div>
                            <div class="breakdown-grid-modal">
                                <div class="b-box-m hl">
                                    <div class="b-ico-m bi-g"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <line x1="12" y1="1" x2="12" y2="23" />
                                            <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                        </svg></div>
                                    <div class="b-lbl-m">Principal</div>
                                    <div class="b-val-m" id="cb-pri">₱ —</div>
                                    <div class="b-hint-m">Amount you receive</div>
                                </div>
                                <div class="b-box-m">
                                    <div class="b-ico-m bi-o"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg></div>
                                    <div class="b-lbl-m">Interest Rate</div>
                                    <div class="b-val-m" id="cb-rate">— %</div>
                                    <div class="b-hint-m">Monthly flat rate</div>
                                </div>
                                <div class="b-box-m">
                                    <div class="b-ico-m bi-r"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                        </svg></div>
                                    <div class="b-lbl-m">Total Interest</div>
                                    <div class="b-val-m" id="cb-int">₱ —</div>
                                    <div class="b-hint-m">Cost of borrowing</div>
                                </div>
                                <div class="b-box-m">
                                    <div class="b-ico-m bi-b"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" />
                                            <line x1="16" y1="2" x2="16" y2="6" />
                                            <line x1="8" y1="2" x2="8" y2="6" />
                                            <line x1="3" y1="10" x2="21" y2="10" />
                                        </svg></div>
                                    <div class="b-lbl-m">Term</div>
                                    <div class="b-val-m" id="cb-term">— mo</div>
                                    <div class="b-hint-m">Repayment period</div>
                                </div>
                                <div class="b-box-m">
                                    <div class="b-ico-m bi-p"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="12" y1="8" x2="12" y2="12" />
                                            <line x1="12" y1="16" x2="12.01" y2="16" />
                                        </svg></div>
                                    <div class="b-lbl-m">Service Fee</div>
                                    <div class="b-val-m" id="cb-proc">₱ —</div>
                                    <div class="b-hint-m">1% one-time fee</div>
                                </div>
                                <div class="b-box-m hl">
                                    <div class="b-ico-m bi-t"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                            <polyline points="22 4 12 14.01 9 11.01" />
                                        </svg></div>
                                    <div class="b-lbl-m">Total Payable</div>
                                    <div class="b-val-m" id="cb-total">₱ —</div>
                                    <div class="b-hint-m">Principal + Interest + Fee</div>
                                </div>
                            </div>

                            <div class="amort-hd-m">Monthly Amortization Schedule</div>
                            <div class="amort-wrap-m">
                                <div class="amort-scroll-m">
                                    <table class="amort-tbl">
                                        <thead>
                                            <tr>
                                                <th>Month</th>
                                                <th>Payment</th>
                                                <th>Principal</th>
                                                <th>Interest</th>
                                                <th>Remaining</th>
                                            </tr>
                                        </thead>
                                        <tbody id="amortBody">
                                            <tr>
                                                <td colspan="5"
                                                    style="text-align:center;color:var(--muted);padding:16px">No data
                                                    yet.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="modal-footer-bar">
                                <div class="mf-note">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <line x1="12" y1="8" x2="12" y2="12" />
                                        <line x1="12" y1="16" x2="12.01" y2="16" />
                                    </svg>
                                    Rates are indicative
                                </div>
                                <div class="mf-btns">
                                    <button class="m-btn m-btn-outline" onclick="mGoStep(1,true)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="15 18 9 12 15 6" />
                                        </svg>
                                        Back
                                    </button>
                                    <button class="m-btn m-btn-primary" onclick="mGoStep3()">
                                        Proceed to Review
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="9 18 15 12 9 6" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /Panel 2 -->

                        <!-- PANEL 3 — Review & Submit -->
                        <div class="modal-panel" id="mp3">
                            <div class="confirm-hero-m">
                                <div class="confirm-ring-m">
                                    <!-- <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg> -->
                                    <i class="fa fa-shield"></i>
                                </div>
                                <div class="confirm-title-m">Review Your Application</div>
                                <div class="confirm-sub-m">Verify all details carefully before submitting.</div>
                            </div>

                            <div class="sum-card-m">
                                <div class="sum-head-m">Application Summary</div>
                                <div class="sum-row-m"><span class="sum-lbl-m">Loan Type</span><span
                                        class="sum-val-m green" id="cf-type">—</span></div>
                                <div class="sum-row-m"><span class="sum-lbl-m">Amount Requested</span><span
                                        class="sum-val-m" id="cf-amount">—</span></div>
                                <div class="sum-row-m"><span class="sum-lbl-m">Loan Term</span><span class="sum-val-m"
                                        id="cf-term">—</span></div>
                                <div class="sum-row-m"><span class="sum-lbl-m">Monthly Income</span><span
                                        class="sum-val-m" id="cf-income">—</span></div>
                                <div class="sum-row-m"><span class="sum-lbl-m">Interest Rate</span><span
                                        class="sum-val-m" id="cf-rate">—</span></div>
                                <div class="sum-row-m"><span class="sum-lbl-m">Monthly Payment</span><span
                                        class="sum-val-m gold" id="cf-monthly">—</span></div>
                                <div class="sum-row-m"><span class="sum-lbl-m">Total Interest</span><span
                                        class="sum-val-m" id="cf-int">—</span></div>
                                <div class="sum-row-m"><span class="sum-lbl-m">Purpose</span><span class="sum-val-m"
                                        id="cf-purpose">—</span></div>
                                <div class="sum-row-m total"><span class="sum-lbl-m bold">Total Payable</span><span
                                        class="sum-val-m green bigf" id="cf-total">—</span></div>
                            </div>

                            <div class="cb-row-m" id="agreeRow" onclick="document.getElementById('mAgree').click();">
                                <input type="checkbox" id="mAgree"
                                    onclick="event.stopPropagation();mClearAgreeError();">
                                <label for="mAgree" onclick="event.stopPropagation();">I confirm all information is
                                    accurate and I agree to the <strong>Terms and Conditions</strong> of KPMPCATS
                                    Cooperative.</label>
                            </div>
                            <div class="cb-required-msg" id="err-mAgree">
                                <i class="fa fa-circle-exclamation"></i> You must agree to the Terms and Conditions.
                            </div>

                            <div class="modal-footer-bar">
                                <div class="mf-note">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                    </svg>
                                    Data is encrypted
                                </div>
                                <div class="mf-btns">
                                    <button class="m-btn m-btn-outline" onclick="mGoStep(2,true)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="15 18 9 12 15 6" />
                                        </svg>
                                        Back
                                    </button>
                                    <button class="m-btn m-btn-gold" onclick="mSubmit()">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="22 2 11 13" />
                                            <polygon points="22 2 15 22 11 13 2 9 22 2" />
                                        </svg>
                                        Submit Application
                                    </button>
                                </div>
                            </div>
                        </div>
                        <!-- /Panel 3 -->

                        <!-- SUCCESS SCREEN -->
                        <div class="success-screen-m" id="mSuccess">
                            <div class="success-ring-m"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg></div>
                            <div class="success-title-m">Application Submitted!</div>
                            <p class="success-sub-m">Your application is now under review. You will be notified through
                                your member portal once a decision is made.</p>
                            <div class="ref-pill-m">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Ref: <span id="mRefDisplay"></span>
                            </div>
                            <br>
                            <button class="m-btn m-btn-primary" style="margin:0 auto;"
                                onclick="closeLoanModal();location.reload()">Back to Dashboard</button>
                        </div>

                    </div><!-- /modal-main-area -->
                </div><!-- /modal-layout -->
            </div><!-- /loan-modal -->
        </div><!-- /loan-modal-overlay -->

        @if(session("ApplySuccess"))
            <div class="modal-overlay-success" id="success-modal">
                <div class="success-modal-box">
                    <div class="sm-head">
                        <div class="sm-icon">
                            <i class="fa fa-check"></i>
                        </div>
                    </div>
                    <div class="sm-body">
                        <h2>Application Submitted!</h2>
                        <p>Your lending application has been received and is now under review. We'll notify you within 3–5
                            business days.</p>
                        <div class="sm-details">
                            <div class="sm-row"><span class="sm-label">Name</span><span
                                    class="sm-val">{{ session("MemberName") }}</span></div>
                            <div class="sm-row"><span class="sm-label">Status</span><span class="sm-badge">Pending
                                    Review</span></div>
                            <div class="sm-row"><span class="sm-label">Reference</span><span
                                    class="sm-val">#{{ session("ReferenceNo") }}</span></div>
                            <div class="sm-row"><span class="sm-label">Date Filed</span><span
                                    class="sm-val">{{ session("DateFiled") }}</span></div>
                        </div>
                        <button class="sm-btn" onclick="closeSuccessModal()">Got it, Continue</button>
                    </div>
                </div>
            </div>
        @endif

    </div><!-- /container-fluid -->

    <script>
        // ══════════════════════════════════════════════════════════
        //  MODAL OPEN / CLOSE
        // ══════════════════════════════════════════════════════════
        function openLoanModal() {
            document.getElementById('loanModalOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
            mReset();
        }
        function closeLoanModal() {
            document.getElementById('loanModalOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }
        function maybeCloseLoanModal(e) {
            if (e.target === document.getElementById('loanModalOverlay')) closeLoanModal();
        }

        // ══════════════════════════════════════════════════════════
        //  HELPERS
        // ══════════════════════════════════════════════════════════
        const MAX_REMAINING = {{ $remainingLoanable }};
        const fmt = n => '₱' + n.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Read rate from selected <option data-rate> — avoids LOAN_RATES key mismatch bug
        function getSelectedRate() {
            const typeEl = document.getElementById('lending_type');
            const selectedOpt = typeEl.options[typeEl.selectedIndex];
            if (!selectedOpt) return null;
            const r = parseFloat(selectedOpt.getAttribute('data-rate'));
            return (!isNaN(r) && r > 0) ? r : null;
        }

        // ── Inline validation helpers ──────────────────────────────
        // Show error on a field (input/select/textarea)
        function mShowError(fieldEl, errId) {
            fieldEl.classList.add('field-error');
            const e = document.getElementById(errId);
            if (e) e.classList.add('show');
        }

        // Clear error from a field when user starts fixing it
        function mClearError(fieldEl) {
            fieldEl.classList.remove('field-error');
            const errId = 'err-' + fieldEl.id;
            const e = document.getElementById(errId);
            if (e) e.classList.remove('show');
        }

        // Scroll to and focus the first invalid field
        function mFocusFirst(el) {
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });
            try { el.focus(); } catch (e) { }
        }

        // Agree checkbox error helpers
        function mClearAgreeError() {
            document.getElementById('agreeRow').classList.remove('cb-error');
            document.getElementById('err-mAgree').classList.remove('show');
        }

        // ══════════════════════════════════════════════════════════
        //  RESET
        // ══════════════════════════════════════════════════════════
        function mReset() {
            document.getElementById('lending_type').value = '';
            document.getElementById('mLoanAmount').value = '';
            document.getElementById('mMonthlyIncome').value = '';
            document.getElementById('purpose_loan_select').value = '';
            document.getElementById('purpose_loan_textarea').value = '';
            document.getElementById('lending_type_term_nonbusiness').value = '';
            document.getElementById('lending_type_term_business').value = '';
            document.getElementById('lending_type_term').value = '';
            document.getElementById('mAgree').checked = false;
            document.getElementById('mSuccess').classList.remove('show');
            document.getElementById('modalStepsBar').style.display = 'flex';

            // Clear all inline errors
            document.querySelectorAll('.field-error').forEach(el => el.classList.remove('field-error'));
            document.querySelectorAll('.p-field-error.show, .cb-required-msg.show').forEach(el => el.classList.remove('show'));
            document.getElementById('agreeRow').classList.remove('cb-error');

            [1, 2, 3].forEach(i => {
                const p = document.getElementById('mp' + i);
                p.classList.remove('active', 'back');
            });
            document.getElementById('mp1').classList.add('active');
            mUpdateSteps(1);
            mCompute();
            ['personal', 'emergency', 'business', 'education'].forEach(t => {
                document.getElementById('docs-' + t + '-modal').style.display = 'none';
            });
            document.getElementById('docs-wrapper-modal').style.display = 'none';
            document.getElementById('others-textarea-wrapper').style.display = 'none';
            document.getElementById('lending_type_term_nonbusiness').style.display = 'block';
            document.getElementById('lending_type_term_business').style.display = 'none';
        }

        // ══════════════════════════════════════════════════════════
        //  STEP MANAGEMENT
        // ══════════════════════════════════════════════════════════
        function mUpdateSteps(n) {
            [1, 2, 3].forEach(i => {
                const el = document.getElementById('mst' + i);
                const c = document.getElementById('msc' + i);
                el.className = 'm-step';
                if (i < n) { el.classList.add('done'); c.innerHTML = '✓'; }
                else if (i === n) { el.classList.add('active'); c.textContent = i; }
                else { el.classList.add('pending'); c.textContent = i; }
                const con = document.getElementById('mcon' + i);
                if (con) i < n ? con.classList.add('done') : con.classList.remove('done');
            });
        }
        function mGoStep(n, back = false) {
            [1, 2, 3].forEach(i => document.getElementById('mp' + i).classList.remove('active', 'back'));
            const p = document.getElementById('mp' + n);
            p.classList.add('active');
            if (back) p.classList.add('back');
            mUpdateSteps(n);
            document.querySelector('.modal-main-area').scrollTop = 0;
        }

        // ══════════════════════════════════════════════════════════
        //  TERM SELECTOR
        // ══════════════════════════════════════════════════════════
        function mUpdateTermOptions() {
            const type = document.getElementById('lending_type').value;
            const nbWrap = document.getElementById('lending_type_term_nonbusiness').closest('.p-sel-wrap')
                || document.getElementById('lending_type_term_nonbusiness').parentElement;
            const bWrap = document.getElementById('term-business-wrap');
            const nbEl = document.getElementById('lending_type_term_nonbusiness');
            const bEl = document.getElementById('lending_type_term_business');

            if (type === 'Business Loan') {
                nbWrap.style.display = 'none';
                bWrap.style.display = 'block';
                bEl.style.display = 'block';
                if (bEl.options.length > 1) bEl.selectedIndex = 1;
            } else if (type) {
                nbWrap.style.display = 'block';
                bWrap.style.display = 'none';
                bEl.style.display = 'none';
                if (nbEl.options.length > 1) nbEl.selectedIndex = 1;
            } else {
                nbWrap.style.display = 'block';
                bWrap.style.display = 'none';
                bEl.style.display = 'none';
            }
            mSyncTerm();
            const docsMap = {
                'Personal Loan': 'docs-personal-modal',
                'Emergency Loan': 'docs-emergency-modal',
                'Business Loan': 'docs-business-modal',
                'Education Loan': 'docs-education-modal',
            };
            ['personal', 'emergency', 'business', 'education'].forEach(t => {
                document.getElementById('docs-' + t + '-modal').style.display = 'none';
            });
            if (type && docsMap[type]) {
                document.getElementById('docs-wrapper-modal').style.display = 'block';
                document.getElementById(docsMap[type]).style.display = 'block';
            } else {
                document.getElementById('docs-wrapper-modal').style.display = 'none';
            }
        }
        function mSyncTerm() {
            const bEl = document.getElementById('lending_type_term_business');
            const nbEl = document.getElementById('lending_type_term_nonbusiness');
            const hidden = document.getElementById('lending_type_term');
            hidden.value = (bEl.style.display === 'block') ? bEl.value : nbEl.value;
            // Clear term error when term syncs
            const termErr = document.getElementById('err-lending_type_term');
            if (hidden.value) {
                if (termErr) termErr.classList.remove('show');
                nbEl.classList.remove('field-error');
                bEl.classList.remove('field-error');
            }
            mCompute();
        }

        // ══════════════════════════════════════════════════════════
        //  PURPOSE TOGGLE
        // ══════════════════════════════════════════════════════════
        function mHandlePurpose(sel) {
            const wrap = document.getElementById('others-textarea-wrapper');
            wrap.style.display = sel.value === 'Others' ? 'block' : 'none';
            document.getElementById('purpose_loan_textarea').required = sel.value === 'Others';
        }

        // ══════════════════════════════════════════════════════════
        //  LOAN LIMIT CHECK
        // ══════════════════════════════════════════════════════════
        function mCheckLimit(input) {
            const warn = document.getElementById('loan-limit-warning');
            const val = parseFloat(input.value);
            const show = isNaN(val) || val <= 0 || val > MAX_REMAINING;
            warn.style.display = show ? 'block' : 'none';
            input.style.borderColor = show ? '#fca5a5' : '';
        }

        // ══════════════════════════════════════════════════════════
        //  LIVE INVOICE COMPUTE
        // ══════════════════════════════════════════════════════════
        function mCompute() {
            const typeEl = document.getElementById('lending_type');
            const type = typeEl.value;
            const r = getSelectedRate();
            const a = parseFloat(document.getElementById('mLoanAmount').value) || 0;
            const termStr = document.getElementById('lending_type_term').value || '';
            const t = termStr ? parseInt(termStr) : 0;

            const setV = (id, value, active) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.textContent = value;
                active ? el.classList.remove('dim') : el.classList.add('dim');
            };

            setV('mis-type', type ? type : '—', !!type);
            setV('mis-amount', a > 0 ? fmt(a) : '—', a > 0);
            setV('mis-rate', r ? (r * 100).toFixed(1) + '% / mo' : '—', !!r);
            setV('mis-term', t > 0 ? t + ' months' : '—', t > 0);

            if (r && a > 0 && t > 0) {
                const interest = a * r * t;
                const proc = a * 0.01;
                const total = a + interest + proc;
                const mo = (a + interest) / t;

                setV('mis-mo', fmt(mo), true);
                setV('mis-int', fmt(interest), true);
                setV('mis-total', fmt(total), true);

                document.getElementById('mis-hint').textContent = 'Flat interest method';
                document.getElementById('hidden-monthly').value = mo.toFixed(2);
                document.getElementById('hidden-total').value = total.toFixed(2);
                document.getElementById('hidden-interest').value = interest.toFixed(2);
            } else {
                setV('mis-mo', '—', false);
                setV('mis-int', '—', false);
                setV('mis-total', '—', false);
                document.getElementById('mis-hint').textContent = 'Enter details to compute';
            }
        }

        // ══════════════════════════════════════════════════════════
        //  STEP 2 — VALIDATE & BUILD BREAKDOWN
        //  All alert() calls replaced with inline field errors
        // ══════════════════════════════════════════════════════════
        function mGoStep2() {
            const typeEl = document.getElementById('lending_type');
            const amountEl = document.getElementById('mLoanAmount');
            const termStr = document.getElementById('lending_type_term').value;
            const incEl = document.getElementById('mMonthlyIncome');
            const purEl = document.getElementById('purpose_loan_select');
            const purOthEl = document.getElementById('purpose_loan_textarea');

            const type = typeEl.value;
            const a = parseFloat(amountEl.value);
            const inc = incEl.value;
            const pur = purEl.value;

            let hasError = false;
            let firstErrorEl = null;

            // Clear all previous errors first
            [typeEl, amountEl, incEl, purEl, purOthEl].forEach(el => {
                el.classList.remove('field-error');
            });
            document.getElementById('lending_type_term_nonbusiness').classList.remove('field-error');
            document.getElementById('lending_type_term_business').classList.remove('field-error');
            ['err-lending_type', 'err-mLoanAmount', 'err-lending_type_term', 'err-mMonthlyIncome', 'err-purpose_loan_select', 'err-purpose_loan_textarea'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('show');
            });

            // Validate: Loan Type
            if (!type) {
                mShowError(typeEl, 'err-lending_type');
                if (!firstErrorEl) firstErrorEl = typeEl;
                hasError = true;
            }

            // Validate: Loan Amount
            if (!amountEl.value || isNaN(a) || a <= 0) {
                mShowError(amountEl, 'err-mLoanAmount');
                if (!firstErrorEl) firstErrorEl = amountEl;
                hasError = true;
            } else if (a > MAX_REMAINING) {
                mShowError(amountEl, 'err-mLoanAmount');
                document.getElementById('err-mLoanAmount').querySelector
                    ? (document.getElementById('err-mLoanAmount').innerHTML = '<i class="fa fa-circle-exclamation"></i> Amount exceeds your borrowing limit.')
                    : null;
                if (!firstErrorEl) firstErrorEl = amountEl;
                hasError = true;
            }

            // Validate: Loan Term
            if (!termStr) {
                const visibleTerm = document.getElementById('lending_type_term_business').style.display === 'block'
                    ? document.getElementById('lending_type_term_business')
                    : document.getElementById('lending_type_term_nonbusiness');
                mShowError(visibleTerm, 'err-lending_type_term');
                if (!firstErrorEl) firstErrorEl = visibleTerm;
                hasError = true;
            }

            // Validate: Monthly Income
            if (!inc) {
                mShowError(incEl, 'err-mMonthlyIncome');
                if (!firstErrorEl) firstErrorEl = incEl;
                hasError = true;
            }

            // Validate: Purpose
            if (!pur) {
                mShowError(purEl, 'err-purpose_loan_select');
                if (!firstErrorEl) firstErrorEl = purEl;
                hasError = true;
            }

            // Validate: Others textarea (if Others selected)
            if (pur === 'Others' && !purOthEl.value.trim()) {
                mShowError(purOthEl, 'err-purpose_loan_textarea');
                if (!firstErrorEl) firstErrorEl = purOthEl;
                hasError = true;
            }

            // Validate: Required documents
            const reqMap = {
                'Personal Loan': ['personal_valid_id', 'personal_proof_of_income'],
                'Emergency Loan': ['emergency_valid_id', 'emergency_proof_of_income', 'proof_of_emergency'],
                'Business Loan': ['business_valid_id', 'business_proof_of_income', 'business_permit'],
                'Education Loan': ['school_id', 'cor', 'education_valid_id'],
            };
            const reqFields = reqMap[type] || [];

            document.querySelectorAll('.upload-card-modal').forEach(c => {
                c.style.border = '';
                const e = c.querySelector('.uc-error');
                if (e) { e.style.display = 'none'; e.textContent = ''; }
            });

            let firstMissingCard = null;
            reqFields.forEach(name => {
                const inp = document.querySelector(`input[name="${name}"]`);
                if (inp && !inp.files.length) {
                    const card = inp.closest('.upload-card-modal');
                    if (card) {
                        card.style.border = '2px solid #dc2626';
                        let errEl = card.querySelector('.uc-error');
                        if (!errEl) {
                            errEl = document.createElement('div');
                            errEl.className = 'uc-error';
                            card.appendChild(errEl);
                        }
                        errEl.textContent = 'This document is required.';
                        errEl.style.display = 'block';
                        if (!firstMissingCard) firstMissingCard = card;
                    }
                    hasError = true;
                }
            });

            // Scroll to first error
            if (hasError) {
                const scrollTarget = firstErrorEl || firstMissingCard;
                if (scrollTarget) mFocusFirst(scrollTarget);
                return;
            }

            // All valid — build breakdown
            const t = parseInt(termStr);
            const r = getSelectedRate() ?? 0.02;
            const interest = a * r * t;
            const proc = a * 0.01;
            const total = a + interest + proc;
            const mo = (a + interest) / t;

            document.getElementById('cb-pri').textContent = fmt(a);
            document.getElementById('cb-rate').textContent = (r * 100).toFixed(1) + '% / mo';
            document.getElementById('cb-int').textContent = fmt(interest);
            document.getElementById('cb-term').textContent = t + ' months';
            document.getElementById('cb-proc').textContent = fmt(proc);
            document.getElementById('cb-total').textContent = fmt(total);

            const tb = document.getElementById('amortBody');
            tb.innerHTML = '';
            const moPrin = a / t;
            const moInt = a * r;
            let bal = a;
            for (let m = 1; m <= t; m++) {
                bal -= moPrin;
                tb.innerHTML += `<tr>
                    <td><span class="mo-n">${m}</span></td>
                    <td><strong>${fmt(mo)}</strong></td>
                    <td>${fmt(moPrin)}</td>
                    <td class="int-n">${fmt(moInt)}</td>
                    <td class="bal-n">${fmt(Math.max(bal, 0))}</td>
                </tr>`;
            }

            mGoStep(2);
        }

        // ══════════════════════════════════════════════════════════
        //  STEP 3 — POPULATE REVIEW
        // ══════════════════════════════════════════════════════════
        function mGoStep3() {
            const type = document.getElementById('lending_type').value;
            const a = parseFloat(document.getElementById('mLoanAmount').value);
            const termStr = document.getElementById('lending_type_term').value;
            const inc = parseFloat(document.getElementById('mMonthlyIncome').value) || 0;
            const pur = document.getElementById('purpose_loan_select').value;
            const purOth = document.getElementById('purpose_loan_textarea').value.trim();
            const t = parseInt(termStr);
            const r = getSelectedRate() ?? 0.02;
            const interest = a * r * t;
            const total = a + interest + (a * 0.01);
            const mo = (a + interest) / t;

            document.getElementById('cf-type').textContent = type;
            document.getElementById('cf-amount').textContent = fmt(a);
            document.getElementById('cf-term').textContent = termStr;
            document.getElementById('cf-income').textContent = inc > 0 ? fmt(inc) : '—';
            document.getElementById('cf-purpose').textContent = pur === 'Others' ? (purOth || 'Others') : pur;
            document.getElementById('cf-rate').textContent = (r * 100).toFixed(1) + '% / mo';
            document.getElementById('cf-monthly').textContent = fmt(mo);
            document.getElementById('cf-int').textContent = fmt(interest);
            document.getElementById('cf-total').textContent = fmt(total);

            mGoStep(3);
        }

        // ══════════════════════════════════════════════════════════
        //  SUBMIT — inline error for unchecked checkbox
        // ══════════════════════════════════════════════════════════
        function mSubmit() {
            if (!document.getElementById('mAgree').checked) {
                document.getElementById('agreeRow').classList.add('cb-error');
                document.getElementById('err-mAgree').classList.add('show');
                document.getElementById('agreeRow').scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            document.getElementById('loan-form').submit();
        }

        // ══════════════════════════════════════════════════════════
        //  FILE UPLOAD FEEDBACK
        // ══════════════════════════════════════════════════════════
        function mOnFileSelected(input, cardId, nameId) {
            const card = document.getElementById(cardId);
            const nameEl = document.getElementById(nameId);
            if (!card || !nameEl) return;

            const badge = card.querySelector('.uc-badge');

            if (input.files && input.files[0]) {
                card.classList.add('has-file');
                card.style.border = '';
                const errEl = card.querySelector('.uc-error');
                if (errEl) { errEl.style.display = 'none'; }
                nameEl.textContent = input.files[0].name;

                if (badge) {
                    badge.textContent = 'Uploaded';
                    badge.classList.remove('required', 'optional');
                    badge.classList.add('uploaded');
                }
            } else {
                card.classList.remove('has-file');
                nameEl.textContent = '';

                if (badge) {
                    // restore original label — store it on first render
                    const original = badge.dataset.original || 'Required';
                    badge.textContent = original;
                    badge.classList.remove('uploaded');
                    badge.classList.add(original.toLowerCase());
                }
            }
        }
    </script>

</body>

</html>