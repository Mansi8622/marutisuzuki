@extends('custom.master')

@section('content')

<style>
/* ============================================================
   About page — same "Fitting Guide" theme (blueprint navy +
   safety orange, corner-bracket fit frames, technical type)
   ============================================================ */
:root{
  --navy:        #0B1622;
  --navy-2:      #101F30;
  --navy-3:      #16283C;
  --line:        rgba(111,168,220,0.16);
  --orange:      #FF5A1F;
  --orange-dk:   #D9450F;
  --paper:       #EBF1F6;
  --steel:       #6b7d8f;
}

@media (prefers-reduced-motion: reduce){
  *{ animation-duration:0.001ms !important; animation-iteration-count:1 !important; transition-duration:0.001ms !important; }
}

.msv-breadcrumb{ font-family:'IBM Plex Mono', monospace; font-size:.82rem; color:var(--steel); letter-spacing:.02em; }
.msv-breadcrumb a.primary{ color:#2F6FA8; text-decoration:none; font-weight:600; }
.msv-breadcrumb a.primary:hover{ color:var(--orange-dk); }

.fit-frame{ position:relative; }
.fit-frame::before, .fit-frame::after{
  content:""; position:absolute; width:16px; height:16px;
  border:2px solid var(--orange); opacity:0;
  transition:opacity .2s ease, transform .2s ease; pointer-events:none; z-index:3;
}
.fit-frame::before{ top:-8px; left:-8px; border-right:0; border-bottom:0; transform:scale(.6); }
.fit-frame::after{ bottom:-8px; right:-8px; border-left:0; border-top:0; transform:scale(.6); }
.fit-frame:hover::before, .fit-frame:hover::after{ opacity:1; transform:scale(1); }

.msv-fade-up{ opacity:0; transform:translateY(18px); animation:fadeUp .6s ease forwards; }
@keyframes fadeUp{ to{ opacity:1; transform:translateY(0); } }

.msv-eyebrow{
  display:inline-flex; align-items:center; gap:.4rem;
  font-family:'IBM Plex Mono', monospace; font-size:.72rem; font-weight:600;
  letter-spacing:.08em; text-transform:uppercase; color:var(--navy);
  background:var(--orange); padding:.32rem .7rem; border-radius:2px;
}

.msv-title-xl{
  font-family:'Barlow Condensed', sans-serif; font-weight:800; letter-spacing:.01em;
  color:#16283C; line-height:1.08; font-size:clamp(1.7rem, 3vw, 2.6rem);
}
.msv-title-md{
  font-family:'Barlow Condensed', sans-serif; font-weight:700; text-transform:uppercase;
  letter-spacing:.03em; color:#16283C; position:relative; padding-bottom:.5rem; display:inline-block;
}
.msv-title-md::after{ content:""; position:absolute; left:0; bottom:0; height:3px; width:52px; background:var(--orange); }
.msv-title-md.center::after{ left:50%; transform:translateX(-50%); }

.msv-lead{ color:#3d4c5a; }

/* hero images with blueprint frame */
.msv-img-frame{
  position:relative; border-radius:6px; overflow:hidden; border:1px solid #e1e8ef;
}
.msv-img-frame img{ display:block; width:100%; height:auto; transition:transform .4s ease; }
.msv-img-frame:hover img{ transform:scale(1.04); }
.msv-img-frame .corner-tick{
  position:absolute; width:18px; height:18px; border:2px solid var(--orange); z-index:2;
}
.msv-img-frame .tl{ top:8px; left:8px; border-right:0; border-bottom:0; }
.msv-img-frame .br{ bottom:8px; right:8px; border-left:0; border-top:0; }

/* navy blueprint panels used for mission/vision + why-choose imagery */
.msv-panel{
  background: linear-gradient(160deg, var(--navy) 0%, var(--navy-3) 100%);
  background-image:
    linear-gradient(160deg, var(--navy) 0%, var(--navy-3) 100%),
    linear-gradient(var(--line) 1px, transparent 1px),
    linear-gradient(90deg, var(--line) 1px, transparent 1px);
  background-size: auto, 24px 24px, 24px 24px;
  border-radius:6px; overflow:hidden; position:relative;
  display:flex; align-items:center; justify-content:center;
}
.msv-panel img{ max-height:340px; width:auto; position:relative; z-index:1; filter:drop-shadow(0 10px 20px rgba(0,0,0,.35)); }

/* mission / vision cards */
.msv-mv-card{
  background:#fff; border:1px solid #e6edf3; border-radius:6px; padding:1.4rem 1.3rem;
  transition:transform .2s ease, box-shadow .2s ease;
}
.msv-mv-card:hover{ transform:translateY(-3px); box-shadow:0 14px 26px rgba(16,32,48,.08); }
.msv-mv-card .icon{
  width:46px; height:46px; border-radius:50%; border:2px solid var(--orange); color:var(--orange);
  display:flex; align-items:center; justify-content:center; font-size:1.15rem; margin-bottom:.9rem;
}

/* why choose list */
.msv-spec-list{ list-style:none; padding-left:0; }
.msv-spec-list li{
  display:flex; gap:.7rem; align-items:flex-start; padding:.6rem 0; border-bottom:1px dashed #e1e8ef;
}
.msv-spec-list li:last-child{ border-bottom:0; }
.msv-spec-list li i{
  color:#fff; background:var(--orange); border-radius:50%; width:24px; height:24px; flex-shrink:0;
  display:flex; align-items:center; justify-content:center; font-size:.72rem; margin-top:.2rem;
}

/* partners */
.msv-partner{
  background:#fff; border:1px solid #e6edf3; border-radius:6px; padding:1.6rem 1rem; text-align:center;
  display:block; text-decoration:none; height:100%; transition:transform .2s ease, box-shadow .2s ease;
}
.msv-partner:hover{ transform:translateY(-4px); box-shadow:0 14px 26px rgba(16,32,48,.09); }
.msv-partner img{ max-height:70px; width:auto; filter:grayscale(1); opacity:.75; transition:filter .25s, opacity .25s; }
.msv-partner:hover img{ filter:grayscale(0); opacity:1; }
.msv-partner p{ margin:.75rem 0 0; font-family:'Inter'; font-weight:600; color:#26333f; font-size:.95rem; }

/* FAQ */
.msv-faq{ background:#fff; border:1px solid #e6edf3; border-radius:8px; padding:1.6rem 1.6rem 1.2rem; }
.msv-faq-item{ border-bottom:1px dashed #e1e8ef; padding:1rem 0; }
.msv-faq-item:last-child{ border-bottom:0; }
.msv-faq-head{ display:flex; align-items:center; justify-content:space-between; gap:1rem; cursor:pointer; }
.msv-faq-num{
  font-family:'IBM Plex Mono', monospace; color:var(--orange); font-weight:600; margin-right:.6rem;
}
.msv-faq-q{ font-size:1.05rem; color:#1c2a37; font-weight:600; margin:0; }
.msv-faq-toggle{
  width:42px; height:42px; border-radius:50%; border:none; background:var(--orange); color:#fff;
  display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:background .2s;
}
.msv-faq-toggle i{ transition:transform .25s ease; }
.msv-faq-toggle[aria-expanded="true"]{ background:var(--navy); }
.msv-faq-toggle[aria-expanded="true"] i{ transform:rotate(45deg); }
.msv-faq-body{ color:#4c5b68; font-size:.98rem; padding-top:.8rem; }
</style>

<!-- about us start -->
<section class="about py-4">
<div class="container">

  <div class="row">
    <div class="col-lg-12 py-3">
      <div class="msv-breadcrumb">
        <a href="/" class="decoration primary">Home</a>
        &nbsp;<i class="fa-solid fa-chevron-right"></i>&nbsp;
        About
      </div>
    </div>
  </div>

  <!-- Intro -->
  <div class="row align-items-center">
    <div class="col-lg-6">
      <div class="row g-3">
        <div class="col-12 msv-fade-up">
          <div class="msv-img-frame fit-frame">
            <span class="corner-tick tl"></span><span class="corner-tick br"></span>
            <img src="https://www.bizzbuzz.news/h-upload/2024/09/29/1937655-how-to-choose-the-right-car-accessories.webp" alt="Choosing the right car accessories">
          </div>
        </div>
        <div class="col-12 msv-fade-up" style="animation-delay:.1s;">
          <div class="msv-img-frame fit-frame">
            <span class="corner-tick tl"></span><span class="corner-tick br"></span>
            <img src="asset/img/about/p2.png" alt="Maruti Suzuki Ventures accessories" class="img-2">
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-6 mt-4 mt-lg-0 msv-fade-up" style="animation-delay:.15s;">
      <span class="msv-eyebrow"><i class="fa-solid fa-building"></i> About Company</span>
      <h1 class="msv-title-xl mt-3 mb-3">Welcome to Maruti Suzuki Ventures — Driving Quality, Trust, and Innovation in Vehicle Accessories</h1>
      <p class="fs-5 msv-lead">At Maruti Suzuki Ventures, we are committed to enhancing your driving experience by offering a comprehensive range of genuine, high-quality accessories tailored specifically for all kind of vehicles. Our platform serves as a trusted digital marketplace where vehicle owners can find authentic products that elevate both the functionality and aesthetics of their cars.</p>
      <p class="mt-3 fs-5 msv-lead">Backed by strategic collaborations with industry leaders like , , and EEMOT, Maruti Suzuki Ventures ensures that every product listed on our platform upholds the highest standards of durability, safety, and performance.</p>
    </div>
  </div>

  <!-- Mission & Vision -->
  <div class="row mt-5">
    <div class="col-lg-6 order-2 order-lg-1 mt-4 mt-lg-0">
      <div class="msv-mv-card mb-3 msv-fade-up">
        <div class="icon"><i class="fa-solid fa-bullseye"></i></div>
        <h3 class="msv-title-md">Our Mission</h3>
        <p class="fs-5 msv-lead mb-0">We want to make it easy for every owner to buy real, reliable, and useful car accessories — without any worry.</p>
      </div>
      <div class="msv-mv-card msv-fade-up" style="animation-delay:.1s;">
        <div class="icon"><i class="fa-solid fa-compass"></i></div>
        <h3 class="msv-title-md">Our Vision</h3>
        <p class="fs-5 msv-lead mb-0">To become India's most trusted digital destination for vehicle accessories through innovation, integrity, and industry-leading partnerships.</p>
      </div>
    </div>
    <div class="col-lg-6 order-1 order-lg-2">
      <div class="msv-panel fit-frame msv-fade-up" style="min-height:380px;">
        <img src="asset/img/car-5.png" alt="Vehicle accessories">
      </div>
    </div>
  </div>

  <!-- Why choose us -->
  <div class="row mt-lg-5 mt-4">
    <div class="col-lg-6">
      <div class="msv-panel fit-frame msv-fade-up" style="min-height:380px;">
        <img src="asset/img/car-2.png" alt="Why choose Maruti Suzuki Ventures">
      </div>
    </div>
    <div class="col-lg-6 mt-4 mt-lg-0 msv-fade-up" style="animation-delay:.1s;">
      <h3 class="msv-title-md mb-3">Why Choose Maruti Suzuki Ventures?</h3>
      <p class="fs-5 msv-lead">We are here to help you find the best and most trusted accessories for your vehicles — all in one place.</p>
      <ul class="msv-spec-list fs-5 mt-3">
        <li><i class="fa-solid fa-check"></i><span><b>100% Genuine Products:</b> We only sell original accessories that fit perfectly with your car.</span></li>
        <li><i class="fa-solid fa-check"></i><span><b>Extensive Product Range:</b> From seat covers and floor mats to music systems and safety tools — we have it all!</span></li>
        <li><i class="fa-solid fa-check"></i><span><b>Trusted Brands:</b> Our collaborations with top-tier manufacturers like , , and EEMOT guarantee you receive only the best, OEM-quality accessories.</span></li>
        <li><i class="fa-solid fa-check"></i><span><b>Easy Shopping:</b> Our website is simple to use, and we're always here to help if you have questions.</span></li>
      </ul>
    </div>
  </div>

  <!-- Partners -->
  <!-- <h2 class="msv-title-md center text-center d-block mt-lg-5 mt-5 mb-4">Our Partners</h2>
  <div class="row g-3">
    <div class="col-lg-4 col-6 msv-fade-up">
      <a href="https://xenos.com/" target="_blank" class="msv-partner fit-frame">
        <img src="asset/img/-logo.png" alt=" Xenos">
        <p> Xenos</p>
      </a>
    </div>
    <div class="col-lg-4 col-6 msv-fade-up" style="animation-delay:.08s;">
      <a href="https://www.eemotrack.com/" target="_blank" class="msv-partner fit-frame">
        <img src="asset/img/eemot-logo.webp" alt="EEMOTRACK" style="max-height:60px;">
        <p>EEMOTRACK</p>
      </a>
    </div>
    <div class="col-lg-4 col-12 msv-fade-up" style="animation-delay:.16s;">
      <a href="https://accessories.com/" target="_blank" class="msv-partner fit-frame">
        <img src="asset/img/_logo.avif" alt=" Accessories" style="max-height:60px;">
        <p> Accessories</p>
      </a>
    </div>
  </div> -->

  <!-- FAQ -->
  <div class="col-12 my-3 mt-lg-5">
    <div class="msv-faq msv-fade-up">
      <h2 class="msv-title-md center text-center d-block mb-4">FAQs</h2>

      <div class="msv-faq-item">
        <div class="msv-faq-head" data-bs-toggle="collapse" data-bs-target="#collapseExample1">
          <p class="msv-faq-q"><span class="msv-faq-num">01</span>Are the accessories on Maruti Suzuki Ventures genuine?</p>
          <button class="msv-faq-toggle" type="button" aria-expanded="false" aria-controls="collapseExample1">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
        <div class="collapse" id="collapseExample1">
          <p class="msv-faq-body mb-0">Yes! We only sell 100% genuine accessories that are made for all kind of vehicles. We work with trusted companies like , , and EEMOT, so you can shop with confidence.</p>
        </div>
      </div>

      <div class="msv-faq-item">
        <div class="msv-faq-head" data-bs-toggle="collapse" data-bs-target="#collapseExample2">
          <p class="msv-faq-q"><span class="msv-faq-num">02</span>What if I receive a damaged or wrong product?</p>
          <button class="msv-faq-toggle" type="button" aria-expanded="false" aria-controls="collapseExample2">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
        <div class="collapse" id="collapseExample2">
          <p class="msv-faq-body mb-0">No worries! If you receive a damaged or incorrect item, you can contact us within a few days of delivery. We will help you with the replacement.</p>
        </div>
      </div>

      <div class="msv-faq-item">
        <div class="msv-faq-head" data-bs-toggle="collapse" data-bs-target="#collapseExample3">
          <p class="msv-faq-q"><span class="msv-faq-num">03</span>Can I get help if I am not sure which accessory is right for my car?</p>
          <button class="msv-faq-toggle" type="button" aria-expanded="false" aria-controls="collapseExample3">
            <i class="fa-solid fa-plus"></i>
          </button>
        </div>
        <div class="collapse" id="collapseExample3">
          <p class="msv-faq-body mb-0">Yes! Our customer support team is always ready to help. Just reach out to us through our contact page, and we will guide you to the right product.</p>
        </div>
      </div>

    </div>
  </div>

</div>
</section>

<script>
  // Keep the plus/rotate icon in sync with Bootstrap's collapse state
  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.msv-faq-toggle').forEach(function (btn) {
      var target = document.querySelector(btn.getAttribute('aria-controls') ? '#' + btn.getAttribute('aria-controls') : '');
      if (!target) return;
      target.addEventListener('shown.bs.collapse', function () { btn.setAttribute('aria-expanded', 'true'); });
      target.addEventListener('hidden.bs.collapse', function () { btn.setAttribute('aria-expanded', 'false'); });
    });
  });
</script>

@endsection