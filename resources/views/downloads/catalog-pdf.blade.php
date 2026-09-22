<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    @php
        // ---------- Page size (A4 in pt) ----------
        $W = 595.28;
        $H = 836; // A4 se thoda kam, taaki extra blank page na bane

        // ---------- Brand colors (yahin se change karo) ----------
        $navy   = '#0a2a5e';
        $blue   = '#0b78b7';
        $sky    = '#1ab8e8';
        $orange = '#ff8a00';
        $ink    = '#1b2b3a';
        $year   = date('Y');

        // ---------- Gradient helper ----------
        // DomPDF CSS linear-gradient support nahi karta, isliye SVG gradient banate hain.
        // $angle CSS ki tarah degree me: 90 = left->right, 180 = top->bottom, 135 = diagonal
        $grad = function (float $w, float $h, int $angle, array $stops, string $shapes = '') {
            $r  = deg2rad($angle);
            $s  = sin($r);
            $c  = cos($r);
            $x1 = round($w * (0.5 - $s / 2), 2);
            $y1 = round($h * (0.5 + $c / 2), 2);
            $x2 = round($w * (0.5 + $s / 2), 2);
            $y2 = round($h * (0.5 - $c / 2), 2);
            $n  = count($stops);
            $st = '';
            foreach (array_values($stops) as $i => $col) {
                $st .= '<stop offset="' . round($i / max($n - 1, 1), 3) . '" stop-color="' . $col . '"/>';
            }
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $w . '" height="' . $h . '" viewBox="0 0 ' . $w . ' ' . $h . '">'
                 . '<defs><linearGradient id="g" gradientUnits="userSpaceOnUse" x1="' . $x1 . '" y1="' . $y1 . '" x2="' . $x2 . '" y2="' . $y2 . '">' . $st . '</linearGradient></defs>'
                 . '<rect width="' . $w . '" height="' . $h . '" fill="url(#g)"/>' . $shapes . '</svg>';
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        };

        // ---------- NEW: sidebar ka vertical company-name text ab SVG se banta hai ----------
        // Wajah: DomPDF me CSS "transform: rotate(-90deg)" text par bharosemand nahi hai -
        // kabhi kabhi text clip/cut ho jaata hai ya galat position par render hota hai
        // (yahi "RES" wala tuta hua text screenshot me dikha). SVG <text> ke saath
        // rotate karna DomPDF me stable rehta hai, bilkul gradients ki tarah.
        $sideText = function (float $w, float $h, string $name, string $tagline, int $nameSize) {
            $cx = round($w / 2, 2);
            $cy = round($h / 2, 2);
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $w . '" height="' . $h . '" viewBox="0 0 ' . $w . ' ' . $h . '">'
                 . '<g transform="translate(' . $cx . ',' . $cy . ') rotate(-90)">'
                 . '<text x="0" y="-8" text-anchor="middle" font-family="DejaVu Sans, sans-serif" font-weight="bold" '
                 . 'font-size="' . $nameSize . '" letter-spacing="3" fill="#ffffff">' . htmlspecialchars(mb_strtoupper($name)) . '</text>'
                 . '<text x="0" y="16" text-anchor="middle" font-family="DejaVu Sans, sans-serif" '
                 . 'font-size="9" letter-spacing="2" fill="#cfeaf7">' . htmlspecialchars(mb_strtoupper($tagline)) . '</text>'
                 . '</g></svg>';
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        };

        // ---------- Image ko box me fit karne ka helper (object-fit DomPDF me nahi chalta) ----------
        $fit = function ($path, $bw, $bh) {
            $w = $bw; $h = $bh;
            if ($path && is_file($path) && ($i = @getimagesize($path)) && $i[0] > 0 && $i[1] > 0) {
                $r = min($bw / $i[0], $bh / $i[1]);
                $w = round($i[0] * $r, 1);
                $h = round($i[1] * $r, 1);
            }
            return [$w, $h];
        };

        // ---------- Pre-built gradients ----------
        $bar = $grad(400, 4, 90, [$navy, $blue, $sky]);

        $coverBg = $grad($W, $H, 155, [$navy, $blue, $sky],
            '<circle cx="520" cy="90" r="170" fill="#ffffff" fill-opacity="0.08"/>'
          . '<circle cx="60" cy="640" r="220" fill="#ffffff" fill-opacity="0.06"/>'
          . '<circle cx="300" cy="330" r="130" fill="none" stroke="#ffffff" stroke-opacity="0.18" stroke-width="2"/>'
          . '<circle cx="300" cy="330" r="175" fill="none" stroke="#ffffff" stroke-opacity="0.10" stroke-width="2"/>'
          . '<polygon points="0,700 595,630 595,656 0,726" fill="' . $orange . '"/>'
          . '<polygon points="0,726 595,656 595,838 0,838" fill="#ffffff"/>');

        $backBg = $grad($W, $H, 200, [$navy, $blue, $sky],
            '<circle cx="80" cy="120" r="190" fill="#ffffff" fill-opacity="0.07"/>'
          . '<circle cx="540" cy="720" r="230" fill="#ffffff" fill-opacity="0.06"/>'
          . '<polygon points="0,760 595,690 595,712 0,782" fill="' . $orange . '"/>');

        $infoHdr = $grad($W, 150, 120, [$navy, $blue, $sky],
            '<circle cx="520" cy="30" r="120" fill="#ffffff" fill-opacity="0.08"/>'
          . '<circle cx="40" cy="150" r="80" fill="#ffffff" fill-opacity="0.06"/>'
          . '<polygon points="0,138 595,92 595,104 0,150" fill="' . $orange . '"/>'
          . '<polygon points="0,150 595,104 595,150" fill="#ffffff"/>');

        $prodHdr = $grad(467, 84, 110, [$navy, $blue, $sky],
            '<circle cx="430" cy="10" r="80" fill="#ffffff" fill-opacity="0.08"/>'
          . '<polygon points="0,72 467,52 467,58 0,78" fill="' . $orange . '"/>'
          . '<polygon points="0,78 467,58 467,84 0,84" fill="#ffffff"/>');

        $sideShapes = '<circle cx="84" cy="200" r="90" fill="#ffffff" fill-opacity="0.07"/>'
                    . '<circle cx="0" cy="720" r="120" fill="#ffffff" fill-opacity="0.07"/>'
                    . '<circle cx="42" cy="470" r="150" fill="none" stroke="#ffffff" stroke-opacity="0.10" stroke-width="2"/>';
        $sideL = $grad(84, $H, 170, [$navy, $blue, $sky], $sideShapes . '<rect x="80" y="0" width="4" height="' . $H . '" fill="' . $orange . '"/>');
        $sideR = $grad(84, $H, 190, [$navy, $blue, $sky], $sideShapes . '<rect x="0" y="0" width="4" height="' . $H . '" fill="' . $orange . '"/>');

        // ---------- Category wise pages + contents ka page number ----------
        $groups = $products
            ->groupBy(fn($product) => optional($product->categories->where('is_subcategory', false)->first())->name ?? 'Products')
            ->sortKeys();

        // NEW: Contents (TOC) page ab FIXED 1 page nahi hai. Agar categories zyada hain
        // (jaise 13+), to pehle table area (~626pt) me utni rows nahi aa paati thi aur
        // footer ki line last row (jaise "SPEAKER") ke text ko cut kar deti thi.
        // Ab TOC apne aap zaroorat ke hisaab se 2, 3... pages me split ho jaayega, aur
        // us hisaab se product pages ka starting number bhi sahi calculate hota hai.
        $tocPerPage   = 12; // ek Contents page par max kitni categories (safe estimate)
        $catCount     = $groups->count();
        $tocPageCount = max(1, (int) ceil($catCount / $tocPerPage));

        $frontPages = 2 + $tocPageCount; // 1 cover + 1 about-us + N contents pages
        $pageNo     = $frontPages + 1;   // yahi se product pages actually start honge

        $pages  = [];
        $toc    = [];
        foreach ($groups as $catName => $catItems) {
            $toc[] = ['name' => $catName, 'count' => $catItems->count(), 'page' => $pageNo];
            foreach ($catItems->values()->chunk(9) as $chunk) {
                $pages[] = ['cat' => $catName, 'items' => $chunk, 'no' => $pageNo];
                $pageNo++;
            }
        }
        $tocChunks = collect($toc)->chunk($tocPerPage)->values();

        // Sidebar me company name ka size (naam lamba ho to chhota font)
        $nameLen  = mb_strlen($meta['name']);
        $nameSize = $nameLen <= 16 ? 34 : ($nameLen <= 24 ? 28 : 22);

        // Sidebar text image ek hi baar bana lo (dono left/right sidebar isi ko reuse karenge)
        $sideTextImg = $sideText(84, $H, $meta['name'], $meta['tagline'], $nameSize);
    @endphp
    <style>
        @page { margin: 0; }
        html, body { margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; color: #1b2b3a; font-size: 9pt; }
        .page { position: relative; width: 595pt; height: 836pt; page-break-after: always; }
        .page.last { page-break-after: avoid; }
        .abs { position: absolute; }
        .bg { position: absolute; top: 0; left: 0; }
        .center { text-align: center; }
        .white { color: #ffffff; }

        .sec-title { font-size: 13pt; font-weight: bold; color: #0a2a5e; letter-spacing: 1pt; margin: 0 0 4pt; }
        .sec-line { width: 38pt; height: 3pt; background: #ff8a00; margin-bottom: 9pt; }
        .para { font-size: 10pt; line-height: 1.65; color: #33475b; text-align: justify; }

        .card-lite { background: #f2f8fd; border-radius: 8pt; padding: 12pt 14pt 12pt 14pt; }
        .card-lite .h { font-size: 12pt; font-weight: bold; color: #0b78b7; margin: 8pt 0 5pt; letter-spacing: 1pt; }
        .card-lite p, .card-lite li { font-size: 9pt; line-height: 1.55; color: #33475b; }
        .card-lite ul { margin: 0; padding-left: 12pt; }

        .val { background: #ffffff; border: 0.75pt solid #d7e6f1; border-radius: 8pt; padding: 10pt 8pt; text-align: center; height: 78pt; }
        .val .n { font-size: 17pt; font-weight: bold; color: #0b78b7; }
        .val .t { font-size: 10pt; font-weight: bold; color: #0a2a5e; margin: 2pt 0 3pt; }
        .val .d { font-size: 7.5pt; color: #657382; line-height: 1.4; }

        .toc td { padding: 9pt 0; border-bottom: 0.75pt dotted #9db2c4; vertical-align: middle; }
        .toc .no { font-size: 17pt; font-weight: bold; color: #0b78b7; width: 46pt; }
        .toc .nm { font-size: 12pt; font-weight: bold; color: #0a2a5e; }
        .toc .ct { font-size: 8pt; color: #657382; }
        .toc .pg { text-align: right; font-size: 11pt; font-weight: bold; color: #ff8a00; width: 60pt; }

        /* ---- Product grid: card height thoda badha diya + har section ko thoda extra
           breathing room diya gaya hai taaki naam/meta text kabhi hard-clip na ho ---- */
        .grid { border-collapse: separate; border-spacing: 8pt; width: 467pt; table-layout: fixed; }
        .grid td { vertical-align: top; padding: 0; }
        .pcard { height: 204pt; border: 0.75pt solid #d7e6f1; border-radius: 8pt; padding: 5pt 7pt; background: #ffffff; overflow: hidden; }
        .pimg { height: 78pt; background: #f2f8fd; border-radius: 6pt; text-align: center; margin-top: 4pt; overflow: hidden; }
        .pname { font-size: 8.6pt; font-weight: bold; color: #0a2a5e; margin-top: 7pt; line-height: 1.3; height: 26pt; overflow: hidden; }
        .pdesc { font-size: 6.3pt; color: #6b7a88; line-height: 1.3; height: 10pt; overflow: hidden; margin-top: 3pt; }
        .prow { margin: 6pt 0 5pt; white-space: nowrap; }
        .pcode { display: inline-block; background: #0b78b7; color: #ffffff; font-size: 6.8pt; font-weight: bold; padding: 2pt 6pt; border-radius: 8pt; max-width: 78pt; overflow: hidden; white-space: nowrap; vertical-align: middle; margin-right: 5pt; }
        .pmrp { display: inline-block; font-size: 7.4pt; font-weight: bold; color: #ff8a00; vertical-align: middle; }
        .pmeta { font-size: 6.3pt; color: #657382; line-height: 1.4; height: 34pt; overflow: hidden; }
        .pmeta b { color: #0a2a5e; }

        .foot-txt { font-size: 7.5pt; color: #657382; }
        .foot-pg { font-size: 9pt; font-weight: bold; color: #ff8a00; text-align: right; }
    </style>
</head>
<body>

{{-- ===================== PAGE 1 : COVER ===================== --}}
<div class="page">
    <img class="bg" src="{{ $coverBg }}" style="width:595pt;height:836pt;">

    <div class="abs" style="top:60pt;left:{{ ($W - 130) / 2 }}pt;width:130pt;height:130pt;border-radius:65pt;background:#ffffff;text-align:center;">
        <img src="{{ $meta['logo'] }}" style="width:84pt;max-height:84pt;margin-top:23pt;">
    </div>

    <div class="abs center white" style="top:222pt;left:0;width:595pt;">
        <div style="font-size:10pt;letter-spacing:6pt;color:#cfeaf7;">{{ $year }} EDITION</div>
        <div style="font-size:50pt;font-weight:bold;letter-spacing:4pt;line-height:56pt;margin-top:8pt;">PRODUCT</div>
        <div style="font-size:50pt;font-weight:bold;letter-spacing:4pt;line-height:56pt;">CATALOGUE</div>
    </div>

    <div class="abs center" style="top:410pt;left:0;width:595pt;">
        <span style="background:{{ $orange }};color:#ffffff;font-size:11pt;font-weight:bold;letter-spacing:1.5pt;padding:8pt 20pt;border-radius:20pt;">AUTOMOTIVE ACCESSORIES &amp; SPARE PARTS</span>
    </div>

    <div class="abs center white" style="top:490pt;left:40pt;width:515pt;">
        <div style="font-size:22pt;font-weight:bold;letter-spacing:2pt;">{{ strtoupper($meta['name']) }}</div>
        <div style="font-size:11pt;color:#d9f0fb;margin-top:6pt;">{{ $meta['tagline'] }}</div>
    </div>

    <div class="abs" style="top:755pt;left:40pt;width:515pt;">
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td style="width:33%;vertical-align:top;">
                    <div style="font-size:7pt;letter-spacing:2pt;color:{{ $blue }};font-weight:bold;">PHONE</div>
                    <div style="font-size:9pt;color:{{ $navy }};margin-top:2pt;">{{ $meta['phone'] }}</div>
                </td>
                <td style="width:33%;vertical-align:top;">
                    <div style="font-size:7pt;letter-spacing:2pt;color:{{ $blue }};font-weight:bold;">EMAIL</div>
                    <div style="font-size:9pt;color:{{ $navy }};margin-top:2pt;">{{ $meta['email'] }}</div>
                </td>
                <td style="width:34%;vertical-align:top;">
                    <div style="font-size:7pt;letter-spacing:2pt;color:{{ $blue }};font-weight:bold;">ADDRESS</div>
                    <div style="font-size:9pt;color:{{ $navy }};margin-top:2pt;">{{ $meta['address'] }}</div>
                </td>
            </tr>
        </table>
    </div>
</div>

{{-- ===================== PAGE 2 : ABOUT US ===================== --}}
<div class="page">
    <img class="bg" src="{{ $infoHdr }}" style="width:595pt;height:150pt;">
    <div class="abs white" style="top:40pt;left:40pt;">
        <div style="font-size:9pt;letter-spacing:5pt;color:#cfeaf7;">WHO WE ARE</div>
        <div style="font-size:32pt;font-weight:bold;letter-spacing:1pt;margin-top:4pt;">About Us</div>
    </div>
    <div class="abs" style="top:26pt;left:489pt;width:66pt;height:66pt;border-radius:33pt;background:#ffffff;text-align:center;">
        <img src="{{ $meta['logo'] }}" style="width:46pt;max-height:46pt;margin-top:10pt;">
    </div>

    <div class="abs" style="top:170pt;left:40pt;width:515pt;">
        <div class="sec-title">OUR STORY</div>
        <div class="sec-line"></div>
        <div class="para">
            {{ $meta['name'] }} is a trusted name in automotive accessories and spare parts, serving vehicle owners,
            workshops and retailers with products that are built to fit properly and last. Every item in our range is
            carefully selected, quality checked and documented with a clear item code, category and vehicle fitment,
            so finding the right part is quick and free from guesswork.
            <br><br>
            From everyday accessories that add comfort and style to essential spare parts that keep a vehicle running
            safely, we bring a wide selection under one roof, backed by honest dealing and responsive after-sales
            support. Our aim is simple: help every customer get the right part, the first time.
        </div>

        <table width="100%" cellspacing="0" cellpadding="0" style="margin-top:20pt;border-collapse:separate;border-spacing:0;">
            <tr>
                <td style="width:49%;vertical-align:top;">
                    <div class="card-lite" style="height:150pt;">
                        <img src="{{ $bar }}" style="width:100%;height:3pt;">
                        <div class="h">OUR VISION</div>
                        <p style="margin:0;">To become the most dependable name in automotive accessories and spare parts, where every
                            customer finds the right product, at the right quality, with complete confidence.</p>
                    </div>
                </td>
                <td style="width:2%;"></td>
                <td style="width:49%;vertical-align:top;">
                    <div class="card-lite" style="height:150pt;">
                        <img src="{{ $bar }}" style="width:100%;height:3pt;">
                        <div class="h">OUR MISSION</div>
                        <ul>
                            <li>Supply genuine, well-tested products with accurate fitment details.</li>
                            <li>Keep item codes, categories and vehicle information clear and up to date.</li>
                            <li>Support customers and retail partners with fast, honest service.</li>
                            <li>Build long-term relationships on trust and consistent quality.</li>
                        </ul>
                    </div>
                </td>
            </tr>
        </table>

        <div class="sec-title" style="margin-top:22pt;">OUR VALUES</div>
        <div class="sec-line"></div>
        <table width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;border-spacing:0;">
            <tr>
                <td style="width:24%;"><div class="val"><div class="n">01</div><div class="t">Quality</div><div class="d">Products chosen for consistent performance</div></div></td>
                <td style="width:1.33%;"></td>
                <td style="width:24%;"><div class="val"><div class="n">02</div><div class="t">Trust</div><div class="d">Transparent details and honest dealing</div></div></td>
                <td style="width:1.33%;"></td>
                <td style="width:24%;"><div class="val"><div class="n">03</div><div class="t">Fitment</div><div class="d">Accurate vehicle and item information</div></div></td>
                <td style="width:1.33%;"></td>
                <td style="width:24%;"><div class="val"><div class="n">04</div><div class="t">Support</div><div class="d">Practical help before and after purchase</div></div></td>
            </tr>
        </table>
    </div>

    <div class="abs" style="bottom:20pt;left:40pt;width:515pt;">
        <img src="{{ $bar }}" style="width:515pt;height:3pt;">
        <table width="100%" cellspacing="0" cellpadding="0" style="margin-top:5pt;">
            <tr>
                <td class="foot-txt">{{ $meta['name'] }} &nbsp;|&nbsp; {{ $meta['phone'] }} &nbsp;|&nbsp; {{ $meta['email'] }}</td>
                <td class="foot-pg">2</td>
            </tr>
        </table>
    </div>
</div>

{{-- ===================== PAGE 3+ : CONTENTS (ab zaroorat ke hisaab se multi-page) ===================== --}}
@foreach($tocChunks as $tIndex => $tRows)
<div class="page">
    <img class="bg" src="{{ $infoHdr }}" style="width:595pt;height:150pt;">
    <div class="abs white" style="top:40pt;left:40pt;">
        <div style="font-size:9pt;letter-spacing:5pt;color:#cfeaf7;">BROWSE BY CATEGORY</div>
        <div style="font-size:32pt;font-weight:bold;letter-spacing:1pt;margin-top:4pt;">
            Contents
            @if($tocChunks->count() > 1)
                <span style="font-size:13pt;font-weight:normal;color:#cfeaf7;">&nbsp;({{ $tIndex + 1 }}/{{ $tocChunks->count() }})</span>
            @endif
        </div>
    </div>
    <div class="abs" style="top:26pt;left:489pt;width:66pt;height:66pt;border-radius:33pt;background:#ffffff;text-align:center;">
        <img src="{{ $meta['logo'] }}" style="width:46pt;max-height:46pt;margin-top:10pt;">
    </div>

    <div class="abs" style="top:172pt;left:40pt;width:515pt;">
        <table class="toc" width="100%" cellspacing="0" cellpadding="0">
            @foreach($tRows as $row)
                <tr>
                    <td class="no">{{ str_pad($tIndex * $tocPerPage + $loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        <div class="nm">{{ $row['name'] }}</div>
                        <div class="ct">{{ $row['count'] }} {{ $row['count'] == 1 ? 'item' : 'items' }}</div>
                    </td>
                    <td class="pg">Page {{ $row['page'] }}</td>
                </tr>
            @endforeach
        </table>
    </div>

    <div class="abs" style="bottom:20pt;left:40pt;width:515pt;">
        <img src="{{ $bar }}" style="width:515pt;height:3pt;">
        <table width="100%" cellspacing="0" cellpadding="0" style="margin-top:5pt;">
            <tr>
                <td class="foot-txt">{{ $meta['name'] }} &nbsp;|&nbsp; {{ $meta['phone'] }} &nbsp;|&nbsp; {{ $meta['email'] }}</td>
                <td class="foot-pg">{{ 2 + $tIndex + 1 }}</td>
            </tr>
        </table>
    </div>
</div>
@endforeach

{{-- ===================== PRODUCT PAGES (4 se aage) ===================== --}}
@foreach($pages as $pg)
    @php
        $isLeft = $loop->iteration % 2 === 1;      // sidebar left/right alternate
        $cx     = $isLeft ? 42 : $W - 42;          // sidebar ka center x
        $cl     = $isLeft ? 104 : 24;              // content ka left
    @endphp
    <div class="page">
        {{-- Sidebar : gradient background --}}
        <img class="bg" src="{{ $isLeft ? $sideL : $sideR }}" style="left:{{ $isLeft ? 0 : $W - 84 }}pt;width:84pt;height:836pt;">

        {{-- Sidebar : vertical company name/tagline, ab ek hi flattened SVG image ke roop me.
             Left sidebar par jaisa-hai-waisa; right sidebar par mirror karne ki zaroorat nahi -
             text hamesha bottom-to-top hi reads, jo dono side theek lagta hai. --}}
        <img class="bg" src="{{ $sideTextImg }}" style="left:{{ $isLeft ? 0 : $W - 84 }}pt;top:0;width:84pt;height:836pt;">

        <div class="abs" style="top:22pt;left:{{ $cx - 33 }}pt;width:66pt;height:66pt;border-radius:33pt;background:#ffffff;text-align:center;">
            <img src="{{ $meta['logo'] }}" style="width:48pt;max-height:48pt;margin-top:9pt;">
        </div>

        {{-- Header --}}
        <img class="bg" src="{{ $prodHdr }}" style="left:{{ $cl }}pt;width:467pt;height:84pt;">
        <div class="abs white" style="top:16pt;left:{{ $cl + 18 }}pt;width:430pt;">
            <div style="font-size:8pt;letter-spacing:4pt;color:#cfeaf7;">PRODUCT RANGE</div>
            <div style="font-size:19pt;font-weight:bold;margin-top:3pt;">{{ mb_strimwidth($pg['cat'], 0, 30, '...') }}</div>
        </div>

        {{-- Product grid 3 x 3 --}}
        <div class="abs" style="top:92pt;left:{{ $cl - 6 }}pt;">
            <table class="grid" cellspacing="0" cellpadding="0">
                @foreach($pg['items']->pad(9, null)->chunk(3) as $row)
                    <tr>
                        @foreach($row as $product)
                            <td style="width:33.33%;">
                                @if($product)
                                    @php
                                        $category = $product->categories->where('is_subcategory', false)->first();
                                        $sub      = $product->categories->where('is_subcategory', true)->first() ?: optional($product->fitments->first())->vehicle?->subcategory;
                                        $vehicle  = optional($product->fitments->first())->vehicle;
                                        $photo    = $product->photo->first();
                                        $path     = $photo ? $photo->getPath() : null;
                                        [$iw, $ih] = $fit($path, 122, 70);
                                    @endphp
                                    <div class="pcard">
                                        <img src="{{ $bar }}" style="width:100%;height:3pt;">
                                        <div class="pimg">
                                            @if($photo && is_file($path))
                                                <img src="{{ $path }}" style="width:{{ $iw }}pt;height:{{ $ih }}pt;margin-top:{{ round((78 - $ih) / 2, 1) }}pt;">
                                            @else
                                                <div style="line-height:78pt;color:#9aa6b2;font-size:8pt;">No Image</div>
                                            @endif
                                        </div>
                                        {{-- Naam ko 32 chars tak hi rakha hai (pehle 38 tha) taaki
                                             card width me reliably 2 lines me fit ho jaaye aur
                                             3rd line clip hone ka risk na rahe --}}
                                        <div class="pname">{{ mb_strimwidth($product->name, 0, 30, '...') }}</div>
                                        <div class="pdesc">{{ mb_strimwidth(strip_tags($product->description ?? ''), 0, 46, '...') }}</div>
                                        <div class="prow">
                                            <span class="pcode">CODE: {{ mb_strimwidth($product->item_code ?? '-', 0, 16, '...') }}</span>
                                            @if($product->mrp() > 0)
                                                <span class="pmrp">&#8377;{{ number_format($product->mrp(), 0) }}</span>
                                            @endif
                                        </div>
                                        <div class="pmeta">
                                            <b>Category:</b> {{ mb_strimwidth($category->name ?? '-', 0, 20, '...') }}<br>
                                            @if($sub)<b>Sub:</b> {{ mb_strimwidth($sub->name, 0, 20, '...') }}<br>@endif
                                            @if($vehicle)<b>Vehicle:</b> {{ mb_strimwidth($vehicle->name, 0, 20, '...') }}@endif
                                        </div>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </table>
        </div>

        {{-- Footer --}}
        <div class="abs" style="bottom:20pt;left:{{ $cl }}pt;width:467pt;">
            <img src="{{ $bar }}" style="width:467pt;height:3pt;">
            <table width="100%" cellspacing="0" cellpadding="0" style="margin-top:5pt;">
                <tr>
                    <td class="foot-txt">{{ $meta['name'] }} &nbsp;|&nbsp; {{ $meta['phone'] }} &nbsp;|&nbsp; {{ $meta['email'] }}</td>
                    <td class="foot-pg">{{ $pg['no'] }}</td>
                </tr>
            </table>
        </div>
    </div>
@endforeach

{{-- ===================== BACK COVER ===================== --}}
<div class="page last">
    <img class="bg" src="{{ $backBg }}" style="width:595pt;height:836pt;">

    <div class="abs" style="top:170pt;left:{{ ($W - 120) / 2 }}pt;width:120pt;height:120pt;border-radius:60pt;background:#ffffff;text-align:center;">
        <img src="{{ $meta['logo'] }}" style="width:78pt;max-height:78pt;margin-top:21pt;">
    </div>

    <div class="abs center white" style="top:315pt;left:40pt;width:515pt;">
        <div style="font-size:34pt;font-weight:bold;letter-spacing:3pt;">Thank You</div>
        <div style="font-size:12pt;color:#d9f0fb;margin-top:8pt;">for choosing {{ $meta['name'] }}</div>
        <div style="width:50pt;height:3pt;background:{{ $orange }};margin:18pt auto;"></div>
        <div style="font-size:20pt;font-weight:bold;letter-spacing:2pt;">{{ strtoupper($meta['name']) }}</div>
        <div style="font-size:10pt;color:#d9f0fb;margin-top:6pt;">{{ $meta['tagline'] }}</div>
        <div style="font-size:10pt;margin-top:34pt;line-height:1.9;">
            {{ $meta['phone'] }}<br>{{ $meta['email'] }}<br>{{ $meta['address'] }}
        </div>
    </div>
</div>

</body>
</html>