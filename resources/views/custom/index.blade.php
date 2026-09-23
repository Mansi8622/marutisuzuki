@extends('custom.master')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

@php
    // Current logged-in user ke wishlist product IDs (heart ko refresh ke baad bhi red dikhane ke liye)
    $wishlistProductIds = [];
    if (Auth::guard('customer')->check()) {
        $wishlistProductIds = \App\Models\Wishlist::where('customer_id', Auth::guard('customer')->user()->id)
                                ->pluck('product_id')->toArray();
    } elseif (Auth::guard('web')->check()) {
        $wishlistProductIds = \App\Models\Wishlist::where('user_id', Auth::guard('web')->user()->id)
                                ->pluck('product_id')->toArray();
    }
@endphp

<style>
                                                                                                                                  
    :root{
        --ink:      #0A0E1A;
        --ink-2:    #121A2E;
        --ink-3:    #1B2540;
        --steel:    #8A93A6;
        --ice:      #F4F6FB;
        --ice-2:    #E7EBF5;
        --volt:     #3D7EFF;
        --volt-dim: #2557C7;
        --ember:    #FF4D5E;
        --mint:     #1FC58C;

        --f-display: 'Sora', sans-serif;
        --f-body:    'Inter', sans-serif;
        --f-mono:    'JetBrains Mono', monospace;

        /* Product-card palette (namespaced so it never clashes with the theme colors above) */
        --msv-navy:        #0B1622;
        --msv-blueprint:   #2F6FA8;
        --msv-orange:      #FF5A1F;
        --msv-orange-dk:   #D9450F;
        --msv-steel:       #6b7d8f;
        --msv-fk-yellow:   #FFD814;
        --msv-fk-yellow-dk:#F7CA00;
        --msv-fk-green:    #388E3C;
    }

    .sb-page{ font-family: var(--f-body); color: var(--ink); background: var(--ice); overflow-x: hidden; }
    .sb-page h1, .sb-page h2, .sb-page h3, .sb-page h5{ font-family: var(--f-display); }
    a.decoration{ text-decoration:none !important; }

    .sb-eyebrow{
        font-family: var(--f-mono);
        font-size: 0.75rem;
        letter-spacing: 0.2em;
        text-transform: uppercase;
        color: var(--volt);
        display:flex; align-items:center; gap:10px;
        margin-bottom: 10px;
    }
    .sb-eyebrow::before{ content:""; width:24px; height:2px; background:var(--ember); display:inline-block; }
    .sb-eyebrow.center{ justify-content:center; }
    .sb-eyebrow.center::before{ display:none; }

    /* ---------- scroll reveal ---------- */
    .reveal{ opacity:0; transform: translateY(28px); transition: opacity .7s cubic-bezier(.22,.61,.36,1), transform .7s cubic-bezier(.22,.61,.36,1); }
    .reveal.in{ opacity:1; transform:none; }
    .reveal-d1{ transition-delay:.08s; } .reveal-d2{ transition-delay:.16s; } .reveal-d3{ transition-delay:.24s; } .reveal-d4{ transition-delay:.32s; }

    @media (prefers-reduced-motion: reduce){
        .reveal{ opacity:1; transform:none; transition:none; }
        *{ animation-duration: 0.001ms !important; animation-iteration-count: 1 !important; }
    }

    /* ---------- Buttons ---------- */
    .sb-btn{
        font-family: var(--f-mono); font-weight:700; font-size:0.82rem;
        letter-spacing:0.06em; text-transform:uppercase; color:#fff;
        background: linear-gradient(90deg, var(--volt), var(--volt-dim));
        border:none; padding: 13px 26px; border-radius: 999px;
        display:inline-flex; align-items:center; gap:10px;
        transition: transform .25s ease, box-shadow .25s ease;
        box-shadow: 0 8px 24px rgba(61,126,255,0.28);
    }
    .sb-btn:hover{ transform: translateY(-3px); color:#fff; box-shadow: 0 14px 30px rgba(61,126,255,0.4); }
    .sb-btn-outline{
        font-family: var(--f-mono); font-weight:700; font-size:0.8rem;
        letter-spacing:0.06em; text-transform:uppercase; color:#fff;
        background:transparent; border:1.5px solid rgba(255,255,255,0.4);
        padding:11px 24px; border-radius:999px; transition: all .25s ease;
    }
    .sb-btn-outline:hover{ background:#fff; color:var(--ink); border-color:#fff; }

    /* ============================================================
       HERO
       ============================================================ */
    .sb-hero{ position:relative; background:var(--ink); overflow:hidden; }
    .sb-hero .carousel-item{ position:relative; }
    .sb-hero .carousel-item img{
        height: 560px; object-fit:cover;
        filter: brightness(0.42) saturate(1.1);
        transform: scale(1.06);
        animation: kenburns 9s ease-in-out infinite alternate;
    }
    @keyframes kenburns{ from{ transform:scale(1.06);} to{ transform:scale(1.14) translateX(-1%);} }

    .sb-hero::before{
        content:"";
        position:absolute; inset:0; z-index:2; pointer-events:none;
        background:
            linear-gradient(180deg, rgba(10,14,26,0.15), rgba(10,14,26,0.55) 70%, var(--ink) 100%);
    }
    .sb-streaks{ position:absolute; inset:0; z-index:1; opacity:0.5; pointer-events:none; }
    .sb-streaks span{
        position:absolute; height:2px; width:40%;
        background: linear-gradient(90deg, transparent, rgba(61,126,255,0.9), transparent);
        animation: streak 3.6s linear infinite;
        opacity:0;
    }
    .sb-streaks span:nth-child(1){ top:18%; animation-delay:0s; }
    .sb-streaks span:nth-child(2){ top:42%; animation-delay:1.1s; }
    .sb-streaks span:nth-child(3){ top:66%; animation-delay:2.2s; }
    .sb-streaks span:nth-child(4){ top:82%; animation-delay:0.6s; }
    @keyframes streak{
        0%{ left:-45%; opacity:0; }
        8%{ opacity:0.8; }
        50%{ opacity:0.5; }
        100%{ left:105%; opacity:0; }
    }

    .sb-hero .carousel-caption{
        z-index:3; text-align:left; left:7%; right:auto; bottom:16%; max-width:600px;
    }
    .sb-hero .carousel-caption .sb-tag{
        font-family: var(--f-mono); font-size:0.72rem; letter-spacing:0.2em; text-transform:uppercase;
        color: var(--ink); background: var(--volt);
        padding:5px 12px; border-radius:999px; display:inline-block; margin-bottom:18px;
    }
    .sb-hero .carousel-caption h1{
        color:#fff; font-size:2.9rem; font-weight:700; line-height:1.12; margin-bottom:24px;
        text-shadow: 0 4px 30px rgba(0,0,0,0.4);
    }
    .sb-hero .carousel-indicators [data-bs-target]{
        background-color: var(--volt); width:30px; height:3px; border-radius:2px; opacity:0.5;
    }
    .sb-hero .carousel-indicators .active{ opacity:1; }

    /* stat strip on hero */
    .sb-stats{
        position:relative; z-index:3;
        background: var(--ink-2);
        border-top: 1px solid rgba(255,255,255,0.08);
    }
    .sb-stats .row > div{
        padding: 26px 10px; text-align:center;
        border-right: 1px solid rgba(255,255,255,0.08);
    }
    .sb-stats .row > div:last-child{ border-right:none; }
    .sb-stats .num{
        font-family: var(--f-display); font-weight:800; font-size:2.1rem; color:#fff;
        display:flex; align-items:baseline; justify-content:center; gap:2px;
    }
    .sb-stats .num span.suffix{ color: var(--volt); font-size:1.4rem; }
    .sb-stats .label{
        font-family: var(--f-mono); font-size:0.7rem; letter-spacing:0.12em; text-transform:uppercase; color: var(--steel);
        margin-top:4px;
    }

    /* ============================================================
       INTRO SPLIT
       ============================================================ */
    .sb-intro{ padding: 90px 0; }
    .sb-intro h1{ font-size:2.2rem; font-weight:700; }
    .sb-intro h5{
        font-family:var(--f-mono); text-transform:none; letter-spacing:0.02em;
        color: var(--volt-dim); font-size:1rem; font-weight:500;
    }
    .sb-intro p{ color:#4B5468; line-height:1.8; }
    .sb-intro .sb-frame{
        position:relative; border-radius:22px; overflow:hidden;
        box-shadow: 0 30px 60px -20px rgba(18,26,46,0.35);
    }
    .sb-intro .sb-frame img{ display:block; width:100%; height:500px; object-fit:cover; transition: transform .6s ease; }
    .sb-intro .sb-frame:hover img{ transform: scale(1.06); }
    .sb-intro .sb-frame .sb-chip{
        position:absolute; bottom:18px; left:18px;
        background: rgba(10,14,26,0.75); backdrop-filter: blur(6px);
        color:#fff; font-family:var(--f-mono); font-size:0.72rem; letter-spacing:0.1em; text-transform:uppercase;
        padding:8px 14px; border-radius:999px; display:flex; align-items:center; gap:8px;
    }
    .sb-intro .sb-frame .sb-chip::before{ content:""; width:8px; height:8px; border-radius:50%; background:var(--mint); box-shadow:0 0 0 4px rgba(31,197,140,0.25); }

    /* ============================================================
       WHY SHOP WITH US
       ============================================================ */
    .sb-why{ background: var(--ink); padding: 90px 0; position:relative; overflow:hidden; }
    .sb-why::after{
        content:""; position:absolute; width:600px; height:600px; border-radius:50%;
        background: radial-gradient(circle, rgba(61,126,255,0.18), transparent 70%);
        top:-250px; right:-150px; pointer-events:none;
    }
    .sb-why .sb-eyebrow{ color: var(--volt); }
    .sb-why > .container > h1{ color:#fff; font-size:2.1rem; margin-bottom:44px; font-weight:700; }
    .cardtext .card{
        background: var(--ink-2); border:1px solid rgba(255,255,255,0.06); border-radius:18px;
        height:100%; padding:6px; transition: transform .3s ease, border-color .3s ease, background .3s ease;
    }
    .cardtext .card:hover{ transform: translateY(-8px); border-color: rgba(61,126,255,0.5); background: var(--ink-3); }
    .cardtext .card-body{ padding: 32px 24px; }
    .cardtext .card-body .fa-solid{
        color: var(--ink); background: var(--volt);
        width:52px; height:52px; border-radius:14px;
        justify-content:center !important; align-items:center; display:flex !important;
        padding:0 !important; font-size:1.3rem !important; margin-bottom:18px;
    }
    .cardtext .card-body h5{ color:#fff !important; font-size:1.02rem; margin-top:0 !important; font-weight:600; }
    .cardtext .card-body p{ color: var(--steel); font-size:0.95rem !important; }

    /* ============================================================
       PARTNERS — marquee
       ============================================================ */
    .sb-partners{ padding: 70px 0 40px; overflow:hidden; }
    .sb-partners h2{ text-align:center; font-size:1.7rem; font-weight:700; margin-bottom: 40px; }
    .sb-marquee{ width:100%; overflow:hidden; -webkit-mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); mask-image: linear-gradient(90deg, transparent, #000 8%, #000 92%, transparent); }
    .sb-marquee-track{ display:flex; gap:22px; width:max-content; animation: marquee 22s linear infinite; }
    .sb-marquee:hover .sb-marquee-track{ animation-play-state: paused; }
    @keyframes marquee{ from{ transform: translateX(0);} to{ transform: translateX(-50%);} }
    .sb-plate{
        border:1px solid var(--ice-2); background:#fff; border-radius:16px;
        padding: 26px 34px; text-align:center; width: 260px; flex: 0 0 auto;
        transition: border-color .2s ease, transform .2s ease, box-shadow .2s ease;
    }
    .sb-plate:hover{ border-color: var(--volt); transform: translateY(-4px); box-shadow: 0 16px 30px -14px rgba(61,126,255,0.3); }
    .sb-plate img{ max-height:56px; object-fit:contain; margin-bottom:12px; }
    .sb-plate p{ font-family:var(--f-mono); font-size:0.82rem; letter-spacing:0.04em; margin:0; color: var(--ink); }

    /* ============================================================
       RAILS
       ============================================================ */
    .sb-rail{ padding: 64px 0; }
    .sb-rail-head{ display:flex; justify-content:space-between; align-items:flex-end; margin-bottom: 30px; }
    .sb-rail-head h2{ font-size:1.9rem; margin:0; font-weight:700; }
    .sb-rail-head a{
        font-family: var(--f-mono); font-size:0.78rem; letter-spacing:0.1em; text-transform:uppercase;
        color: var(--volt-dim) !important; display:flex; align-items:center; gap:6px;
        transition: gap .2s ease;
    }
    .sb-rail-head a:hover{ gap:10px; }

    /* ============================================================
       PRODUCT SLIDER (used by Trending Products)
       ============================================================ */
    .msv-slider-wrap{ position:relative; }
    .msv-slider{ overflow:hidden; }
    .msv-slider-track{
        display:flex; gap:18px;
        transition: transform .6s cubic-bezier(.22,.61,.36,1);
    }
    .msv-slide{ flex:0 0 calc(25% - 13.5px); min-width:0; }
    .msv-slider-btn{
        position:absolute; top:50%; transform:translateY(-50%); z-index:5;
        width:42px; height:42px; border-radius:50%; background:#fff;
        border:1px solid #e6edf3; box-shadow:0 6px 16px rgba(11,22,34,.12);
        display:flex; align-items:center; justify-content:center; cursor:pointer;
        color:#16283C; transition: background .2s ease, color .2s ease, box-shadow .2s ease;
    }
    .msv-slider-btn:hover{ background:var(--msv-orange); color:#fff; box-shadow:0 10px 22px rgba(255,90,31,.28); }
    .msv-slider-btn.prev{ left:-20px; }
    .msv-slider-btn.next{ right:-20px; }
    @media (max-width:991.98px){
        .msv-slide{ flex:0 0 calc(50% - 9px); }
        .msv-slider-btn.prev{ left:-10px; }
        .msv-slider-btn.next{ right:-10px; }
    }
    @media (max-width:575.98px){
        .msv-slide{ flex:0 0 calc(100% - 0px); }
        .msv-slider-wrap{ padding:0 6px; }
        .msv-slider-btn{ width:34px; height:34px; }
        .msv-slider-btn.prev{ left:-4px; }
        .msv-slider-btn.next{ right:-4px; }
    }


    /* ============================================================
       BRAND / COMPANY TILE  (Explore Our Company Products)
       ============================================================ */
    .msv-brand-card{
        background:#fff; border:1px solid #e6edf3; border-radius:16px;
        padding: 26px 14px; text-align:center; height:100%;
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }
    .msv-brand-card:hover{
        transform: translateY(-6px); border-color: var(--msv-orange);
        box-shadow: 0 18px 32px -16px rgba(11,22,34,0.20);
    }
    .msv-brand-card .logo-wrap{
        width:112px; height:112px; margin:0 auto 14px; border-radius:50%;
        background:#f5f7fa; display:flex; align-items:center; justify-content:center;
        overflow:hidden; border:1px solid #e6edf3;
    }
    .msv-brand-card .logo-wrap img{ width:78%; height:78%; object-fit:contain; }
    .msv-brand-card h5{
        font-family: var(--f-body); font-weight:600; font-size:0.95rem; color:#16283C;
        margin:0; text-transform:none;
    }

    /* ============================================================
       PRODUCT CARD — same Flipkart/Amazon-style card used on the
       product listing page, reused here for New Launches, Trending
       Products and More to Explore so the design matches exactly.
       ============================================================ */
    .msv-product-card{
        border:1px solid #e6edf3; border-radius:4px; overflow:hidden;
        background:#fff; height:100%;
        transition: box-shadow .2s ease, transform .2s ease;
    }
    .msv-product-card:hover{ box-shadow:0 8px 20px rgba(16,32,48,.10); transform: translateY(-2px); }

    .msv-img-wrap{ position:relative; overflow:hidden; background:#f5f7fa; }
    .msv-img-wrap img{
        width:100%; height:200px; object-fit:contain; display:block;
        padding:.75rem; transition: transform .3s ease;
        background:#fff;
    }
    .msv-product-card:hover .msv-img-wrap img{ transform: scale(1.04); }

    .msv-wish{
        position:absolute; top:8px; right:8px; z-index:3;
        width:28px; height:28px; border-radius:50%; background:#fff;
        border:1px solid #e6edf3; display:flex; align-items:center; justify-content:center;
        color:#9aa7b3; font-size:.85rem; box-shadow:0 1px 4px rgba(16,32,48,.08);
        cursor:pointer;
    }
    .msv-wish.active{ background:var(--msv-orange); border-color:var(--msv-orange); }
    .msv-wish.active i{ color:#fff; }

    .msv-tag{
        position:absolute; top:10px; left:10px; z-index:2;
        background:var(--msv-orange) !important; color:#fff !important;
        font-family:'JetBrains Mono', monospace; font-size:.65rem; font-weight:600;
        letter-spacing:.03em; text-transform:uppercase;
        padding:.28rem .5rem !important; border-radius:2px;
    }

    .msv-card-body{ padding:.85rem .9rem 1rem; text-align:left; }
    .msv-product-name{
        font-size:.85rem; color:#212121; font-weight:400; min-height:2.3em;
        overflow:hidden; text-overflow:ellipsis; display:-webkit-box;
        -webkit-line-clamp:2; -webkit-box-orient:vertical; line-height:1.3;
        text-transform:none;
    }

    .msv-item-code{
        font-size:.72rem; color:var(--msv-steel); font-family:'JetBrains Mono', monospace;
        margin:.15rem 0 .3rem;
    }
    .msv-item-code span{ color:#26333f; font-weight:600; }

    .msv-assured{
        display:inline-flex; align-items:center; gap:.25rem;
        font-size:.72rem; color:var(--msv-blueprint); font-weight:600;
    }
    .msv-assured i{ color:var(--msv-blueprint); }

    .msv-price-row{ margin:.45rem 0 .55rem; display:flex; align-items:baseline; gap:.55rem; flex-wrap:wrap; }
    .msv-price-row .cur{ font-size:1.1rem; font-weight:800; color:#171717; font-family:'Inter', sans-serif; letter-spacing:-.02em; }
    .msv-price-row .mrp-price{ color:#8a8a8a; font-size:.82rem; font-weight:400; text-decoration:line-through; text-decoration-thickness:1.5px; }
    .msv-price-row .off{ color:var(--msv-fk-green); font-size:.78rem; font-weight:700; }

    .msv-actions{ display:flex; gap:.5rem; margin-top:.6rem; }
    .msv-add-cart{
        background:var(--msv-fk-yellow); color:#0F1111; border:1px solid #FCD200;
        width:100%; font-family:'Inter'; font-weight:600; font-size:.85rem;
        letter-spacing:.01em; padding:.5rem 0; border-radius:20px;
        display:flex; align-items:center; justify-content:center; gap:.4rem;
        box-shadow:0 1px 0 rgba(0,0,0,.05);
        transition: background .15s ease, box-shadow .15s ease;
    }
    .msv-add-cart:hover{ background:var(--msv-fk-yellow-dk); color:#0F1111; box-shadow:0 2px 6px rgba(0,0,0,.12); }
    .msv-add-cart:active{ background:var(--msv-fk-yellow-dk); transform: translateY(1px); }
    .msv-add-cart i{ font-size:.82rem; }

    /* ============================================================
       DISCOUNT BANNER
       ============================================================ */
    .sb-discount{
        position:relative; border-radius: 28px; overflow:hidden;
        background: var(--ink); min-height: 380px; display:flex; align-items:center;
    }
    .sb-discount img.bg{
        position:absolute; inset:0; width:100%; height:100%; object-fit:cover;
        opacity:0.4; filter: saturate(1.1);
    }
    .sb-discount::before{
        content:""; position:absolute; inset:0;
        background: linear-gradient(100deg, rgba(10,14,26,0.96) 35%, rgba(10,14,26,0.5) 75%, rgba(10,14,26,0.15));
    }
    .sb-discount-content{ position:relative; z-index:2; padding: 60px 50px; }
    .sb-discount-content .sb-eyebrow{ color: var(--ember); }
    .sb-discount-content .sb-eyebrow::before{ background: var(--volt); }
    .sb-discount-content h1.display-1{ color:#fff; font-weight:800; font-size:4.2rem; }
    .sb-discount-content h1.sub{ color: var(--steel); font-size:1.5rem; font-weight:500; margin-bottom: 20px; }

    /* ============================================================
       BUDGET CARDS
       ============================================================ */
    .sb-budget{
        background: linear-gradient(145deg, #fff, var(--ice));
        border: 1px solid var(--ice-2); border-radius: 22px; position:relative; overflow:hidden;
        transition: transform .3s ease, box-shadow .3s ease;
    }
    .sb-budget:hover{ transform: translateY(-6px); box-shadow: 0 24px 44px -20px rgba(18,26,46,0.25); }
    .sb-budget::before{
        content:""; position:absolute; top:-60px; right:-60px; width:180px; height:180px; border-radius:50%;
        background: radial-gradient(circle, rgba(61,126,255,0.18), transparent 70%);
    }
    .sb-budget h1{ font-size:1.4rem; margin-bottom:0; font-weight:600; }
    .sb-budget h1.fw-bold{ font-size:2.2rem; }
    .sb-budget p{ font-family:var(--f-mono); font-size:0.8rem; letter-spacing:0.06em; color: var(--steel); }

    @media (max-width: 767px){
        .sb-hero .carousel-caption{ left:6%; right:6%; max-width:none; bottom:11%; }
        .sb-hero .carousel-caption h1{ font-size:1.55rem; margin-bottom:16px; }
        .sb-hero .carousel-caption .sb-tag{ margin-bottom:12px; }
        .sb-hero .carousel-caption .sb-btn{ padding:11px 20px; font-size:0.72rem; }
        .sb-hero .carousel-item img{ height:420px; }

        .sb-intro{ padding: 48px 0; }
        .sb-intro h1{ font-size:1.7rem; }
        .sb-intro .sb-frame img{ height:260px; }

        .sb-why{ padding: 56px 0; }
        .sb-why > .container > h1{ font-size:1.6rem; margin-bottom:28px; }
        .cardtext .card-body{ padding: 22px 18px; }

        .sb-rail{ padding: 42px 0; }
        .sb-rail-head{ flex-direction:column; align-items:flex-start; gap:8px; margin-bottom:20px; }
        .sb-rail-head h2{ font-size:1.4rem; }

        .msv-img-wrap img{ height:150px !important; }
        .msv-brand-card .logo-wrap{ width:88px; height:88px; }

        .sb-discount{ min-height:300px; border-radius:18px; }
        .sb-discount-content{ padding: 32px 22px; }
        .sb-discount-content h1.display-1{ font-size:2.3rem; }
        .sb-discount-content h1.sub{ font-size:1.1rem; }

        .sb-budget{ padding: 8px 0; }
        .sb-budget h1{ font-size:1.15rem; }
        .sb-budget h1.fw-bold{ font-size:1.7rem; }

        .sb-stats .row > div{ border-right:none; border-bottom:1px solid rgba(255,255,255,0.08); padding: 18px 8px; }
        .sb-stats .num{ font-size:1.6rem; }
    }

    @media (max-width: 420px){
        .sb-hero .carousel-item img{ height:380px; }
        .sb-hero .carousel-caption h1{ font-size:1.32rem; }
    }
</style>

<div class="sb-page">

<!-- ============ HERO / CAROUSEL ============ -->
<div id="carouselExampleDark" class="carousel carousel-dark slide sb-hero" data-bs-ride="carousel">
  <div class="sb-streaks"><span></span><span></span><span></span><span></span></div>
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>
  <div class="carousel-inner">
    <div class="carousel-item active" data-bs-interval="5000">
      <img src="https://images.pexels.com/photos/32726107/pexels-photo-32726107.jpeg?auto=compress&cs=tinysrgb&w=1920" class="d-block w-100" alt="Performance alloy wheel">
      <div class="carousel-caption">
        <span class="sb-tag">100% Genuine Fitment</span>
        <h1>Built for Your Ride. Backed by the Brand You Trust.</h1>
        <button class="sb-btn">Shop the Range <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="5000">
      <img src="https://images.pexels.com/photos/34036091/pexels-photo-34036091.jpeg?auto=compress&cs=tinysrgb&w=1920" class="d-block w-100" alt="Premium wheel detail">
      <div class="carousel-caption">
        <span class="sb-tag">Precision Engineered</span>
        <h1>Every Accessory, Fitted With Precision.</h1>
        <button class="sb-btn">Shop the Range <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>
    <div class="carousel-item" data-bs-interval="5000">
      <img src="https://images.pexels.com/photos/5158160/pexels-photo-5158160.jpeg?auto=compress&cs=tinysrgb&w=1920" class="d-block w-100" alt="Modern car interior">
      <div class="carousel-caption">
        <span class="sb-tag">Interior & Beyond</span>
        <h1>From Dashboard to Wheel Arch — All Genuine.</h1>
        <button class="sb-btn">Shop the Range <i class="fa-solid fa-arrow-right"></i></button>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- ============ STAT STRIP ============ -->
<div class="sb-stats">
  <div class="container">
    <div class="row">
      <div class="col-6 col-md-3">
        <div class="num" data-count="100"><span class="val">0</span><span class="suffix">%</span></div>
        <div class="label">Genuine Parts</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="num" data-count="3"><span class="val">0</span><span class="suffix">+</span></div>
        <div class="label">Trusted Brands</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="num" data-count="500"><span class="val">0</span><span class="suffix">+</span></div>
        <div class="label">Accessories Listed</div>
      </div>
      <div class="col-6 col-md-3">
        <div class="num" data-count="24"><span class="val">0</span><span class="suffix">h</span></div>
        <div class="label">Dispatch Time</div>
      </div>
    </div>
  </div>
</div>

<!-- ============ INTRO SPLIT ============ -->
<div class="container sb-intro">
  <div class="row align-items-center g-5">
    <div class="col-12 col-lg-6 reveal">
      <div class="sb-eyebrow">Maruti Suzuki Ventures</div>
      <h1><b>Genuine Accessories, Built to Fit</b></h1>
      <h5 class="mt-2"><b>Your Trusted Source for Genuine Vehicle Accessories</b></h5>
      <p class="fs-5 mt-3">Looking to upgrade, protect, or personalize your vehicle? You are in the right place.
      We bring you a wide range of 100% genuine car accessories — sourced directly from trusted manufacturers like , , and EEMOT.
      <br><br>
      Whether you drive a Swift, Baleno, Brezza, Alto, or any other Maruti Suzuki model, we have accessories designed to fit perfectly and perform reliably.</p>
      <a href="/product" class="decoration"><button class="sb-btn-outline" style="color:var(--ink); border-color: var(--ink);">Browse Accessories</button></a>
    </div>
    <div class="col-12 col-lg-6 reveal reveal-d2">
      <div class="sb-frame">
        <img src="https://images.pexels.com/photos/9145477/pexels-photo-9145477.jpeg?auto=compress&cs=tinysrgb&w=1400" alt="Maruti Suzuki Ventures interior accessories">
        <div class="sb-chip">In Stock &amp; Ready to Ship</div>
      </div>
    </div>
  </div>
</div>

<!-- ============ WHY SHOP WITH US ============ -->
<div class="cardtext">
  <div class="sb-why">
    <div class="container">
      <div class="sb-eyebrow">Why Shop With Us</div>
      <h1 class="reveal">Built On Trust, Fitted For Life</h1>
      <div class="row mt-3 g-4">
        <div class="col-6 col-lg-3 reveal reveal-d1">
          <div class="card">
            <div class="card-body">
              <i class="fa-solid fa-handshake"></i>
              <h5><b>Authentic, Original &amp; Trusted Products</b></h5>
              <p class="fs-5">All our car accessories are original, high-quality, reliable, and made to last.</p>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3 reveal reveal-d2">
          <div class="card">
            <div class="card-body">
              <i class="fa-solid fa-thumbs-up"></i>
              <h5><b>Reliable, Recognized, Genuine, Quality Brands</b></h5>
              <p class="fs-5">We partner with top automotive names like , , and EEMOT.</p>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3 reveal reveal-d3">
          <div class="card">
            <div class="card-body">
              <i class="fa-solid fa-cart-shopping"></i>
              <h5><b>Easy &amp; Secure Online Shopping</b></h5>
              <p class="fs-5">Browse by model, category, or need — fast delivery right to your doorstep.</p>
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3 reveal reveal-d4">
          <div class="card">
            <div class="card-body">
              <i class="fa-solid fa-magnifying-glass"></i>
              <h5><b>Wide Range of Accessories</b></h5>
              <p class="fs-5">Seat covers to alloy wheels, infotainment to safety – we offer everything.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ============ PARTNERS — marquee ============ -->
<!-- <div class="container sb-partners">
  <h2 class="reveal">Our Partners</h2>
</div>
<div class="sb-marquee">
  <div class="sb-marquee-track">
    <a href="https://xenos.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/-logo.png"><p> Xenos</p></div></a>
    <a href="https://www.eemotrack.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/eemot-logo.webp"><p>EEMOTRACK</p></div></a>
    <a href="https://accessories.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/_logo.avif"><p> Accessories</p></div></a>
    <a href="https://xenos.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/-logo.png"><p> Xenos</p></div></a>
    <a href="https://www.eemotrack.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/eemot-logo.webp"><p>EEMOTRACK</p></div></a>
    <a href="https://accessories.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/_logo.avif"><p> Accessories</p></div></a>
  </div>
</div> -->

<!-- ============ SECTION 1: COMPANY PRODUCTS ============ -->
<section class="sb-rail">
  <div class="container">
    <div class="sb-rail-head reveal">
      <h2>Explore Our Company Products</h2>
      <a href="/product" class="decoration">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="row g-3">
           @foreach($allproducts as $company)
        @php
            // DB me company ka logo set nahi hai to EEMOT ke liye local fallback image use karo
            $companyLogoUrl = $company->company_logo->first()?->getUrl('preview');
            if (!$companyLogoUrl && str_contains(strtolower($company->company_name), 'eemot')) {
                $companyLogoUrl = asset('asset/img/logo.webp');
            }
        @endphp
        <div class="col-lg-3 col-6 mb-3 reveal">
          <a href="{{ route('company.products', $company->id) }}" class="decoration">
            <div class="msv-brand-card">
              <div class="logo-wrap">
                <img src="{{ $companyLogoUrl }}" alt="{{ $company->company_name }}">
              </div>
              <h5>{{ $company->company_name }}</h5>
            </div>
          </a>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ============ SECTION 2: NEW LAUNCHES ============ -->
<section class="sb-rail" style="background: var(--ice-2);">
  <div class="container">
    <div class="sb-rail-head reveal">
      <h2>New Launches</h2>
      <a href="/product" class="decoration">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="row g-3">
      @foreach($newproducts as $product)
        @php
            $mrp = (float) ($product->price ?? 0);
            if (Auth::guard('customer')->check()) {
                $displayPrice = (float) ($product->rate_2 ?? $mrp);
            } elseif (Auth::guard('web')->check()) {
                $displayPrice = (float) ($product->price_1 ?? $mrp);
            } else {
                $displayPrice = (float) $product->sellingPrice();
            }
            if ($displayPrice <= 0) { $displayPrice = $mrp; }
            $priceDiscount = 0;
            if ($mrp > 0 && $displayPrice < $mrp) {
                $priceDiscount = round((($mrp - $displayPrice) / $mrp) * 100);
            }
            $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        @endphp
        <div class="col-6 col-lg-3 mb-3 reveal">
          <div class="card border-0 msv-product-card position-relative">
            <span class="msv-wish {{ $isWishlisted ? 'active' : '' }}" onclick="toggleWishlist(event, {{ $product->id }})">
              <i class="fa-{{ $isWishlisted ? 'solid' : 'regular' }} fa-heart"></i>
            </span>
            <a href="/product-detail/{{ $product->id }}" class="decoration">
              <div class="msv-img-wrap">
                @if($product->tags->isNotEmpty())
                  @foreach($product->tags as $tag)
                    <span class="msv-tag">{{ $tag->name }}</span>
                  @endforeach
                @endif
                <img src="{{ $product->photo->first()?->getUrl() }}" alt="{{ $product->name }}">
              </div>
              <div class="msv-card-body">
                <h5 class="msv-product-name">{{ $product->name }}</h5>
                @if($product->item_code)
                  <p class="msv-item-code mb-0">Item: <span>{{ $product->item_code }}</span></p>
                @endif
                <div class="msv-assured mb-1"><i class="fa-solid fa-shield-halved"></i> Assured</div>
                <div class="msv-price-row">
                  <span class="cur">₹{{ number_format($displayPrice, 0) }}</span>
                  <del class="mrp-price">₹{{ number_format($mrp, 0) }}</del>
                  @if($priceDiscount > 0)<span class="off">{{ $priceDiscount }}% off</span>@endif
                </div>
              </div>
            </a>
            <div class="px-3 pb-3">
              <form action="{{ route('cart.add') }}" method="POST" class="msv-actions">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="name" value="{{ $product->name }}">
                <input type="hidden" name="price" value="{{ $product->price }}">
                <input type="hidden" name="discount" value="{{ $product->discount }}">
                <input type="hidden" name="price_1" value="{{ $product->price_1 }}">
                <input type="hidden" name="rate_2" value="{{ $product->rate_2 }}">
                <input type="hidden" name="quantity" value="{{ $product->quantity }}">
                <input type="hidden" name="description" value="{{ $product->description }}">
                <input type="hidden" name="photo" value="{{ $product->photo->first()?->getUrl() ?? 'default.png' }}">
                <button type="submit" class="msv-add-cart"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

<!-- ============ SECTION 3: DISCOUNT BANNER ============ -->
<section class="sb-rail">
  <div class="container">
    <div class="sb-discount reveal">
      <img class="bg" src="https://images.pexels.com/photos/30734966/pexels-photo-30734966.jpeg?auto=compress&cs=tinysrgb&w=1920" alt="Discount on accessories">
      <div class="sb-discount-content">
        <div class="sb-eyebrow">Limited Time</div>
        <h1 class="display-1">50%–80%</h1>
        <h1 class="sub">off on all accessories</h1>
        <a href="/product" class="decoration"><button class="sb-btn">Shop Now <i class="fa-solid fa-arrow-right"></i></button></a>
      </div>
    </div>
  </div>
</section>

<!-- ============ SECTION 4: TRENDING PRODUCTS ============ -->
<section class="sb-rail">
  <div class="container">
    <div class="sb-rail-head reveal">
      <h2>Trending Products</h2>
      <a href="/product" class="decoration">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="msv-slider-wrap reveal">
      <button type="button" class="msv-slider-btn prev" onclick="msvSlide('trendingSlider',-1)" aria-label="Previous">
        <i class="fa-solid fa-chevron-left"></i>
      </button>
      <div class="msv-slider" id="trendingSlider">
        <div class="msv-slider-track">
          @foreach($trendingproducts as $product)
            @php
                $mrp = (float) ($product->price ?? 0);
                if (Auth::guard('customer')->check()) {
                    $displayPrice = (float) ($product->rate_2 ?? $mrp);
                } elseif (Auth::guard('web')->check()) {
                    $displayPrice = (float) ($product->price_1 ?? $mrp);
                } else {
                    $displayPrice = (float) $product->sellingPrice();
                }
                if ($displayPrice <= 0) { $displayPrice = $mrp; }
                $priceDiscount = 0;
                if ($mrp > 0 && $displayPrice < $mrp) {
                    $priceDiscount = round((($mrp - $displayPrice) / $mrp) * 100);
                }
                $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
            @endphp
            <div class="msv-slide">
              <div class="card border-0 msv-product-card position-relative">
                <span class="msv-wish {{ $isWishlisted ? 'active' : '' }}" onclick="toggleWishlist(event, {{ $product->id }})">
                  <i class="fa-{{ $isWishlisted ? 'solid' : 'regular' }} fa-heart"></i>
                </span>
                <a href="/product-detail/{{ $product->id }}" class="decoration">
                  <div class="msv-img-wrap">
                    @if($product->tags->isNotEmpty())
                      @foreach($product->tags as $tag)
                        <span class="msv-tag">{{ $tag->name }}</span>
                      @endforeach
                    @endif
                    <img src="{{ $product->photo->first()?->getUrl() }}" alt="{{ $product->name }}">
                  </div>
                  <div class="msv-card-body">
                    <h5 class="msv-product-name">{{ $product->name }}</h5>
                    @if($product->item_code)
                      <p class="msv-item-code mb-0">Item: <span>{{ $product->item_code }}</span></p>
                    @endif
                    <div class="msv-assured mb-1"><i class="fa-solid fa-shield-halved"></i> Assured</div>
                    <div class="msv-price-row">
                      <span class="cur">₹{{ number_format($displayPrice, 0) }}</span>
                      <del class="mrp-price">₹{{ number_format($mrp, 0) }}</del>
                      @if($priceDiscount > 0)<span class="off">{{ $priceDiscount }}% off</span>@endif
                    </div>
                  </div>
                </a>
                <div class="px-3 pb-3">
                  <form action="{{ route('cart.add') }}" method="POST" class="msv-actions">
                    @csrf
                    <input type="hidden" name="id" value="{{ $product->id }}">
                    <input type="hidden" name="name" value="{{ $product->name }}">
                    <input type="hidden" name="price" value="{{ $product->price }}">
                    <input type="hidden" name="discount" value="{{ $product->discount }}">
                    <input type="hidden" name="price_1" value="{{ $product->price_1 }}">
                    <input type="hidden" name="rate_2" value="{{ $product->rate_2 }}">
                    <input type="hidden" name="quantity" value="{{ $product->quantity }}">
                    <input type="hidden" name="description" value="{{ $product->description }}">
                    <input type="hidden" name="photo" value="{{ $product->photo->first()?->getUrl() ?? 'default.png' }}">
                    <button type="submit" class="msv-add-cart"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                  </form>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
      <button type="button" class="msv-slider-btn next" onclick="msvSlide('trendingSlider',1)" aria-label="Next">
        <i class="fa-solid fa-chevron-right"></i>
      </button>
    </div>
  </div>
</section>

<!-- ============ SECTION 5: BUDGET SHOP ============ -->
<section class="sb-rail" style="background: var(--ice-2); padding-top: 40px;">
  <div class="container">
    <div class="row g-3">
      <div class="col-12 col-lg-6 reveal">
        <div class="sb-budget text-center py-5">
          <h1>Shop</h1>
          <h1 class="fw-bold">Under <strong style="color: var(--volt);">₹ 1999</strong></h1>
          <p>Car Accessories | Bike Accessories</p>
          <a href="/product" class="decoration"><button class="sb-btn mt-3">Shop Now <i class="fa-solid fa-arrow-right"></i></button></a>
        </div>
      </div>
      <div class="col-12 col-lg-6 reveal reveal-d2">
        <div class="sb-budget text-center py-5">
          <h1>Shop</h1>
          <h1 class="fw-bold">Under <strong style="color: var(--ember);">₹ 1999</strong></h1>
          <p>Car Accessories | Bike Accessories</p>
          <a href="/product" class="decoration"><button class="sb-btn mt-3">Shop Now <i class="fa-solid fa-arrow-right"></i></button></a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============ SECTION 6: MORE TO EXPLORE ============ -->
<section class="sb-rail">
  <div class="container">
    <div class="sb-rail-head reveal">
      <h2>More to Explore</h2>
      <a href="/product" class="decoration">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="row g-3">
      @foreach($exploreproducts as $product)
        @php
            $mrp = (float) ($product->price ?? 0);
            if (Auth::guard('customer')->check()) {
                $displayPrice = (float) ($product->rate_2 ?? $mrp);
            } elseif (Auth::guard('web')->check()) {
                $displayPrice = (float) ($product->price_1 ?? $mrp);
            } else {
                $displayPrice = (float) $product->sellingPrice();
            }
            if ($displayPrice <= 0) { $displayPrice = $mrp; }
            $priceDiscount = 0;
            if ($mrp > 0 && $displayPrice < $mrp) {
                $priceDiscount = round((($mrp - $displayPrice) / $mrp) * 100);
            }
            $isWishlisted = in_array($product->id, $wishlistProductIds ?? []);
        @endphp
        <div class="col-6 col-lg-3 mb-3 reveal">
          <div class="card border-0 msv-product-card position-relative">
            <span class="msv-wish {{ $isWishlisted ? 'active' : '' }}" onclick="toggleWishlist(event, {{ $product->id }})">
              <i class="fa-{{ $isWishlisted ? 'solid' : 'regular' }} fa-heart"></i>
            </span>
            <a href="/product-detail/{{ $product->id }}" class="decoration">
              <div class="msv-img-wrap">
                @if($product->tags->isNotEmpty())
                  @foreach($product->tags as $tag)
                    <span class="msv-tag">{{ $tag->name }}</span>
                  @endforeach
                @endif
                <img src="{{ $product->photo->first()?->getUrl() }}" alt="{{ $product->name }}">
              </div>
              <div class="msv-card-body">
                <h5 class="msv-product-name">{{ $product->name }}</h5>
                @if($product->item_code)
                  <p class="msv-item-code mb-0">Item: <span>{{ $product->item_code }}</span></p>
                @endif
                <div class="msv-assured mb-1"><i class="fa-solid fa-shield-halved"></i> Assured</div>
                <div class="msv-price-row">
                  <span class="cur">₹{{ number_format($displayPrice, 0) }}</span>
                  <del class="mrp-price">₹{{ number_format($mrp, 0) }}</del>
                  @if($priceDiscount > 0)<span class="off">{{ $priceDiscount }}% off</span>@endif
                </div>
              </div>
            </a>
            <div class="px-3 pb-3">
              <form action="{{ route('cart.add') }}" method="POST" class="msv-actions">
                @csrf
                <input type="hidden" name="id" value="{{ $product->id }}">
                <input type="hidden" name="name" value="{{ $product->name }}">
                <input type="hidden" name="price" value="{{ $product->price }}">
                <input type="hidden" name="discount" value="{{ $product->discount }}">
                <input type="hidden" name="price_1" value="{{ $product->price_1 }}">
                <input type="hidden" name="rate_2" value="{{ $product->rate_2 }}">
                <input type="hidden" name="quantity" value="{{ $product->quantity }}">
                <input type="hidden" name="description" value="{{ $product->description }}">
                <input type="hidden" name="photo" value="{{ $product->photo->first()?->getUrl() ?? 'default.png' }}">
                <button type="submit" class="msv-add-cart"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
              </form>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Scroll-reveal
    var revealEls = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('in');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });
        revealEls.forEach(function (el) { io.observe(el); });
    } else {
        revealEls.forEach(function (el) { el.classList.add('in'); });
    }

    // Count-up stats
    var statEls = document.querySelectorAll('.sb-stats .num');
    if ('IntersectionObserver' in window && statEls.length) {
        var statIO = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (!entry.isIntersecting) return;
                var el = entry.target;
                var target = parseInt(el.getAttribute('data-count'), 10) || 0;
                var valEl = el.querySelector('.val');
                var duration = 1200;
                var startTime = null;
                function step(ts) {
                    if (!startTime) startTime = ts;
                    var progress = Math.min((ts - startTime) / duration, 1);
                    var eased = 1 - Math.pow(1 - progress, 3);
                    valEl.textContent = Math.floor(eased * target);
                    if (progress < 1) requestAnimationFrame(step);
                    else valEl.textContent = target;
                }
                requestAnimationFrame(step);
                statIO.unobserve(el);
            });
        }, { threshold: 0.4 });
        statEls.forEach(function (el) { statIO.observe(el); });
    }
});

// ---------------------------------------------------------------
// Product slider (Trending Products) — auto-advances one card at a
// time, pauses on hover, and supports the prev/next arrow buttons.
// ---------------------------------------------------------------
const msvSliders = {};

function msvItemsPerView() {
    const w = window.innerWidth;
    if (w <= 575.98) return 1;
    if (w <= 991.98) return 2;
    return 4;
}

function msvInitSlider(id) {
    const wrap = document.getElementById(id);
    if (!wrap) return;
    const track = wrap.querySelector('.msv-slider-track');
    const slides = track.querySelectorAll('.msv-slide');
    const total = slides.length;
    if (!total) return;

    const state = { index: 0, track, wrap, total, timer: null };
    msvSliders[id] = state;

    function maxIndex() {
        return Math.max(total - msvItemsPerView(), 0);
    }

    function update() {
        const slideWidth = wrap.clientWidth / msvItemsPerView();
        track.style.transform = `translateX(-${state.index * slideWidth}px)`;
    }
    state.update = update;

    function autoNext() {
        const mi = maxIndex();
        state.index = state.index >= mi ? 0 : state.index + 1;
        update();
    }

    function play() {
        if (maxIndex() <= 0) return; // nothing to slide
        state.timer = setInterval(autoNext, 3000);
    }
    function pause() { clearInterval(state.timer); }

    window.addEventListener('resize', function () {
        state.index = Math.min(state.index, maxIndex());
        update();
    });

    wrap.addEventListener('mouseenter', pause);
    wrap.addEventListener('mouseleave', play);

    update();
    play();
}

function msvSlide(id, dir) {
    const state = msvSliders[id];
    if (!state) return;
    const mi = Math.max(state.total - msvItemsPerView(), 0);
    state.index = Math.min(Math.max(state.index + dir, 0), mi);
    state.update();
}

document.addEventListener('DOMContentLoaded', function () {
    msvInitSlider('trendingSlider');
});

// Wishlist toggle — used by the "New Launches", "Trending Products" and
// "More to Explore" product cards above. Uses event.currentTarget instead
// of an element id, so it works correctly even if the same product card
// appears more than once on this page.
function toggleWishlist(event, productId) {
    event.preventDefault();
    event.stopPropagation();

    const wrapper = event.currentTarget;
    const icon = wrapper.querySelector('i');
    const url = "{{ url('/add-to-wishlist') }}/" + productId;

    fetch(url, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(res => {
        if (res.status === 401) {
            alert("Please login to add items to your wishlist.");
            return null;
        }
        return res.json();
    })
    .then(data => {
        if (!data) return;
        if (data.status === 'added') {
            wrapper.classList.add('active');
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
        } else if (data.status === 'removed') {
            wrapper.classList.remove('active');
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(err => console.error('Wishlist error:', err));
}
</script>

@endsection