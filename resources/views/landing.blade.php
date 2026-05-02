@php
    $defaultHeroSlides = [
        ['src' => '/images/model1-cutout.png?v=' . time(), 'alt' => 'Model 1', 'class' => 'slide'],
        ['src' => '/images/model2-cutout.png?v=' . time(), 'alt' => 'Model 2', 'class' => 'slide'],
        ['src' => '/images/model3-cutout.png?v=' . time(), 'alt' => 'Model 3', 'class' => 'slide'],
        ['src' => '/images/model4-cutout.png?v=' . time(), 'alt' => 'Model 4', 'class' => 'slide slide-grow-4'],
        ['src' => '/images/model7-cutout.png?v=' . time(), 'alt' => 'Model 5', 'class' => 'slide slide-grow-5'],
    ];
    $heroSlides = $defaultHeroSlides;
    if (Illuminate\Support\Facades\Storage::disk('public')->exists('landing-hero-slides.json')) {
        $raw = Illuminate\Support\Facades\Storage::disk('public')->get('landing-hero-slides.json');
        $parsed = json_decode($raw, true);
        if (is_array($parsed) && count($parsed)) {
            $heroSlides = collect($parsed)
                ->filter(fn ($entry) => is_array($entry) && !empty($entry['src']))
                ->map(function ($entry, $index) {
                    $baseClass = 'slide';
                    if (($entry['class'] ?? '') === 'slide slide-grow-4') {
                        $baseClass = 'slide slide-grow-4';
                    } elseif (($entry['class'] ?? '') === 'slide slide-grow-5') {
                        $baseClass = 'slide slide-grow-5';
                    }
                    return [
                        'src' => (string) $entry['src'],
                        'alt' => (string) ($entry['alt'] ?? ('Model ' . ($index + 1))),
                        'class' => $baseClass,
                    ];
                })
                ->values()
                ->all();
        }
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Preloved Picks</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
            background: #f4f4f4;
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
        .container {
            width: min(1120px, 94%);
            margin: 0 auto;
        }
        .top {
            padding: 22px 0;
        }
        .nav-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 16px 22px;
            flex-wrap: wrap;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.6px;
            color: #111;
        }
        .brand span {
            font-family: "Great Vibes", "Segoe Script", "Lucida Handwriting", cursive;
            font-size: 44px;
            line-height: 1;
            letter-spacing: 0.4px;
            font-weight: 700;
        }
        .brand img {
            height: 62px;
            width: auto;
            display: block;
        }
        nav {
            display: flex;
            gap: 18px;
            align-items: center;
            flex-wrap: wrap;
            font-size: 13px;
            font-weight: 700;
        }
        nav a {
            color: #1b1b1b;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 0 14px;
            border-radius: 999px;
            border: 1px solid #dbe0e7;
            background: #fff;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }
        nav a:hover {
            color: #111;
            transform: translateY(-1px);
            border-color: #c8d1dd;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.11);
        }
        .hero-box {
            margin-top: 18px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 18px;
            min-height: 440px;
            padding: 28px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 26px;
            position: relative;
            overflow: hidden;
        }
        .hero-copy {
            z-index: 1;
            align-self: center;
        }
        .label {
            font-size: 12px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #666;
            font-weight: 700;
        }
        h1 {
            margin: 12px 0 10px;
            font-size: clamp(2rem, 4.4vw, 3.5rem);
            line-height: 1.05;
            color: #111;
        }
        .lead {
            margin: 0 0 18px;
            font-size: 14px;
            line-height: 1.7;
            color: #444;
            max-width: 520px;
        }
        .cta-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            display: inline-block;
            border-radius: 999px;
            padding: 11px 20px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 800;
        }
        .btn-primary {
            background: #111;
            color: #fff;
            border: 1px solid #111;
        }
        .btn-outline {
            border: 1px solid #bbb;
            color: #222;
            background: #fff;
        }
        .slider-wrap {
            z-index: 1;
            align-self: center;
            width: min(100%, 420px);
            margin-inline: auto;
        }
        .slider {
            width: 100%;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: none;
            background: transparent;
            border: 0;
            min-height: 520px;
        }
        .slides {
            position: relative;
            width: 100%;
            height: 520px;
        }
        .slide {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 520px;
            max-height: 520px;
            object-fit: contain;
            display: block;
            background: transparent;
            border-radius: 18px;
            opacity: 0;
            transition: opacity .8s ease;
            pointer-events: none;
        }
        .slide.active { opacity: 1; }
        .slide-grow-4 {
            transform: scale(1.60) translateX(5%) !important;
            transform-origin: center center;
        }
        .slide-grow-5 {
            transform: scale(1.9) translateY(-20%) !important;
            transform-origin: center center;
        }
        .slide-zoom-up {
            object-position: center top;
            transform: scale(1.51) translateY(-88px);
            transform-origin: center top;
        }
        .dots {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-top: 12px;
        }
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #b9b9b9;
            border: 0;
            cursor: pointer;
            padding: 0;
        }
        .dot.active { background: #111; }
        .hero-editor {
            margin-top: 12px;
            border: 1px solid #d9dee6;
            background: #f8fafc;
            border-radius: 12px;
            padding: 10px;
            display: grid;
            gap: 8px;
        }
        .hero-editor.hidden {
            display: none;
        }
        .hero-editor-title {
            margin: 0;
            font-size: 12px;
            font-weight: 800;
            color: #111827;
            letter-spacing: .4px;
        }
        .hero-editor-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }
        .hero-editor label {
            display: grid;
            gap: 4px;
            font-size: 11px;
            color: #374151;
            font-weight: 700;
        }
        .hero-editor input,
        .hero-editor select,
        .hero-editor button {
            font-size: 12px;
        }
        .hero-editor input[type="range"] {
            width: 100%;
        }
        .hero-editor-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .hero-editor-btn {
            border: 1px solid #111827;
            background: #111827;
            color: #fff;
            border-radius: 999px;
            padding: 7px 12px;
            cursor: pointer;
            font-weight: 700;
        }
        .hero-editor-btn.secondary {
            background: #fff;
            color: #111827;
        }
        .hero-editor-note {
            margin: 0;
            font-size: 10px;
            color: #6b7280;
        }
        section.info {
            padding: 46px 0;
        }
        section.info .container {
            width: min(980px, 92%);
        }
        .grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
        .card {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #ddd;
            padding: 22px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        }
        .card h2 {
            margin: 0 0 8px;
            font-size: 1.2rem;
            color: #111;
        }
        #about.card {
            padding: 20px;
            border-radius: 22px;
            border: 1px solid #dfe4ea;
            box-shadow: 0 10px 24px rgba(17, 24, 39, 0.08);
        }
        #about.card h2 {
            font-size: 1.35rem;
            margin-bottom: 10px;
        }
        #about.card > p {
            font-size: 14px;
            font-family: Georgia, "Times New Roman", serif;
            font-style: italic;
            color: #2f3743;
        }
        .about-shop-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 12px;
            font-weight: 800;
            color: #111;
        }
        .about-shop-sub {
            color: #6b7280;
            font-weight: 700;
        }
        .card p {
            margin: 0;
            color: #444;
            font-size: 14px;
            line-height: 1.7;
        }
        .about-story-grid {
            display: block;
            margin-top: 10px;
        }
        .about-person {
            border: 0;
            border-radius: 0;
            padding: 0 0 12px;
            background: transparent;
            box-shadow: none;
            display: block;
            border-bottom: 1px dashed #d7dde6;
            margin-bottom: 12px;
        }
        .about-person-copy {
            font-family: Georgia, "Times New Roman", serif;
            font-style: italic;
            color: #2f3743;
            text-align: justify;
        }
        .about-photo-frame {
            border-radius: 0;
            border: 0;
            background: transparent;
            overflow: visible;
            margin: 0 0 6px 14px;
            width: min(330px, 52%);
            max-height: none;
            float: right;
            shape-outside: inset(0 17% 0 17% round 50%);
            -webkit-shape-outside: inset(0 17% 0 17% round 50%);
        }
        .about-photo-frame-partner {
            width: min(313px, 49.4%);
        }
        .about-photo {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
            object-position: center center;
            background: transparent;
        }
        .about-photo-founder {
            object-position: center 20%;
        }
        .about-photo-partner {
            object-fit: contain;
            object-position: center bottom;
            background: transparent;
        }
        .about-person h3 {
            margin: 0 0 5px;
            font-size: 15px;
            color: #111;
            font-style: normal;
            letter-spacing: 0.2px;
        }
        .about-person p {
            margin: 0;
            font-size: 14px;
            line-height: 1.75;
            color: #3f4956;
            font-family: Georgia, "Times New Roman", serif;
            font-style: italic;
        }
        .about-person::after {
            content: "";
            display: block;
            clear: both;
        }
        .contact-list {
            margin-top: 12px;
            display: grid;
            gap: 8px;
        }
        .contact-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #222;
            text-decoration: none;
        }
        .contact-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            border: 1px solid #bbb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
        }
        .seller-status {
            margin-top: 10px;
            border-radius: 10px;
            background: #fff;
            border: 1px solid #ccc;
            color: #111;
            padding: 10px 12px;
            font-size: 13px;
        }
        .auth-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            display: none;
            z-index: 80;
        }
        .auth-overlay.show {
            display: block;
        }
        .auth-sidebar {
            position: fixed;
            top: 0;
            right: -430px;
            width: min(420px, 100%);
            height: 100vh;
            background: #fff;
            border-left: 1px solid #ddd;
            box-shadow: -8px 0 24px rgba(0, 0, 0, 0.15);
            z-index: 85;
            display: flex;
            flex-direction: column;
            transition: right .25s ease;
        }
        .auth-sidebar.show {
            right: 0;
        }
        .auth-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #e5e7eb;
            padding: 14px;
        }
        .auth-head-title {
            margin: 0;
            font-size: 16px;
            font-weight: 800;
            color: #111;
        }
        .auth-close {
            border: 1px solid #ccc;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            background: #fff;
            cursor: pointer;
            font-size: 18px;
            line-height: 1;
        }
        .auth-body {
            padding: 16px 14px;
        }
        .auth-body p {
            margin: 0 0 12px;
            color: #4b5563;
            font-size: 13px;
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
        .auth-input {
            width: 100%;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 10px 11px;
            font-size: 13px;
            margin-bottom: 10px;
            outline: none;
        }
        .auth-input:focus {
            border-color: #111;
            box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.08);
        }
        .auth-submit {
            width: 100%;
            border: 1px solid #111;
            border-radius: 999px;
            background: #111;
            color: #fff;
            padding: 10px 12px;
            font-size: 13px;
            font-weight: 800;
            cursor: pointer;
        }
        .auth-helper {
            margin: 0;
            font-size: 12px;
            color: #616b79;
        }
        .auth-inline-link {
            color: #111;
            font-weight: 800;
            text-decoration: underline;
            cursor: pointer;
        }
        .auth-msg {
            margin-top: 8px;
            font-size: 12px;
            color: #065f46;
            min-height: 16px;
        }
        .landing-mobile-nav {
            display: none;
        }
        .back-to-top {
            position: fixed;
            right: 18px;
            bottom: 22px;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            border: 1px solid #d1d5db;
            background: #111;
            color: #fff;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 10px 24px rgba(17, 24, 39, 0.25);
            z-index: 75;
        }
        .back-to-top.show {
            display: inline-flex;
        }
        .back-to-top svg {
            width: 18px;
            height: 18px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2.2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }
        footer {
            text-align: center;
            color: #666;
            font-size: 13px;
            padding: 8px 0 28px;
        }
        @media (max-width: 900px) {
            body { padding-bottom: 84px; }
            .hero-box { grid-template-columns: 1fr; min-height: auto; }
            .grid { grid-template-columns: 1fr; }
            .about-story-grid { grid-template-columns: 1fr; }
            section.info .container { width: min(94%, 680px); }
            #about.card { padding: 16px; }
            .about-person {
                border-bottom: 1px dashed #d7dde6;
                margin-bottom: 10px;
            }
            .about-photo-frame {
                float: right;
                width: min(224px, 58%);
                margin: 0 0 6px 10px;
                border-radius: 0;
                shape-outside: inset(0 14% 0 14% round 50%);
                -webkit-shape-outside: inset(0 14% 0 14% round 50%);
            }
            .about-photo-frame-partner {
                width: min(213px, 55.1%);
            }
            .about-person p {
                font-size: 13px;
                line-height: 1.68;
            }
            .slider { min-height: 420px; }
            .slides { height: 420px; }
            .slide { height: 420px; max-height: 420px; }
            .slide-grow-4 { transform: scale(1.60) translateX(5%) !important; }
            .slide-grow-5 { transform: scale(1.9) translateY(-20%) !important; }
            .slide-zoom-up { transform: scale(1.37) translateY(-58px); }
            .nav-card nav { display: none; }
            .landing-mobile-nav {
                position: fixed;
                left: 50%;
                bottom: 14px;
                transform: translateX(-50%);
                width: min(94%, 430px);
                border: 1px solid #d4d9e1;
                border-radius: 999px;
                background: rgba(255, 255, 255, 0.96);
                backdrop-filter: blur(6px);
                padding: 8px 10px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                z-index: 70;
                box-shadow: 0 12px 28px rgba(17, 24, 39, 0.2);
            }
            .landing-mobile-nav a {
                width: 42px;
                height: 42px;
                border: 1px solid #d4d9e1;
                border-radius: 50%;
                background: #fff;
                color: #111;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                text-decoration: none;
                box-shadow: 0 6px 16px rgba(17, 24, 39, 0.12);
            }
            .landing-mobile-nav a svg {
                width: 18px;
                height: 18px;
                stroke: currentColor;
                fill: none;
                stroke-width: 2;
                stroke-linecap: round;
                stroke-linejoin: round;
            }
            .landing-mobile-nav a.mobile-signin-link {
                min-width: 74px;
                width: auto;
                border-radius: 999px;
                padding: 0 14px;
                font-size: 12px;
                font-weight: 800;
            }
            .back-to-top {
                right: 14px;
                bottom: 76px;
                width: 40px;
                height: 40px;
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
    <header class="top">
        <div class="container">
            <div class="nav-card">
                <div class="brand">
                    <img src="/images/logo-cutout.png?v={{ time() }}" alt="Preloved Picks logo">
                    <span>𝓹𝓻𝓮𝓵𝓸𝓿𝓮𝓭 𝓹𝓲𝓬𝓴𝓼</span>
                </div>
                <nav>
                    <a href="#about">About Us</a>
                    <a href="{{ route('shop') }}">Shop</a>
                    <a href="#contact">Contact</a>
                    @guest
                        <a href="#" id="landingSignInTrigger">Sign In</a>
                    @else
                        <a href="{{ route('shop') }}?panel=profile">Profile</a>
                    @endguest
                </nav>
            </div>

            @if (session('status'))
                <div class="seller-status">
                    {{ session('status') }}
                    @if (session('seller_email'))
                        Seller: {{ session('seller_email') }}
                    @endif
                </div>
            @endif

            <div class="hero-box">
                <div class="hero-copy">
                    <div class="label">Outfit of the day</div>
                    <h1>All your styles are here.</h1>
                    <p class="lead">
                        Modern preloved fashion with clean curated pieces. Explore personal drops,
                        timeless styles, and fresh looks in one place.
                    </p>
                    <div class="cta-row">
                        <a class="btn btn-primary" href="{{ route('shop') }}">Shop Now</a>
                        <a class="btn btn-outline" href="#about">Learn More</a>
                    </div>
                </div>
                <div class="slider-wrap">
                    <div class="slider">
                        <div class="slides" id="slides">
                            @foreach ($heroSlides as $i => $slide)
                                <img class="{{ $slide['class'] }}" src="{{ $slide['src'] }}" alt="{{ $slide['alt'] }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" decoding="async">
                            @endforeach
                        </div>
                    </div>
                    <div class="dots" id="dots">
                        @foreach ($heroSlides as $i => $slide)
                            <button class="dot{{ $i === 0 ? ' active' : '' }}" aria-label="Slide {{ $i + 1 }}"></button>
                        @endforeach
                    </div>
                    @if (auth()->check() && auth()->user()->is_admin)
                        <button id="heroEditorToggleBtn" class="hero-editor-btn secondary" type="button">Edit Hero Photos</button>
                        <div id="heroEditor" class="hero-editor hidden">
                            <p class="hero-editor-title">Hero Image Editor (Admin)</p>
                            <label>
                                Choose photo to edit
                                <select id="heroSlidePicker"></select>
                            </label>
                            <div class="hero-editor-row">
                                <label>
                                    Scale
                                    <input id="heroScaleInput" type="range" min="0.6" max="3" step="0.05" value="1">
                                </label>
                                <label>
                                    Crop mode
                                    <select id="heroCropInput">
                                        <option value="contain">Contain</option>
                                        <option value="cover">Cover</option>
                                    </select>
                                </label>
                            </div>
                            <div class="hero-editor-row">
                                <label>
                                    Move Left/Right
                                    <input id="heroOffsetXInput" type="range" min="-260" max="260" step="1" value="0">
                                </label>
                                <label>
                                    Move Up/Down
                                    <input id="heroOffsetYInput" type="range" min="-260" max="260" step="1" value="0">
                                </label>
                            </div>
                            <label>
                                Add photo(s)
                                <input id="heroImageUploadInput" type="file" accept="image/*" multiple>
                            </label>
                            <label style="display:flex;align-items:center;gap:8px;">
                                <input id="heroRemoveBgInput" type="checkbox">
                                Try remove background on upload (experimental)
                            </label>
                            <div class="hero-editor-actions">
                                <button id="heroDeleteSlideBtn" class="hero-editor-btn secondary" type="button">Delete current photo</button>
                                <button id="heroResetStyleBtn" class="hero-editor-btn secondary" type="button">Reset style</button>
                            </div>
                            <p class="hero-editor-note">Changes are saved in this browser. Background removal works best on solid-color backgrounds.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </header>

    <nav class="landing-mobile-nav" aria-label="Landing mobile navigation">
        <a href="#about" aria-label="About Us">
            <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"></circle><path d="M12 10v6"></path><path d="M12 7h.01"></path></svg>
        </a>
        <a href="{{ route('shop') }}" class="mobile-shop-link" aria-label="Shop">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 8h12l-1 11H7L6 8z"></path><path d="M9 8a3 3 0 0 1 6 0"></path></svg>
        </a>
        <a href="#contact" aria-label="Contact">
            <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16v12H4z"></path><path d="M4 8l8 5 8-5"></path></svg>
        </a>
        @guest
            <a href="#" id="landingSignInTriggerMobile" class="mobile-signin-link" aria-label="Sign In">Sign In</a>
        @else
            <a href="{{ route('shop') }}?panel=profile" class="mobile-signin-link" aria-label="Profile">Profile</a>
        @endguest
    </nav>

    <div id="landingAuthOverlay" class="auth-overlay" aria-hidden="true"></div>
    <aside id="landingAuthSidebar" class="auth-sidebar" aria-hidden="true">
        <div class="auth-head">
            <p class="auth-head-title">Sign In</p>
            <button id="landingAuthClose" class="auth-close" type="button" aria-label="Close sign in">×</button>
        </div>
        <div class="auth-body">
            <p>Sign in or create an account to continue.</p>
            <div class="auth-switch">
                <button id="landingShowSignInBtn" class="auth-switch-btn active" type="button">Sign In</button>
                <button id="landingShowSignUpBtn" class="auth-switch-btn" type="button">Sign Up</button>
            </div>
            <div id="landingSignInPanel" class="auth-panel show">
                <form id="landingSignInForm">
                    @csrf
                    <input class="auth-input" type="email" name="email" placeholder="Email address" required>
                    <input class="auth-input" type="password" name="password" placeholder="Password" required>
                    <button class="auth-submit" type="submit">Sign In</button>
                </form>
            </div>
            <div id="landingSignUpPanel" class="auth-panel">
                <form id="landingSignUpForm">
                    <input class="auth-input" type="text" name="name" placeholder="Full Name" required>
                    <input class="auth-input" type="email" name="email" placeholder="Email Address" required>
                    <input class="auth-input" type="password" name="password" placeholder="Password" required>
                    <button class="auth-submit" type="submit">Create Account</button>
                </form>
            </div>
            <p id="landingAuthMsg" class="auth-msg"></p>
        </div>
    </aside>

    <button id="backToTopBtn" class="back-to-top" type="button" aria-label="Back to top">
        <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 18V6"></path><path d="M7 11l5-5 5 5"></path></svg>
    </button>

    <section class="info container">
        <div class="grid">
            <article class="card" id="about">
                <div class="about-shop-head">
                    <span>About Us</span>
                    <span class="about-shop-sub">Our Story</span>
                </div>
                <p>
                    Preloved Picks started when they met and discovered they shared the same love for fashion
                    and small business goals. They began by posting curated preloved pieces for friends, then
                    grew into a full online shop as more buyers trusted their picks.
                </p>
                <div class="about-story-grid">
                    <article class="about-person">
                        <div class="about-photo-frame">
                            <img class="about-photo about-photo-founder" src="/images/model5-cutout.png?v={{ time() }}" alt="Founder of Preloved Picks">
                        </div>
                        <div class="about-person-copy">
                            <h3>Founder - Creative Lead</h3>
                            <p style="font-weight:700; color:#111; margin:0 0 6px; font-style:normal;">Ma. Richie Orillaneda</p>
                            <p>
                                She manages the page look, product presentation, and customer styling ideas.
                                Her focus is to keep each collection clean, wearable, and on trend.
                            </p>
                            <p style="margin-top:8px;">
                                She keeps every collection polished, feminine, and easy to style, so buyers can
                                build confident looks for daily wear.
                            </p>
                        </div>
                    </article>
                    <article class="about-person">
                        <div class="about-photo-frame about-photo-frame-partner">
                            <img class="about-photo about-photo-partner" src="/images/about-partner.png?v={{ time() }}" alt="Partner of Preloved Picks">
                        </div>
                        <div class="about-person-copy">
                            <h3>Co-CEO - Sourcing and Operations</h3>
                            <p style="font-weight:700; color:#111; margin:0 0 6px; font-style:normal;">Clifford Illupar</p>
                            <p>
                                Her partner is known for finding strong preloved items with great quality.
                                He checks fabric, fit, and condition so only good pieces are posted.
                            </p>
                            <p style="margin-top:8px;">
                                With strict quality checks and smart sourcing, he helps maintain reliable standards
                                so each drop is ready for a second life.
                            </p>
                            <p style="margin-top:8px;">
                                He also coordinates inventory timing and release flow, helping each collection stay
                                balanced in style, sizing, and day-to-day wearability.
                            </p>
                        </div>
                    </article>
                </div>
            </article>
            <article class="card" id="contact">
                <h2>Contact</h2>
                <p>
                    For inquiries, collaborations, or direct orders, reach out via your preferred contact
                    channel and we will respond as soon as possible.
                </p>
                <div class="contact-list">
                    <a class="contact-item" href="https://www.instagram.com/casualapparel.ph" target="_blank" rel="noopener noreferrer">
                        <span class="contact-icon">IG</span>
                        <span>@casualapparel.ph</span>
                    </a>
                    <a class="contact-item" href="https://www.facebook.com" target="_blank" rel="noopener noreferrer">
                        <span class="contact-icon">f</span>
                        <span>Ma. Richie Orillaneda</span>
                    </a>
                    <a class="contact-item" href="https://www.facebook.com" target="_blank" rel="noopener noreferrer">
                        <span class="contact-icon">f</span>
                        <span>Clifford llupar</span>
                    </a>
                </div>
            </article>
        </div>
    </section>

    <footer>Preloved Picks - Modern personal apparel landing page</footer>

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
                // Safari bfcache can restore previous hidden state.
                pageLoader.classList.add("hidden");
            });

            document.addEventListener("click", (event) => {
                const link = event.target.closest("a[href]");
                if (!link || !pageLoader) return;
                const href = link.getAttribute("href") ?? "";
                if (!href || href.startsWith("#") || href.startsWith("javascript:")) return;
                if (link.target === "_blank" || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey) return;
                pageLoader.classList.remove("hidden");
            });
        })();

        (() => {
            const slidesRoot = document.getElementById("slides");
            const dotsRoot = document.getElementById("dots");
            if (!slidesRoot || !dotsRoot) return;

            const storageKey = null;
            const persistEndpoint = "/landing/hero-slides";
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ?? "";
            const slideDefaults = { scale: 1, x: 0, y: 0, crop: "contain" };
            let index = 0;
            let timer = null;

            const getSlides = () => Array.from(slidesRoot.querySelectorAll(".slide"));
            const getDots = () => Array.from(dotsRoot.querySelectorAll(".dot"));

            const applySlideStyle = (el, cfg) => {
                const next = { ...slideDefaults, ...(cfg ?? {}) };
                el.style.objectFit = next.crop === "cover" ? "cover" : "contain";
                el.style.transform = `translate(${Number(next.x) || 0}px, ${Number(next.y) || 0}px) scale(${Number(next.scale) || 1})`;
                el.dataset.scale = String(next.scale);
                el.dataset.x = String(next.x);
                el.dataset.y = String(next.y);
                el.dataset.crop = next.crop;
            };

            const snapshotSlides = () => getSlides().map((img) => ({
                src: img.getAttribute("src") ?? "",
                alt: img.getAttribute("alt") ?? "Model",
                className: img.className ?? "slide",
                scale: Number(img.dataset.scale ?? 1),
                x: Number(img.dataset.x ?? 0),
                y: Number(img.dataset.y ?? 0),
                crop: img.dataset.crop ?? "contain",
            }));

            const saveSlides = () => {
                const payloadSlides = snapshotSlides()
                    .filter((entry) => {
                        const src = String(entry?.src ?? "");
                        return src && !src.startsWith("blob:") && !src.startsWith("data:");
                    })
                    .map((entry) => ({
                        src: entry.src,
                        alt: entry.alt,
                        class: entry.className,
                    }));
                if (payloadSlides.length) {
                    fetch(persistEndpoint, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json",
                        },
                        body: JSON.stringify({ slides: payloadSlides }),
                    }).catch(() => {});
                }
                if (!storageKey) return;
                try {
                    localStorage.setItem(storageKey, JSON.stringify(snapshotSlides()));
                } catch {}
            };

            const rebuildDots = () => {
                const slides = getSlides();
                dotsRoot.innerHTML = "";
                slides.forEach((_, i) => {
                    const dot = document.createElement("button");
                    dot.className = "dot";
                    if (i === index) dot.classList.add("active");
                    dot.setAttribute("aria-label", `Slide ${i + 1}`);
                    dot.type = "button";
                    dot.addEventListener("click", () => {
                        index = i;
                        render();
                    });
                    dotsRoot.appendChild(dot);
                });
                const heroSlidePicker = document.getElementById("heroSlidePicker");
                if (heroSlidePicker) {
                    heroSlidePicker.innerHTML = slides
                        .map((_, i) => `<option value="${i}">Photo ${i + 1}</option>`)
                        .join("");
                }
            };

            const render = () => {
                const slides = getSlides();
                const dots = getDots();
                if (!slides.length) return;
                if (index >= slides.length) index = 0;
                slides.forEach((img, i) => img.classList.toggle("active", i === index));
                dots.forEach((dot, i) => dot.classList.toggle("active", i === index));
                syncEditorInputs();
            };

            const startAuto = () => {
                if (timer) clearInterval(timer);
                timer = setInterval(() => {
                    const slides = getSlides();
                    if (!slides.length) return;
                    index = (index + 1) % slides.length;
                    render();
                }, 3000);
            };
            const stopAuto = () => {
                if (!timer) return;
                clearInterval(timer);
                timer = null;
            };

            const hydrateFromStorage = () => {
                if (!storageKey) return;
                try {
                    const raw = localStorage.getItem(storageKey);
                    if (!raw) return;
                    const parsed = JSON.parse(raw);
                    if (!Array.isArray(parsed) || !parsed.length) return;
                    slidesRoot.innerHTML = "";
                    parsed.forEach((entry, i) => {
                        const img = document.createElement("img");
                        img.className = "slide";
                        img.loading = i === 0 ? "eager" : "lazy";
                        img.decoding = "async";
                        img.alt = entry.alt || `Model ${i + 1}`;
                        img.src = entry.src || "";
                        applySlideStyle(img, entry);
                        slidesRoot.appendChild(img);
                    });
                } catch {}
            };

            const fallbackMap = ["/images/1.jpg", "/images/2.jpg", "/images/3.jpg", "/images/4.jpg", "/image4.jpg"];
            const wireImageErrors = () => {
                getSlides().forEach((img, i) => {
                    img.addEventListener("error", () => {
                        img.src = `${fallbackMap[i] ?? "/image1.jpg"}?v=${Date.now()}`;
                    }, { once: true });
                });
            };

            const removeBackground = async (file) => {
                const bitmap = await createImageBitmap(file);
                const canvas = document.createElement("canvas");
                canvas.width = bitmap.width;
                canvas.height = bitmap.height;
                const ctx = canvas.getContext("2d");
                if (!ctx) return URL.createObjectURL(file);
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
                if (!blob) return URL.createObjectURL(file);
                return URL.createObjectURL(blob);
            };

            const heroEditor = document.getElementById("heroEditor");
            const heroEditorToggleBtn = document.getElementById("heroEditorToggleBtn");
            const heroSlidePicker = document.getElementById("heroSlidePicker");
            const scaleInput = document.getElementById("heroScaleInput");
            const xInput = document.getElementById("heroOffsetXInput");
            const yInput = document.getElementById("heroOffsetYInput");
            const cropInput = document.getElementById("heroCropInput");
            const uploadInput = document.getElementById("heroImageUploadInput");
            const removeBgInput = document.getElementById("heroRemoveBgInput");
            const deleteBtn = document.getElementById("heroDeleteSlideBtn");
            const resetBtn = document.getElementById("heroResetStyleBtn");

            const syncEditorInputs = () => {
                if (!heroEditor) return;
                const active = getSlides()[index];
                if (!active) return;
                if (heroSlidePicker) heroSlidePicker.value = String(index);
                if (scaleInput) scaleInput.value = String(active.dataset.scale ?? 1);
                if (xInput) xInput.value = String(active.dataset.x ?? 0);
                if (yInput) yInput.value = String(active.dataset.y ?? 0);
                if (cropInput) cropInput.value = String(active.dataset.crop ?? "contain");
            };

            const applyEditorToActive = () => {
                const active = getSlides()[index];
                if (!active) return;
                applySlideStyle(active, {
                    scale: Number(scaleInput?.value ?? 1),
                    x: Number(xInput?.value ?? 0),
                    y: Number(yInput?.value ?? 0),
                    crop: String(cropInput?.value ?? "contain"),
                });
                saveSlides();
                render();
            };

            hydrateFromStorage();
            const initialSlides = getSlides();
            initialSlides.forEach((img) => {
                applySlideStyle(img, {
                    scale: Number(img.dataset.scale ?? 1),
                    x: Number(img.dataset.x ?? 0),
                    y: Number(img.dataset.y ?? 0),
                    crop: img.dataset.crop ?? "contain",
                });
            });
            wireImageErrors();
            rebuildDots();
            render();
            startAuto();

            if (!heroEditor) return;
            heroEditorToggleBtn?.addEventListener("click", () => {
                const opening = heroEditor.classList.contains("hidden");
                heroEditor.classList.toggle("hidden");
                if (heroEditorToggleBtn) {
                    heroEditorToggleBtn.textContent = opening ? "Close Editor" : "Edit Hero Photos";
                }
                if (opening) {
                    stopAuto();
                    syncEditorInputs();
                } else {
                    startAuto();
                }
            });
            heroSlidePicker?.addEventListener("change", () => {
                const next = Number(heroSlidePicker.value);
                if (!Number.isInteger(next)) return;
                index = Math.max(0, Math.min(next, getSlides().length - 1));
                render();
            });
            [scaleInput, xInput, yInput, cropInput].forEach((input) => {
                input?.addEventListener("input", applyEditorToActive);
            });

            uploadInput?.addEventListener("change", async () => {
                const files = Array.from(uploadInput.files ?? []);
                if (!files.length) return;
                for (const file of files) {
                    const src = removeBgInput?.checked
                        ? await removeBackground(file)
                        : URL.createObjectURL(file);
                    const img = document.createElement("img");
                    img.className = "slide";
                    img.loading = "lazy";
                    img.decoding = "async";
                    img.alt = `Model ${getSlides().length + 1}`;
                    img.src = src;
                    applySlideStyle(img, slideDefaults);
                    slidesRoot.appendChild(img);
                }
                uploadInput.value = "";
                rebuildDots();
                index = getSlides().length - 1;
                render();
                saveSlides();
            });

            deleteBtn?.addEventListener("click", () => {
                const slides = getSlides();
                if (slides.length <= 1) return;
                slides[index]?.remove();
                rebuildDots();
                if (index >= getSlides().length) index = getSlides().length - 1;
                render();
                saveSlides();
            });

            resetBtn?.addEventListener("click", () => {
                const active = getSlides()[index];
                if (!active) return;
                applySlideStyle(active, slideDefaults);
                syncEditorInputs();
                saveSlides();
            });
        })();

        (() => {
            const trigger = document.getElementById("landingSignInTrigger");
            const triggerMobile = document.getElementById("landingSignInTriggerMobile");
            const overlay = document.getElementById("landingAuthOverlay");
            const sidebar = document.getElementById("landingAuthSidebar");
            const closeBtn = document.getElementById("landingAuthClose");
            const showSignInBtn = document.getElementById("landingShowSignInBtn");
            const showSignUpBtn = document.getElementById("landingShowSignUpBtn");
            const signInPanel = document.getElementById("landingSignInPanel");
            const signUpPanel = document.getElementById("landingSignUpPanel");
            const signInForm = document.getElementById("landingSignInForm");
            const signUpForm = document.getElementById("landingSignUpForm");
            const authMsg = document.getElementById("landingAuthMsg");
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content") ?? "";

            if (!overlay || !sidebar || !closeBtn) return;

            const openAuth = () => {
                overlay.classList.add("show");
                sidebar.classList.add("show");
                overlay.setAttribute("aria-hidden", "false");
                sidebar.setAttribute("aria-hidden", "false");
            };

            const closeAuth = () => {
                overlay.classList.remove("show");
                sidebar.classList.remove("show");
                overlay.setAttribute("aria-hidden", "true");
                sidebar.setAttribute("aria-hidden", "true");
            };
            const showPanel = (panel) => {
                const showSignIn = panel !== "signup";
                if (signInPanel) signInPanel.classList.toggle("show", showSignIn);
                if (signUpPanel) signUpPanel.classList.toggle("show", !showSignIn);
                if (showSignInBtn) showSignInBtn.classList.toggle("active", showSignIn);
                if (showSignUpBtn) showSignUpBtn.classList.toggle("active", !showSignIn);
                if (authMsg) authMsg.textContent = "";
            };

            if (trigger) {
                trigger.addEventListener("click", (event) => {
                    event.preventDefault();
                    openAuth();
                });
            }
            if (triggerMobile) {
                triggerMobile.addEventListener("click", (event) => {
                    event.preventDefault();
                    openAuth();
                });
            }
            closeBtn.addEventListener("click", closeAuth);
            overlay.addEventListener("click", closeAuth);
            if (showSignInBtn) showSignInBtn.addEventListener("click", () => showPanel("signin"));
            if (showSignUpBtn) showSignUpBtn.addEventListener("click", () => showPanel("signup"));
            if (signInForm) {
                signInForm.addEventListener("submit", async (event) => {
                    event.preventDefault();
                    const formData = new FormData(signInForm);
                    try {
                        const response = await fetch("/shop/sign-in", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Accept": "application/json",
                            },
                            body: formData,
                        });
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            throw new Error(data.message || "Sign in failed.");
                        }
                        window.location.href = "/shop";
                    } catch (error) {
                        if (authMsg) authMsg.textContent = error.message || "Unable to sign in.";
                    }
                });
            }
            if (signUpForm) {
                signUpForm.addEventListener("submit", async (event) => {
                    event.preventDefault();
                    const formData = new FormData(signUpForm);
                    try {
                        const response = await fetch("/shop/sign-up", {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": csrfToken,
                                "Accept": "application/json",
                            },
                            body: formData,
                        });
                        const data = await response.json().catch(() => ({}));
                        if (!response.ok) {
                            throw new Error(data.message || "Sign up failed.");
                        }
                        window.location.href = "/shop";
                    } catch (error) {
                        if (authMsg) authMsg.textContent = error.message || "Unable to sign up.";
                    }
                });
            }
        })();

        (() => {
            const backToTopBtn = document.getElementById("backToTopBtn");
            if (!backToTopBtn) return;

            const handleScroll = () => {
                const shouldShow = window.scrollY > 240;
                backToTopBtn.classList.toggle("show", shouldShow);
            };

            backToTopBtn.addEventListener("click", () => {
                window.scrollTo({ top: 0, behavior: "smooth" });
            });
            window.addEventListener("scroll", handleScroll, { passive: true });
            handleScroll();
        })();
    </script>
</body>
</html>
