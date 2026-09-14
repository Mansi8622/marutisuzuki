@php
    use App\Models\ProductCategory;
    $categories = ProductCategory::orderBy('name')->get(); // Sorted alphabetically
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Maruti Suzuki Ventures</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <!-- Google Fonts: condensed industrial display + clean body + mono for specs -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{asset('asset/css/style.css')}}">

<style>
/* ============================================================
   MSV "FITTING GUIDE" THEME
   Concept: parts catalogue / workshop blueprint. Corner-bracket
   "fit frames" (from the tagline "Perfect Fit") mark every
   interactive element like alignment reticles on a spec sheet.
   ============================================================ */
:root{
  --navy:        #0B1622;
  --navy-2:      #101F30;
  --navy-3:      #16283C;
  --line:        rgba(111,168,220,0.14);
  --blueprint:   #2F6FA8;
  --blueprint-lt:#6FA8DC;
  --orange:      #FF5A1F;
  --orange-dk:   #D9450F;
  --paper:       #EBF1F6;
  --steel:       #9FB3C6;
}

body{ font-family:'Inter', system-ui, sans-serif; color:#1c2a37; }

@media (prefers-reduced-motion: reduce){
  *{ animation-duration:0.001ms !important; animation-iteration-count:1 !important; transition-duration:0.001ms !important; }
}

/* ---------- shared bracket "fit frame" ---------- */
.fit-frame{ position:relative; }
.fit-frame::before,
.fit-frame::after{
  content:"";
  position:absolute; width:12px; height:12px;
  border:2px solid var(--orange);
  opacity:0; transition:opacity .2s ease, transform .2s ease;
  pointer-events:none;
}
.fit-frame::before{ top:-6px; left:-6px; border-right:0; border-bottom:0; transform:scale(.6); }
.fit-frame::after{ bottom:-6px; right:-6px; border-left:0; border-top:0; transform:scale(.6); }
.fit-frame:hover::before, .fit-frame:hover::after,
.fit-frame:focus-within::before, .fit-frame:focus-within::after{
  opacity:1; transform:scale(1);
}

/* ---------- blueprint grid texture ---------- */
.blueprint-grid{
  background-image:
    linear-gradient(var(--line) 1px, transparent 1px),
    linear-gradient(90deg, var(--line) 1px, transparent 1px);
  background-size: 26px 26px;
}

/* ================= TOP UTILITY BAR ================= */
.top-bar{
  background: var(--navy);
  color: var(--steel);
  border-bottom: 1px solid rgba(111,168,220,0.2);
  font-family:'IBM Plex Mono', monospace;
  letter-spacing:.02em;
}
.top-bar .container{ min-height:38px; font-size:.78rem; }
.top-bar a{ color: var(--steel); transition: color .15s; }
.top-bar a:hover{ color: var(--orange); }
.top-bar .divider-dot{ color: rgba(159,179,198,.4); margin:0 .65rem; }

#userSection .dropdown-toggle{ color: var(--paper) !important; }
#userSection .dropdown-menu{
  background: var(--navy-2); border:1px solid var(--line); border-radius:2px;
}
#userSection .dropdown-item{ color: var(--paper); font-family:'Inter'; font-size:.85rem; }
#userSection .dropdown-item:hover{ background: var(--navy-3); color: var(--orange); }

/* ================= MAIN NAVBAR ================= */
nav.navbar{
  background: var(--navy-2);
  padding: .9rem 0;
  border-bottom: 1px solid var(--line);
  position: relative;
  /* The live-search panel must extend beyond the navbar, above the category bar. */
  overflow: visible;
  /* z-index: 1050; */
}
/* subtle scanning sweep across the navbar on load, like a fitting-check pass */
nav.navbar::before{
  content:"";
  position:absolute; top:0; left:-40%; width:35%; height:100%;
  background: linear-gradient(100deg, transparent, rgba(111,168,220,.14), transparent);
  animation: sweep 3.2s ease-in-out 1;
}
@keyframes sweep{
  0%{ left:-40%; }
  100%{ left:110%; }
}

.navbar-toggler{ color: var(--paper); font-size:1.3rem; cursor:pointer; }

.navbar-brand{
  background: var(--paper);
  padding: .35rem .6rem;
  border-radius: 3px;
  line-height: 0;
  margin-right: 1.25rem;
}
.navbar-brand img{ width:100px; display:block; }

