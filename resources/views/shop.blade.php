<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Shop | Preloved Picks</title>
    <style>
        * { box-sizing: border-box; }
        :root {
            --bg: #f4f5f7;
            --text: #111;
            --muted: #5d6470;
            --card: #ffffff;
            --line: #e3e6ea;
        }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: radial-gradient(circle at top right, #ffffff, var(--bg) 55%);
            color: var(--text);
        }
        .page-loader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity .35s ease, visibility .35s ease;
        }
        .page-loader.hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .page-loader-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            text-align: center;
            padding: 20px;
        }
        .page-loader-logo {
            width: min(210px, 54vw);
            height: auto;
            animation: logo-heartbeat 1.2s ease-in-out infinite;
            transform-origin: center center;
        }
        .page-loader-tagline {
            margin: 0;
            font-family: "Trebuchet MS", "Segoe UI", Tahoma, sans-serif;
            font-size: clamp(1rem, 2.2vw, 1.35rem);
            letter-spacing: 0.4px;
            font-weight: 700;
            color: #1f2937;
        }
        @keyframes logo-heartbeat {
            0%, 100% { transform: scale(1); }
            14% { transform: scale(1.12); }
            28% { transform: scale(1); }
            42% { transform: scale(1.12); }
            70% { transform: scale(1); }
        }
        a { color: inherit; text-decoration: none; }
        .shell {
            width: min(1180px, 94%);
            margin: 26px auto;
        }
        .bubble {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 24px;
            box-shadow: 0 10px 24px rgba(17, 24, 39, 0.06);
        }
        .top-nav {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 18px;
            margin-bottom: 16px;
        }
        .brand-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 220px;
            position: relative;
        }
        .brand-logo {
            height: 56px;
            width: auto;
            display: block;
        }
        .shop-search {
            width: min(460px, 100%);
            border: 1px solid #cfd5dd;
            border-radius: 999px;
            background: #fff;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 600;
            color: #222;
            outline: none;
        }
        .shop-search:focus {
            border-color: #111;
            box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.08);
        }
        .floating-categories {
            position: absolute;
            top: calc(100% + 8px);
            left: 66px;
            width: min(460px, calc(100vw - 48px));
            display: none;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px;
            border: 1px solid #dbe1e8;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 12px 26px rgba(17, 24, 39, 0.14);
            z-index: 20;
        }
        .floating-categories.show {
            display: flex;
        }
        .floating-chip {
            border: 1px solid #cfd5dd;
            border-radius: 999px;
            background: #fff;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 700;
            color: #222;
            line-height: 1;
            cursor: pointer;
        }
        .floating-chip:hover {
            border-color: #111;
            color: #111;
        }
        .floating-chip.active {
            background: #111;
            border-color: #111;
            color: #fff;
        }
        .nav-links {
            display: flex;
            gap: 16px;
            font-size: 13px;
            font-weight: 700;
            flex-wrap: wrap;
        }
        .nav-links a { color: #1d1d1d; }
        .desktop-nav-pill {
            border: 1px solid #cfd5dd;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 800;
            color: #111;
            background: #fff;
        }
        .admin-add-trigger {
            display: none;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border: 1px solid #cfd5dd;
            border-radius: 50%;
            background: #fff;
            color: #111;
            box-shadow: 0 6px 14px rgba(17, 24, 39, 0.12);
        }
        .admin-add-trigger svg {
            width: 16px;
            height: 16px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .cart-pill {
            position: relative;
        }
        .cart-badge {
            position: absolute;
            top: -7px;
            right: -7px;
            min-width: 18px;
            height: 18px;
            border-radius: 999px;
            background: #111;
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 5px;
            border: 2px solid #fff;
        }
        .profile-logo {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1px solid #d4d9e1;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #111;
            box-shadow: 0 6px 16px rgba(17, 24, 39, 0.12);
        }
        .profile-logo svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .mobile-bottom-nav {
            display: none;
        }
        .cart-overlay {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.36);
            display: none;
            z-index: 80;
        }
        .cart-overlay.show {
            display: block;
        }
        .cart-panel {
            position: fixed;
            top: 0;
            right: -430px;
            width: min(420px, 100%);
            height: 100vh;
            background: #fff;
            border-left: 1px solid #dbe1e8;
            box-shadow: -8px 0 26px rgba(17, 24, 39, 0.16);
            z-index: 85;
            display: flex;
            flex-direction: column;
            transition: right 0.25s ease;
        }
        .cart-panel.show {
            right: 0;
        }
        .cart-panel.auth-mode {
            width: min(430px, 100%);
        }
        .cart-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 14px 10px;
            border-bottom: 1px solid #e5e9ef;
        }
        .cart-title {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
        }
        .cart-close {
            border: 1px solid #cfd5dd;
            border-radius: 999px;
            width: 32px;
            height: 32px;
            background: #fff;
            cursor: pointer;
        }
        .cart-items {
            flex: 1;
            overflow-y: auto;
            padding: 10px 14px;
        }
        .cart-empty {
            margin: 18px 0;
            color: #5d6470;
            font-size: 13px;
        }
        .cart-item {
            display: grid;
            grid-template-columns: 58px 1fr auto;
            gap: 10px;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #edf1f5;
        }
        .cart-item img {
            width: 58px;
            height: 58px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #e2e7ed;
            background: #f3f6f9;
        }
        .cart-item-title {
            margin: 0 0 4px;
            font-size: 13px;
            font-weight: 700;
        }
        .cart-item-meta {
            margin: 0;
            font-size: 12px;
            color: #4d5562;
        }
        .cart-item-remove {
            border: 1px solid #ef4444;
            background: #fff5f5;
            color: #b91c1c;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
        }
        .cart-foot {
            border-top: 1px solid #e5e9ef;
            padding: 12px 14px 14px;
        }
        .cart-guest-prompt {
            display: none;
            padding: 18px 14px;
            border-top: 1px solid #e5e9ef;
            background: #fbfcfe;
        }
        .cart-guest-prompt.show {
            display: block;
        }
        .cart-panel.auth-mode .cart-guest-prompt.show {
            display: flex;
            flex-direction: column;
            flex: 1;
            overflow-y: auto;
            padding: 20px 16px 16px;
            border-top: 0;
            background: #fff;
        }
        .cart-guest-title {
            margin: 0 0 6px;
            font-size: 14px;
            font-weight: 800;
        }
        .cart-guest-text {
            margin: 0 0 10px;
            font-size: 12px;
            color: #586171;
        }
        .cart-guest-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .cart-guest-form {
            margin-bottom: 10px;
        }
        .auth-logo-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }
        .auth-logo {
            height: 64px;
            width: auto;
            display: block;
        }
        .auth-switch {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
            background: #f1f4f8;
            border-radius: 999px;
            padding: 4px;
        }
        .auth-switch-btn {
            flex: 1;
            border: 0;
            background: transparent;
            color: #4b5563;
            border-radius: 999px;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }
        .auth-switch-btn.active {
            background: #111;
            color: #fff;
        }
        .auth-panel {
            display: none;
        }
        .auth-panel.show {
            display: block;
        }
        .cart-guest-input {
            width: 100%;
            border: 1px solid #d5dbe3;
            border-radius: 12px;
            padding: 9px 11px;
            font-size: 12px;
            margin-bottom: 8px;
            outline: none;
        }
        .cart-guest-input:focus {
            border-color: #111;
            box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.08);
        }
        .cart-guest-submit {
            width: 100%;
            border: 1px solid #111;
            border-radius: 999px;
            background: #111;
            color: #fff;
            padding: 9px 12px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
            margin-bottom: 8px;
        }
        .cart-guest-btn {
            border: 1px solid #111;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 800;
        }
        .cart-guest-btn.primary {
            background: #111;
            color: #fff;
        }
        .cart-guest-btn.secondary {
            background: #fff;
            color: #111;
        }
        .auth-helper {
            margin: 0 0 8px;
            font-size: 12px;
            color: #616b79;
        }
        .auth-inline-link {
            color: #111;
            font-weight: 800;
            text-decoration: underline;
            cursor: pointer;
        }
        .profile-panel {
            display: none;
            flex: 1;
            overflow-y: auto;
            padding: 16px 14px 18px;
            background: #fff;
            gap: 10px;
        }
        .profile-panel.show {
            display: flex;
            flex-direction: column;
        }
        .profile-panel.admin-view #profileOrdersSummaryCard,
        .profile-panel.admin-view #profileAddressCard,
        .profile-panel.admin-view #profileOrderListCard {
            display: none !important;
        }
        .profile-panel.admin-view #profileEmail {
            display: none !important;
        }
        .profile-panel.admin-view #adminOrderCard {
            display: none !important;
        }
        .profile-panel.admin-view.manage-orders-mode #adminOrderCard {
            display: block !important;
        }
        .profile-panel.admin-view.manage-orders-mode #profileLogoutBtn {
            display: none !important;
        }
        .profile-panel.admin-profile-only #profileOrdersSummaryCard,
        .profile-panel.admin-profile-only #profileAddressCard,
        .profile-panel.admin-profile-only #profileOrderListCard,
        .profile-panel.admin-profile-only #adminProductCard,
        .profile-panel.admin-profile-only #adminOrderCard {
            display: none !important;
        }
        .profile-panel.admin-profile-only #profileEmail {
            display: none !important;
        }
        .profile-card {
            border: 1px solid #e3e8ef;
            border-radius: 12px;
            padding: 12px;
            background: #fff;
        }
        .profile-name {
            margin: 0 0 4px;
            font-size: 15px;
            font-weight: 800;
            color: #111;
        }
        .profile-email {
            margin: 0;
            font-size: 12px;
            color: #5b6574;
        }
        .profile-label {
            margin: 0 0 8px;
            font-size: 12px;
            font-weight: 800;
            color: #111;
            letter-spacing: 0.2px;
            text-transform: uppercase;
        }
        .order-status-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
        }
        .order-status-item {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            text-align: center;
            padding: 8px 6px;
            background: #f9fbfd;
        }
        .order-status-icon {
            font-size: 18px;
            line-height: 1;
        }
        .order-status-title {
            margin: 4px 0 1px;
            font-size: 11px;
            font-weight: 800;
            color: #111;
        }
        .order-status-count {
            margin: 0;
            font-size: 11px;
            color: #5b6574;
        }
        .profile-address-list {
            margin: 0;
            padding-left: 18px;
            display: grid;
            gap: 6px;
            color: #2f3743;
            font-size: 12px;
        }
        .address-form {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 8px;
            margin-top: 10px;
        }
        .address-input {
            border: 1px solid #d5dbe3;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 12px;
            outline: none;
        }
        .address-input:focus {
            border-color: #111;
            box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.08);
        }
        .address-btn {
            border: 1px solid #111;
            border-radius: 999px;
            background: #111;
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            padding: 8px 12px;
            cursor: pointer;
        }
        .admin-badge {
            display: inline-flex;
            align-items: center;
            border: 1px solid #111;
            border-radius: 999px;
            padding: 2px 8px;
            font-size: 10px;
            font-weight: 800;
            margin-left: 6px;
        }
        .admin-form-grid {
            display: grid;
            gap: 8px;
        }
        .admin-input,
        .admin-textarea,
        .admin-select {
            width: 100%;
            border: 1px solid #d5dbe3;
            border-radius: 10px;
            padding: 8px 10px;
            font-size: 12px;
            outline: none;
        }
        .admin-textarea {
            min-height: 78px;
            resize: vertical;
        }
        .admin-input:focus,
        .admin-textarea:focus,
        .admin-select:focus {
            border-color: #111;
            box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.08);
        }
        .admin-image-preview-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(68px, 1fr));
            gap: 8px;
        }
        .admin-image-tile {
            position: relative;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid #d7dde6;
            background: #f8fafc;
            aspect-ratio: 1 / 1;
        }
        .admin-image-tile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .admin-image-remove {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 18px;
            height: 18px;
            border: none;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.76);
            color: #fff;
            font-size: 12px;
            line-height: 1;
            cursor: pointer;
            display: grid;
            place-items: center;
        }
        .admin-submit,
        .logout-btn {
            width: 100%;
            border: 1px solid #111;
            border-radius: 999px;
            padding: 9px 12px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }
        .admin-submit {
            background: #111;
            color: #fff;
        }
        .logout-btn {
            background: #fff;
            color: #111;
        }
        .orders-list {
            display: grid;
            gap: 8px;
        }
        .order-row {
            border: 1px solid #e5eaf1;
            border-radius: 10px;
            padding: 8px;
            background: #fbfcfe;
        }
        .order-row-title {
            margin: 0 0 4px;
            font-size: 12px;
            font-weight: 700;
            color: #111;
        }
        .order-row-meta {
            margin: 0 0 6px;
            font-size: 11px;
            color: #5d6470;
        }
        .order-row-action {
            border: 1px solid #111;
            border-radius: 999px;
            background: #111;
            color: #fff;
            font-size: 11px;
            font-weight: 800;
            padding: 6px 10px;
            cursor: pointer;
        }
        .order-row-action.secondary {
            background: #fff;
            color: #111;
        }
        .order-row-icon-btn {
            border: 1px solid #d3d9e2;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            background: #fff;
            color: #b91c1c;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 2px;
        }
        .order-row-icon-btn svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .admin-status-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 10px;
        }
        .admin-status-btn {
            border: 1px solid #dbe1e8;
            border-radius: 10px;
            background: #f9fbfd;
            padding: 8px 6px;
            text-align: center;
            cursor: pointer;
        }
        .admin-status-btn.active {
            border-color: #111;
            background: #eef2f7;
        }
        .admin-status-icon {
            font-size: 18px;
            line-height: 1;
        }
        .admin-status-label {
            margin: 3px 0 1px;
            font-size: 11px;
            font-weight: 800;
            color: #111;
        }
        .admin-status-count {
            margin: 0;
            font-size: 11px;
            color: #5d6470;
        }
        .order-action-modal {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            z-index: 95;
        }
        .order-action-modal.show {
            display: flex;
        }
        .order-action-card {
            width: min(420px, 100%);
            background: #fff;
            border-radius: 16px;
            border: 1px solid #dfe4ea;
            box-shadow: 0 16px 36px rgba(17, 24, 39, 0.24);
            overflow: hidden;
        }
        .order-action-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 14px;
            border-bottom: 1px solid #e5e9ef;
        }
        .order-action-title {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
            color: #111;
        }
        .order-action-body {
            padding: 12px 14px;
            display: grid;
            gap: 8px;
            font-size: 13px;
            color: #374151;
        }
        .order-action-footer {
            padding: 0 14px 14px;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
        .order-action-btn {
            border: 1px solid #111;
            border-radius: 999px;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }
        .order-action-btn.cancel {
            background: #fff;
            color: #111;
        }
        .order-action-btn.confirm {
            background: #111;
            color: #fff;
        }
        .cart-total {
            margin: 0 0 10px;
            font-size: 14px;
            font-weight: 800;
        }
        .cart-checkout {
            width: 100%;
            border: 1px solid #111;
            border-radius: 999px;
            background: #111;
            color: #fff;
            padding: 10px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }
        .mobile-bottom-link {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid #d4d9e1;
            background: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #111;
            box-shadow: 0 6px 16px rgba(17, 24, 39, 0.12);
        }
        .mobile-bottom-link svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        .mobile-bottom-link.cart-pill .cart-badge {
            top: -5px;
            right: -4px;
        }
        .mobile-admin-add {
            display: none;
        }
        .mobile-signin-word {
            min-width: 74px;
            height: 44px;
            border-radius: 999px;
            border: 1px solid #d4d9e1;
            background: #111;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            font-size: 12px;
            font-weight: 800;
            box-shadow: 0 6px 16px rgba(17, 24, 39, 0.2);
        }
        .hero {
            padding: 20px;
            margin-bottom: 16px;
        }
        .hero-grid {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            gap: 16px;
        }
        .hero-panel {
            border-radius: 20px;
            border: 1px solid var(--line);
            background: #fff;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        .hero-panel::after {
            content: "";
            position: absolute;
            inset: -35% -25% auto auto;
            width: 260px;
            height: 260px;
            background: radial-gradient(circle, rgba(129, 140, 248, 0.22), rgba(129, 140, 248, 0));
            pointer-events: none;
            z-index: 0;
        }
        .hero-panel > * {
            position: relative;
            z-index: 1;
        }
        .hero-title {
            margin: 0;
            font-size: clamp(2rem, 5vw, 3rem);
            line-height: 1.05;
        }
        .hero-text {
            margin: 10px 0 16px;
            color: var(--muted);
            line-height: 1.6;
            font-size: 14px;
        }
        .hero-focus-image {
            width: 100%;
            height: 290px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid var(--line);
            display: block;
            background: #eceff3;
            filter: saturate(1.1) contrast(1.05);
            transition: transform .55s ease, filter .55s ease;
            box-shadow: 0 18px 34px rgba(17, 24, 39, 0.2);
        }
        .hero-panel:hover .hero-focus-image {
            transform: scale(1.035);
            filter: saturate(1.2) contrast(1.08);
        }
        .preview-overlay {
            position: absolute;
            left: 18px;
            right: 18px;
            bottom: 18px;
            z-index: 3;
            color: #fff;
            padding: 12px 14px;
            border-radius: 12px;
            background: linear-gradient(160deg, rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.35));
            border: 1px solid rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(2px);
            box-shadow: 0 10px 24px rgba(2, 6, 23, 0.3);
            pointer-events: none;
        }
        .preview-kicker {
            margin: 0 0 4px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.1px;
            text-transform: uppercase;
            color: #dbeafe;
        }
        .preview-title {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
            line-height: 1.2;
        }
        .preview-sub {
            margin: 4px 0 0;
            font-size: 12px;
            color: #e5e7eb;
        }
        .pill-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .cat-chip {
            border: 1px solid #cfd5dd;
            background: #fff;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 800;
            cursor: default;
        }
        .cat-chip.active {
            background: #111;
            border-color: #111;
            color: #fff;
        }
        .category-title {
            margin: 0 0 8px;
            font-size: 12px;
            color: var(--muted);
            letter-spacing: .6px;
            text-transform: uppercase;
            font-weight: 700;
        }
        .icon-title {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .icon-badge {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1px solid #cfd5dd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        .right-bubble {
            border-radius: 20px;
            border: 1px solid var(--line);
            background: linear-gradient(145deg, #fff, #f6f8fb);
            min-height: 240px;
            padding: 18px;
        }
        .feature-slider {
            position: relative;
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            background: #f7f9ff;
            min-height: 290px;
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.45), 0 14px 28px rgba(17, 24, 39, 0.16);
        }
        .feature-slider::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.18), rgba(17, 24, 39, 0.12));
            pointer-events: none;
            z-index: 2;
        }
        .feature-slider-caption {
            position: absolute;
            left: 14px;
            right: 14px;
            bottom: 14px;
            z-index: 3;
            color: #fff;
            padding: 10px 12px;
            border-radius: 12px;
            background: linear-gradient(160deg, rgba(17, 24, 39, 0.72), rgba(17, 24, 39, 0.36));
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 22px rgba(2, 6, 23, 0.28);
            pointer-events: none;
        }
        .feature-slider-caption p {
            margin: 0;
            font-size: 11px;
            color: #e5e7eb;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            font-weight: 700;
        }
        .feature-slider-caption strong {
            display: block;
            margin-top: 2px;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.2;
            color: #f8fafc;
        }
        .feature-image {
            width: 100%;
            height: 290px;
            object-fit: cover;
            display: none;
            background: #eceff3;
            filter: saturate(1.18) contrast(1.06);
            transform: scale(1.02);
            transition: transform .8s ease, filter .8s ease;
        }
        .feature-image.active {
            display: block;
            transform: scale(1.07);
            filter: saturate(1.24) contrast(1.08);
        }
        .section-head {
            padding: 16px 18px 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            font-weight: 800;
            text-transform: uppercase;
        }
        .top-filter-bar {
            padding: 0 18px 8px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .top-filter {
            border: 1px solid #cfd5dd;
            background: #fff;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: 800;
            color: #222;
            cursor: pointer;
        }
        .top-filter.active {
            background: #111;
            border-color: #111;
            color: #fff;
        }
        .products {
            padding: 10px 18px 18px;
            display: grid;
            grid-template-columns: repeat(5, minmax(160px, 1fr));
            gap: 12px;
        }
        .empty-state {
            display: none;
            margin: 6px 18px 18px;
            padding: 14px;
            border: 1px dashed #cfd5dd;
            border-radius: 12px;
            font-size: 13px;
            color: #5d6470;
            background: #fafbfd;
        }
        .product {
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--line);
            transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease;
            position: relative;
        }
        .product:hover {
            transform: translateY(-5px);
            border-color: #d6dce5;
            box-shadow: 0 16px 34px rgba(17, 24, 39, 0.16);
        }
        .thumb {
            aspect-ratio: 4 / 5;
            background: radial-gradient(circle at 25% 20%, #ffffff, #edf1f8 58%, #dbe2ee);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }
        .thumb::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.12), rgba(17, 24, 39, 0.22));
            pointer-events: none;
            z-index: 1;
        }
        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            filter: saturate(1.08) contrast(1.06);
            transform: scale(1.01);
            transition: transform .42s ease, filter .42s ease;
        }
        .product:hover .thumb img {
            transform: scale(1.08);
            filter: saturate(1.22) contrast(1.1);
        }
        .tag {
            position: absolute;
            left: 8px;
            top: 8px;
            background: #111;
            color: #fff;
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 999px;
            letter-spacing: .4px;
            z-index: 3;
            box-shadow: 0 4px 10px rgba(17, 24, 39, 0.35);
        }
        .heart {
            position: absolute;
            right: 8px;
            top: 8px;
            font-size: 14px;
            z-index: 3;
            color: #f8fafc;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.45);
        }
        .product-drag-btn,
        .product-edit-btn,
        .product-delete-btn {
            position: absolute;
            top: 8px;
            width: 30px;
            height: 30px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.45);
            background: rgba(15, 23, 42, 0.68);
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 5;
            box-shadow: 0 8px 16px rgba(2, 6, 23, 0.28);
        }
        .product-drag-btn {
            right: 80px;
            cursor: grab;
        }
        .product-drag-btn:active {
            cursor: grabbing;
        }
        .product-edit-btn {
            right: 44px;
        }
        .product-delete-btn {
            right: 8px;
        }
        .product-edit-btn:hover {
            background: rgba(37, 99, 235, 0.9);
        }
        .product-delete-btn:hover {
            background: rgba(127, 29, 29, 0.9);
        }
        .product-drag-btn svg,
        .product-edit-btn svg,
        .product-delete-btn svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.1;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        body.admin-mode .product-drag-btn,
        body.admin-mode .product-edit-btn,
        body.admin-mode .product-delete-btn {
            display: inline-flex;
        }
        body.admin-mode .product .heart {
            display: none;
        }
        body.admin-mode .product {
            cursor: grab;
        }
        body.admin-mode .product:active {
            cursor: grabbing;
        }
        body.admin-mode .product.is-dragging {
            opacity: .55;
            transform: scale(.98);
            box-shadow: 0 10px 24px rgba(17, 24, 39, 0.2);
        }
        .product-info {
            padding: 10px;
            font-size: 13px;
        }
        .product-title {
            margin: 0 0 4px;
            font-weight: 700;
        }
        .product-price {
            margin: 0 0 4px;
            font-size: 13px;
            font-weight: 800;
            color: #111;
        }
        .product-trigger {
            all: unset;
            display: block;
            cursor: pointer;
            width: 100%;
        }
        .meta {
            margin: 0;
            color: #555;
            font-size: 12px;
        }
        .product-blurb {
            margin: 6px 0 0;
            color: #6b7280;
            font-size: 11px;
            line-height: 1.45;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            min-height: 30px;
        }
        .product-modal {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            z-index: 90;
        }
        .product-modal.show {
            display: flex;
        }
        .product-modal-card {
            width: min(680px, 100%);
            background: #fff;
            border-radius: 18px;
            border: 1px solid #dfe4ea;
            box-shadow: 0 18px 40px rgba(17, 24, 39, 0.25);
            overflow: hidden;
        }
        .product-modal-body {
            display: grid;
            grid-template-columns: 0.95fr 1.05fr;
            gap: 14px;
            padding: 14px;
        }
        .product-modal-image {
            width: 100%;
            border-radius: 12px;
            border: 1px solid #e2e6eb;
            aspect-ratio: 4 / 5;
            object-fit: cover;
            background: #eef1f5;
        }
        .product-modal-image-wrap {
            position: relative;
        }
        .product-modal-image-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 28px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 999px;
            background: rgba(17, 24, 39, 0.64);
            color: #fff;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            z-index: 2;
        }
        .product-modal-image-nav.prev { left: 8px; }
        .product-modal-image-nav.next { right: 8px; }
        .product-modal-image-wrap.has-multi .product-modal-image-nav {
            display: inline-flex;
        }
        .product-modal-image-count {
            margin: 6px 0 0;
            font-size: 11px;
            color: #64748b;
            font-weight: 700;
            text-align: center;
        }
        .product-modal-title {
            margin: 0 0 8px;
            font-size: 20px;
            font-weight: 800;
        }
        .product-modal-price {
            margin: 0 0 8px;
            font-size: 15px;
            font-weight: 800;
            color: #111;
        }
        .product-modal-description {
            margin: 0 0 12px;
            color: #4c5563;
            line-height: 1.5;
            font-size: 13px;
        }
        .qty-wrap {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #d1d7e0;
            border-radius: 999px;
            padding: 4px;
            margin-bottom: 12px;
        }
        .qty-btn {
            width: 30px;
            height: 30px;
            border: 1px solid #d1d7e0;
            border-radius: 50%;
            background: #fff;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }
        .qty-value {
            min-width: 26px;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
        }
        .modal-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .modal-btn {
            border: 1px solid #111;
            border-radius: 999px;
            padding: 10px 14px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }
        .modal-btn.add {
            background: #fff;
            color: #111;
        }
        .modal-btn.buy {
            background: #111;
            color: #fff;
        }
        .product-modal-close {
            position: absolute;
            margin: 10px;
            right: 0;
            border: 1px solid #d1d7e0;
            border-radius: 999px;
            width: 34px;
            height: 34px;
            background: #fff;
            font-size: 18px;
            cursor: pointer;
        }
        .checkout-modal {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            z-index: 91;
        }
        .checkout-modal.show {
            display: flex;
        }
        .checkout-modal-card {
            width: min(560px, 100%);
            background: #fff;
            border-radius: 18px;
            border: 1px solid #dfe4ea;
            box-shadow: 0 18px 40px rgba(17, 24, 39, 0.25);
            overflow: hidden;
        }
        .checkout-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 16px;
            border-bottom: 1px solid #e5e9ef;
        }
        .checkout-title {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
        }
        .checkout-body {
            padding: 14px 16px;
            display: grid;
            gap: 10px;
            max-height: 65vh;
            overflow-y: auto;
        }
        .checkout-lines {
            border: 1px solid #e5eaf1;
            border-radius: 12px;
            padding: 10px;
            display: grid;
            gap: 6px;
            background: #fbfcfe;
        }
        .checkout-line {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
            color: #2f3743;
        }
        .checkout-address {
            border: 1px solid #e5eaf1;
            border-radius: 12px;
            padding: 10px;
            font-size: 12px;
            color: #2f3743;
            background: #fbfcfe;
        }
        .checkout-summary {
            border-top: 1px dashed #d6dde6;
            padding-top: 8px;
            display: grid;
            gap: 5px;
        }
        .checkout-total {
            font-size: 14px;
            font-weight: 800;
            color: #111;
        }
        .checkout-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end;
            padding: 0 16px 14px;
        }
        .checkout-btn {
            border: 1px solid #111;
            border-radius: 999px;
            padding: 9px 14px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }
        .checkout-btn.cancel {
            background: #fff;
            color: #111;
        }
        .checkout-btn.proceed {
            background: #111;
            color: #fff;
        }
        .admin-product-modal {
            position: fixed;
            inset: 0;
            background: rgba(17, 24, 39, 0.55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            z-index: 95;
        }
        .admin-product-modal.show {
            display: flex;
        }
        .admin-product-modal-card {
            width: min(560px, 100%);
            background: #fff;
            border-radius: 16px;
            border: 1px solid #dde3ea;
            box-shadow: 0 18px 38px rgba(17, 24, 39, 0.24);
            overflow: hidden;
        }
        .admin-product-modal-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        .admin-product-modal-title {
            margin: 0;
            font-size: 15px;
            font-weight: 800;
            color: #111827;
        }
        .admin-product-modal-body {
            padding: 14px;
        }
        .status-filter-btn {
            cursor: pointer;
        }
        .status-filter-btn.active {
            border-color: #111;
            background: #eef2f7;
        }
        .orders-note {
            margin: 0 0 8px;
            font-size: 11px;
            color: #5d6470;
            font-weight: 700;
        }
        .swal-popup {
            border-radius: 20px;
            padding: 1.1rem 1rem 1rem;
        }
        .swal-title {
            font-size: 20px;
            font-weight: 800;
        }
        .swal-confirm {
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 800;
        }
        .swal-deny {
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 800;
        }
        .swal-cancel {
            border-radius: 999px;
            padding: 10px 18px;
            font-weight: 800;
        }
        @media (max-width: 1024px) {
            .hero-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .products { grid-template-columns: repeat(3, minmax(160px, 1fr)); }
        }
        @media (max-width: 700px) {
            body { padding-bottom: 84px; }
            .top-nav { flex-direction: column; align-items: flex-start; gap: 10px; }
            .nav-links { display: none; }
            .products { grid-template-columns: repeat(2, minmax(140px, 1fr)); }
            .brand-logo { height: 66px; }
            .brand-wrap { width: 100%; }
            .shop-search { width: 100%; }
            .floating-categories { left: 0; width: 100%; }
            .hero { padding: 12px; }
            .hero-grid { gap: 10px; }
            .hero-panel,
            .right-bubble { padding: 10px; border-radius: 14px; }
            .hero-focus-image,
            .feature-image { height: 190px; }
            .feature-slider { min-height: 190px; }
            .preview-overlay {
                left: 10px;
                right: 10px;
                bottom: 10px;
                padding: 9px 10px;
            }
            .preview-kicker { font-size: 9px; }
            .preview-title { font-size: 13px; }
            .preview-sub { display: none; }
            .feature-slider-caption {
                left: 10px;
                right: 10px;
                bottom: 10px;
                padding: 8px 10px;
            }
            .feature-slider-caption p { font-size: 9px; }
            .feature-slider-caption strong { font-size: 12px; }
            .product-modal-body { grid-template-columns: 1fr; }
            .mobile-bottom-nav {
                position: fixed;
                left: 50%;
                bottom: 14px;
                transform: translateX(-50%);
                width: min(92%, 360px);
                border: 1px solid #d4d9e1;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.96);
                backdrop-filter: blur(6px);
                padding: 8px 12px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                z-index: 50;
                box-shadow: 0 12px 28px rgba(17, 24, 39, 0.2);
            }
            .cart-panel {
                width: 100%;
                right: -100%;
                border-left: 0;
                box-shadow: none;
            }
            .cart-panel.auth-mode {
                width: 100%;
                max-width: 100%;
                border-radius: 0;
            }
            .cart-panel.auth-mode .cart-head {
                padding-top: 16px;
            }
            .cart-panel.auth-mode .cart-guest-prompt.show {
                padding: 18px 16px 16px;
            }
        }
    </style>
</head>
<body>
    <div id="pageLoader" class="page-loader" aria-live="polite" aria-label="Loading page">
        <div class="page-loader-inner">
            <img class="page-loader-logo" src="/images/logo-cutout.png?v={{ time() }}" alt="Preloved Picks logo">
            <p class="page-loader-tagline">Wear Your Confidence</p>
        </div>
    </div>
    @php
        $dailyImages = [
            '/images/products/hero-slide-1.jpg',
            '/images/products/hero-slide-2.jpg',
            '/images/products/hero-slide-3.jpg',
            '/images/products/hero-slide-1.jpg',
            '/images/products/hero-slide-2.jpg',
        ];
        $daySeed = (int) date('z');
    @endphp
    <main class="shell">
        <header class="top-nav bubble">
            <div class="brand-wrap">
                <img class="brand-logo" src="/images/logo-cutout.png?v={{ time() }}" alt="Preloved Picks logo">
                <input
                    id="productSearch"
                    class="shop-search"
                    type="search"
                    placeholder="Search products (e.g. jacket, size M, plaid)"
                    aria-label="Search products"
                >
                <div id="floatingCategories" class="floating-categories" aria-label="Quick categories">
                    <button class="floating-chip" type="button" data-category="Men">Men</button>
                    <button class="floating-chip" type="button" data-category="Women">Women</button>
                    <button class="floating-chip" type="button" data-category="Kids">Kids</button>
                    <button class="floating-chip" type="button" data-category="Teens">Teens</button>
                </div>
            </div>
            <nav class="nav-links">
                @guest
                    <a class="desktop-nav-pill" href="{{ url('/') }}">Home</a>
                    <a class="desktop-nav-pill cart-pill" href="#" id="desktopCartTrigger">Cart <span id="desktopCartBadge" class="cart-badge" style="display:none;">0</span></a>
                    <a class="admin-add-trigger" href="#" aria-label="Add Product" id="desktopAddProductTrigger" style="display:none;">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
                    </a>
                    <a class="desktop-nav-pill" href="#" id="desktopSignInTrigger">Sign In</a>
                @else
                    <a class="desktop-nav-pill" href="{{ url('/') }}">Home</a>
                    <a class="desktop-nav-pill cart-pill" href="#" id="desktopCartTrigger">Cart <span id="desktopCartBadge" class="cart-badge" style="display:none;">0</span></a>
                    <a class="admin-add-trigger" href="#" aria-label="Add Product" id="desktopAddProductTrigger" style="display:none;">
                        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
                    </a>
                    <a class="desktop-nav-pill" href="#" id="desktopProfileTrigger">Profile</a>
                @endguest
            </nav>
        </header>

        <section class="hero bubble">
            <div class="hero-grid">
                <div class="hero-panel">
                    <img class="hero-focus-image" src="/images/products/hero-left.jpg?v={{ date('Ymd') }}" alt="Featured style category">
                    <div class="preview-overlay">
                        <p class="preview-kicker">Curated Daily Look</p>
                        <p class="preview-title">Streetwear Mood, Elevated</p>
                        <p class="preview-sub">Fresh preloved picks styled for everyday confidence.</p>
                    </div>
                </div>
                <div class="right-bubble">
                    <div class="feature-slider" id="featureSlider">
                        <img class="feature-image active" src="{{ $dailyImages[$daySeed % count($dailyImages)] }}?v={{ date('Ymd') }}" alt="Featured slide 1">
                        <img class="feature-image" src="{{ $dailyImages[($daySeed + 1) % count($dailyImages)] }}?v={{ date('Ymd') }}" alt="Featured slide 2">
                        <img class="feature-image" src="{{ $dailyImages[($daySeed + 2) % count($dailyImages)] }}?v={{ date('Ymd') }}" alt="Featured slide 3">
                        <div class="feature-slider-caption">
                            <p>Style Story</p>
                            <strong>Preloved pieces, modern vibe.</strong>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bubble">
            <div class="section-head">
                <span>Categories</span>
                <span id="activeFilterHeading">Top Picks</span>
            </div>
            <div class="top-filter-bar">
                <button class="top-filter active" type="button" data-filter="all">All</button>
                <button class="top-filter" type="button" data-filter="sale">Sale</button>
                <button class="top-filter" type="button" data-filter="top-picks">Top Picks</button>
                <button class="top-filter" type="button" data-filter="fresh-drop">Fresh Drop</button>
                <button class="top-filter" type="button" data-filter="most-viewed">Most Viewed</button>
            </div>
            <section class="products">
                <article class="product" data-search-text="washed black spider print tee male shirt streetwear" data-groups="top-picks fresh-drop" data-price="₱179" data-description="Washed black oversized tee with spider chest print. Soft cotton feel and relaxed everyday fit." data-image="/images/products/male-shirt-1.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/male-shirt-1.png" alt="Washed Black Spider Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Washed Black Spider Tee</p>
                            <p class="product-price">₱179</p>
                            <p class="meta">Male Shirt · Size L</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="gray anime graphic tee male shirt oversized" data-groups="sale most-viewed" data-price="₱129" data-description="Gray oversized graphic shirt with bold anime-style print. Lightweight and breathable fabric." data-image="/images/products/male-shirt-2.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/male-shirt-2.png" alt="Gray Anime Graphic Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Gray Anime Graphic Tee</p>
                            <p class="product-price">₱129</p>
                            <p class="meta">Male Shirt · Size XL</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="white gojo print tee male shirt cotton" data-groups="top-picks most-viewed" data-price="₱159" data-description="White character-print tee with clean contrast graphics. Smooth cotton blend for daily use." data-image="/images/products/male-shirt-3.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/male-shirt-3.png" alt="White Character Print Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">White Character Print Tee</p>
                            <p class="product-price">₱159</p>
                            <p class="meta">Male Shirt · Size M</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="black intense graphic tee male shirt street fashion" data-groups="sale fresh-drop" data-price="₱199" data-description="Black statement tee with full graphic back design. Premium print and modern oversized cut." data-image="/images/products/male-shirt-4.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/male-shirt-4.png" alt="Black Intense Graphic Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Black Intense Graphic Tee</p>
                            <p class="product-price">₱199</p>
                            <p class="meta">Male Shirt · Size L</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="black signature print tee male shirt basic fit" data-groups="most-viewed" data-price="₱99" data-description="Minimal black tee with centered signature print. Comfortable basic style that pairs with any outfit." data-image="/images/products/male-shirt-5.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/male-shirt-5.png" alt="Black Signature Print Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Black Signature Print Tee</p>
                            <p class="product-price">₱99</p>
                            <p class="meta">Male Shirt · Size M</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="kids bluey blue shirt kids tee cartoon" data-groups="fresh-drop most-viewed" data-price="₱139" data-description="Bright blue kids tee with playful Bluey graphic. Soft fabric and comfy fit for active days." data-image="/images/products/kids-shirt-1.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/kids-shirt-1.png" alt="Kids Bluey Graphic Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Kids Bluey Graphic Tee</p>
                            <p class="product-price">₱139</p>
                            <p class="meta">Kids Shirt · Size 6-7Y</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="kids yellow stripe shirt kids tee casual" data-groups="sale top-picks" data-price="₱89" data-description="Yellow striped kids shirt with clean minimalist style. Lightweight cotton for everyday comfort." data-image="/images/products/kids-shirt-2.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/kids-shirt-2.png" alt="Kids Yellow Stripe Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Kids Yellow Stripe Tee</p>
                            <p class="product-price">₱89</p>
                            <p class="meta">Kids Shirt · Size 5-6Y</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="kids sunrise adventure print shirt kids tee brown" data-groups="top-picks most-viewed" data-price="₱169" data-description="Adventure-themed kids tee with colorful vehicle prints. Durable stitching for playtime wear." data-image="/images/products/kids-shirt-3.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/kids-shirt-3.png" alt="Kids Sunrise Adventure Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Kids Sunrise Adventure Tee</p>
                            <p class="product-price">₱169</p>
                            <p class="meta">Kids Shirt · Size 7-8Y</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="kids kitty white shirt kids tee cute print" data-groups="sale fresh-drop" data-price="₱119" data-description="White kids tee with bold kitty print and soft texture. Easy-to-style design for daily use." data-image="/images/products/kids-shirt-4.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/kids-shirt-4.png" alt="Kids Kitty Print Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Kids Kitty Print Tee</p>
                            <p class="product-price">₱119</p>
                            <p class="meta">Kids Shirt · Size 6-7Y</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="kids cat face white shirt kids tee playful" data-groups="most-viewed" data-price="₱149" data-description="Cute cat-face print shirt for kids with a loose comfy silhouette and breathable material." data-image="/images/products/kids-shirt-5.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/kids-shirt-5.png" alt="Kids Cat Face Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Kids Cat Face Tee</p>
                            <p class="product-price">₱149</p>
                            <p class="meta">Kids Shirt · Size 7-8Y</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="women navy halter ruched top body fit" data-groups="top-picks fresh-drop" data-price="₱189" data-description="Elegant navy ruched halter top with flattering silhouette and soft stretch feel." data-image="/images/products/women-shirt-1.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/women-shirt-1.png" alt="Navy Ruched Halter Top"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Navy Ruched Halter Top</p>
                            <p class="product-price">₱189</p>
                            <p class="meta">Women Top · Size M</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="women brown polo slim fit top casual" data-groups="sale most-viewed" data-price="₱99" data-description="Classic brown polo top with clean collar detail for polished casual styling." data-image="/images/products/women-shirt-2.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/women-shirt-2.png" alt="Brown Polo Slim Top"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Brown Polo Slim Top</p>
                            <p class="product-price">₱99</p>
                            <p class="meta">Women Top · Size S</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="women sweetheart white crop tee graphic" data-groups="fresh-drop most-viewed" data-price="₱129" data-description="White crop tee with sweetheart print, lightweight cotton and comfy everyday cut." data-image="/images/products/women-shirt-3.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/women-shirt-3.png" alt="Sweetheart Crop Tee"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Sweetheart Crop Tee</p>
                            <p class="product-price">₱129</p>
                            <p class="meta">Women Top · Size M</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="women pink floral mesh long sleeve tie front" data-groups="top-picks sale" data-price="₱179" data-description="Pink floral mesh tie-front top with long sleeves and a soft romantic look." data-image="/images/products/women-shirt-4.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/women-shirt-4.png" alt="Pink Floral Tie Top"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Pink Floral Tie Top</p>
                            <p class="product-price">₱179</p>
                            <p class="meta">Women Top · Size S</p>
                        </div>
                    </button>
                </article>
                <article class="product" data-search-text="women anime print layered white black top" data-groups="most-viewed" data-price="₱159" data-description="Layered white-and-black anime print top with edgy details and streetwear vibe." data-image="/images/products/women-shirt-5.png">
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="/images/products/women-shirt-5.png" alt="Anime Layered Top"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">Anime Layered Top</p>
                            <p class="product-price">₱159</p>
                            <p class="meta">Women Top · Size M</p>
                        </div>
                    </button>
                </article>
            </section>
            <p id="emptySearchState" class="empty-state">No products match your search.</p>
        </section>
    </main>

    <div id="productModal" class="product-modal" aria-hidden="true">
        <div class="product-modal-card">
            <button id="productModalClose" class="product-modal-close" type="button" aria-label="Close modal">×</button>
            <div class="product-modal-body">
                <div id="modalImageWrap" class="product-modal-image-wrap">
                    <button id="modalPrevImageBtn" class="product-modal-image-nav prev" type="button" aria-label="Previous product photo">‹</button>
                    <img id="modalImage" class="product-modal-image" src="" alt="Selected product">
                    <button id="modalNextImageBtn" class="product-modal-image-nav next" type="button" aria-label="Next product photo">›</button>
                    <p id="modalImageCount" class="product-modal-image-count"></p>
                </div>
                <div>
                    <p id="modalTitle" class="product-modal-title">Product</p>
                    <p id="modalPrice" class="product-modal-price">₱0</p>
                    <p id="modalMeta" class="meta"></p>
                    <p id="modalDescription" class="product-modal-description"></p>
                    <div class="qty-wrap">
                        <button id="qtyMinus" class="qty-btn" type="button" aria-label="Decrease quantity">-</button>
                        <span id="qtyValue" class="qty-value">1</span>
                        <button id="qtyPlus" class="qty-btn" type="button" aria-label="Increase quantity">+</button>
                    </div>
                    <div class="modal-actions">
                        <button id="addToCartBtn" class="modal-btn add" type="button">Add to Cart</button>
                        <button id="buyNowBtn" class="modal-btn buy" type="button">Buy Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="mobile-bottom-nav" aria-label="Mobile bottom navigation">
        <a class="mobile-bottom-link" href="{{ url('/') }}" aria-label="Home">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 11.5L12 4l9 7.5"></path><path d="M5 10.5V20h14v-9.5"></path></svg>
        </a>
        <a class="mobile-bottom-link cart-pill" href="#" aria-label="Cart" id="mobileCartTrigger">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="19" r="1.3"></circle><circle cx="17" cy="19" r="1.3"></circle><path d="M3 4h2l2.2 10.2a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H7"></path></svg>
            <span id="mobileCartBadge" class="cart-badge" style="display:none;">0</span>
        </a>
        <a class="mobile-bottom-link mobile-admin-add" href="#" aria-label="Add Product" id="mobileAddProductTrigger">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14"></path><path d="M5 12h14"></path></svg>
        </a>
        @guest
            <a class="mobile-signin-word" href="#" aria-label="Sign In" id="mobileSignInTrigger">
                Sign In
            </a>
        @else
            <a class="mobile-signin-word" href="#" aria-label="Profile" id="mobileSignInTrigger">
                Profile
            </a>
        @endguest
    </nav>

    <div id="cartOverlay" class="cart-overlay" aria-hidden="true"></div>
    <aside id="cartPanel" class="cart-panel" aria-hidden="true">
        <div class="cart-head">
            <p class="cart-title">Your Cart</p>
            <button id="cartCloseBtn" class="cart-close" type="button" aria-label="Close cart">×</button>
        </div>
        <div id="cartItems" class="cart-items">
            <p class="cart-empty">Your cart is empty.</p>
        </div>
        <div class="cart-foot">
            <p id="cartTotal" class="cart-total">Total: ₱0</p>
            <button id="cartCheckoutBtn" class="cart-checkout" type="button">Checkout</button>
        </div>
        <div id="guestCartPrompt" class="cart-guest-prompt" aria-live="polite">
            <div class="auth-logo-wrap">
                <img class="auth-logo" src="/images/logo-cutout.png?v={{ time() }}" alt="Preloved Picks logo">
            </div>
            <p class="cart-guest-title">Unlock checkout access</p>
            <p class="cart-guest-text">Please sign in or create an account to review your selected items and complete your order.</p>

            <div class="auth-switch">
                <button id="showSignInPanelBtn" class="auth-switch-btn active" type="button">Sign In</button>
                <button id="showSignUpPanelBtn" class="auth-switch-btn" type="button">Sign Up</button>
            </div>

            <div id="signInPanel" class="auth-panel show">
                <form id="shopSignInForm" class="cart-guest-form" method="POST" action="#">
                    @csrf
                    <input class="cart-guest-input" type="email" name="email" placeholder="Username or Email" required>
                    <input class="cart-guest-input" type="password" name="password" placeholder="Password" required>
                    <button class="cart-guest-submit" type="submit">Sign In</button>
                </form>
                
            </div>

            <div id="signUpPanel" class="auth-panel">
                <form id="sidebarSignUpForm" class="cart-guest-form" action="#" method="POST">
                    <input class="cart-guest-input" type="text" name="name" placeholder="Full Name" required>
                    <input class="cart-guest-input" type="email" name="email" placeholder="Email Address" required>
                    <input class="cart-guest-input" type="password" name="password" placeholder="Password" required>
                    <button class="cart-guest-submit" type="submit">Create Account</button>
                </form>
                <p class="auth-helper">
                    Returning customer?
                    <span id="switchToSignInInline" class="auth-inline-link">Open Sign In</span>
                </p>
            </div>
        </div>
        <div id="profilePanel" class="profile-panel" aria-live="polite">
            <article id="profileHeaderCard" class="profile-card">
                <p id="profileName" class="profile-name">Profile</p>
                <p id="profileEmail" class="profile-email">customer@example.com</p>
            </article>
            <article id="profileOrdersSummaryCard" class="profile-card">
                <p class="profile-label">My Orders</p>
                <div class="order-status-grid">
                    <div class="order-status-item status-filter-btn" id="toShipStatusBtn">
                        <p class="order-status-icon">🚚</p>
                        <p class="order-status-title">To Ship</p>
                        <p id="toShipCount" class="order-status-count">0</p>
                    </div>
                    <div class="order-status-item status-filter-btn" id="toReceiveStatusBtn">
                        <p class="order-status-icon">📦</p>
                        <p class="order-status-title">To Receive</p>
                        <p id="toReceiveCount" class="order-status-count">0</p>
                    </div>
                    <div class="order-status-item status-filter-btn" id="receivedStatusBtn">
                        <p class="order-status-icon">✅</p>
                        <p class="order-status-title">Order Received</p>
                        <p id="receivedCount" class="order-status-count">0</p>
                    </div>
                </div>
            </article>
            <article id="profileAddressCard" class="profile-card">
                <p class="profile-label">Addresses</p>
                <ul id="profileAddressList" class="profile-address-list"></ul>
                <form id="addAddressForm" class="address-form">
                    <input id="newAddressInput" class="address-input" type="text" placeholder="Add shipping address" required>
                    <button class="address-btn" type="submit">Save</button>
                </form>
            </article>
            <article id="profileOrderListCard" class="profile-card">
                <p class="profile-label">My Order List</p>
                <p id="ordersNote" class="orders-note">Showing all orders.</p>
                <div id="userOrdersList" class="orders-list"></div>
            </article>
            <article id="adminProductCard" class="profile-card" style="display:none;">
                <p class="profile-label">Admin Tools <span class="admin-badge">ADMIN</span></p>
                <form id="adminAddProductForm" class="admin-form-grid">
                    <input class="admin-input" type="file" id="adminProductImage" name="images[]" accept="image/*" multiple>
                    <label class="admin-input" style="display:flex; align-items:center; gap:8px; font-size:13px;">
                        <input type="checkbox" id="adminRemoveBgCheckbox">
                        Remove background from selected photos
                    </label>
                    <div id="adminAddImagePreview" class="admin-image-preview-list"></div>
                    <input class="admin-input" type="text" id="adminProductName" name="name" placeholder="Product name" required>
                    <textarea class="admin-textarea" id="adminProductDescription" name="description" placeholder="Description" required></textarea>
                    <input class="admin-input" type="text" id="adminProductSize" name="size" placeholder="Size (e.g. M, XL, 6-7Y)" required>
                    <input class="admin-input" type="number" id="adminProductPrice" name="price" placeholder="Price" min="1" required>
                    <select class="admin-select" id="adminProductCategory" name="category" required>
                        <option value="">Select category</option>
                        <option value="Male">Male</option>
                        <option value="Women">Women</option>
                        <option value="Kids">Kids</option>
                        <option value="Teens">Teens</option>
                    </select>
                    <button class="admin-submit" type="submit">Add Product</button>
                </form>
            </article>
            <article id="adminOrderCard" class="profile-card" style="display:none;">
                <p class="profile-label">Customer Orders</p>
                <div class="admin-status-grid">
                    <button id="adminToShipBtn" class="admin-status-btn active" type="button">
                        <p class="admin-status-icon">🚚</p>
                        <p class="admin-status-label">To Ship</p>
                        <p id="adminToShipCount" class="admin-status-count">0</p>
                    </button>
                    <button id="adminToReceiveBtn" class="admin-status-btn" type="button">
                        <p class="admin-status-icon">📦</p>
                        <p class="admin-status-label">To Receive</p>
                        <p id="adminToReceiveCount" class="admin-status-count">0</p>
                    </button>
                    <button id="adminReceivedBtn" class="admin-status-btn" type="button">
                        <p class="admin-status-icon">✅</p>
                        <p class="admin-status-label">Received</p>
                        <p id="adminReceivedCount" class="admin-status-count">0</p>
                    </button>
                </div>
                <div id="adminOrdersList" class="orders-list"></div>
            </article>
            <button id="profileLogoutBtn" class="logout-btn" type="button">Logout</button>
        </div>
    </aside>

    <div id="orderActionModal" class="order-action-modal" aria-hidden="true">
        <div class="order-action-card">
            <div class="order-action-head">
                <p id="orderActionTitle" class="order-action-title">Confirm action</p>
                <button id="orderActionCloseBtn" class="cart-close" type="button" aria-label="Close order action">×</button>
            </div>
            <div class="order-action-body">
                <p id="orderActionItem">Item:</p>
                <p id="orderActionInfo">Status:</p>
            </div>
            <div class="order-action-footer">
                <button id="orderActionCancelBtn" class="order-action-btn cancel" type="button">Cancel</button>
                <button id="orderActionConfirmBtn" class="order-action-btn confirm" type="button">Confirm</button>
            </div>
        </div>
    </div>

    <div id="checkoutModal" class="checkout-modal" aria-hidden="true">
        <div class="checkout-modal-card">
            <div class="checkout-head">
                <p class="checkout-title">Checkout Summary</p>
                <button id="checkoutCloseBtn" class="cart-close" type="button" aria-label="Close checkout">×</button>
            </div>
            <div class="checkout-body">
                <div id="checkoutItems" class="checkout-lines"></div>
                <div class="checkout-address">
                    <strong>Ship to:</strong>
                    <p id="checkoutAddress" style="margin:6px 0 0;">No address selected.</p>
                </div>
                <div class="checkout-summary">
                    <div class="checkout-line"><span>Items Total</span><strong id="checkoutItemsTotal">₱0</strong></div>
                    <div class="checkout-line"><span>Shipping Fee</span><strong id="checkoutShippingFee">₱100</strong></div>
                    <div class="checkout-line checkout-total"><span>Total to Pay</span><strong id="checkoutGrandTotal">₱100</strong></div>
                </div>
            </div>
            <div class="checkout-actions">
                <button id="checkoutCancelBtn" class="checkout-btn cancel" type="button">Cancel</button>
                <button id="checkoutProceedBtn" class="checkout-btn proceed" type="button">Proceed to Checkout</button>
            </div>
        </div>
    </div>

    <div id="adminProductModal" class="admin-product-modal" aria-hidden="true">
        <div class="admin-product-modal-card">
            <div class="admin-product-modal-head">
                <p class="admin-product-modal-title">Add Product <span class="admin-badge">ADMIN</span></p>
                <button id="adminProductModalClose" class="cart-close" type="button" aria-label="Close add product">×</button>
            </div>
            <div class="admin-product-modal-body">
                <form id="adminQuickAddProductForm" class="admin-form-grid">
                    <input class="admin-input" type="file" id="quickAdminProductImage" name="images[]" accept="image/*" multiple>
                    <label class="admin-input" style="display:flex; align-items:center; gap:8px; font-size:13px;">
                        <input type="checkbox" id="quickAdminRemoveBgCheckbox">
                        Remove background from selected photos
                    </label>
                    <div id="adminQuickAddImagePreview" class="admin-image-preview-list"></div>
                    <input class="admin-input" type="text" id="quickAdminProductName" name="name" placeholder="Product name" required>
                    <textarea class="admin-textarea" id="quickAdminProductDescription" name="description" placeholder="Description" required></textarea>
                    <input class="admin-input" type="text" id="quickAdminProductSize" name="size" placeholder="Size (e.g. M, XL, 6-7Y)" required>
                    <input class="admin-input" type="number" id="quickAdminProductPrice" name="price" placeholder="Price" min="1" required>
                    <select class="admin-select" id="quickAdminProductCategory" name="category" required>
                        <option value="">Select category</option>
                        <option value="Male">Male</option>
                        <option value="Women">Women</option>
                        <option value="Kids">Kids</option>
                        <option value="Teens">Teens</option>
                    </select>
                    <button class="admin-submit" type="submit">Add Product</button>
                </form>
            </div>
        </div>
    </div>

    <div id="adminEditProductModal" class="admin-product-modal" aria-hidden="true">
        <div class="admin-product-modal-card">
            <div class="admin-product-modal-head">
                <p class="admin-product-modal-title">Edit Product <span class="admin-badge">ADMIN</span></p>
                <button id="adminEditProductModalClose" class="cart-close" type="button" aria-label="Close edit product">×</button>
            </div>
            <div class="admin-product-modal-body">
                <form id="adminEditProductForm" class="admin-form-grid">
                    <input class="admin-input" type="file" id="adminEditProductImage" name="images[]" accept="image/*" multiple>
                    <div id="adminEditImagePreview" class="admin-image-preview-list"></div>
                    <input class="admin-input" type="text" id="adminEditProductName" name="name" placeholder="Product name" required>
                    <textarea class="admin-textarea" id="adminEditProductDescription" name="description" placeholder="Description" required></textarea>
                    <input class="admin-input" type="text" id="adminEditProductSize" name="size" placeholder="Size (e.g. M, XL, 6-7Y)" required>
                    <input class="admin-input" type="number" id="adminEditProductPrice" name="price" placeholder="Price" min="1" required>
                    <select class="admin-select" id="adminEditProductCategory" name="category" required>
                        <option value="">Select category</option>
                        <option value="Male">Male</option>
                        <option value="Women">Women</option>
                        <option value="Kids">Kids</option>
                        <option value="Teens">Teens</option>
                    </select>
                    <button class="admin-submit" type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (() => {
            const pageLoader = document.getElementById("pageLoader");
            const loaderShownAt = Date.now();
            if (!pageLoader) return;
            pageLoader.classList.remove("hidden");

            const navEntries = typeof performance.getEntriesByType === "function"
                ? performance.getEntriesByType("navigation")
                : [];
            const legacyType = performance?.navigation?.type;
            const isReload = (Array.isArray(navEntries) && navEntries[0]?.type === "reload")
                || legacyType === 1;
            const minLoaderMs = isReload ? 1800 : 1300;

            const hideLoader = () => {
                const elapsed = Date.now() - loaderShownAt;
                const waitMs = Math.max(0, minLoaderMs - elapsed);
                setTimeout(() => {
                    pageLoader.classList.add("hidden");
                }, waitMs);
            };

            if (document.readyState === "complete") {
                hideLoader();
            } else {
                window.addEventListener("load", hideLoader, { once: true });
            }

            window.addEventListener("beforeunload", () => {
                pageLoader.classList.remove("hidden");
            });

            window.addEventListener("pageshow", () => {
                pageLoader.classList.add("hidden");
            });

            document.addEventListener("click", (event) => {
                const link = event.target.closest("a[href]");
                if (!link) return;
                const href = link.getAttribute("href") ?? "";
                if (!href || href.startsWith("#") || href.startsWith("javascript:")) return;
                if (link.target === "_blank" || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
                pageLoader.classList.remove("hidden");
            });
        })();

        (() => {
            const slides = Array.from(document.querySelectorAll("#featureSlider .feature-image"));
            if (!slides.length) return;
            let idx = 0;
            setInterval(() => {
                slides[idx].classList.remove("active");
                idx = (idx + 1) % slides.length;
                slides[idx].classList.add("active");
            }, 2800);
        })();

        (() => {
            const isGuestUser = @json(auth()->guest());
            const searchInput = document.getElementById("productSearch");
            const products = Array.from(document.querySelectorAll(".products .product"));
            const productsContainer = document.querySelector(".products");
            const emptyState = document.getElementById("emptySearchState");
            const floatingCategories = document.getElementById("floatingCategories");
            const floatingChips = Array.from(document.querySelectorAll(".floating-chip"));
            const topFilters = Array.from(document.querySelectorAll(".top-filter"));
            const activeFilterHeading = document.getElementById("activeFilterHeading");
            const productTriggers = Array.from(document.querySelectorAll(".product-trigger"));
            const heroFocusImage = document.querySelector(".hero-focus-image");
            const sliderImages = Array.from(document.querySelectorAll("#featureSlider .feature-image"));
            const productModal = document.getElementById("productModal");
            const productModalClose = document.getElementById("productModalClose");
            const modalImageWrap = document.getElementById("modalImageWrap");
            const modalImage = document.getElementById("modalImage");
            const modalPrevImageBtn = document.getElementById("modalPrevImageBtn");
            const modalNextImageBtn = document.getElementById("modalNextImageBtn");
            const modalImageCount = document.getElementById("modalImageCount");
            const modalTitle = document.getElementById("modalTitle");
            const modalPrice = document.getElementById("modalPrice");
            const modalMeta = document.getElementById("modalMeta");
            const modalDescription = document.getElementById("modalDescription");
            const qtyValue = document.getElementById("qtyValue");
            const qtyMinus = document.getElementById("qtyMinus");
            const qtyPlus = document.getElementById("qtyPlus");
            const addToCartBtn = document.getElementById("addToCartBtn");
            const buyNowBtn = document.getElementById("buyNowBtn");
            const desktopCartTrigger = document.getElementById("desktopCartTrigger");
            const mobileCartTrigger = document.getElementById("mobileCartTrigger");
            const desktopAddProductTrigger = document.getElementById("desktopAddProductTrigger");
            const mobileAddProductTrigger = document.getElementById("mobileAddProductTrigger");
            const cartOverlay = document.getElementById("cartOverlay");
            const cartPanel = document.getElementById("cartPanel");
            const cartCloseBtn = document.getElementById("cartCloseBtn");
            const cartItems = document.getElementById("cartItems");
            const cartTotal = document.getElementById("cartTotal");
            const cartCheckoutBtn = document.getElementById("cartCheckoutBtn");
            const cartTitle = document.querySelector(".cart-title");
            const desktopCartBadge = document.getElementById("desktopCartBadge");
            const mobileCartBadge = document.getElementById("mobileCartBadge");
            const guestCartPrompt = document.getElementById("guestCartPrompt");
            const desktopSignInTrigger = document.getElementById("desktopSignInTrigger");
            const mobileSignInTrigger = document.getElementById("mobileSignInTrigger");
            const showSignInPanelBtn = document.getElementById("showSignInPanelBtn");
            const showSignUpPanelBtn = document.getElementById("showSignUpPanelBtn");
            const signInPanel = document.getElementById("signInPanel");
            const signUpPanel = document.getElementById("signUpPanel");
            const signInForm = document.getElementById("shopSignInForm");
            const switchToSignUpInline = document.getElementById("switchToSignUpInline");
            const switchToSignInInline = document.getElementById("switchToSignInInline");
            const sidebarSignUpForm = document.getElementById("sidebarSignUpForm");
            const desktopProfileTrigger = document.getElementById("desktopProfileTrigger");
            const profilePanel = document.getElementById("profilePanel");
            const profileName = document.getElementById("profileName");
            const profileEmail = document.getElementById("profileEmail");
            const profileOrdersSummaryCard = document.getElementById("profileOrdersSummaryCard");
            const profileAddressCard = document.getElementById("profileAddressCard");
            const profileOrderListCard = document.getElementById("profileOrderListCard");
            const profileAddressList = document.getElementById("profileAddressList");
            const addAddressForm = document.getElementById("addAddressForm");
            const newAddressInput = document.getElementById("newAddressInput");
            const toShipStatusBtn = document.getElementById("toShipStatusBtn");
            const toReceiveStatusBtn = document.getElementById("toReceiveStatusBtn");
            const receivedStatusBtn = document.getElementById("receivedStatusBtn");
            const ordersNote = document.getElementById("ordersNote");
            const toShipCount = document.getElementById("toShipCount");
            const toReceiveCount = document.getElementById("toReceiveCount");
            const receivedCount = document.getElementById("receivedCount");
            const userOrdersList = document.getElementById("userOrdersList");
            const adminProductCard = document.getElementById("adminProductCard");
            const adminOrderCard = document.getElementById("adminOrderCard");
            const adminOrdersList = document.getElementById("adminOrdersList");
            const adminToShipBtn = document.getElementById("adminToShipBtn");
            const adminToReceiveBtn = document.getElementById("adminToReceiveBtn");
            const adminReceivedBtn = document.getElementById("adminReceivedBtn");
            const adminToShipCount = document.getElementById("adminToShipCount");
            const adminToReceiveCount = document.getElementById("adminToReceiveCount");
            const adminReceivedCount = document.getElementById("adminReceivedCount");
            const adminAddProductForm = document.getElementById("adminAddProductForm");
            const adminProductModal = document.getElementById("adminProductModal");
            const adminProductModalClose = document.getElementById("adminProductModalClose");
            const adminQuickAddProductForm = document.getElementById("adminQuickAddProductForm");
            const adminProductImage = document.getElementById("adminProductImage");
            const quickAdminProductImage = document.getElementById("quickAdminProductImage");
            const adminRemoveBgCheckbox = document.getElementById("adminRemoveBgCheckbox");
            const quickAdminRemoveBgCheckbox = document.getElementById("quickAdminRemoveBgCheckbox");
            const adminAddImagePreview = document.getElementById("adminAddImagePreview");
            const adminQuickAddImagePreview = document.getElementById("adminQuickAddImagePreview");
            const adminEditProductModal = document.getElementById("adminEditProductModal");
            const adminEditProductModalClose = document.getElementById("adminEditProductModalClose");
            const adminEditProductForm = document.getElementById("adminEditProductForm");
            const adminEditProductImage = document.getElementById("adminEditProductImage");
            const adminEditImagePreview = document.getElementById("adminEditImagePreview");
            const adminEditProductName = document.getElementById("adminEditProductName");
            const adminEditProductDescription = document.getElementById("adminEditProductDescription");
            const adminEditProductSize = document.getElementById("adminEditProductSize");
            const adminEditProductPrice = document.getElementById("adminEditProductPrice");
            const adminEditProductCategory = document.getElementById("adminEditProductCategory");
            const profileLogoutBtn = document.getElementById("profileLogoutBtn");
            const orderActionModal = document.getElementById("orderActionModal");
            const orderActionTitle = document.getElementById("orderActionTitle");
            const orderActionItem = document.getElementById("orderActionItem");
            const orderActionInfo = document.getElementById("orderActionInfo");
            const orderActionCloseBtn = document.getElementById("orderActionCloseBtn");
            const orderActionCancelBtn = document.getElementById("orderActionCancelBtn");
            const orderActionConfirmBtn = document.getElementById("orderActionConfirmBtn");
            const checkoutModal = document.getElementById("checkoutModal");
            const checkoutCloseBtn = document.getElementById("checkoutCloseBtn");
            const checkoutCancelBtn = document.getElementById("checkoutCancelBtn");
            const checkoutProceedBtn = document.getElementById("checkoutProceedBtn");
            const checkoutItems = document.getElementById("checkoutItems");
            const checkoutAddress = document.getElementById("checkoutAddress");
            const checkoutItemsTotal = document.getElementById("checkoutItemsTotal");
            const checkoutShippingFee = document.getElementById("checkoutShippingFee");
            const checkoutGrandTotal = document.getElementById("checkoutGrandTotal");

            const hasSearchState = !!emptyState;
            products.forEach((product) => appendProductBlurb(product));
            const normalizeProductKey = (name, description) => `${String(name ?? "").trim().toLowerCase()}::${String(description ?? "").trim().toLowerCase()}`;
            products.forEach((product) => {
                if (!product.dataset.images) {
                    const baseImage = product.dataset.image
                        || product.querySelector(".thumb img")?.getAttribute("src")
                        || "";
                    const list = baseImage ? [baseImage] : [];
                    product.dataset.images = JSON.stringify(list);
                    if (!product.dataset.image && list[0]) product.dataset.image = list[0];
                }
            });
            let activeFilter = "all";
            const selectedQuickCategories = new Set();
            let currentQty = 1;
            let activeProductTitle = "";
            let activeProductImages = [];
            let activeProductImageIndex = 0;
            let orderViewFilter = "all";
            const cartStore = new Map();
            const fixedShippingFee = 100;
            let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ?? "";
            let adminOrderFilter = "to_ship";
            let pendingOrderAction = null;
            let checkoutMode = "cart";
            let checkoutSingleItem = null;
            const initialPanel = new URLSearchParams(window.location.search).get("panel");
            let editingProductCard = null;
            let editModalExistingImages = [];
            let editModalRemovedImages = [];
            let editModalNewImages = [];
            let adminAddSelectedImages = [];
            let adminQuickAddSelectedImages = [];
            const adminProductOrderStorageKey = "shop_admin_product_order_v1";
            let adminProductOrder = [];
            let activeDraggedProduct = null;
            let productDragContainerBound = false;
            @php
                $initialProfile = null;
                if (auth()->check()) {
                    $initialProfile = [
                        'id' => auth()->user()->id,
                        'name' => auth()->user()->name,
                        'email' => auth()->user()->email,
                        'is_admin' => (bool) auth()->user()->is_admin,
                        'addresses' => [
                            'Home - Blk 12 Lot 8, San Isidro, Quezon City',
                            'Office - Unit 305, Nova Plaza, Quezon City',
                        ],
                        'orders' => ['to_ship' => 0, 'to_receive' => 0, 'received' => 0],
                    ];
                }
            @endphp
            let profileState = @json($initialProfile);
            let userOrdersState = [];
            let adminOrdersState = [];
            let adminPanelMode = "profile";

            const api = async (url, options = {}) => {
                const response = await fetch(url, {
                    ...options,
                    credentials: "same-origin",
                    headers: {
                        "X-CSRF-TOKEN": csrfToken,
                        "Accept": "application/json",
                        ...(options.headers ?? {}),
                    },
                });
                const data = await response.json().catch(() => ({}));
                if (!response.ok) {
                    throw new Error(data.message || "Request failed.");
                }
                return data;
            };

            const hasActiveProfile = () => !!profileState;
            const isAdminUser = () => {
                if (!profileState) return false;
                if (profileState.is_admin) return true;
                const email = String(profileState.email ?? "").toLowerCase();
                return email.includes("admin");
            };
            const placeholderAddressText = "add your first shipping address.";
            const getValidAddresses = () => {
                const addresses = Array.isArray(profileState?.addresses) ? profileState.addresses : [];
                return addresses
                    .map((line) => String(line ?? "").trim())
                    .filter((line) => line && line.toLowerCase() !== placeholderAddressText);
            };
            const hasValidAddress = () => getValidAddresses().length > 0;

            const updateAuthTriggers = () => {
                const profileMode = hasActiveProfile();
                const adminMode = profileMode && isAdminUser();
                document.body.classList.toggle("admin-mode", adminMode);
                if (desktopSignInTrigger) desktopSignInTrigger.textContent = profileMode ? "Profile" : "Sign In";
                if (mobileSignInTrigger) mobileSignInTrigger.textContent = profileMode ? "Profile" : "Sign In";
                if (desktopCartTrigger?.childNodes?.length) {
                    desktopCartTrigger.childNodes[0].nodeValue = isAdminUser() ? "Manage Orders " : "Cart ";
                }
                if (mobileCartTrigger) {
                    mobileCartTrigger.setAttribute("aria-label", isAdminUser() ? "Manage Orders" : "Cart");
                }
                if (desktopAddProductTrigger) desktopAddProductTrigger.style.display = adminMode ? "inline-flex" : "none";
                if (mobileAddProductTrigger) mobileAddProductTrigger.style.display = adminMode ? "inline-flex" : "none";
                refreshProductDraggingState();
            };

            const getProductOrderKey = (product) => {
                if (!product) return "";
                const productId = String(product.dataset.productId ?? "").trim();
                if (productId) return `db:${productId}`;
                const name = product.querySelector(".product-title")?.textContent ?? "";
                const description = product.dataset.description ?? "";
                return `static:${normalizeProductKey(name, description)}`;
            };

            const getProductOrderKeyFromPayload = (productData) => {
                const productId = String(productData?.id ?? "").trim();
                if (productId) return `db:${productId}`;
                return `static:${normalizeProductKey(productData?.name ?? "", productData?.description ?? "")}`;
            };

            const syncProductArrayOrderFromDom = () => {
                if (!productsContainer) return;
                const domProducts = Array.from(productsContainer.querySelectorAll(".product"));
                products.length = 0;
                products.push(...domProducts);
            };

            const loadSavedAdminProductOrder = () => {
                try {
                    const raw = localStorage.getItem(adminProductOrderStorageKey);
                    const parsed = JSON.parse(raw ?? "[]");
                    return Array.isArray(parsed)
                        ? parsed.map((entry) => String(entry ?? "").trim()).filter(Boolean)
                        : [];
                } catch {
                    return [];
                }
            };

            const saveAdminProductOrder = () => {
                if (!isAdminUser() || !productsContainer) return;
                const orderedKeys = Array.from(productsContainer.querySelectorAll(".product"))
                    .map((product) => getProductOrderKey(product))
                    .filter(Boolean);
                adminProductOrder = orderedKeys;
                try {
                    localStorage.setItem(adminProductOrderStorageKey, JSON.stringify(orderedKeys));
                } catch {
                    // Ignore storage write errors.
                }
            };

            const applySavedAdminProductOrder = () => {
                if (!productsContainer || !adminProductOrder.length) return;
                const cardsByKey = new Map();
                Array.from(productsContainer.querySelectorAll(".product")).forEach((product) => {
                    const key = getProductOrderKey(product);
                    if (key) cardsByKey.set(key, product);
                });
                const orderedCards = [];
                adminProductOrder.forEach((key) => {
                    const card = cardsByKey.get(key);
                    if (card) {
                        orderedCards.push(card);
                        cardsByKey.delete(key);
                    }
                });
                cardsByKey.forEach((card) => orderedCards.push(card));
                orderedCards.forEach((card) => productsContainer.appendChild(card));
                syncProductArrayOrderFromDom();
            };

            const resolveDropTarget = (container, pointerX, pointerY) => {
                const candidates = Array.from(container.querySelectorAll(".product:not(.is-dragging)"))
                    .filter((card) => card.style.display !== "none");
                if (!candidates.length) return null;

                let closest = null;
                let closestDistance = Number.POSITIVE_INFINITY;
                candidates.forEach((card) => {
                    const rect = card.getBoundingClientRect();
                    const centerX = rect.left + rect.width / 2;
                    const centerY = rect.top + rect.height / 2;
                    const distance = Math.hypot(pointerX - centerX, pointerY - centerY);
                    if (distance < closestDistance) {
                        closestDistance = distance;
                        closest = { card, centerX, centerY };
                    }
                });

                if (!closest) return null;
                const insertBefore = pointerY < closest.centerY || (Math.abs(pointerY - closest.centerY) < 18 && pointerX < closest.centerX);
                return insertBefore ? closest.card : closest.card.nextElementSibling;
            };

            const bindDragForProductCard = (product) => {
                if (!product || product.dataset.dragBound === "1") return;
                product.dataset.dragBound = "1";
                product.addEventListener("dragstart", (event) => {
                    if (!isAdminUser() || !product.draggable) {
                        event.preventDefault();
                        return;
                    }
                    if (event.target?.closest?.(".product-edit-btn, .product-delete-btn")) {
                        event.preventDefault();
                        return;
                    }
                    if (product.dataset.dragReady !== "1") {
                        event.preventDefault();
                        return;
                    }
                    activeDraggedProduct = product;
                    product.classList.add("is-dragging");
                    if (event.dataTransfer) {
                        event.dataTransfer.effectAllowed = "move";
                        event.dataTransfer.setData("text/plain", getProductOrderKey(product));
                    }
                });
                product.addEventListener("dragend", () => {
                    product.dataset.dragReady = "0";
                    if (!product.classList.contains("is-dragging")) return;
                    product.classList.remove("is-dragging");
                    activeDraggedProduct = null;
                    syncProductArrayOrderFromDom();
                    saveAdminProductOrder();
                    applyFilters();
                });
            };

            const bindProductDragContainer = () => {
                if (!productsContainer || productDragContainerBound) return;
                productDragContainerBound = true;
                productsContainer.addEventListener("dragover", (event) => {
                    if (!isAdminUser() || !activeDraggedProduct) return;
                    event.preventDefault();
                    const nextTarget = resolveDropTarget(productsContainer, event.clientX, event.clientY);
                    if (!nextTarget) {
                        productsContainer.appendChild(activeDraggedProduct);
                        return;
                    }
                    if (nextTarget !== activeDraggedProduct) {
                        productsContainer.insertBefore(activeDraggedProduct, nextTarget);
                    }
                });
                productsContainer.addEventListener("drop", (event) => {
                    if (!isAdminUser() || !activeDraggedProduct) return;
                    event.preventDefault();
                    syncProductArrayOrderFromDom();
                    saveAdminProductOrder();
                    applyFilters();
                });
            };

            const refreshProductDraggingState = () => {
                const adminMode = isAdminUser();
                products.forEach((product) => {
                    product.draggable = adminMode;
                    bindDragForProductCard(product);
                });
                if (adminMode) {
                    bindProductDragContainer();
                }
            };

            adminProductOrder = loadSavedAdminProductOrder();

            const ensureAdminProductActionButtons = () => {
                products.forEach((product, index) => {
                    const title = product.querySelector(".product-title")?.textContent?.trim() ?? `Product ${index + 1}`;
                    if (!product.querySelector(".product-drag-btn")) {
                        const dragBtn = document.createElement("button");
                        dragBtn.type = "button";
                        dragBtn.className = "product-drag-btn";
                        dragBtn.setAttribute("aria-label", `Drag ${title}`);
                        dragBtn.setAttribute("title", "Drag to reorder");
                        dragBtn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5h.01M8 12h.01M8 19h.01M16 5h.01M16 12h.01M16 19h.01"></path></svg>';
                        dragBtn.addEventListener("mousedown", () => {
                            product.dataset.dragReady = "1";
                        });
                        dragBtn.addEventListener("touchstart", () => {
                            product.dataset.dragReady = "1";
                        }, { passive: true });
                        dragBtn.addEventListener("mouseup", () => {
                            setTimeout(() => { product.dataset.dragReady = "0"; }, 0);
                        });
                        dragBtn.addEventListener("mouseleave", () => {
                            if (!product.classList.contains("is-dragging")) product.dataset.dragReady = "0";
                        });
                        product.appendChild(dragBtn);
                    }
                    if (!product.querySelector(".product-edit-btn")) {
                        const editBtn = document.createElement("button");
                        editBtn.type = "button";
                        editBtn.className = "product-edit-btn";
                        editBtn.setAttribute("data-edit-product", "1");
                        editBtn.setAttribute("aria-label", `Edit ${title}`);
                        editBtn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 20h9"></path><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z"></path></svg>';
                        product.appendChild(editBtn);
                    }
                    if (!product.querySelector(".product-delete-btn")) {
                        const deleteBtn = document.createElement("button");
                        deleteBtn.type = "button";
                        deleteBtn.className = "product-delete-btn";
                        deleteBtn.setAttribute("data-delete-product", "1");
                        deleteBtn.setAttribute("aria-label", `Delete ${title}`);
                        deleteBtn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 6h18"></path><path d="M8 6V4h8v2"></path><path d="M6 6l1 14h10l1-14"></path><path d="M10 10v7"></path><path d="M14 10v7"></path></svg>';
                        product.appendChild(deleteBtn);
                    }
                });
            };

            const getProductImages = (product) => {
                if (!product) return [];
                try {
                    const parsed = JSON.parse(product.dataset.images ?? "[]");
                    if (Array.isArray(parsed) && parsed.length) {
                        return parsed.map((img) => String(img ?? "").trim()).filter(Boolean);
                    }
                } catch {
                    // Fallback to legacy single image field.
                }
                const fallback = String(product.dataset.image ?? "").trim();
                return fallback ? [fallback] : [];
            };

            const setProductImages = (product, imageList) => {
                if (!product) return;
                const cleaned = (Array.isArray(imageList) ? imageList : [])
                    .map((img) => String(img ?? "").trim())
                    .filter(Boolean);
                product.dataset.images = JSON.stringify(cleaned);
                product.dataset.image = cleaned[0] ?? "";
                const imageEl = product.querySelector(".thumb img");
                if (imageEl && cleaned[0]) {
                    imageEl.src = cleaned[0];
                }
            };

            const renderEditImagePreview = () => {
                if (!adminEditImagePreview) return;
                const existingTiles = editModalExistingImages
                    .filter((img) => !editModalRemovedImages.includes(img))
                    .map((img) => `
                        <div class="admin-image-tile">
                            <img src="${img}" alt="Existing product photo">
                            <button class="admin-image-remove" type="button" data-remove-existing-image="${img}" aria-label="Remove photo">×</button>
                        </div>
                    `);
                const newTiles = editModalNewImages.map((img, index) => `
                    <div class="admin-image-tile">
                        <img src="${img.preview}" alt="New product photo">
                        <button class="admin-image-remove" type="button" data-remove-new-image="${index}" aria-label="Remove new photo">×</button>
                    </div>
                `);
                adminEditImagePreview.innerHTML = [...existingTiles, ...newTiles].join("");
            };

            const renderUploadPreview = (previewEl, selectedImages) => {
                if (!previewEl) return;
                previewEl.innerHTML = selectedImages.map((item, index) => `
                    <div class="admin-image-tile">
                        <img src="${item.preview}" alt="Selected product photo ${index + 1}">
                        <button class="admin-image-remove" type="button" data-remove-upload-image="${index}" aria-label="Remove photo">×</button>
                    </div>
                `).join("");
            };

            const removeBackgroundFromFile = async (file) => {
                const bitmap = await createImageBitmap(file);
                const canvas = document.createElement("canvas");
                canvas.width = bitmap.width;
                canvas.height = bitmap.height;
                const ctx = canvas.getContext("2d");
                if (!ctx) return file;
                ctx.drawImage(bitmap, 0, 0);
                const img = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const data = img.data;
                const sample = (x, y) => {
                    const idx = (y * canvas.width + x) * 4;
                    return [data[idx], data[idx + 1], data[idx + 2]];
                };
                const [r0, g0, b0] = sample(0, 0);
                const threshold = 55;
                for (let i = 0; i < data.length; i += 4) {
                    const dr = Math.abs(data[i] - r0);
                    const dg = Math.abs(data[i + 1] - g0);
                    const db = Math.abs(data[i + 2] - b0);
                    if (dr + dg + db < threshold) data[i + 3] = 0;
                }
                ctx.putImageData(img, 0, 0);
                const blob = await new Promise((resolve) => canvas.toBlob(resolve, "image/png"));
                if (!blob) return file;
                return new File([blob], `${file.name.replace(/\.[^.]+$/, "")}-nobg.png`, { type: "image/png" });
            };

            const appendSelectedFiles = async (targetList, files, removeBg) => {
                for (const file of files) {
                    let finalFile = file;
                    let bgRemoved = false;
                    if (removeBg) {
                        try {
                            finalFile = await removeBackgroundFromFile(file);
                            bgRemoved = finalFile !== file;
                        } catch {
                            finalFile = file;
                        }
                    }
                    targetList.push({
                        file: finalFile,
                        preview: URL.createObjectURL(finalFile),
                        bgRemoved,
                    });
                }
            };

            const applyRemoveBgToSelected = async (targetList, previewEl) => {
                if (!Array.isArray(targetList) || !targetList.length) return;
                let processedCount = 0;
                for (let i = 0; i < targetList.length; i += 1) {
                    const item = targetList[i];
                    if (!item?.file || item.bgRemoved) continue;
                    try {
                        const processedFile = await removeBackgroundFromFile(item.file);
                        if (processedFile && processedFile !== item.file) {
                            if (item.preview) URL.revokeObjectURL(item.preview);
                            targetList[i] = {
                                ...item,
                                file: processedFile,
                                preview: URL.createObjectURL(processedFile),
                                bgRemoved: true,
                            };
                            processedCount += 1;
                        }
                    } catch {
                        // Keep original file when processing fails.
                    }
                }
                renderUploadPreview(previewEl, targetList);
                if (processedCount > 0) {
                    showActionAlert("success", "Background removed", `${processedCount} photo(s) processed.`);
                }
            };

            function appendProductBlurb(product) {
                const info = product.querySelector(".product-info");
                const sourceDescription = (product.dataset.description ?? "").trim();
                if (!info || !sourceDescription || info.querySelector(".product-blurb")) return;
                const shortDescription = sourceDescription.length > 96
                    ? `${sourceDescription.slice(0, 93).trim()}...`
                    : sourceDescription;
                const blurb = document.createElement("p");
                blurb.className = "product-blurb";
                blurb.textContent = shortDescription;
                info.appendChild(blurb);
            }

            const updateAdminOrderBadge = () => {
                if (!isAdminUser()) return;
                const pendingCount = adminOrdersState.filter((order) => order.status !== "received").length;
                [desktopCartBadge, mobileCartBadge].forEach((badge) => {
                    if (!badge) return;
                    badge.textContent = String(pendingCount);
                    badge.style.display = pendingCount > 0 ? "inline-flex" : "none";
                });
            };

            const statusLabel = (status) => {
                if (status === "to_ship") return "To Ship";
                if (status === "to_receive") return "To Receive";
                if (status === "received") return "Order Received";
                if (status === "cancelled") return "Cancelled";
                return status;
            };

            const renderUserOrders = () => {
                if (!userOrdersList) return;
                const filteredOrders = orderViewFilter === "all"
                    ? userOrdersState
                    : userOrdersState.filter((order) => order.status === orderViewFilter);

                if (!filteredOrders.length) {
                    if (orderViewFilter === "to_ship") {
                        userOrdersList.innerHTML = '<p class="order-row-meta">No waiting orders in To Ship.</p>';
                    } else {
                        userOrdersList.innerHTML = '<p class="order-row-meta">No orders yet.</p>';
                    }
                    if (ordersNote) {
                        ordersNote.textContent = orderViewFilter === "to_ship"
                            ? "Showing To Ship orders (waiting for seller confirmation)."
                            : "Showing all orders.";
                    }
                    [toShipStatusBtn, toReceiveStatusBtn, receivedStatusBtn].forEach((btn) => btn?.classList.remove("active"));
                    if (orderViewFilter === "to_ship") toShipStatusBtn?.classList.add("active");
                    if (orderViewFilter === "to_receive") toReceiveStatusBtn?.classList.add("active");
                    if (orderViewFilter === "received") receivedStatusBtn?.classList.add("active");
                    return;
                }
                userOrdersList.innerHTML = filteredOrders.map((order) => `
                    <div class="order-row">
                        <p class="order-row-title">${order.item?.name ?? "Item"}</p>
                        <p class="order-row-meta">Status: ${statusLabel(order.status)}</p>
                        ${order.status === "to_ship" ? '<p class="order-row-meta">Waiting for seller confirmation.</p>' : ""}
                        ${order.status === "to_ship" ? `<button class="order-row-action secondary" type="button" data-cancel-order="${order.id}">Cancel Order</button>` : ""}
                        ${order.status === "to_receive" ? `<button class="order-row-action" type="button" data-receive-order="${order.id}">Receive Order</button>` : ""}
                        ${order.status === "cancelled" || order.status === "received" ? `<button class="order-row-icon-btn" type="button" data-delete-order="${order.id}" aria-label="Delete order"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7h16"></path><path d="M9 7V5h6v2"></path><path d="M8 7l1 12h6l1-12"></path></svg></button>` : ""}
                    </div>
                `).join("");
                if (ordersNote) {
                    ordersNote.textContent = orderViewFilter === "to_ship"
                        ? "Showing To Ship orders (waiting for seller confirmation)."
                        : "Showing all orders.";
                }
                [toShipStatusBtn, toReceiveStatusBtn, receivedStatusBtn].forEach((btn) => btn?.classList.remove("active"));
                if (orderViewFilter === "to_ship") toShipStatusBtn?.classList.add("active");
                if (orderViewFilter === "to_receive") toReceiveStatusBtn?.classList.add("active");
                if (orderViewFilter === "received") receivedStatusBtn?.classList.add("active");
            };

            const renderAdminOrders = () => {
                if (!adminOrdersList) return;
                const toShip = adminOrdersState.filter((order) => order.status === "to_ship").length;
                const toReceive = adminOrdersState.filter((order) => order.status === "to_receive").length;
                const received = adminOrdersState.filter((order) => order.status === "received").length;
                if (adminToShipCount) adminToShipCount.textContent = String(toShip);
                if (adminToReceiveCount) adminToReceiveCount.textContent = String(toReceive);
                if (adminReceivedCount) adminReceivedCount.textContent = String(received);
                [adminToShipBtn, adminToReceiveBtn, adminReceivedBtn].forEach((btn) => btn?.classList.remove("active"));
                if (adminOrderFilter === "to_ship") adminToShipBtn?.classList.add("active");
                if (adminOrderFilter === "to_receive") adminToReceiveBtn?.classList.add("active");
                if (adminOrderFilter === "received") adminReceivedBtn?.classList.add("active");

                const filtered = adminOrdersState.filter((order) => order.status === adminOrderFilter);
                if (!filtered.length) {
                    adminOrdersList.innerHTML = '<p class="order-row-meta">No customer orders yet.</p>';
                    return;
                }
                adminOrdersList.innerHTML = filtered.map((order) => {
                    const nextAction = order.status === "to_ship"
                        ? `<button class="order-row-action" type="button" data-admin-order="${order.id}" data-next-status="to_receive" data-order-item="${order.item?.name ?? "Item"}" data-order-user="${order.user?.name ?? "User"}">Confirm To Receive</button>`
                        : "";
                    return `
                        <div class="order-row">
                            <p class="order-row-title">${order.user?.name ?? "User"} - ${order.item?.name ?? "Item"}</p>
                            <p class="order-row-meta">${order.user?.email ?? ""} | ${statusLabel(order.status)}</p>
                            <p class="order-row-meta">Address: ${order.shipping_address ?? "No address provided"}</p>
                            ${nextAction}
                        </div>
                    `;
                }).join("");
            };

            const renderProfilePanel = () => {
                const profile = profileState;
                if (!profile) return;
                const resolvedName = (profile.name && String(profile.name).trim())
                    ? String(profile.name).trim()
                    : ((profile.email && String(profile.email).includes("@"))
                        ? String(profile.email).split("@")[0]
                        : "Admin");
                if (profileName) {
                    profileName.textContent = profile.is_admin
                        ? `Admin: ${resolvedName}`
                        : resolvedName;
                }
                if (profileEmail) profileEmail.textContent = profile.email || "customer@example.com";
                if (profilePanel) profilePanel.classList.toggle("admin-view", !!profile.is_admin);
                if (toShipCount) toShipCount.textContent = String(profile.orders?.to_ship ?? 0);
                if (toReceiveCount) toReceiveCount.textContent = String(profile.orders?.to_receive ?? 0);
                if (receivedCount) receivedCount.textContent = String(profile.orders?.received ?? 0);
                if (profileAddressList) {
                    const addresses = Array.isArray(profile.addresses) && profile.addresses.length
                        ? profile.addresses
                        : ["Add your first shipping address."];
                    profileAddressList.innerHTML = addresses.map((line) => `<li>${line}</li>`).join("");
                }
                if (profile.is_admin) {
                    if (profileEmail) profileEmail.style.display = "none";
                    if (profileOrdersSummaryCard) profileOrdersSummaryCard.style.display = "none";
                    if (profileAddressCard) profileAddressCard.style.display = "none";
                    if (profileOrderListCard) profileOrderListCard.style.setProperty("display", "none", "important");
                    if (ordersNote) ordersNote.textContent = "";
                    if (adminProductCard) adminProductCard.style.display = "none";
                    if (adminOrderCard) adminOrderCard.style.display = adminPanelMode === "orders" ? "" : "none";
                    if (profileLogoutBtn) profileLogoutBtn.style.display = adminPanelMode === "orders" ? "none" : "";
                } else {
                    if (profileEmail) profileEmail.style.display = "";
                    if (profileOrdersSummaryCard) profileOrdersSummaryCard.style.display = "";
                    if (profileAddressCard) profileAddressCard.style.display = "";
                    if (profileOrderListCard) profileOrderListCard.style.removeProperty("display");
                    if (adminProductCard) adminProductCard.style.display = "none";
                    if (adminOrderCard) adminOrderCard.style.display = "none";
                    if (profileLogoutBtn) profileLogoutBtn.style.display = "";
                    renderUserOrders();
                }
                renderAdminOrders();
                updateAdminOrderBadge();
            };

            const buildSearchableEntry = (product) => {
                const title = product.querySelector(".product-title")?.textContent ?? "";
                const meta = product.querySelector(".meta")?.textContent ?? "";
                const seed = product.dataset.searchText ?? "";
                const groups = (product.dataset.groups ?? "").toLowerCase().split(/\s+/).filter(Boolean);
                return {
                    element: product,
                    text: `${title} ${meta} ${seed}`.toLowerCase(),
                    groups,
                };
            };

            const searchableProducts = products.map((product) => buildSearchableEntry(product));

            const addProductCardToPreview = (productData) => {
                if (!productsContainer) return;
                const productId = productData?.id ?? null;
                if (productId && products.some((entry) => String(entry.dataset.productId ?? "") === String(productId))) {
                    return;
                }
                const name = (productData?.name ?? "New Product").trim();
                const description = (productData?.description ?? "Freshly added product.").trim();
                const size = (productData?.size ?? "N/A").trim();
                const category = (productData?.category ?? "Item").trim();
                const dedupeKey = normalizeProductKey(name, description);
                const existingStatic = products.find((entry) => !entry.dataset.productId && normalizeProductKey(
                    entry.querySelector(".product-title")?.textContent ?? "",
                    entry.dataset.description ?? ""
                ) === dedupeKey);
                if (existingStatic) {
                    existingStatic.remove();
                    const index = products.indexOf(existingStatic);
                    if (index >= 0) products.splice(index, 1);
                }
                const images = Array.isArray(productData?.image_urls)
                    ? productData.image_urls.map((img) => String(img ?? "").trim()).filter(Boolean)
                    : [];
                const image = (images[0] ?? productData?.image_url ?? productData?.image ?? "/images/products/hero-left.jpg").trim();
                const amount = Number(productData?.price) || 0;
                const price = `₱${amount.toFixed(0)}`;
                const product = document.createElement("article");
                product.className = "product";
                product.dataset.dbProduct = "1";
                if (productId) product.dataset.productId = String(productId);
                product.dataset.searchText = `${name} ${category} ${size} ${description}`.toLowerCase();
                product.dataset.groups = "fresh-drop";
                product.dataset.price = price;
                product.dataset.description = description;
                product.dataset.images = JSON.stringify(images.length ? images : [image]);
                product.dataset.image = image;
                product.innerHTML = `
                    <button class="product-trigger" type="button">
                        <div class="thumb"><img src="${image}" alt="${name}"><span class="tag">NEW</span><span class="heart">♡</span></div>
                        <div class="product-info">
                            <p class="product-title">${name}</p>
                            <p class="product-price">${price}</p>
                            <p class="meta">${category} · Size ${size}</p>
                        </div>
                    </button>
                `;
                productsContainer.prepend(product);
                products.unshift(product);
                searchableProducts.unshift(buildSearchableEntry(product));
                appendProductBlurb(product);
                ensureAdminProductActionButtons();
                const trigger = product.querySelector(".product-trigger");
                if (trigger) {
                    trigger.addEventListener("click", () => {
                        const target = trigger.closest(".product");
                        if (target) openModal(target);
                    });
                }
                refreshProductDraggingState();
                applySavedAdminProductOrder();
                applyFilters();
                saveAdminProductOrder();
            };

            const removeDbProductCards = () => {
                const dbCards = products.filter((entry) => entry.dataset.dbProduct === "1");
                dbCards.forEach((card) => {
                    card.remove();
                    const index = products.indexOf(card);
                    if (index >= 0) products.splice(index, 1);
                });
            };

            const loadDatabaseProducts = async () => {
                try {
                    const response = await api("/shop/products");
                    const dbProducts = Array.isArray(response?.products) ? response.products : [];
                    const orderIndex = new Map(adminProductOrder.map((key, index) => [key, index]));
                    dbProducts.sort((a, b) => {
                        const aIndex = orderIndex.get(getProductOrderKeyFromPayload(a));
                        const bIndex = orderIndex.get(getProductOrderKeyFromPayload(b));
                        const safeA = Number.isFinite(aIndex) ? aIndex : Number.MAX_SAFE_INTEGER;
                        const safeB = Number.isFinite(bIndex) ? bIndex : Number.MAX_SAFE_INTEGER;
                        if (safeA !== safeB) return safeA - safeB;
                        return Number(b?.id ?? 0) - Number(a?.id ?? 0);
                    });
                    removeDbProductCards();
                    dbProducts.forEach((product) => addProductCardToPreview(product));
                    applySavedAdminProductOrder();
                    refreshProductDraggingState();
                } catch {
                    // Keep existing static products visible if API fetch fails.
                }
            };

            const categoryQueryMap = {
                men: ["men", "male"],
                women: ["women", "female"],
                kids: ["kids", "kid"],
                teens: ["teens", "teen"],
            };

            const escapeRegex = (value) => value.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");

            const matchesWholeWord = (text, term) => {
                const matcher = new RegExp(`\\b${escapeRegex(term)}\\b`, "i");
                return matcher.test(text);
            };

            const matchesSearchQuery = (query, text) => {
                if (!query) return true;

                const categoryTerms = categoryQueryMap[query];
                if (categoryTerms) {
                    return categoryTerms.some((term) => matchesWholeWord(text, term));
                }

                return text.includes(query);
            };

            const matchesSelectedQuickCategories = (text) => {
                if (!selectedQuickCategories.size) return true;
                return Array.from(selectedQuickCategories).some((category) => matchesSearchQuery(category, text));
            };

            const applyFilters = () => {
                const query = searchInput ? searchInput.value.trim().toLowerCase() : "";
                if (activeFilterHeading) {
                    const filterLabelMap = {
                        "all": "All",
                        "sale": "Sale",
                        "top-picks": "Top Picks",
                        "fresh-drop": "Fresh Drop",
                        "most-viewed": "Most Viewed",
                    };
                    activeFilterHeading.textContent = filterLabelMap[activeFilter] ?? "Top Picks";
                }
                let visibleCount = 0;
                const visibleProducts = [];

                searchableProducts.forEach(({ element, text, groups }) => {
                    if (!element.isConnected) return;
                    const searchMatch = matchesSearchQuery(query, text);
                    const quickCategoryMatch = matchesSelectedQuickCategories(text);
                    const categoryMatch = activeFilter === "all" || groups.includes(activeFilter);
                    const isMatch = searchMatch && quickCategoryMatch && categoryMatch;
                    element.style.display = isMatch ? "" : "none";
                    if (isMatch) {
                        visibleCount += 1;
                        visibleProducts.push(element);
                    }
                });

                if (hasSearchState) {
                    emptyState.style.display = visibleCount ? "none" : "block";
                }

                if (visibleProducts.length && heroFocusImage) {
                    const imagePool = visibleProducts
                        .map((product) => product.dataset.image)
                        .filter(Boolean);

                    if (imagePool.length) {
                        heroFocusImage.src = imagePool[0];
                        sliderImages.forEach((img, index) => {
                            img.src = imagePool[index % imagePool.length];
                        });
                    }
                }
            };

            if (searchInput) {
                searchInput.addEventListener("input", applyFilters);
            }

            topFilters.forEach((chip) => {
                chip.addEventListener("click", () => {
                    activeFilter = chip.dataset.filter ?? "all";
                    topFilters.forEach((button) => button.classList.remove("active"));
                    chip.classList.add("active");
                    applyFilters();
                });
            });

            if (floatingCategories && searchInput) {
                searchInput.addEventListener("focus", () => {
                    floatingCategories.classList.add("show");
                });

                document.addEventListener("click", (event) => {
                    if (event.target === searchInput || floatingCategories.contains(event.target)) return;
                    floatingCategories.classList.remove("show");
                });
            }

            floatingChips.forEach((chip) => {
                chip.addEventListener("click", () => {
                    const category = chip.dataset.category ?? "";
                    const normalizedCategory = category.trim().toLowerCase();
                    if (!normalizedCategory) return;
                    if (selectedQuickCategories.has(normalizedCategory)) {
                        selectedQuickCategories.delete(normalizedCategory);
                    } else {
                        selectedQuickCategories.add(normalizedCategory);
                    }
                    chip.classList.toggle("active", selectedQuickCategories.has(normalizedCategory));
                    applyFilters();
                    if (searchInput) searchInput.focus();
                });
            });

            const setQty = (nextQty) => {
                currentQty = Math.max(1, Math.min(99, nextQty));
                if (qtyValue) qtyValue.textContent = String(currentQty);
            };

            const openModal = (product) => {
                if (!productModal || !modalImage || !modalTitle || !modalPrice || !modalMeta || !modalDescription) return;
                const title = product.querySelector(".product-title")?.textContent?.trim() ?? "Product";
                const meta = product.querySelector(".meta")?.textContent?.trim() ?? "";
                const price = product.dataset.price ?? "₱0";
                const description = product.dataset.description ?? "Great preloved item.";
                activeProductImages = getProductImages(product);
                if (!activeProductImages.length && product.dataset.image) activeProductImages = [product.dataset.image];
                activeProductImageIndex = 0;
                const image = activeProductImages[activeProductImageIndex] ?? "";

                activeProductTitle = title;
                modalTitle.textContent = title;
                modalMeta.textContent = meta;
                modalPrice.textContent = price;
                modalDescription.textContent = description;
                modalImage.src = image;
                modalImage.alt = title;
                if (modalImageWrap) modalImageWrap.classList.toggle("has-multi", activeProductImages.length > 1);
                if (modalImageCount) {
                    modalImageCount.textContent = activeProductImages.length > 1
                        ? `${activeProductImageIndex + 1}/${activeProductImages.length}`
                        : "";
                }
                setQty(1);
                if (addToCartBtn) {
                    addToCartBtn.textContent = isAdminUser() ? "Manage Orders" : "Add to Cart";
                }
                if (buyNowBtn) {
                    buyNowBtn.style.display = isAdminUser() ? "none" : "";
                }

                productModal.classList.add("show");
                productModal.setAttribute("aria-hidden", "false");
            };

            const moveModalImage = (direction) => {
                if (!modalImage || !activeProductImages.length) return;
                const total = activeProductImages.length;
                activeProductImageIndex = (activeProductImageIndex + direction + total) % total;
                modalImage.src = activeProductImages[activeProductImageIndex] ?? "";
                if (modalImageCount) {
                    modalImageCount.textContent = total > 1 ? `${activeProductImageIndex + 1}/${total}` : "";
                }
            };

            const closeModal = () => {
                if (!productModal) return;
                productModal.classList.remove("show");
                productModal.setAttribute("aria-hidden", "true");
            };

            const openCheckoutModal = (singleItem = null) => {
                if (!checkoutModal || !checkoutItems || !checkoutItemsTotal || !checkoutShippingFee || !checkoutGrandTotal || !checkoutAddress) return;
                checkoutMode = singleItem ? "single" : "cart";
                checkoutSingleItem = singleItem;
                const items = singleItem ? [singleItem] : Array.from(cartStore.values());
                if (!items.length) {
                    showActionAlert("warning", "Cart is empty", "Add items first before checkout.");
                    return;
                }
                if (!hasValidAddress()) {
                    promptAddressSetup();
                    return;
                }

                const itemsTotal = items.reduce((sum, item) => sum + (item.amount * item.qty), 0);
                const grandTotal = itemsTotal + fixedShippingFee;
                checkoutItems.innerHTML = items.map((item) => `
                    <div class="checkout-line">
                        <span>${item.title} x${item.qty}</span>
                        <strong>₱${(item.amount * item.qty).toFixed(2)}</strong>
                    </div>
                `).join("");
                checkoutItemsTotal.textContent = `₱${itemsTotal.toFixed(2)}`;
                checkoutShippingFee.textContent = `₱${fixedShippingFee.toFixed(2)}`;
                checkoutGrandTotal.textContent = `₱${grandTotal.toFixed(2)}`;
                checkoutAddress.textContent = getValidAddresses()[0] ?? "No address selected. Please add one in Profile.";

                checkoutModal.classList.add("show");
                checkoutModal.setAttribute("aria-hidden", "false");
            };

            const closeCheckoutModal = () => {
                if (!checkoutModal) return;
                checkoutModal.classList.remove("show");
                checkoutModal.setAttribute("aria-hidden", "true");
            };

            const getPriceNumber = (priceString) => Number((priceString || "0").replace(/[^\d.]/g, "")) || 0;

            const renderCart = () => {
                if (!cartItems || !cartTotal) return;
                if (isAdminUser()) {
                    updateAdminOrderBadge();
                    return;
                }
                const items = Array.from(cartStore.values());
                const totalCount = items.reduce((sum, item) => sum + item.qty, 0);
                [desktopCartBadge, mobileCartBadge].forEach((badge) => {
                    if (!badge) return;
                    badge.textContent = String(totalCount);
                    badge.style.display = totalCount > 0 ? "inline-flex" : "none";
                });

                if (!items.length) {
                    cartItems.innerHTML = '<p class="cart-empty">Your cart is empty.</p>';
                    cartTotal.textContent = "Total: ₱0";
                    return;
                }

                cartItems.innerHTML = items.map((item) => `
                    <div class="cart-item">
                        <img src="${item.image}" alt="${item.title}">
                        <div>
                            <p class="cart-item-title">${item.title}</p>
                            <p class="cart-item-meta">Qty ${item.qty} · ${item.price}</p>
                        </div>
                        <button class="cart-item-remove" type="button" data-remove-key="${item.key}">Delete</button>
                    </div>
                `).join("");

                const total = items.reduce((sum, item) => sum + (item.amount * item.qty), 0);
                cartTotal.textContent = `Total: ₱${total}`;

                Array.from(cartItems.querySelectorAll("[data-remove-key]")).forEach((btn) => {
                    btn.addEventListener("click", () => {
                        cartStore.delete(btn.dataset.removeKey);
                        renderCart();
                    });
                });
            };

            const openCart = () => {
                closeModal();
                if (!cartOverlay || !cartPanel) return;
                cartPanel.classList.remove("auth-mode");
                cartOverlay.classList.add("show");
                cartPanel.classList.add("show");
                cartOverlay.setAttribute("aria-hidden", "false");
                cartPanel.setAttribute("aria-hidden", "false");
                if (cartTitle) cartTitle.textContent = "Your Cart";
                if (guestCartPrompt) guestCartPrompt.classList.remove("show");
                if (profilePanel) profilePanel.classList.remove("show");
                if (cartItems) cartItems.style.display = "";
                if (cartTotal) cartTotal.style.display = "";
                if (cartCheckoutBtn) cartCheckoutBtn.style.display = "";
            };

            const openGuestPurchasePrompt = () => {
                openCart();
                if (cartPanel) cartPanel.classList.add("auth-mode");
                if (cartTitle) cartTitle.textContent = "Sign In / Sign Up";
                if (guestCartPrompt) guestCartPrompt.classList.add("show");
                if (profilePanel) profilePanel.classList.remove("show");
                if (cartItems) cartItems.style.display = "none";
                if (cartTotal) cartTotal.style.display = "none";
                if (cartCheckoutBtn) cartCheckoutBtn.style.display = "none";
                const promptFirstInput = guestCartPrompt?.querySelector("input");
                if (promptFirstInput) {
                    setTimeout(() => promptFirstInput.focus(), 20);
                }
            };

            const openProfilePanel = () => {
                adminPanelMode = "profile";
                openCart();
                if (cartPanel) cartPanel.classList.add("auth-mode");
                if (cartTitle) cartTitle.textContent = "Profile";
                if (guestCartPrompt) guestCartPrompt.classList.remove("show");
                if (profilePanel) profilePanel.classList.add("show");
                if (profilePanel) profilePanel.classList.remove("manage-orders-mode");
                if (cartItems) cartItems.style.display = "none";
                if (cartTotal) cartTotal.style.display = "none";
                if (cartCheckoutBtn) cartCheckoutBtn.style.display = "none";
                renderProfilePanel();
            };

            const promptAddressSetup = (message = "Please add a shipping address in Profile first.") => {
                showActionAlert("warning", "Address required", message);
                closeCheckoutModal();
                openProfilePanel();
                if (newAddressInput) {
                    setTimeout(() => {
                        newAddressInput.scrollIntoView({ behavior: "smooth", block: "center" });
                        newAddressInput.focus();
                    }, 60);
                }
            };

            const openManageOrdersPanel = () => {
                adminPanelMode = "orders";
                openCart();
                if (cartPanel) cartPanel.classList.add("auth-mode");
                if (cartTitle) cartTitle.textContent = "Manage Orders";
                if (guestCartPrompt) guestCartPrompt.classList.remove("show");
                if (profilePanel) profilePanel.classList.add("show");
                if (profilePanel) profilePanel.classList.add("manage-orders-mode");
                if (cartItems) cartItems.style.display = "none";
                if (cartTotal) cartTotal.style.display = "none";
                if (cartCheckoutBtn) cartCheckoutBtn.style.display = "none";
                renderProfilePanel();
            };

            const openAdminProductModal = () => {
                if (!isAdminUser() || !adminProductModal) return;
                adminProductModal.classList.add("show");
                adminProductModal.setAttribute("aria-hidden", "false");
            };

            const closeAdminProductModal = () => {
                if (!adminProductModal) return;
                adminProductModal.classList.remove("show");
                adminProductModal.setAttribute("aria-hidden", "true");
            };

            const openEditProductModal = (card) => {
                if (!adminEditProductModal || !adminEditProductForm || !card) return;
                const titleEl = card.querySelector(".product-title");
                const priceEl = card.querySelector(".product-price");
                const metaEl = card.querySelector(".meta");
                const currentName = titleEl?.textContent?.trim() ?? "";
                const currentPrice = (priceEl?.textContent ?? "").replace(/[^\d.]/g, "");
                const metaText = metaEl?.textContent ?? "";
                const categoryMatch = metaText.match(/^(.*?)\s*·/);
                const sizeMatch = metaText.match(/Size\s*(.+)$/i);
                const currentCategory = categoryMatch?.[1]?.trim() ?? "";
                const currentSize = sizeMatch?.[1]?.trim() ?? "";
                const currentDescription = card.dataset.description ?? "";

                editingProductCard = card;
                editModalExistingImages = getProductImages(card);
                editModalRemovedImages = [];
                editModalNewImages = [];
                adminEditProductForm.reset();
                if (adminEditProductName) adminEditProductName.value = currentName;
                if (adminEditProductPrice) adminEditProductPrice.value = currentPrice;
                if (adminEditProductCategory) adminEditProductCategory.value = currentCategory;
                if (adminEditProductSize) adminEditProductSize.value = currentSize;
                if (adminEditProductDescription) adminEditProductDescription.value = currentDescription;
                if (adminEditProductImage) adminEditProductImage.value = "";
                renderEditImagePreview();

                adminEditProductModal.classList.add("show");
                adminEditProductModal.setAttribute("aria-hidden", "false");
            };

            const closeEditProductModal = () => {
                if (!adminEditProductModal) return;
                adminEditProductModal.classList.remove("show");
                adminEditProductModal.setAttribute("aria-hidden", "true");
                editingProductCard = null;
                editModalExistingImages = [];
                editModalRemovedImages = [];
                editModalNewImages = [];
                if (adminEditImagePreview) adminEditImagePreview.innerHTML = "";
                if (adminEditProductForm) adminEditProductForm.reset();
            };

            const loadProfileData = async () => {
                if (!hasActiveProfile()) return;
                try {
                    const profileRes = await api("/shop/profile");
                    profileState = profileRes.profile ?? profileState;
                    userOrdersState = profileState?.orders_list ?? [];
                    if (orderViewFilter !== "all" && !userOrdersState.some((order) => order.status === orderViewFilter)) {
                        orderViewFilter = "all";
                    }
                    if (profileState?.is_admin) {
                        const adminOrderRes = await api("/shop/admin/orders");
                        adminOrdersState = adminOrderRes.orders ?? [];
                        updateAdminOrderBadge();
                    } else {
                        adminOrdersState = [];
                    }
                    renderProfilePanel();
                } catch {
                    profileState = null;
                    userOrdersState = [];
                    adminOrdersState = [];
                    updateAuthTriggers();
                }
            };

            let liveSyncTimer = null;
            const startLiveSync = () => {
                if (liveSyncTimer) clearInterval(liveSyncTimer);
                liveSyncTimer = setInterval(() => {
                    if (!hasActiveProfile()) return;
                    loadProfileData();
                }, 5000);
            };
            const stopLiveSync = () => {
                if (!liveSyncTimer) return;
                clearInterval(liveSyncTimer);
                liveSyncTimer = null;
            };

            const showAuthPanel = (panel) => {
                const showSignIn = panel !== "signup";
                if (signInPanel) signInPanel.classList.toggle("show", showSignIn);
                if (signUpPanel) signUpPanel.classList.toggle("show", !showSignIn);
                if (showSignInPanelBtn) showSignInPanelBtn.classList.toggle("active", showSignIn);
                if (showSignUpPanelBtn) showSignUpPanelBtn.classList.toggle("active", !showSignIn);
                if (showSignIn) {
                    sidebarSignUpForm?.reset();
                } else {
                    signInForm?.reset();
                }
            };

            const closeCart = () => {
                if (!cartOverlay || !cartPanel) return;
                cartPanel.classList.remove("auth-mode");
                cartOverlay.classList.remove("show");
                cartPanel.classList.remove("show");
                cartOverlay.setAttribute("aria-hidden", "true");
                cartPanel.setAttribute("aria-hidden", "true");
                if (profilePanel) profilePanel.classList.remove("show");
            };

            productTriggers.forEach((trigger) => {
                trigger.addEventListener("click", () => {
                    const product = trigger.closest(".product");
                    if (product) openModal(product);
                });
            });

            if (productModalClose) productModalClose.addEventListener("click", closeModal);
            if (modalPrevImageBtn) {
                modalPrevImageBtn.addEventListener("click", () => moveModalImage(-1));
            }
            if (modalNextImageBtn) {
                modalNextImageBtn.addEventListener("click", () => moveModalImage(1));
            }
            if (productModal) {
                productModal.addEventListener("click", (event) => {
                    if (event.target === productModal) closeModal();
                });
            }
            document.addEventListener("keydown", (event) => {
                if (event.key === "Escape") {
                    closeModal();
                    closeAdminProductModal();
                    closeEditProductModal();
                }
            });

            if (qtyMinus) qtyMinus.addEventListener("click", () => setQty(currentQty - 1));
            if (qtyPlus) qtyPlus.addEventListener("click", () => setQty(currentQty + 1));
            ensureAdminProductActionButtons();
            loadDatabaseProducts();
            setInterval(loadDatabaseProducts, 5000);

            const showActionAlert = (icon, title, text) => {
                if (window.Swal) {
                    Swal.fire({
                        icon,
                        title,
                        text,
                        timer: 1700,
                        showConfirmButton: false,
                        customClass: {
                            popup: "swal-popup",
                            title: "swal-title",
                        },
                    });
                    return;
                }

                alert(text);
            };

            const submitAdminProductForm = async (formElement) => {
                if (!formElement) return false;
                const submitBtn = formElement.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn?.textContent ?? "Add Product";
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.textContent = "Adding Product...";
                }
                try {
                    const formData = new FormData(formElement);
                    const selectedImages = formElement === adminAddProductForm
                        ? adminAddSelectedImages
                        : adminQuickAddSelectedImages;
                    const shouldRemoveBg = formElement === adminAddProductForm
                        ? !!adminRemoveBgCheckbox?.checked
                        : !!quickAdminRemoveBgCheckbox?.checked;
                    formData.set("remove_bg", shouldRemoveBg ? "1" : "0");
                    if (shouldRemoveBg) {
                        if (formElement === adminAddProductForm) {
                            await applyRemoveBgToSelected(adminAddSelectedImages, adminAddImagePreview);
                        } else {
                            await applyRemoveBgToSelected(adminQuickAddSelectedImages, adminQuickAddImagePreview);
                        }
                    }
                    formData.delete("images[]");
                    selectedImages.forEach((item) => formData.append("images[]", item.file));
                    const files = selectedImages.map((item) => item.file).filter((file) => file instanceof File && file.size > 0);
                    if (!files.length) {
                        showActionAlert("error", "Missing photos", "Please select at least one image.");
                        return false;
                    }
                    const response = await api("/shop/products", {
                        method: "POST",
                        body: formData,
                    });
                    formElement.reset();
                    if (formElement === adminAddProductForm) {
                        adminAddSelectedImages.forEach((item) => URL.revokeObjectURL(item.preview));
                        adminAddSelectedImages = [];
                        if (adminAddImagePreview) adminAddImagePreview.innerHTML = "";
                    }
                    if (formElement === adminQuickAddProductForm) {
                        adminQuickAddSelectedImages.forEach((item) => URL.revokeObjectURL(item.preview));
                        adminQuickAddSelectedImages = [];
                        if (adminQuickAddImagePreview) adminQuickAddImagePreview.innerHTML = "";
                    }
                    addProductCardToPreview(response?.product ?? null);
                    showActionAlert("success", "Product added", "New product details were saved.");
                    return true;
                } catch (error) {
                    showActionAlert("error", "Unable to add product", error.message);
                    return false;
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalBtnText;
                    }
                }
            };

            const openOrderActionModal = (payload) => {
                pendingOrderAction = payload;
                if (orderActionTitle) orderActionTitle.textContent = payload.title;
                if (orderActionItem) orderActionItem.textContent = `Item: ${payload.itemName ?? "Item"}`;
                if (orderActionInfo) orderActionInfo.textContent = payload.info;
                if (orderActionConfirmBtn) orderActionConfirmBtn.textContent = payload.confirmText ?? "Confirm";
                if (orderActionModal) {
                    orderActionModal.classList.add("show");
                    orderActionModal.setAttribute("aria-hidden", "false");
                }
            };

            const closeOrderActionModal = () => {
                pendingOrderAction = null;
                if (orderActionModal) {
                    orderActionModal.classList.remove("show");
                    orderActionModal.setAttribute("aria-hidden", "true");
                }
            };

            if (addToCartBtn) {
                addToCartBtn.addEventListener("click", async () => {
                    if (isAdminUser()) {
                        closeModal();
                        openManageOrdersPanel();
                        await loadProfileData();
                        return;
                    }
                    if (!hasActiveProfile()) {
                        openGuestPurchasePrompt();
                        return;
                    }

                    const key = activeProductTitle.toLowerCase();
                    const currentItem = cartStore.get(key);
                    const priceText = modalPrice?.textContent ?? "₱0";
                    const amount = getPriceNumber(priceText);
                    const itemImage = modalImage?.src ?? "";
                    cartStore.set(key, {
                        key,
                        title: activeProductTitle,
                        qty: (currentItem?.qty ?? 0) + currentQty,
                        price: priceText,
                        amount,
                        image: itemImage,
                    });
                    renderCart();
                    closeModal();

                    if (window.Swal) {
                        await Swal.fire({
                            icon: "success",
                            title: "Added to cart",
                            text: `${activeProductTitle} (Qty: ${currentQty})`,
                            timer: 1300,
                            showConfirmButton: false,
                            customClass: {
                                popup: "swal-popup",
                                title: "swal-title",
                            },
                        });
                        return;
                    }

                    showActionAlert("success", "Added to cart", `${activeProductTitle} added to cart (Qty: ${currentQty}).`);
                });
            }

            if (buyNowBtn) {
                buyNowBtn.addEventListener("click", async () => {
                    if (isAdminUser()) return;
                    if (!hasActiveProfile()) {
                        openGuestPurchasePrompt();
                        return;
                    }
                    const singlePriceText = modalPrice?.textContent ?? "₱0";
                    const singleAmount = getPriceNumber(singlePriceText);
                    closeModal();
                    openCheckoutModal({
                        title: activeProductTitle,
                        qty: currentQty,
                        amount: singleAmount,
                    });
                });
            }
            if (toShipStatusBtn) {
                toShipStatusBtn.addEventListener("click", () => {
                    orderViewFilter = "to_ship";
                    renderUserOrders();
                });
            }
            if (toReceiveStatusBtn) {
                toReceiveStatusBtn.addEventListener("click", () => {
                    orderViewFilter = "to_receive";
                    renderUserOrders();
                });
            }
            if (receivedStatusBtn) {
                receivedStatusBtn.addEventListener("click", () => {
                    orderViewFilter = "received";
                    renderUserOrders();
                });
            }
            if (adminToShipBtn) adminToShipBtn.addEventListener("click", () => { adminOrderFilter = "to_ship"; renderAdminOrders(); });
            if (adminToReceiveBtn) adminToReceiveBtn.addEventListener("click", () => { adminOrderFilter = "to_receive"; renderAdminOrders(); });
            if (adminReceivedBtn) adminReceivedBtn.addEventListener("click", () => { adminOrderFilter = "received"; renderAdminOrders(); });

            if (desktopCartTrigger) {
                desktopCartTrigger.addEventListener("click", (event) => {
                    event.preventDefault();
                    if (isAdminUser()) {
                        openManageOrdersPanel();
                        loadProfileData();
                        return;
                    }
                    openCart();
                });
            }
            if (mobileCartTrigger) {
                mobileCartTrigger.addEventListener("click", (event) => {
                    event.preventDefault();
                    if (isAdminUser()) {
                        openManageOrdersPanel();
                        loadProfileData();
                        return;
                    }
                    openCart();
                });
            }
            if (desktopAddProductTrigger) {
                desktopAddProductTrigger.addEventListener("click", (event) => {
                    event.preventDefault();
                    if (!isAdminUser()) return;
                    openAdminProductModal();
                });
            }
            if (mobileAddProductTrigger) {
                mobileAddProductTrigger.addEventListener("click", (event) => {
                    event.preventDefault();
                    if (!isAdminUser()) return;
                    openAdminProductModal();
                });
            }
            if (desktopSignInTrigger) {
                desktopSignInTrigger.addEventListener("click", (event) => {
                    event.preventDefault();
                    if (hasActiveProfile()) {
                        openProfilePanel();
                        loadProfileData();
                        return;
                    }
                    openGuestPurchasePrompt();
                });
            }
            if (mobileSignInTrigger) {
                mobileSignInTrigger.addEventListener("click", (event) => {
                    event.preventDefault();
                    if (hasActiveProfile()) {
                        openProfilePanel();
                        loadProfileData();
                        return;
                    }
                    openGuestPurchasePrompt();
                });
            }
            if (desktopProfileTrigger) {
                desktopProfileTrigger.addEventListener("click", (event) => {
                    event.preventDefault();
                    openProfilePanel();
                    loadProfileData();
                });
            }
            if (showSignInPanelBtn) {
                showSignInPanelBtn.addEventListener("click", () => showAuthPanel("signin"));
            }
            if (showSignUpPanelBtn) {
                showSignUpPanelBtn.addEventListener("click", () => showAuthPanel("signup"));
            }
            if (switchToSignUpInline) {
                switchToSignUpInline.addEventListener("click", () => showAuthPanel("signup"));
            }
            if (switchToSignInInline) {
                switchToSignInInline.addEventListener("click", () => showAuthPanel("signin"));
            }
            if (sidebarSignUpForm) {
                sidebarSignUpForm.addEventListener("submit", async (event) => {
                    event.preventDefault();
                    const formData = new FormData(sidebarSignUpForm);
                    try {
                        const response = await api("/shop/sign-up", {
                            method: "POST",
                            body: formData,
                        });
                        if (response?.csrf_token) csrfToken = response.csrf_token;
                        profileState = response.profile ?? null;
                        sidebarSignUpForm.reset();
                        updateAuthTriggers();
                        openProfilePanel();
                        await loadProfileData();
                        showActionAlert("success", "Welcome", "Your account is ready. You are now in your profile.");
                    } catch (error) {
                        showActionAlert("error", "Sign up failed", error.message);
                    }
                });
            }
            if (signInForm) {
                signInForm.addEventListener("submit", async (event) => {
                    event.preventDefault();
                    const formData = new FormData(signInForm);
                    try {
                        const response = await api("/shop/sign-in", {
                            method: "POST",
                            body: formData,
                        });
                        if (response?.csrf_token) csrfToken = response.csrf_token;
                        profileState = response.profile ?? null;
                        signInForm.reset();
                        updateAuthTriggers();
                        openProfilePanel();
                        await loadProfileData();
                        showActionAlert("success", "Signed in", "Profile is now active.");
                    } catch (error) {
                        showActionAlert("error", "Sign in failed", error.message);
                    }
                });
            }
            if (adminAddProductForm) {
                adminAddProductForm.addEventListener("submit", async (event) => {
                    event.preventDefault();
                    await submitAdminProductForm(adminAddProductForm);
                });
            }
            if (adminQuickAddProductForm) {
                adminQuickAddProductForm.addEventListener("submit", async (event) => {
                    event.preventDefault();
                    const ok = await submitAdminProductForm(adminQuickAddProductForm);
                    if (ok) closeAdminProductModal();
                });
            }
            if (adminProductImage) {
                adminProductImage.addEventListener("change", async () => {
                    const nextFiles = Array.from(adminProductImage.files ?? []);
                    await appendSelectedFiles(adminAddSelectedImages, nextFiles, false);
                    if (adminRemoveBgCheckbox?.checked) {
                        await applyRemoveBgToSelected(adminAddSelectedImages, adminAddImagePreview);
                    }
                    adminProductImage.value = "";
                    renderUploadPreview(adminAddImagePreview, adminAddSelectedImages);
                });
            }
            if (quickAdminProductImage) {
                quickAdminProductImage.addEventListener("change", async () => {
                    const nextFiles = Array.from(quickAdminProductImage.files ?? []);
                    await appendSelectedFiles(adminQuickAddSelectedImages, nextFiles, false);
                    if (quickAdminRemoveBgCheckbox?.checked) {
                        await applyRemoveBgToSelected(adminQuickAddSelectedImages, adminQuickAddImagePreview);
                    }
                    quickAdminProductImage.value = "";
                    renderUploadPreview(adminQuickAddImagePreview, adminQuickAddSelectedImages);
                });
            }
            if (adminRemoveBgCheckbox) {
                adminRemoveBgCheckbox.addEventListener("change", async () => {
                    if (!adminRemoveBgCheckbox.checked) return;
                    await applyRemoveBgToSelected(adminAddSelectedImages, adminAddImagePreview);
                });
            }
            if (quickAdminRemoveBgCheckbox) {
                quickAdminRemoveBgCheckbox.addEventListener("change", async () => {
                    if (!quickAdminRemoveBgCheckbox.checked) return;
                    await applyRemoveBgToSelected(adminQuickAddSelectedImages, adminQuickAddImagePreview);
                });
            }
            if (adminAddImagePreview) {
                adminAddImagePreview.addEventListener("click", (event) => {
                    const removeBtn = event.target.closest("[data-remove-upload-image]");
                    if (!removeBtn) return;
                    const index = Number(removeBtn.getAttribute("data-remove-upload-image"));
                    if (!Number.isInteger(index) || index < 0 || index >= adminAddSelectedImages.length) return;
                    const [removed] = adminAddSelectedImages.splice(index, 1);
                    if (removed?.preview) URL.revokeObjectURL(removed.preview);
                    renderUploadPreview(adminAddImagePreview, adminAddSelectedImages);
                });
            }
            if (adminQuickAddImagePreview) {
                adminQuickAddImagePreview.addEventListener("click", (event) => {
                    const removeBtn = event.target.closest("[data-remove-upload-image]");
                    if (!removeBtn) return;
                    const index = Number(removeBtn.getAttribute("data-remove-upload-image"));
                    if (!Number.isInteger(index) || index < 0 || index >= adminQuickAddSelectedImages.length) return;
                    const [removed] = adminQuickAddSelectedImages.splice(index, 1);
                    if (removed?.preview) URL.revokeObjectURL(removed.preview);
                    renderUploadPreview(adminQuickAddImagePreview, adminQuickAddSelectedImages);
                });
            }
            if (adminProductModalClose) adminProductModalClose.addEventListener("click", closeAdminProductModal);
            if (adminProductModal) {
                adminProductModal.addEventListener("click", (event) => {
                    if (event.target === adminProductModal) closeAdminProductModal();
                });
            }
            if (adminEditProductModalClose) adminEditProductModalClose.addEventListener("click", closeEditProductModal);
            if (adminEditProductModal) {
                adminEditProductModal.addEventListener("click", (event) => {
                    if (event.target === adminEditProductModal) closeEditProductModal();
                });
            }
            if (adminEditProductImage) {
                adminEditProductImage.addEventListener("change", () => {
                    const nextFiles = Array.from(adminEditProductImage.files ?? []);
                    if (!nextFiles.length) return;
                    nextFiles.forEach((file) => {
                        editModalNewImages.push({
                            file,
                            preview: URL.createObjectURL(file),
                        });
                    });
                    adminEditProductImage.value = "";
                    renderEditImagePreview();
                });
            }
            if (adminEditImagePreview) {
                adminEditImagePreview.addEventListener("click", (event) => {
                    const removeExisting = event.target.closest("[data-remove-existing-image]");
                    if (removeExisting) {
                        const imageUrl = removeExisting.getAttribute("data-remove-existing-image");
                        if (imageUrl && !editModalRemovedImages.includes(imageUrl)) {
                            editModalRemovedImages.push(imageUrl);
                        }
                        renderEditImagePreview();
                        return;
                    }
                    const removeNew = event.target.closest("[data-remove-new-image]");
                    if (removeNew) {
                        const index = Number(removeNew.getAttribute("data-remove-new-image"));
                        if (Number.isInteger(index) && index >= 0 && index < editModalNewImages.length) {
                            const [removed] = editModalNewImages.splice(index, 1);
                            if (removed?.preview) URL.revokeObjectURL(removed.preview);
                        }
                        renderEditImagePreview();
                    }
                });
            }
            if (adminEditProductForm) {
                adminEditProductForm.addEventListener("submit", async (event) => {
                    event.preventDefault();
                    if (!editingProductCard) return;
                    const saveBtn = adminEditProductForm.querySelector('button[type="submit"]');
                    const saveBtnText = saveBtn?.textContent ?? "Save Changes";
                    if (saveBtn) {
                        saveBtn.disabled = true;
                        saveBtn.textContent = "Saving...";
                    }
                    const formData = new FormData(adminEditProductForm);
                    const payload = {
                        name: String(formData.get("name") ?? "").trim(),
                        description: String(formData.get("description") ?? "").trim(),
                        size: String(formData.get("size") ?? "").trim(),
                        category: String(formData.get("category") ?? "").trim(),
                        price: Number(String(formData.get("price") ?? "").replace(/[^\d.]/g, "")),
                    };
                    if (!payload.name || !payload.description || !payload.size || !payload.category || !Number.isFinite(payload.price) || payload.price <= 0) {
                        showActionAlert("error", "Invalid update", "Please fill all fields with valid values.");
                        return;
                    }
                    const keptExisting = editModalExistingImages.filter((img) => !editModalRemovedImages.includes(img));
                    const newFiles = editModalNewImages.map((item) => item.file);
                    if (!keptExisting.length && !newFiles.length) {
                        showActionAlert("error", "Missing photos", "Please keep or upload at least one image.");
                        return;
                    }
                    try {
                        const productId = editingProductCard.dataset.productId;
                        let updateResponse = null;
                        const requestBody = new FormData();
                        requestBody.append("name", payload.name);
                        requestBody.append("description", payload.description);
                        requestBody.append("size", payload.size);
                        requestBody.append("category", payload.category);
                        requestBody.append("price", String(payload.price));
                        if (productId) {
                            requestBody.append("_method", "PATCH");
                            editModalRemovedImages.forEach((url) => requestBody.append("removed_images[]", url));
                            newFiles.forEach((file) => requestBody.append("images[]", file));
                            updateResponse = await api(`/shop/products/${productId}`, {
                                method: "POST",
                                body: requestBody,
                            });
                        } else {
                            keptExisting.forEach((url) => requestBody.append("existing_image_urls[]", url));
                            newFiles.forEach((file) => requestBody.append("images[]", file));
                            updateResponse = await api("/shop/products", {
                                method: "POST",
                                body: requestBody,
                            });
                            if (updateResponse?.product?.id) {
                                editingProductCard.dataset.productId = String(updateResponse.product.id);
                                editingProductCard.dataset.dbProduct = "1";
                            }
                        }

                        const titleEl = editingProductCard.querySelector(".product-title");
                        const priceEl = editingProductCard.querySelector(".product-price");
                        const metaEl = editingProductCard.querySelector(".meta");
                        const imageEl = editingProductCard.querySelector(".thumb img");
                        if (titleEl) titleEl.textContent = payload.name;
                        if (priceEl) priceEl.textContent = `₱${payload.price.toFixed(0)}`;
                        if (metaEl) metaEl.textContent = `${payload.category} · Size ${payload.size}`;
                        editingProductCard.dataset.description = payload.description;
                        editingProductCard.dataset.searchText = `${payload.name} ${payload.category} ${payload.size} ${payload.description}`.toLowerCase();
                        const responseImages = Array.isArray(updateResponse?.product?.image_urls)
                            ? updateResponse.product.image_urls.map((img) => String(img ?? "").trim()).filter(Boolean)
                            : [];
                        const fallbackResponseImage = String(updateResponse?.product?.image_url ?? "").trim();
                        const finalImages = responseImages.length
                            ? responseImages
                            : (fallbackResponseImage ? [fallbackResponseImage] : [...keptExisting, ...editModalNewImages.map((item) => item.preview)]);
                        setProductImages(editingProductCard, finalImages);
                        if (!editingProductCard.dataset.productId && updateResponse?.product?.id) {
                            editingProductCard.dataset.productId = String(updateResponse.product.id);
                        }
                        if (imageEl && !finalImages.length) imageEl.removeAttribute("src");
                        const oldBlurb = editingProductCard.querySelector(".product-blurb");
                        if (oldBlurb) oldBlurb.remove();
                        appendProductBlurb(editingProductCard);
                        applyFilters();
                        showActionAlert("success", "Updated", `${payload.name} was updated.`);
                        closeEditProductModal();
                    } catch (error) {
                        showActionAlert("error", "Update failed", error.message || "Unable to update this product.");
                    } finally {
                        if (saveBtn) {
                            saveBtn.disabled = false;
                            saveBtn.textContent = saveBtnText;
                        }
                    }
                });
            }
            if (profileLogoutBtn) {
                profileLogoutBtn.addEventListener("click", async () => {
                    try {
                        const response = await api("/shop/logout", { method: "POST" });
                        if (response?.csrf_token) csrfToken = response.csrf_token;
                        window.location.href = "/";
                    } catch (error) {
                        showActionAlert("error", "Logout failed", error.message || "Please try again.");
                    }
                });
            }
            if (userOrdersList) {
                userOrdersList.addEventListener("click", async (event) => {
                    const cancelBtn = event.target.closest("[data-cancel-order]");
                    if (cancelBtn) {
                        const cancelId = cancelBtn.getAttribute("data-cancel-order");
                        const row = cancelBtn.closest(".order-row");
                        const itemName = row?.querySelector(".order-row-title")?.textContent ?? "Item";
                        openOrderActionModal({
                            title: "Cancel Waiting Order",
                            itemName,
                            info: "This order will be marked as cancelled.",
                            confirmText: "Confirm Cancel",
                            run: async () => {
                                await api(`/shop/orders/${cancelId}/cancel`, { method: "PATCH" });
                                await loadProfileData();
                                showActionAlert("success", "Order cancelled", "Your waiting order was cancelled.");
                            },
                        });
                        return;
                    }
                    const deleteBtn = event.target.closest("[data-delete-order]");
                    if (deleteBtn) {
                        const deleteId = deleteBtn.getAttribute("data-delete-order");
                        const row = deleteBtn.closest(".order-row");
                        const itemName = row?.querySelector(".order-row-title")?.textContent ?? "Item";
                        openOrderActionModal({
                            title: "Delete Order Entry",
                            itemName,
                            info: "This will remove this order from your list.",
                            confirmText: "Delete",
                            run: async () => {
                                await api(`/shop/orders/${deleteId}`, { method: "DELETE" });
                                await loadProfileData();
                                showActionAlert("success", "Order deleted", "Order entry removed.");
                            },
                        });
                        return;
                    }
                    const button = event.target.closest("[data-receive-order]");
                    if (!button) return;
                    const orderId = button.getAttribute("data-receive-order");
                    const row = button.closest(".order-row");
                    const itemName = row?.querySelector(".order-row-title")?.textContent ?? "Item";
                    openOrderActionModal({
                        title: "Confirm Receive Order",
                        itemName,
                        info: "Track update: To Receive -> Received",
                        confirmText: "Confirm Received",
                        run: async () => {
                            const response = await api(`/shop/orders/${orderId}/receive`, { method: "PATCH" });
                            if (isAdminUser()) {
                                const updatedOrder = response?.order ?? null;
                                if (updatedOrder?.id) {
                                    const targetId = Number(updatedOrder.id);
                                    adminOrdersState = adminOrdersState.map((order) => (
                                        Number(order.id) === targetId
                                            ? { ...order, ...updatedOrder, status: "received" }
                                            : order
                                    ));
                                    updateAdminOrderBadge();
                                    renderAdminOrders();
                                }
                            }
                            await loadProfileData();
                            showActionAlert("success", "Order received", "Thank you for confirming the delivery.");
                        },
                    });
                });
            }
            const productGrid = document.querySelector(".products");
            if (productGrid) {
                productGrid.addEventListener("click", async (event) => {
                    const editBtn = event.target.closest("[data-edit-product]");
                    if (editBtn) {
                        event.preventDefault();
                        event.stopPropagation();
                        if (!isAdminUser()) return;
                        const card = editBtn.closest(".product");
                        if (!card) return;
                        openEditProductModal(card);
                        return;
                    }
                    const deleteBtn = event.target.closest("[data-delete-product]");
                    if (!deleteBtn) return;
                    event.preventDefault();
                    event.stopPropagation();
                    if (!isAdminUser()) return;
                    const card = deleteBtn.closest(".product");
                    if (!card) return;
                    const name = card.querySelector(".product-title")?.textContent?.trim() ?? "this product";
                    const confirmed = window.Swal
                        ? await Swal.fire({
                            icon: "warning",
                            title: "Delete product?",
                            text: `${name} will be removed.`,
                            showCancelButton: true,
                            confirmButtonText: "Delete",
                            cancelButtonText: "Cancel",
                            confirmButtonColor: "#b91c1c",
                        }).then((result) => result.isConfirmed)
                        : window.confirm(`Delete ${name} from preview?`);
                    if (!confirmed) return;
                    const productId = card.dataset.productId;
                    if (productId) {
                        await api(`/shop/products/${productId}`, { method: "DELETE" });
                    } else {
                        showActionAlert("warning", "Default product", "Only admin-added products can be deleted globally.");
                        return;
                    }
                    card.remove();
                    const removeIndex = products.indexOf(card);
                    if (removeIndex >= 0) products.splice(removeIndex, 1);
                    showActionAlert("success", "Removed", `${name} was removed.`);
                    applyFilters();
                });
            }
            if (adminOrdersList) {
                adminOrdersList.addEventListener("click", (event) => {
                    const button = event.target.closest("[data-admin-order]");
                    if (!button) return;
                    const orderId = button.getAttribute("data-admin-order");
                    const nextStatus = button.getAttribute("data-next-status");
                    const itemName = button.getAttribute("data-order-item") ?? "Item";
                    const userName = button.getAttribute("data-order-user") ?? "User";
                    const row = button.closest(".order-row");
                    const addressText = row?.querySelectorAll(".order-row-meta")?.[1]?.textContent ?? "Address: No address provided";
                    openOrderActionModal({
                        title: "Confirm Order Tracking",
                        itemName,
                        info: `${userName} order: To Ship -> To Receive\n${addressText}`,
                        confirmText: "Confirm To Receive",
                        run: async () => {
                            await api(`/shop/admin/orders/${orderId}`, {
                                method: "PATCH",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({ status: nextStatus }),
                            });
                            await loadProfileData();
                            showActionAlert("success", "Order updated", "Order status has been changed.");
                        },
                    });
                });
            }
            if (orderActionCloseBtn) orderActionCloseBtn.addEventListener("click", closeOrderActionModal);
            if (orderActionCancelBtn) orderActionCancelBtn.addEventListener("click", closeOrderActionModal);
            if (orderActionModal) {
                orderActionModal.addEventListener("click", (event) => {
                    if (event.target === orderActionModal) closeOrderActionModal();
                });
            }
            if (orderActionConfirmBtn) {
                orderActionConfirmBtn.addEventListener("click", async () => {
                    if (!pendingOrderAction?.run) return;
                    try {
                        await pendingOrderAction.run();
                        closeOrderActionModal();
                    } catch (error) {
                        showActionAlert("error", "Update failed", error.message);
                    }
                });
            }
            if (addAddressForm) {
                addAddressForm.addEventListener("submit", async (event) => {
                    event.preventDefault();
                    const value = newAddressInput?.value?.trim();
                    if (!value) return;
                    try {
                        const response = await api("/shop/addresses", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ address: value }),
                        });
                        profileState = {
                            ...(profileState ?? {}),
                            addresses: response.addresses ?? [],
                        };
                        if (newAddressInput) newAddressInput.value = "";
                        renderProfilePanel();
                        showActionAlert("success", "Address saved", "Your shipping address was added.");
                    } catch (error) {
                        showActionAlert("error", "Unable to save", error.message);
                    }
                });
            }
            if (cartCloseBtn) {
                cartCloseBtn.addEventListener("click", closeCart);
            }
            if (cartOverlay) {
                cartOverlay.addEventListener("click", closeCart);
            }
            if (cartCheckoutBtn) {
                cartCheckoutBtn.addEventListener("click", () => {
                    if (!hasActiveProfile()) {
                        openGuestPurchasePrompt();
                        return;
                    }
                    openCheckoutModal();
                });
            }
            if (checkoutCloseBtn) checkoutCloseBtn.addEventListener("click", closeCheckoutModal);
            if (checkoutCancelBtn) checkoutCancelBtn.addEventListener("click", closeCheckoutModal);
            if (checkoutModal) {
                checkoutModal.addEventListener("click", (event) => {
                    if (event.target === checkoutModal) closeCheckoutModal();
                });
            }
            if (checkoutProceedBtn) {
                checkoutProceedBtn.addEventListener("click", async () => {
                    const items = checkoutMode === "single" && checkoutSingleItem
                        ? [checkoutSingleItem]
                        : Array.from(cartStore.values());
                    if (!items.length) {
                        closeCheckoutModal();
                        return;
                    }
                    if (!hasValidAddress()) {
                        promptAddressSetup();
                        return;
                    }
                    try {
                        for (const item of items) {
                            await api("/shop/orders", {
                                method: "POST",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({ item_name: item.title }),
                            });
                        }
                        if (checkoutMode === "cart") {
                            cartStore.clear();
                            renderCart();
                        }
                        checkoutMode = "cart";
                        checkoutSingleItem = null;
                        closeCheckoutModal();
                        closeCart();
                        orderViewFilter = "to_ship";
                        await loadProfileData();
                        showActionAlert("success", "Checkout success", "Order placed successfully.");
                    } catch (error) {
                        showActionAlert("error", "Checkout failed", error.message);
                    }
                });
            }

            applyFilters();
            renderCart();
            updateAuthTriggers();
            startLiveSync();
            if (hasActiveProfile()) {
                loadProfileData();
            }
            if (initialPanel === "profile") {
                if (hasActiveProfile()) {
                    openProfilePanel();
                    loadProfileData();
                } else {
                    openGuestPurchasePrompt();
                }
            }
            window.addEventListener("beforeunload", stopLiveSync);
        })();
    </script>
</body>
</html>
