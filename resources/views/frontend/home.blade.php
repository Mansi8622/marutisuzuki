@extends('layouts.frontend')

@section('frontend-content')
@php
$member = auth()->user();
$creditWallet = \App\Models\WalletRequest::where('vendor_id',
$member->id)->latest()->first();
$creditAvailable = (float) ($creditWallet->welcome_amount ?? 0);
$creditDue = (float) ($creditWallet->due ?? 0);
$creditApproved = $creditAvailable + $creditDue;
$creditUsed = \App\Models\Transaction::where('vendor_id',
$member->id)->where('transaction_type', 'purchase')->sum('request_amount');
$creditRepaid = \App\Models\Transaction::where('vendor_id',
$member->id)->whereIn('transaction_type',
['payout','cash','cheque','bank_transfer','upi','other'])->where('status',
'success')->sum('request_amount');
$creditHistory = \App\Models\Transaction::where('vendor_id',
$member->id)->latest()->take(4)->get();
$range = request('range', 'month'); $from = now()->startOfMonth(); $to = now();
if ($range === 'today') $from = now()->startOfDay(); elseif ($range === 'week')
$from = now()->startOfWeek(); elseif ($range === 'last_month') { $from =
now()->subMonthNoOverflow()->startOfMonth(); $to =
now()->subMonthNoOverflow()->endOfMonth(); } elseif ($range === 'custom' &&
request('from') && request('to')) { $from =
\Carbon\Carbon::parse(request('from'))->startOfDay(); $to =
\Carbon\Carbon::parse(request('to'))->endOfDay(); }
$rangeTransactions =
\App\Models\Transaction::where('vendor_id',$member->id)->whereBetween('created_at',[$from,$to]);
$rangeUsed = (clone
$rangeTransactions)->where('transaction_type','purchase')->sum('request_amount');
$rangePaid = (clone
$rangeTransactions)->whereIn('transaction_type',['payout','cash','cheque','bank_transfer','upi','other'])->where('status','success')->sum('request_amount');
@endphp
<style>
  .ws {
    --ink: #122238;
    --muted: #718096;
    --blue: #3566e8;
    --line: #e5ebf3;
    background: #f5f7fb;
    padding: 32px 0 54px;
    font-family: Inter, system-ui, sans-serif;
    min-height: 70vh
  }

  .ws a {
    text-decoration: none
  }

  .ws-shell {
    display: grid;
    grid-template-columns: 255px minmax(0, 1fr);
    gap: 24px
  }

  .ws-side {
    background: #122238;
    border-radius: 16px;
    padding: 18px;
    box-shadow: 0 16px 34px #1222382e;
    height: max-content;
    position: sticky;
    top: 18px
  }

  .ws-user {
    padding: 10px 10px 20px;
    border-bottom: 1px solid #ffffff1f;
    display: flex;
    align-items: center;
    gap: 11px;
    margin-bottom: 12px
  }

  .ws-avatar {
    width: 44px;
    height: 44px;
    border-radius: 13px;
    background: linear-gradient(135deg, #6e91ff, #3566e8);
    color: #fff;
    display: grid;
    place-items: center;
    font-weight: 800;
    font-size: 18px
  }

  .ws-user strong {
    display: block;
    color: #fff;
    font-size: .9rem
  }

  .ws-user small {
    color: #9fb0c7;
    font-size: .72rem
  }

  .ws-label {
    color: #8496b0;
    font: 700 .64rem monospace;
    letter-spacing: .1em;
    text-transform: uppercase;
    margin: 17px 10px 7px
  }

  .ws-nav a {
    display: flex;
    align-items: center;
    gap: 11px;
    color: #bdc9d9;
    padding: 10px 11px;
    border-radius: 8px;
    font-size: .84rem;
    font-weight: 600;
    margin: 2px 0;
    transition: .2s
  }

  .ws-nav a i {
    width: 18px;
    text-align: center;
    color: #7fa0d7
  }

  .ws-nav a:hover,
  .ws-nav a.active {
    background: #243b5d;
    color: #fff
  }

  .ws-nav a.active i,
  .ws-nav a:hover i {
    color: #7ca1ff
  }

  .ws-nav .arrow {
    margin-left: auto;
    font-size: .65rem;
    color: #7f91aa
  }

  .ws-main {
    min-width: 0
  }

  .ws-hero {
    background: linear-gradient(115deg, #132640, #244f98);
    border-radius: 18px;
    padding: 28px 30px;
    color: #fff;
    position: relative;
    overflow: hidden;
    box-shadow: 0 14px 30px #1d45873d
  }

  .ws-hero:after {
    content: '';
    position: absolute;
    width: 330px;
    height: 330px;
    border-radius: 50%;
    right: -120px;
    top: -190px;
    border: 48px solid #ffffff12
  }

  .ws-kicker {
    font: 600 .69rem monospace;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #a9c2ff
  }

  .ws-hero h1 {
    font: 800 clamp(1.5rem, 3vw, 2.15rem) 'Barlow Condensed', sans-serif;
    margin: 8px 0
  }

  .ws-hero p {
    color: #c7d8f4;
    max-width: 590px;
    margin: 0;
    font-size: .9rem
  }

  .ws-hero .btn {
    position: relative;
    z-index: 1;
    margin-top: 20px;
    background: #fff;
    color: #204a94;
    border: 0;
    font-weight: 800;
    border-radius: 7px;
    padding: 10px 15px
  }

  .ws-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin: 20px 0
  }

  .ws-card,
  .ws-panel {
    background: #fff;
    border: 1px solid var(--line);
    border-radius: 13px;
    padding: 18px;
    box-shadow: 0 5px 14px #12223809
  }

  .ws-card .ico {
    width: 38px;
    height: 38px;
    display: grid;
    place-items: center;
    border-radius: 10px;
    background: #edf2ff;
    color: var(--blue);
    font-size: 17px
  }

  .ws-card h2 {
    font: 800 1.45rem 'Barlow Condensed', sans-serif;
    color: var(--ink);
    margin: 13px 0 2px
  }

  .ws-card p,
  .ws-panel>p {
    color: var(--muted);
    font-size: .76rem;
    margin: 0
  }

  .credit-split {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
    margin-top: 14px
  }

  .credit-split span {
    display: block;
    background: #f4f7ff;
    border: 1px solid #dfe8ff;
    border-radius: 9px;
    padding: 10px
  }

  .credit-split small {
    display: block;
    color: #718096;
    font-size: .65rem;
    text-transform: uppercase;
    font-weight: 800
  }

  .credit-split b {
    color: #13243d
  }

  .ws-panels {
    display: grid;
    grid-template-columns: 1.2fr .8fr;
    gap: 18px
  }

  .ws-panel {
    padding: 20px
  }

  .ws-panel h3 {
    font: 800 1.08rem 'Barlow Condensed', sans-serif;
    color: var(--ink);
    margin: 0
  }

  .ws-panel>p {
    margin: 5px 0 16px
  }

  .quick {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px
  }

  .quick a {
    border: 1px solid var(--line);
    border-radius: 9px;
    padding: 13px;
    color: var(--ink);
    font-size: .8rem;
    font-weight: 700;
    transition: .2s
  }

  .quick i {
    display: block;
    color: var(--blue);
    font-size: 17px;
    margin-bottom: 8px
  }

  .quick a:hover {
    border-color: #9db4fa;
    background: #f4f7ff;
    transform: translateY(-2px)
  }

  .status {
    padding: 13px 0;
    border-bottom: 1px solid #edf1f5;
    display: flex;
    gap: 10px;
    align-items: center
  }

  .status:last-child {
    border-bottom: 0
  }

  .dot {
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #12b886;
    box-shadow: 0 0 0 4px #e8faf4;
    flex: none
  }

  .status strong {
    font-size: .8rem;
    color: var(--ink);
    display: block
  }

  .status small {
    font-size: .71rem;
    color: var(--muted)
  }

  @media(max-width:991px) {
    .ws-shell {
      grid-template-columns: 1fr
    }

    .ws-side {
      position: static
    }

    .ws-nav {
      display: flex;
      overflow-x: auto;
      gap: 5px
    }

    .ws-label {
      display: none
    }

    .ws-nav a {
      white-space: nowrap;
      flex: none
    }

    .ws-user {
      margin-bottom: 10px
    }

    .ws-grid {
      grid-template-columns: repeat(3, 1fr)
    }
  }

  @media(max-width:650px) {
    .ws {
      padding: 18px 0 35px
    }

    .ws-shell {
      gap: 14px
    }

    .ws-hero {
      padding: 23px 20px
    }

    .ws-grid,
    .ws-panels {
      grid-template-columns: 1fr
    }

    .credit-split {
      grid-template-columns: 1fr
    }

    .ws-side {
      border-radius: 12px;
      padding: 12px
    }

    .ws-user {
      padding: 8px
    }

    .ws-nav a {
      padding: 8px
    }

    .ws-nav a .arrow {
      display: none
    }
  }
  .credit-prism-card {
    position: relative;
    width: 100%;
    max-width: 520px;
    
    padding: 20px;
    border-radius: 24px;

    /* Glass */
    background:
        linear-gradient(
            135deg,
            rgba(255, 255, 255, 0.22),
            rgba(255, 255, 255, 0.07)
        );

    backdrop-filter: blur(22px);
    -webkit-backdrop-filter: blur(22px);

    border: 1px solid rgba(255, 255, 255, 0.28);

    box-shadow:
        0 20px 45px rgba(0, 0, 0, 0.16),
        inset 0 1px 0 rgba(255, 255, 255, 0.45),
        inset 0 -1px 0 rgba(255, 255, 255, 0.08);

    overflow: hidden;
}

/* Prism light */
.credit-prism-card::before {
    content: "";
    position: absolute;
    top: -70%;
    left: -20%;
    width: 75%;
    height: 220%;

    background: linear-gradient(
        120deg,
        transparent 20%,
        rgba(255,255,255,.35) 40%,
        rgba(170,220,255,.16) 50%,
        transparent 70%
    );

    transform: rotate(18deg);
    pointer-events: none;
}

/* Rainbow/prism glow */
.credit-prism-card::after {
    content: "";
    position: absolute;
    right: -90px;
    top: -90px;

    width: 220px;
    height: 220px;

    background:
        radial-gradient(
            circle,
            rgba(100, 180, 255, .28) 0%,
            rgba(180, 120, 255, .18) 35%,
            rgba(255, 120, 200, .10) 55%,
            transparent 72%
        );

    filter: blur(12px);
    pointer-events: none;
}

.credit-prism-glow {
    position: absolute;
    width: 130px;
    height: 130px;
    left: -55px;
    bottom: -70px;

    background: radial-gradient(
        circle,
        rgba(90, 210, 255, .22),
        transparent 70%
    );

    filter: blur(10px);
}

.credit-prism-content {
    position: relative;
    z-index: 5;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 22px;
}

.credit-prism-item {
    flex: 1;

    display: flex;
    align-items: center;
    gap: 14px;
}

.credit-icon {
    width: 48px;
    height: 48px;
    min-width: 48px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 15px;

    background: linear-gradient(
        135deg,
        rgba(255,255,255,.38),
        rgba(255,255,255,.10)
    );

    border: 1px solid rgba(255,255,255,.35);

    box-shadow:
        inset 0 1px 0 rgba(255,255,255,.55),
        0 8px 20px rgba(0,0,0,.08);

    color: #1769aa;
    font-size: 19px;
}

.credit-icon.available {
    color: #0c9b72;
}

.credit-info {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.credit-info small {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .8px;

    color: rgba(40, 50, 65, .65);
}

.credit-info strong {
    font-size: 20px;
    line-height: 1.2;
    font-weight: 800;

    color: #172033;

    white-space: nowrap;
}

.credit-divider {
    width: 1px;
    height: 58px;

    background: linear-gradient(
        to bottom,
        transparent,
        rgba(255,255,255,.8),
        rgba(120,130,150,.25),
        transparent
    );
}

/* Hover */
.credit-prism-card {
    transition:
        transform .3s ease,
        box-shadow .3s ease;
}

.credit-prism-card:hover {
    transform: translateY(-3px);

    box-shadow:
        0 25px 55px rgba(0,0,0,.18),
        inset 0 1px 0 rgba(255,255,255,.55);
}

/* Mobile */
@media (max-width: 576px) {

    .credit-prism-card {
        padding: 16px;
        border-radius: 20px;
    }

    .credit-prism-content {
        gap: 12px;
    }

    .credit-prism-item {
        gap: 9px;
    }

    .credit-icon {
        width: 40px;
        height: 40px;
        min-width: 40px;
        border-radius: 12px;
        font-size: 15px;
    }

    .credit-info small {
        font-size: 9px;
        letter-spacing: .5px;
    }

    .credit-info strong {
        font-size: 15px;
    }

    .credit-divider {
        height: 48px;
    }
}
</style>
<section class="ws">
  <div class="container ws-shell">
    <aside class="ws-side">
      <div class="ws-user"><span
          class="ws-avatar">{{ strtoupper(substr($member->name ?? 'U',0,1)) }}</span>
        <div>
          <strong>{{ $member->name }}</strong><small>{{ $member->business_name ?: 'Partner workspace' }}</small>
          
        </div>
        <div>
          
        </div>
      </div>
      <nav class="ws-nav">
        <div class="ws-label">Workspace</div><a class="active"
          href="{{ route('frontend.home') }}"><i
            class="fa-solid fa-grid-2"></i>Overview</a><a
          href="{{ route('frontend.orders.index') }}"><i
            class="fa-solid fa-bag-shopping"></i>Orders <i
            class="fa-solid fa-chevron-right arrow"></i></a><a
          href="{{ route('frontend.wishlist') }}"><i
            class="fa-regular fa-heart"></i>Wishlist <i
            class="fa-solid fa-chevron-right arrow"></i></a><a
          href="{{ route('frontend.replacements.index') }}"><i
            class="fa-solid fa-rotate-left"></i>Returns & replacements <i
            class="fa-solid fa-chevron-right arrow"></i></a>
        <div class="ws-label">Business tools</div><a
          href="{{ route('frontend.wallet-requests.index') }}"><i
            class="fa-solid fa-wallet"></i>Wallet & credit <i
            class="fa-solid fa-chevron-right arrow"></i></a><a
          href="{{ route('frontend.wallet.statement') }}"><i
            class="fa-solid fa-file-lines"></i>Statement <i
            class="fa-solid fa-chevron-right arrow"></i></a><a
          href="{{ route('downloads.selector', 'price-list') }}"><i
            class="fa-solid fa-file-pdf"></i>Download price list <i
            class="fa-solid fa-chevron-right arrow"></i></a><a
          href="{{ route('downloads.selector', 'catalog') }}"><i
            class="fa-solid fa-book-open"></i>Download catalogs <i
            class="fa-solid fa-chevron-right arrow"></i></a><a
          href="{{ route('frontend.supports.index') }}"><i
            class="fa-solid fa-headset"></i>Support centre <i
            class="fa-solid fa-chevron-right arrow"></i></a>
        <div class="ws-label">Account</div><a
          href="{{ route('frontend.profile.index') }}"><i
            class="fa-solid fa-user-gear"></i>Profile settings <i
            class="fa-solid fa-chevron-right arrow"></i></a>
      </nav>
    </aside>
    <main class="ws-main">
      <div class="ws-hero">
        <div class="ws-kicker">Partner control centre</div>
        <h1>Welcome back, {{ explode(' ', $member->name ?? 'Partner')[0] }}.
        </h1>
        <p>Manage orders, credit and daily operations from one focused
          workspace.</p><a href="/product" class="btn"><i
            class="fa-solid fa-cart-shopping me-2"></i>Browse products</a>
        <a href="{{ route('downloads.selector', 'price-list') }}" class="btn ms-2"><i
            class="fa-solid fa-file-pdf me-2"></i>Download Price List</a>
        <a href="{{ route('downloads.selector', 'catalog') }}" class="btn ms-2"><i
            class="fa-solid fa-book-open me-2"></i>Download Catalogs</a>
        <div style="display:flex;gap:10px;margin-top:20px;flex-wrap:wrap;float:right">
          <div class="credit-prism-card">

            <div class="credit-prism-glow"></div>

            <div class="credit-prism-content">

                <div class="credit-prism-item">
                    <div class="credit-icon">
                        <i class="fas fa-wallet"></i>
                    </div>

                    <div class="credit-info">
                        <small>Approved Limit</small>
                        <strong>
                            Rs {{ number_format($creditApproved, 2) }}
                        </strong>
                    </div>
                </div>

                <div class="credit-divider"></div>

                <div class="credit-prism-item">
                    <div class="credit-icon available">
                        <i class="fas fa-coins"></i>
                    </div>

                    <div class="credit-info">
                        <small>Available Now</small>
                        <strong>
                            Rs {{ number_format($creditAvailable, 2) }}
                        </strong>
                    </div>
                </div>

            </div>
        </div>
        </div>
      </div>
      <form class="mt-3 d-flex gap-2 flex-wrap"><select name="range"
          class="form-select" style="max-width:180px">
          <option value="today" {{ $range==='today'?'selected':'' }}>Today
          </option>
          <option value="week" {{ $range==='week'?'selected':'' }}>This week
          </option>
          <option value="month" {{ $range==='month'?'selected':'' }}>This month
          </option>
          <option value="last_month" {{ $range==='last_month'?'selected':'' }}>
            Last month</option>
          <option value="custom" {{ $range==='custom'?'selected':'' }}>Custom
            dates</option>
        </select><input type="date" name="from" value="{{ request('from') }}"
          class="form-control" style="max-width:160px"><input type="date"
          name="to" value="{{ request('to') }}" class="form-control"
          style="max-width:160px"><button
          class="btn btn-primary">Filter</button></form>
      <div class="ws-grid">
        <div class="ws-card"><span class="ico"><i
              class="fa-solid fa-credit-card"></i></span>
          <h2>Rs {{ number_format($rangeUsed,0) }}</h2>
          <p>Credit line used in selected period</p>
        </div><a href="{{ route('frontend.wallet.statement') }}"
          class="ws-card"><span class="ico"><i
              class="fa-solid fa-file-invoice-dollar"></i></span>
          <h2>Rs {{ number_format($creditDue,0) }}</h2>
          <p>Outstanding due · view invoice details</p>
        </a><a href="{{ route('frontend.wallet.statement') }}"
          class="ws-card"><span class="ico"><i
              class="fa-solid fa-file-lines"></i></span>
          <h2>Statement</h2>
          <p>Full statement for this login</p>
        </a>
      </div>
      <div class="ws-panels">
        <section class="ws-panel">
          <h3>Credit line statement</h3>
          <p>Used Rs {{ number_format($creditUsed,2) }} · Repaid Rs
            {{ number_format($creditRepaid,2) }}</p>
          <div class="credit-split"><span><small>Approved limit</small><b>Rs
                {{ number_format($creditApproved,2) }}</b></span><span><small>Available
                now</small><b>Rs
                {{ number_format($creditAvailable,2) }}</b></span></div>
          <div class="quick mt-3"><a
              href="{{ route('frontend.wallet.statement') }}"><i
                class="fa-solid fa-file-invoice"></i>Invoice dues & pay</a><a
              href="{{ route('frontend.wallet-requests.index') }}"><i
                class="fa-solid fa-wallet"></i>Credit status</a><a
              href="/product"><i class="fa-solid fa-cart-plus"></i>Use credit to
              order</a><a
              href="{{ route('frontend.wallet-requests.create') }}"><i
                class="fa-solid fa-circle-plus"></i>Request more credit</a>
          </div>
        </section>
        <section class="ws-panel">
          <h3>Recent credit activity</h3>
          <p>Every credit purchase is linked to its invoice.</p>
          @forelse($creditHistory as $entry)<div class="status"><span
              class="dot"></span>
            <div>
              <strong>{{ ucfirst(str_replace('-', ' ', $entry->transaction_type)) }}
                · Rs
                {{ number_format($entry->request_amount,2) }}</strong><small>{{ $entry->order_number ? 'Invoice '.$entry->order_number.' · ' : '' }}{{ optional($entry->created_at)->format('d M Y') }}</small>
            </div>
          </div>@empty<div class="status"><span class="dot"></span>
            <div><strong>No credit activity yet</strong><small>Credit purchases
                and repayments appear here.</small></div>
          </div>@endforelse
        </section>
      </div>
    </main>
  </div>
</section>
@endsection