/* search: styled like a spec-lookup field, with fit-frame corners */
.header-search{ flex: 1 1 auto; max-width: 480px; position: relative; margin: 0 1rem; }
.header-search input{
  background: var(--navy);
  border: 1px solid rgba(111,168,220,.3);
  color: var(--paper);
  border-radius: 2px;
  padding: .55rem 2.4rem .55rem .9rem;
  font-size: .92rem;
}
.header-search input::placeholder{ color: var(--steel); }
.header-search input:focus{
  background: var(--navy);
  color: var(--paper);
  border-color: var(--orange);
  box-shadow: 0 0 0 3px rgba(255,90,31,.15);
}
.header-search::after{
  content:"\f002"; font-family:"Font Awesome 6 Free"; font-weight:900;
  position:absolute; right:.9rem; top:50%; transform:translateY(-50%);
  color: var(--steel); pointer-events:none; font-size:.85rem;
}
.header-search .form-control{ position:relative; z-index:2; }
.search-suggestions{
  position:absolute; z-index:1080; top:calc(100% + .55rem); left:0; right:0;
  background:#fff; border:1px solid rgba(111,168,220,.32); border-top:3px solid var(--orange);
  border-radius:3px; box-shadow:0 18px 38px rgba(0,0,0,.32); overflow:hidden;
  color:#1c2a37;
}
.search-suggestions[hidden]{ display:none !important; }
.search-suggestion-head{
  display:flex; align-items:center; justify-content:space-between; padding:.58rem .75rem;
  background:#f4f8fb; border-bottom:1px solid #dce6ee; color:#607687;
  font:600 .7rem 'IBM Plex Mono', monospace; text-transform:uppercase; letter-spacing:.06em;
}
.search-suggestion{
  display:flex; align-items:center; gap:.75rem; padding:.62rem .75rem; color:#1c2a37;
  text-decoration:none; border-bottom:1px solid #edf1f4; transition:background .15s, padding-left .15s;
}
.search-suggestion:last-child{ border-bottom:0; }
.search-suggestion:hover, .search-suggestion.is-active{ background:#fff4ef; color:#1c2a37; padding-left:.95rem; }
.search-suggestion__image{
  width:52px; height:52px; flex:0 0 52px; border:1px solid #d8e2ea; border-radius:3px;
  background:#f7fafc; display:flex; align-items:center; justify-content:center; overflow:hidden;
}
.search-suggestion__image img{ width:100%; height:100%; object-fit:contain; }
.search-suggestion__image i{ color:#9fb3c6; font-size:1.15rem; }
.search-suggestion__body{ min-width:0; flex:1; }
.search-suggestion__name{ display:block; font-size:.88rem; font-weight:700; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.search-suggestion__meta{ display:block; margin-top:.18rem; color:#637788; font-size:.74rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.search-suggestion__price{ color:var(--orange-dk); font:700 .92rem 'IBM Plex Mono', monospace; white-space:nowrap; }
.search-empty{ padding:1.1rem .9rem; text-align:center; color:#637788; font-size:.85rem; }
.search-empty i{ color:var(--orange); margin-right:.35rem; }

.icon-link{
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  color: var(--paper); text-decoration:none; font-size:.68rem;
  padding: .4rem .85rem; margin-left:.25rem;
  letter-spacing:.03em; text-transform:uppercase;
}
.icon-link i{ font-size:1.15rem; margin-bottom:.15rem; transition: transform .2s; }
.icon-link:hover{ color: var(--orange); }
.icon-link:hover i{ transform: translateY(-2px); }

.cart-badge{
  position:absolute; top:-2px; right:2px;
  background: var(--orange); color: var(--navy);
  font-size:10px; font-weight:700; font-family:'IBM Plex Mono', monospace;
  border-radius:50%; width:17px; height:17px;
  display:flex; align-items:center; justify-content:center;
  border: 2px solid var(--navy-2);
}
.cart-badge.has-items{ animation: pulse 1.8s ease-in-out infinite; }
@keyframes pulse{
  0%,100%{ transform: scale(1); }
  50%{ transform: scale(1.18); }
}

/* ================= OFFCANVAS (mobile) ================= */
.offcanvas{
  background: var(--navy) !important;
  opacity: 1 !important;
  border-right:1px solid rgba(111,168,220,.22) !important;
  box-shadow:18px 0 44px rgba(0,0,0,.42);
}
/* The navbar uses z-index:1050 for search results.  Keep the open menu and
   its backdrop above it so the original logo/search can never bleed through. */
#offcanvasMenu{ z-index:1080; }
.offcanvas-backdrop{ z-index:1070; }
.offcanvas-header{ border-bottom:1px solid var(--line); padding:1rem 1.15rem; }
.offcanvas-header h5, .offcanvas .btn-close{ color: var(--paper); }
.offcanvas .btn-close{ filter: invert(1) grayscale(1) brightness(2); opacity:.8; }
.offcanvas-body{ padding:1rem 1.15rem 1.5rem; }
.mobile-menu-brand{
  display:flex; align-items:center; gap:.8rem; padding:.8rem;
  background:var(--navy-2); border:1px solid var(--line); border-left:3px solid var(--orange);
  margin-bottom:1.15rem;
}
.mobile-menu-brand img{ width:54px; height:54px; object-fit:contain; background:var(--paper); border-radius:3px; padding:.2rem; }
.mobile-menu-brand strong{ display:block; color:var(--paper); font-size:.9rem; }
.mobile-menu-brand small{ color:var(--steel); font:500 .66rem 'IBM Plex Mono', monospace; text-transform:uppercase; letter-spacing:.06em; }
.mobile-menu-list{ margin:0; }
.offcanvas-body a.mobile-menu-link{
  color:var(--paper) !important; padding:.85rem .72rem; margin-bottom:.35rem;
  display:flex; align-items:center; gap:.8rem; text-decoration:none; border:1px solid transparent;
  border-radius:3px; font:700 1.06rem 'Barlow Condensed', sans-serif; letter-spacing:.05em; text-transform:uppercase;
  transition:transform .2s ease, background .2s ease, border-color .2s ease, color .2s ease;
  animation:drawerLinkIn .36s both;
}
.offcanvas.show .mobile-menu-list li:nth-child(1) a{ animation-delay:.05s; }
.offcanvas.show .mobile-menu-list li:nth-child(2) a{ animation-delay:.1s; }
.offcanvas.show .mobile-menu-list li:nth-child(3) a{ animation-delay:.15s; }
.offcanvas.show .mobile-menu-list li:nth-child(4) a{ animation-delay:.2s; }
.offcanvas.show .mobile-menu-list li:nth-child(5) a{ animation-delay:.25s; }
.offcanvas-body a.mobile-menu-link i{ color:var(--orange); width:22px; text-align:center; }
.offcanvas-body a.mobile-menu-link span:last-child{ margin-left:auto; font-size:.7rem; color:var(--steel); transition:transform .2s ease; }
.offcanvas-body a.mobile-menu-link:hover, .offcanvas-body a.mobile-menu-link:focus{
  color:var(--paper) !important; background:var(--navy-3); border-color:var(--line); transform:translateX(5px);
}
.offcanvas-body a.mobile-menu-link:hover span:last-child{ transform:translateX(3px); color:var(--orange); }
.mobile-menu-help{ margin-top:1.25rem; padding:.85rem .75rem; border-top:1px dashed var(--line); color:var(--steel); font-size:.75rem; }
.mobile-menu-help a{ color:var(--paper); text-decoration:none; font:600 .8rem 'IBM Plex Mono', monospace; }
@keyframes drawerLinkIn{ from{ opacity:0; transform:translateX(-14px); } to{ opacity:1; transform:translateX(0); } }

/* ================= CATEGORY / SECOND BAR ================= */
.header3{
  background: var(--navy-3);
  border-bottom: 3px solid var(--orange);
  position: relative;
}
.header3 ul{ margin:0; gap:.25rem; }
.header3 > .container{ position: relative; }
.header3 .decoration{
  color: var(--paper); text-decoration:none; font-family:'Barlow Condensed', sans-serif;
  font-weight:600; letter-spacing:.04em; text-transform:uppercase;
  padding: .6rem .9rem; display:inline-block; font-size:1.02rem;
  border-bottom: 2px solid transparent; transition: border-color .15s, color .15s;
}
.header3 .decoration:hover{ color: var(--orange); border-color: var(--orange); }

.header3 .dropdown-toggle{
  color: var(--navy); background: var(--orange);
  font-family:'Barlow Condensed', sans-serif; font-weight:700; text-transform:uppercase;
  letter-spacing:.04em; padding:.6rem 1.1rem; cursor:pointer;
  display:flex; align-items:center; gap:.5rem;
}
.header3 .dropdown-toggle::before{
  content:"\f5fd"; font-family:"Font Awesome 6 Free"; font-weight:900; /* ruler-combined */
}
.header3 .dropdown-menu{
  background: var(--navy-2);
  border: 1px solid var(--line);
  border-radius: 0 0 3px 3px;
  padding: .5rem;
  min-width: 320px;
  box-shadow: 0 18px 34px rgba(0,0,0,.35);
  animation: menuDrop .18s ease-out;
}
@keyframes menuDrop{
  from{ opacity:0; transform: translateY(-6px); }
  to{ opacity:1; transform: translateY(0); }
}
.header3 .dropdown-item{
  color: var(--paper) !important; border-radius:2px; padding:.5rem .6rem;
  font-size:.92rem; transition: background .15s, padding-left .15s;
}
.header3 .dropdown-item:hover{ background: var(--navy-3); padding-left:.85rem; }
.header3 .dropdown-item img{ border:1px solid var(--line); }

/* ================= TRUST STRIP (layout-level, appears on every page) ================= */
.trust-strip{
  background: var(--paper);
  border-bottom: 1px solid #d7e1ea;
}
.trust-strip .container{
  display:flex; flex-wrap:wrap; gap:0;
}
.trust-item{
  flex:1 1 220px; display:flex; align-items:center; gap:.65rem;
  padding: .85rem 1rem; position:relative;
}
.trust-item + .trust-item::before{
  content:""; position:absolute; left:0; top:20%; bottom:20%; width:1px;
  background: #cbd8e3;
}
.trust-item i{
  color: var(--orange); font-size:1.15rem; flex-shrink:0;
  width:34px; height:34px; border:2px solid var(--orange); border-radius:50%;
  display:flex; align-items:center; justify-content:center;
}
.trust-item span{
  font-family:'Inter'; font-weight:600; font-size:.82rem; color: var(--navy);
  line-height:1.2; text-transform:uppercase; letter-spacing:.02em;
}

/* ================= FOOTER ================= */
footer.footer{
  background: var(--navy);
  color: var(--steel);
  position: relative;
  overflow: hidden;
  border-top: 3px solid var(--orange);
}
footer.footer .blueprint-watermark{
  position:absolute; right:-2%; bottom:-4%; width:46%; max-width:520px;
  opacity:.10; pointer-events:none;
}
footer.footer p, footer.footer .detail p{ color: var(--steel); }
footer.footer h1{
  font-family:'Barlow Condensed', sans-serif; font-weight:700; text-transform:uppercase;
  letter-spacing:.06em; font-size:1.15rem; color: var(--paper);
  border-bottom: 2px solid var(--orange); display:inline-block; padding-bottom:.35rem; margin-bottom:1rem;
}
footer.footer .detail p{ display:flex; align-items:center; gap:.5rem; margin-bottom:.5rem; }
footer.footer .detail i{ color: var(--orange); width:18px; }
footer.footer ul.list-unstyled li{ margin-bottom:.4rem; }
footer.footer ul.list-unstyled a.decoration{
  color: var(--steel) !important; text-decoration:none; font-size:.92rem;
  display:flex; align-items:center; gap:.55rem; transition: color .15s, transform .15s;
}
footer.footer ul.list-unstyled a.decoration i{ color: var(--orange); font-size:.75rem; transition: transform .15s; }
footer.footer ul.list-unstyled a.decoration:hover{ color: var(--paper) !important; transform: translateX(3px); }
footer.footer ul.list-unstyled a.decoration:hover i{ transform: translateX(3px); }

footer.footer input.form-control{
  background: var(--navy-2); border:1px solid rgba(111,168,220,.3); color: var(--paper);
  border-radius:2px;
}
footer.footer input.form-control::placeholder{ color: var(--steel); }
footer.footer input.form-control:focus{ border-color: var(--orange); box-shadow:0 0 0 3px rgba(255,90,31,.15); }

.btn.primary-bg{
  background: var(--orange); border:none; position:relative; overflow:hidden;
  font-family:'Inter'; font-weight:600; border-radius:2px;
  transition: transform .15s;
}
.btn.primary-bg:hover{ background: var(--orange-dk); transform: translateY(-1px); }

footer.footer .row.mt-3{ border-top:1px solid var(--line); padding-top:1.1rem; margin-top:2rem !important; }
footer.footer .row.mt-3 p{ font-size:.8rem; color: var(--steel); margin:0; }
footer.footer ul.d-flex li{ list-style:none; }
footer.footer ul.d-flex i{
  color: var(--paper); background: var(--navy-2); border:1px solid var(--line);
  width:42px; height:42px; border-radius:50%; display:flex; align-items:center; justify-content:center;
  font-size:1.05rem !important; padding:0 !important; margin-left:.6rem; transition: background .15s, color .15s, transform .15s;
}
footer.footer ul.d-flex i:hover{ background: var(--orange); color: var(--navy); transform: translateY(-3px); }

@media (max-width: 991.98px){
  nav.navbar{ padding:.7rem 0 .85rem; }
  nav.navbar .container{ gap:.65rem; }
  .navbar-toggler{ width:42px; height:42px; display:grid; place-items:center; padding:0; border:1px solid var(--line) !important; border-radius:3px; }
  .navbar-brand{ margin:0; padding:.25rem .45rem; }
  .navbar-brand img{ width:86px; height:64px; object-fit:contain; }
  header .header-search{ display:block; order:3; flex:0 0 100%; min-width:0; max-width:none; margin:.15rem 0 0; }
  .header-search input{ min-height:46px; font-size:16px; padding-left:1rem; }
  .navbar .d-flex.align-items-center{ margin-left:auto; }
  .icon-link{ padding:.35rem .45rem; margin-left:.1rem; min-width:40px; }
  .icon-link i{ font-size:1.28rem; margin:0; }
  .cart-badge{ top:-4px; right:-3px; }
  .top-bar .container{ justify-content:center !important; min-height:32px; font-size:.67rem; }
  .top-bar .container > div{ margin:0 .35rem !important; }
  .top-bar .divider-dot{ margin:0 .15rem; }
  .trust-item{ flex:1 1 50%; padding:.72rem .6rem; }
  .trust-item span{ font-size:.72rem; }
  .trust-item i{ width:31px; height:31px; font-size:1rem; }
}
@media (max-width: 575.98px){
  .top-bar .container{ justify-content:flex-start !important; overflow-x:auto; white-space:nowrap; }
  .top-bar .container > div:nth-of-type(2), .top-bar .divider-dot, .top-bar .container > div:nth-of-type(3){ display:none; }
  nav.navbar .container{ padding-left:.8rem; padding-right:.8rem; }
  .navbar-brand img{ width:78px; height:58px; }
  .navbar-toggler{ width:39px; height:39px; }
  .icon-link{ min-width:36px; padding:.3rem .34rem; }
  .icon-link i{ font-size:1.2rem; }
  .header-search{ margin-top:.35rem !important; }
  .search-suggestions{ max-height:58vh; overflow-y:auto; }
  .trust-strip .container{ padding:0; }
  .trust-item{ flex-basis:100%; padding:.7rem .8rem; }
  .trust-item + .trust-item::before{ top:0; bottom:auto; left:.8rem; right:.8rem; width:auto; height:1px; }
  /* A phone menu is a dedicated screen, not a narrow strip over the header. */
  #offcanvasMenu{ width:100vw !important; max-width:none; }
  .offcanvas-header{ padding:.9rem 1rem; }
  .offcanvas-body{ padding:1rem; }
  .mobile-menu-brand{ margin-bottom:1rem; }
}
</style>
</head>
<body>

    <header>

  <!-- Top utility bar -->
  <div class="top-bar">
    <div class="container d-flex justify-content-end align-items-center">
      <div class="me-3">
        <small>
          <div id="userSection" class="me-3">
@if(Auth::check())
    {{-- Reseller Logged In --}}
    <div class="dropdown">
        <a href="#" class="dropdown-toggle" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
            <i class="fa fa-user"></i> &nbsp; {{ Auth::user()->name }}
        </a>
        <ul class="dropdown-menu" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="/home">Dashboard</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </li>
        </ul>
    </div>

@elseif(auth('customer')->check())
    {{-- Customer Logged In --}}
    @php $customer = auth('customer')->user(); @endphp
    <div class="dropdown">
        <a href="#" class="dropdown-toggle d-flex align-items-center" id="customerDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="text-decoration: none;">
            @if($customer->profile_photo)
                <img src="{{ asset('storage/' . $customer->profile_photo) }}" alt="Profile Photo" class="rounded-circle me-2" width="28" height="28" style="border:2px solid var(--orange);">
            @endif
            <span class="fw-bold">{{ $customer->name }}</span>
        </a>
        <ul class="dropdown-menu" aria-labelledby="customerDropdown">
            <li><a class="dropdown-item" href="{{ route('customer.dashboard') }}">Dashboard</a></li>
            <li>
                <form method="POST" action="{{ route('customer.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </li>
        </ul>
    </div>

@else
    {{-- Not Logged In --}}
    <a href="/login" style="text-decoration: none;">
        <i class="fa-regular fa-user"></i> &nbsp; Login/Register
    </a>
@endif
          </div>
        </small>
      </div>
      <div class="me-3">
        <small><i class="bi bi-geo-alt"></i> Track Your Order</small>
      </div>
      <span class="divider-dot">|</span>
      <div class="ms-3">
        <small><i class="fa-solid fa-headset"></i> Helpline +91 78578 68055</small>
      </div>
    </div>
  </div>

  <!-- Main Navbar -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">

      <!-- Mobile Menu Toggle -->
      <button type="button" class="navbar-toggler d-lg-none border-0" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu" aria-label="Open menu">
        <i class="fa-solid fa-bars"></i>
      </button>

      <a class="navbar-brand" href="/">
        <img src="{{ asset('asset/img/msv-logo.png') }}" alt="Maruti Suzuki Ventures" width="100" class="img-fluid">
      </a>

      <!-- Search Bar -->
      <div class="header-search fit-frame" id="productSearch">
        <input type="search" class="form-control" id="productSearchInput" placeholder="Search parts, brands, categories..." aria-label="Search products" autocomplete="off" role="combobox" aria-autocomplete="list" aria-expanded="false" aria-controls="productSearchResults">
        <div class="search-suggestions" id="productSearchResults" role="listbox" hidden></div>
      </div>

      <!-- Right Section -->
      <div class="d-flex align-items-center">
        <a href="{{ route('frontend.wishlist') }}" class="icon-link fit-frame">
          <i class="fa-regular fa-heart" style="color: rgb(240, 240, 240);"></i></i><span class="text-white">Wishlist</span>
        </a>
        <a href="/cart" class="icon-link position-relative fit-frame">
          <i class="fa-solid fa-cart-shopping" style="color: rgb(240, 240, 240);"></i>
          <span class="text-white">Cart</span>
          @php $cartCount = session('cart') ? count(session('cart')) : 0; @endphp
          <span class="cart-badge {{ $cartCount > 0 ? 'has-items' : '' }}">{{ $cartCount }}</span>
        </a>
      </div>

    </div>
  </nav>

  <!-- Off-Canvas Menu for Mobile -->
  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasMenuLabel"><i class="fa-solid fa-ruler-combined me-2"></i>Menu</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <div class="mobile-menu-brand">
        <img src="{{ asset('asset/img/msv-logo.png') }}" alt="Maruti Suzuki Ventures">
        <div><strong>Maruti Suzuki Ventures</strong><small>Genuine parts, perfect fit</small></div>
      </div>
      <ul class="list-unstyled mobile-menu-list">
        <li><a href="/" class="mobile-menu-link"><i class="fa-solid fa-house"></i>Home <span class="fa-solid fa-arrow-right"></span></a></li>
        <li><a href="/product" class="mobile-menu-link"><i class="fa-solid fa-gears"></i>Products <span class="fa-solid fa-arrow-right"></span></a></li>
        <li><a href="/offer" class="mobile-menu-link"><i class="fa-solid fa-tags"></i>Offers <span class="fa-solid fa-arrow-right"></span></a></li>
        <li><a href="/about-us" class="mobile-menu-link"><i class="fa-solid fa-building"></i>About us <span class="fa-solid fa-arrow-right"></span></a></li>
        <li><a href="/contact" class="mobile-menu-link"><i class="fa-solid fa-headset"></i>Contact <span class="fa-solid fa-arrow-right"></span></a></li>
      </ul>
      <div class="mobile-menu-help"><i class="fa-solid fa-phone-volume me-2" style="color:var(--orange)"></i>Need help? <a href="tel:+917857868055">+91 78578 68055</a></div>
    </div>
  </div>

  <!-- Category bar -->
  <div class="header3 py-1">
    <div class="container">
      <ul class="list-unstyled d-flex fs-5 align-items-stretch">
        <li>
          <div class="dropdown-toggle" type="button" data-bs-toggle="dropdown">
            Categories
          </div>
          <ul class="dropdown-menu">
            @foreach($categories as $category)
              <li>
                <a href="{{ route('category.products', $category->id) }}" class="dropdown-item text-dark d-flex align-items-center">
                  @if($category->photo)
                    <img src="{{ $category->photo->preview }}" alt="{{ $category->name }}" style="width:25px;height:25px;object-fit:cover;" class="me-2 rounded">
                  @else
                    <i class="fa-solid fa-gear me-2" style="color:var(--orange);width:25px;text-align:center;"></i>
                  @endif
                  {{ $category->name }}
                </a>
              </li>
            @endforeach
          </ul>
        </li>
        <li><a href="/" class="decoration">Home</a></li>
        <li><a href="/product" class="decoration">Products</a></li>
        <li><a href="/offer" class="decoration">Offers</a></li>
        <li><a href="/about-us" class="decoration">About</a></li>
      </ul>
    </div>
  </div>

  <!-- Trust strip: reinforces "Perfect Fit" promise on every page -->
  <div class="trust-strip">
    <div class="container">
      <div class="trust-item"><i class="fa-solid fa-check"></i><span>Genuine Fit&nbsp;Guarantee</span></div>
      <div class="trust-item"><i class="fa-solid fa-truck-fast"></i><span>Pan-India Delivery</span></div>
      <div class="trust-item"><i class="fa-solid fa-rotate-left"></i><span>Easy 7-Day Returns</span></div>
      <div class="trust-item"><i class="fa-solid fa-screwdriver-wrench"></i><span>Certified Installers</span></div>
    </div>
  </div>

</header>

<!-- header end -->


@yield('content')


<!-- footer start -->
<footer class="footer py-5">

  <!-- decorative blueprint car watermark (custom SVG, not a real vehicle) -->
  <svg class="blueprint-watermark" viewBox="0 0 600 260" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <g fill="none" stroke="#6FA8DC" stroke-width="2">
      <path d="M40 180 C40 140 90 120 140 118 L190 80 C210 66 240 60 270 60 L360 60 C395 60 420 78 440 112 L470 118 C500 122 525 140 530 168 L530 180"/>
      <circle cx="140" cy="190" r="34"/>
      <circle cx="430" cy="190" r="34"/>
      <circle cx="140" cy="190" r="10"/>
      <circle cx="430" cy="190" r="10"/>
      <line x1="40" y1="212" x2="530" y2="212" stroke-dasharray="4 5"/>
      <line x1="40" y1="230" x2="40" y2="212"/>
      <line x1="530" y1="230" x2="530" y2="212"/>
      <line x1="40" y1="228" x2="530" y2="228" stroke-dasharray="2 4"/>
    </g>
    <text x="270" y="246" fill="#6FA8DC" font-family="IBM Plex Mono, monospace" font-size="12" text-anchor="middle">PERFECT&#8202;FIT&#8202;&#8212;&#8202;EVERY&#8202;VEHICLE</text>
  </svg>

  <div class="container position-relative">
    <div class="row">
      <div class="col-lg-4 mb-3">
        <img src="{{ asset('asset/img/msv-logo.png') }}" alt="Maruti Suzuki Ventures" width="100" class="img-fluid mb-2" style="background:var(--paper);padding:.35rem .6rem;border-radius:3px;">
        <p><b style="color:var(--paper);">Maruti Suzuki Ventures</b></p>
        <p><b>Perfect Fit Accessories for Every Vehicle</b></p>
        <div class="detail">
          <p><i class="fa-solid fa-phone"></i> 7857868055</p>
          <p><i class="fa-solid fa-location-dot"></i> Patna &middot; Bengaluru &middot; Pune &middot; Punjab</p>
          <p><i class="fa-regular fa-envelope"></i> support@marutisuzukiventures.online</p>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="row">
          <div class="col-lg-4 col-6 mb-3">
            <h1>Quick Links</h1>
            <ul class="list-unstyled">
              <li><a href="/" class="decoration"><i class="fa-solid fa-chevron-right"></i> Home</a></li>
              <li><a href="/about" class="decoration"><i class="fa-solid fa-chevron-right"></i> About</a></li>
              <li><a href="/offer" class="decoration"><i class="fa-solid fa-chevron-right"></i> Offers</a></li>
              <li><a href="/product" class="decoration"><i class="fa-solid fa-chevron-right"></i> Products</a></li>
            </ul>
          </div>

          <div class="col-lg-4 col-6 mb-3">
            <h1>Support Center</h1>
            <ul class="list-unstyled">
              <li><a href="/privacy" class="decoration"><i class="fa-solid fa-chevron-right"></i> Privacy Policy</a></li>
              <li><a href="/term-condition" class="decoration"><i class="fa-solid fa-chevron-right"></i> Terms &amp; Conditions</a></li>
              <li><a href="/refund" class="decoration"><i class="fa-solid fa-chevron-right"></i> Refund</a></li>
              <li><a href="/contact" class="decoration"><i class="fa-solid fa-chevron-right"></i> Contact</a></li>
            </ul>
          </div>

          <div class="col-lg-4 mb-3">
            <h1>Newsletter</h1>
            <p>Fitment guides, launch alerts, and workshop offers — straight to your inbox.</p>
            <input type="email" class="form-control" placeholder="Your email" name="newsletter_email" id="newsletter_email">
            <button class="btn primary-bg text-white px-3 py-2 mt-3">Subscribe Now <i class="fa-solid fa-paper-plane ms-1"></i></button>
          </div>
        </div>
      </div>
    </div>

    <div class="row mt-3">
      <div class="col-lg-6 mb-3">
        <p>&copy; Copyright 2025 EEMOT All Rights Reserved. Designed &amp; Customized: Mansi</p>
      </div>
      <div class="col-lg-6 d-flex justify-content-lg-end">
        <ul class="d-flex m-0 p-0">
          <li><i class="fa-brands fa-facebook-f"></i></li>
          <li><i class="fa-brands fa-twitter"></i></li>
          <li><i class="fa-brands fa-instagram"></i></li>
        </ul>
      </div>
    </div>
  </div>
</footer>
<!-- footer end -->

  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    (() => {
      const input = document.getElementById('productSearchInput');
      const results = document.getElementById('productSearchResults');
      if (!input || !results) return;

      const endpoint = @json(route('custom.product-search'));
      let timer;
      let controller;
      let activeIndex = -1;

      const escapeHtml = (value) => String(value ?? '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));
      const close = () => { results.hidden = true; input.setAttribute('aria-expanded', 'false'); activeIndex = -1; };
      const items = () => [...results.querySelectorAll('.search-suggestion')];
      const setActive = (index) => {
        const matches = items();
        if (!matches.length) return;
        activeIndex = (index + matches.length) % matches.length;
        matches.forEach((item, itemIndex) => item.classList.toggle('is-active', itemIndex === activeIndex));
        matches[activeIndex].scrollIntoView({ block: 'nearest' });
      };
      const render = (products, term) => {
        if (!products.length) {
          results.innerHTML = `<div class="search-empty"><i class="fa-solid fa-magnifying-glass"></i>No products found for “${escapeHtml(term)}”</div>`;
        } else {
          const rows = products.map(product => {
            const image = product.image
              ? `<img src="${escapeHtml(product.image)}" alt="${escapeHtml(product.name)}">`
              : '<i class="fa-solid fa-gears"></i>';
            const labels = [...product.categories, ...product.companies].filter(Boolean).join(' · ') || product.item_code || 'Product';
            return `<a href="${escapeHtml(product.url)}" class="search-suggestion" role="option">
              <span class="search-suggestion__image">${image}</span>
              <span class="search-suggestion__body"><span class="search-suggestion__name">${escapeHtml(product.name)}</span><span class="search-suggestion__meta">${escapeHtml(labels)}</span></span>
              <span class="search-suggestion__price">₹${escapeHtml(product.price)}</span>
            </a>`;
          }).join('');
          results.innerHTML = `<div class="search-suggestion-head"><span><i class="fa-solid fa-sparkles me-1"></i>Best matches</span><span>${products.length} result${products.length === 1 ? '' : 's'}</span></div>${rows}`;
        }
        results.hidden = false;
        input.setAttribute('aria-expanded', 'true');
      };

      input.addEventListener('input', () => {
        const term = input.value.trim();
        clearTimeout(timer);
        if (controller) controller.abort();
        if (term.length < 3) return close();
        timer = setTimeout(async () => {
          controller = new AbortController();
          try {
            const response = await fetch(`${endpoint}?q=${encodeURIComponent(term)}`, { headers: { Accept: 'application/json' }, signal: controller.signal });
            if (!response.ok) throw new Error('Search request failed');
            const data = await response.json();
            if (input.value.trim() === term) render(data.products || [], term);
          } catch (error) {
            if (error.name !== 'AbortError') close();
          }
        }, 260);
      });
      input.addEventListener('keydown', event => {
        const matches = items();
        if (event.key === 'ArrowDown' && matches.length) { event.preventDefault(); setActive(activeIndex + 1); }
        if (event.key === 'ArrowUp' && matches.length) { event.preventDefault(); setActive(activeIndex - 1); }
        if (event.key === 'Enter' && activeIndex >= 0 && matches[activeIndex]) { event.preventDefault(); window.location.href = matches[activeIndex].href; }
        if (event.key === 'Escape') close();
      });
      document.addEventListener('click', event => { if (!document.getElementById('productSearch').contains(event.target)) close(); });
    })();
  </script>

@if(session('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Swal.fire({
                title: "Success!",
                text: "{{ session('success') }}",
                icon: "success",
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
@endif
</body>
</html>
