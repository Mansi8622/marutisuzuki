@extends('custom.master')

@section('content')

<style>
/* ============================================================
   Offers page — same "Fitting Guide" theme as home/products
   (blueprint navy + safety orange, corner-bracket fit frames)
   ============================================================ */
:root{
  --navy:        #0B1622;
  --navy-2:      #101F30;
  --navy-3:      #16283C;
  --line:        rgba(111,168,220,0.18);
  --orange:      #FF5A1F;
  --orange-dk:   #D9450F;
  --paper:       #EBF1F6;
  --steel:       #9FB3C6;
}

@media (prefers-reduced-motion: reduce){
  *{ animation-duration:0.001ms !important; animation-iteration-count:1 !important; transition-duration:0.001ms !important; }
}

.msv-breadcrumb{
  font-family:'IBM Plex Mono', monospace; font-size:.82rem; color:#6b7d8f; letter-spacing:.02em;
}
.msv-breadcrumb a.primary{ color:#2F6FA8; text-decoration:none; font-weight:600; }
.msv-breadcrumb a.primary:hover{ color:var(--orange-dk); }

.fit-frame{ position:relative; }
.fit-frame::before,
.fit-frame::after{
  content:""; position:absolute; width:16px; height:16px;
  border:2px solid var(--orange); opacity:0;
  transition:opacity .2s ease, transform .2s ease; pointer-events:none; z-index:3;
}
.fit-frame::before{ top:-8px; left:-8px; border-right:0; border-bottom:0; transform:scale(.6); }
.fit-frame::after{ bottom:-8px; right:-8px; border-left:0; border-top:0; transform:scale(.6); }
.fit-frame:hover::before, .fit-frame:hover::after{ opacity:1; transform:scale(1); }

/* shared panel look: blueprint grid on navy */
.msv-panel{
  background: linear-gradient(160deg, var(--navy) 0%, var(--navy-3) 100%);
  background-image:
    linear-gradient(160deg, var(--navy) 0%, var(--navy-3) 100%),
    linear-gradient(var(--line) 1px, transparent 1px),
    linear-gradient(90deg, var(--line) 1px, transparent 1px);
  background-size: auto, 24px 24px, 24px 24px;
  border-radius: 6px;
  position: relative;
  overflow: hidden;
  opacity:0; transform: translateY(16px);
  animation: panelIn .55s ease forwards;
}
.msv-panel::before{ /* orange corner stencil ribbon */
  content:""; position:absolute; top:0; left:0; width:0; height:0;
  border-style:solid; border-width: 0 46px 46px 0; border-color: transparent var(--orange) transparent transparent;
  z-index:2;
}
@keyframes panelIn{ to{ opacity:1; transform:translateY(0); } }

.msv-eyebrow{
  display:inline-flex; align-items:center; gap:.4rem;
  font-family:'IBM Plex Mono', monospace; font-size:.72rem; font-weight:600;
  letter-spacing:.08em; text-transform:uppercase; color:var(--navy);
  background: var(--orange); padding:.32rem .7rem; border-radius:2px;
}
.msv-eyebrow i{ font-size:.7rem; }

.msv-title-xl{
  font-family:'Barlow Condensed', sans-serif; font-weight:800; text-transform:uppercase;
  letter-spacing:.02em; color:var(--paper); line-height:1.02;
}
.msv-title-md{
  font-family:'Barlow Condensed', sans-serif; font-weight:700; text-transform:uppercase;
  letter-spacing:.02em; color:var(--paper); margin-bottom:0;
}
.msv-title-accent{
  font-family:'Barlow Condensed', sans-serif; font-weight:800; text-transform:uppercase;
  letter-spacing:.02em; color:var(--orange);
}
.msv-sub{ color:var(--steel); font-size:.9rem; letter-spacing:.02em; text-transform:uppercase; font-family:'Inter'; font-weight:500; }

.msv-shop-btn{
  background: var(--orange); border:none; color:#fff; font-weight:600;
  font-family:'Inter'; border-radius:2px; letter-spacing:.02em; position:relative;
  overflow:hidden; z-index:1; transition:color .2s, transform .15s;
}
.msv-shop-btn::before{
  content:""; position:absolute; inset:0; left:-100%;
  background: var(--paper); transition:left .25s ease; z-index:-1;
}
.msv-shop-btn:hover::before{ left:0; }
.msv-shop-btn:hover{ color:var(--navy); transform:translateY(-1px); }
.msv-shop-btn i{ margin-left:.4rem; transition:transform .2s; }
.msv-shop-btn:hover i{ transform:translateX(3px); }

.msv-float-img{ animation: floaty 4.5s ease-in-out infinite; }
@keyframes floaty{
  0%,100%{ transform: translateY(0); }
  50%{ transform: translateY(-10px); }
}

/* dashed spec ring behind hero product image */
.msv-spec-ring{ position:relative; }
.msv-spec-ring::after{
  content:""; position:absolute; top:50%; left:50%; width:78%; height:78%;
  transform:translate(-50%,-50%); border:1.5px dashed rgba(255,255,255,.22);
  border-radius:50%; z-index:0;
}
.msv-spec-ring img{ position:relative; z-index:1; }

.msv-hero{ padding: 2.2rem 1.5rem; }
.msv-hero .msv-title-xl{ font-size: clamp(2.1rem, 4.2vw, 3.4rem); }

.msv-mini-card{ padding: 1.4rem 1.2rem; min-height: 230px; }
.msv-mini-card .msv-title-md{ font-size:1.6rem; }
.msv-mini-card .msv-title-accent{ font-size:2rem; }

.msv-tall-card{ padding: 2rem 1.4rem; min-height: 484px; }
.msv-tall-card .msv-title-md{ font-size:1.6rem; }
.msv-tall-card .msv-title-accent{ font-size:2.3rem; }
</style>

<!-- offers start -->
<section class="offer py-4">
  <div class="container">
    <div class="row">
      <div class="col-lg-12 py-1">
        <div class="msv-breadcrumb">
          <a href="/" class="decoration primary">Home</a>
          &nbsp;<i class="fa-solid fa-chevron-right"></i>&nbsp;
          Offers
        </div>
      </div>

      <!-- Hero banner -->
      <div class="col-12 mb-4">
        <div class="msv-panel fit-frame msv-hero">
          <div class="row align-items-center">
            <div class="col-lg-7 px-lg-4">
              <span class="msv-eyebrow"><i class="fa-solid fa-bolt"></i> Flat 50% Discount</span>
              <h1 class="msv-title-xl mt-3">All Car Parts</h1>
              <p class="msv-sub mt-2 mb-0">Genuine fit, workshop-grade quality</p>
              <a href="/product" class="decoration">
                <button class="btn msv-shop-btn px-4 py-2 mt-4 text-primary">Shop Now <i class="fa-solid fa-arrow-right"></i></button>
              </a>
            </div>
            <div class="col-lg-5 d-none d-lg-flex justify-content-center msv-spec-ring">
              <img src="asset/img/offer/img01.png" alt="Car parts offer" class="img-fluid msv-float-img" style="max-height:280px;">
            </div>
          </div>
        </div>
      </div>

      <!-- Left: two stacked mini offer cards -->
      <div class="col-lg-6 mb-4">
        <div class="row">
          <div class="col-12 mb-4">
            <div class="msv-panel fit-frame msv-mini-card">
              <div class="row align-items-center h-100">
                <div class="col-6 text-center">
                  <img src="asset/img/offer/img02.png" alt="Best price offer" class="img-fluid msv-float-img" style="max-height:150px;">
                </div>
                <div class="col-6">
                  <p class="msv-sub mb-0">Lowest Price</p>
                  <h2 class="msv-title-accent">Everything</h2>
                  <p class="msv-sub mb-0">Online Offer</p>
                  <a href="/product" class="decoration">
                    <button class="btn msv-shop-btn px-3 py-2 mt-3 text-primary">Shop Now <i class="fa-solid fa-arrow-right"></i></button>
                  </a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12">
            <div class="msv-panel fit-frame msv-mini-card">
              <div class="row align-items-center h-100">
                <div class="col-6 text-center">
                  <img src="asset/img/offer/img02.png" alt="Best price offer" class="img-fluid msv-float-img" style="max-height:150px;">
                </div>
                <div class="col-6">
                  <p class="msv-sub mb-0">Lowest Price</p>
                  <h2 class="msv-title-accent">Everything</h2>
                  <p class="msv-sub mb-0">Online Offer</p>
                  <a href="/product" class="decoration">
                    <button class="btn msv-shop-btn px-3 py-2 mt-3 text-primary">Shop Now <i class="fa-solid fa-arrow-right"></i></button>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Right: tall offer card -->
      <div class="col-lg-6 mb-4">
        <div class="msv-panel fit-frame msv-tall-card d-flex flex-column align-items-center justify-content-center text-center">
          <p class="msv-sub mb-0">Lowest Price</p>
          <h2 class="msv-title-accent">Everything</h2>
          <p class="msv-sub mb-0">Online Offer</p>
          <a href="/product" class="decoration">
            <button class="btn msv-shop-btn px-4 py-2 mt-3 text-primary">Shop Now <i class="fa-solid fa-arrow-right"></i></button>
          </a>
          <img src="asset/img/offer/img03.png" alt="Everything online offer" class="img-fluid msv-float-img mt-4" style="max-height:220px;">
        </div>
      </div>

    </div>
  </div>
</section>
<!-- offers end -->

@endsection