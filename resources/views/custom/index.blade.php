@extends('custom.master')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

<style>
    /* ============================================================
       DESIGN TOKENS — "Velocity" identity
       Deep ignition-blue night sky, cool ice-white sections,
       electric blue + ember red accents. Motion is the material.
       ============================================================ */
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

    /* ---------- product cards ---------- */
    .sb-product{
        border:1px solid var(--ice-2); background:#fff; border-radius:18px;
        position:relative; height:100%; overflow:hidden;
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }
    .sb-product:hover{ transform: translateY(-6px); border-color: transparent; box-shadow: 0 24px 40px -18px rgba(18,26,46,0.28); }
    .sb-product .card-body{ padding: 18px; }
    .sb-product .best{
        border-radius: 999px !important; background: var(--ember) !important;
        font-family: var(--f-mono); font-size:0.65rem !important; letter-spacing:0.06em; text-transform:uppercase;
        opacity:1 !important; top:12px !important; left:12px !important; padding: 5px 12px !important;
    }
    .sb-product img{ transition: transform .5s ease; border-radius: 12px; }
    .sb-product:hover img{ transform: scale(1.05); }
    .sb-product h5{
        font-family: var(--f-body); text-transform:none; font-weight:600; font-size:0.98rem; margin-top:14px;
    }
    .sb-product strong{ font-family: var(--f-mono); display:block; }
    .sb-product strong del{ color: var(--steel); font-weight:400; font-size:0.85rem; display:block; }
    .sb-product strong p{ color: var(--mint); font-size:1.15rem; font-weight:700; margin:2px 0 0 0; }
    .sb-product .primary-bg{
        background: var(--ink) !important; border-radius: 999px !important;
        font-family: var(--f-mono); font-size:0.78rem; letter-spacing:0.06em; text-transform:uppercase;
        transition: background .2s ease;
    }
    .sb-product .primary-bg:hover{ background: var(--volt) !important; }

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

    /* ============================================================
       EXPLORE GRID
       ============================================================ */
    .sb-explore .card{ border:1px solid var(--ice-2); border-radius:18px; position:relative; height:100%; overflow:hidden; transition: transform .3s ease, box-shadow .3s ease; }
    .sb-explore .card:hover{ transform: translateY(-6px); box-shadow: 0 24px 40px -18px rgba(18,26,46,0.22); }
    .sb-explore .card img{ transition: transform .5s ease; }
    .sb-explore .card:hover img{ transform: scale(1.05); }
    .sb-explore .btn.position-absolute{
        background: rgba(255,255,255,0.9) !important; border:none; border-radius:50% !important;
        width:38px; height:38px; display:flex; align-items:center; justify-content:center; backdrop-filter: blur(4px);
        transition: background .2s ease;
    }
    .sb-explore .btn.position-absolute:hover{ background: var(--ember) !important; }
    .sb-explore .btn.position-absolute:hover i{ color:#fff; }
    .sb-explore .btn.position-absolute i{ color: var(--ember); transition: color .2s ease; }
    .sb-explore h5{ font-family: var(--f-body); text-transform:none; font-weight:600; font-size:0.95rem; }
    .sb-explore strong p{ color: var(--mint); font-weight:700; }
    .sb-explore strong del{ color: var(--steel); font-size:0.85rem; }

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

        .sb-product .card-body,
        .sb-explore .card .text-center.p-3{ padding: 12px; }
        .sb-product .card-body img,
        .sb-explore .card img{ height:150px !important; }

        .image-container{ width:110px !important; height:110px !important; }

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
      We bring you a wide range of 100% genuine car accessories — sourced directly from trusted manufacturers like Pricol, JCBL, and EEMOT.
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
              <p class="fs-5">We partner with top automotive names like Pricol, JCBL, and EEMOT.</p>
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
    <a href="https://pricolxenos.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/pricol-logo.png"><p>Pricol Xenos</p></div></a>
    <a href="https://www.eemotrack.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/eemot-logo.webp"><p>EEMOTRACK</p></div></a>
    <a href="https://jcblaccessories.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/jcbl_logo.avif"><p>JCBL Accessories</p></div></a>
    <a href="https://pricolxenos.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/pricol-logo.png"><p>Pricol Xenos</p></div></a>
    <a href="https://www.eemotrack.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/eemot-logo.webp"><p>EEMOTRACK</p></div></a>
    <a href="https://jcblaccessories.com/" target="blank" class="decoration"><div class="sb-plate"><img src="asset/img/jcbl_logo.avif"><p>JCBL Accessories</p></div></a>
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
        <div class="col-lg-3 col-6 mb-3 reveal">
          <a href="{{ route('company.products', $company->id) }}" class="decoration">
            <div class="card border-0 text-center">
              <div class="image-container" style="width: 160px; height: 160px; margin: 0 auto;">
                <img src="{{ $company->company_logo->first()?->getUrl('preview') }}"
                     alt="{{ $company->company_name }}"
                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">
              </div>
              <div class="text-center p-0">
                <h5 class="text-center">{{ $company->company_name }}</h5>
              </div>
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
      <div class="col-6 col-lg-3 mb-3 reveal">
        <a href="/product-detail/{{ $product->id }}" class="decoration">
          <form action="{{ route('cart.add') }}" method="POST">
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

            <div class="card sb-product">
              <div class="card-body">
                @if($product->tags->isNotEmpty())
                  @foreach($product->tags as $tag)
                    <button class="position-absolute btn text-white px-2 py-1 best">
                      {{ $tag->name }}
                    </button>
                  @endforeach
                @endif

                <div class="text-center">
                  <img src="{{ $product->photo->first()?->getUrl() }}" alt="{{ $product->name }}" style="width: 100%; height:220px; object-fit:cover;">
                </div>

                <div class="text-center p-0">
                  <h5 class="text-center text-capitalize">{{ $product->name }}</h5>
                  <strong>
                    @if (Auth::guard('web')->check())
                      <del class="fw-bold">MRP :- ₹ {{ $product->price - ($product->price * $product->discount / 100) }}</del>
                      <p>₹ {{ $product->price_1 }}</p>
                    @elseif (Auth::guard('customer')->check())
                      <del class="fw-bold">MRP :- ₹ {{ $product->price - ($product->price * $product->discount / 100) }}</del>
                      <p>Price :- ₹ {{ $product->rate_2 }}</p>
                    @else
                      <p>Price ₹{{ $product->price - ($product->price * $product->discount / 100) }}</p>
                      <del>MRP :- ₹ {{ $product->price }}</del>
                    @endif
                  </strong>
                  <button type="submit" class="btn text-white w-100 py-2 primary-bg mt-2">Add to Cart</button>
                </div>
              </div>
            </div>
          </form>
        </a>
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
    <div class="row g-3">
      @foreach($trendingproducts as $product)
      <div class="col-6 col-lg-3 mb-3 reveal">
        <a href="/product-detail/{{ $product->id }}" class="decoration">
          <form action="{{ route('cart.add') }}" method="POST">
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

            <div class="card sb-product">
              <div class="card-body">
                @if($product->tags->isNotEmpty())
                  @foreach($product->tags as $tag)
                    <button class="position-absolute btn text-white px-2 py-1 best">
                      {{ $tag->name }}
                    </button>
                  @endforeach
                @endif

                <div class="text-center">
                  <img src="{{ $product->photo->first()?->getUrl() }}" alt="{{ $product->name }}" style="width: 100%; height:220px; object-fit:cover;">
                </div>

                <div class="text-center p-0">
                  <h5 class="text-center">{{ $product->name }}</h5>
                  <strong>
                    @if (Auth::guard('web')->check())
                      <del class="fw-bold">MRP :- ₹ {{ $product->price - ($product->price * $product->discount / 100) }}</del>
                      <p>₹ {{ $product->price_1 }}</p>
                    @elseif (Auth::guard('customer')->check())
                      <del class="fw-bold">MRP :- ₹ {{ $product->price - ($product->price * $product->discount / 100) }}</del>
                      <p>Price :- ₹ {{ $product->rate_2 }}</p>
                    @else
                      <p>Price ₹{{ $product->price - ($product->price * $product->discount / 100) }}</p>
                      <del>MRP :- ₹ {{ $product->price }}</del>
                    @endif
                  </strong>
                  <button type="submit" class="btn text-white w-100 py-2 primary-bg mt-4">Add to Cart</button>
                </div>
              </div>
            </div>
          </form>
        </a>
      </div>
      @endforeach
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
<section class="sb-rail sb-explore">
  <div class="container">
    <div class="sb-rail-head reveal">
      <h2>More to Explore</h2>
      <a href="/product" class="decoration">View All <i class="fa-solid fa-arrow-right"></i></a>
    </div>
    <div class="row g-3">
      @foreach($exploreproducts as $product)
      <div class="col-6 col-lg-3 mb-3 reveal">
        <a href="/product-detail/{{ $product->id }}" class="decoration">
          <div class="card">
            <button class="position-absolute top-0 end-0 btn me-3 mt-3">
              <i class="fa-regular fa-heart"></i>
            </button>
            <div class="text-center">
              <img src="{{ $product->photo->first()?->getUrl() }}" alt="{{ $product->name }}" style="width: 100%; height:220px; object-fit:cover;">
            </div>
            <div class="text-center p-3">
              <h5 class="text-center">{{ $product->name }}</h5>
              <strong>
                @if (Auth::guard('web')->check())
                  <del class="fw-bold">MRP :- ₹ {{ $product->price - ($product->price * $product->discount / 100) }}</del>
                  <p>₹ {{ $product->price_1 }}</p>
                @elseif (Auth::guard('customer')->check())
                  <del class="fw-bold">MRP :- ₹ {{ $product->price - ($product->price * $product->discount / 100) }}</del>
                  <p>Price :- ₹ {{ $product->rate_2 }}</p>
                @else
                  <p>Price ₹{{ $product->price - ($product->price * $product->discount / 100) }}</p>
                  <del>MRP :- ₹ {{ $product->price }}</del>
                @endif
              </strong>
            </div>
          </div>
        </a>
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
</script>

@endsection