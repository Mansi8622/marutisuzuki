<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    @php
        // ---------- Page size (A4 landscape, pt) ----------
        $PW = 841.89;
        
        $PH = 593; // 595.28 se thoda kam, taaki extra blank page na bane

        // ---------- Logo ke colors (yahin se change karo) ----------
        $navy   = '#0d2b5e';
        $blue   = '#1e78d6';
        $blue2  = '#1f6fd0';
        $teal   = '#14b8a6';
        $orange = '#f7941d';
        $red    = '#e5322d';

        // Currency symbol: agar PDF me box (□) dikhe to '₹' ki jagah 'Rs' kar do
        $cur = '₹';

        // Ek page me left + right column, har column me itni rows (row height fixed 20pt hai)
        $rowsPerCol = 13;
        $perPage    = $rowsPerCol * 2;
        $lh         = 7.6; // ek text line ki height (pt)

        $isCustomer = $role === 'customer';
        $gst        = $meta['gst'] ?? ($meta['gstin'] ?? null);
        $brandUp    = strtoupper($meta['name']);
        $nameLen    = mb_strlen($meta['name']);

        // ---------- Gradient helper (FIX) ----------
        // DomPDF SVG <linearGradient> render nahi karta (black aa jata hai), isliye
        // gradient ko chhoti chhoti solid colour strips se banate hain. $angle CSS jaisa degree hai:
        // 90 = left->right, 180 = top->bottom, 135 = diagonal. $poly = custom shape (points ka array)
        $grad = function (float $w, float $h, int $angle, array $stops, string $shapes = '', ?array $poly = null) {
            $rgb  = fn($hex) => [hexdec(substr($hex, 1, 2)), hexdec(substr($hex, 3, 2)), hexdec(substr($hex, 5, 2))];
            $base = $poly ?: [[0, 0], [$w, 0], [$w, $h], [0, $h]];
            $r    = deg2rad($angle);
            $dx   = sin($r);
            $dy   = -cos($r);
            $cx   = $w / 2;
            $cy   = $h / 2;
            $len  = abs($w * $dx) + abs($h * $dy);
            $n    = max(12, min(70, (int) round($len / 5)));
            $cnt  = count($stops);

            // half-plane clip: sirf woh hissa rakho jahan projection >= $t
            $clip = function (array $pts, float $t) use ($dx, $dy, $cx, $cy) {
                $out = [];
                $m   = count($pts);
                for ($i = 0; $i < $m; $i++) {
                    $a  = $pts[$i];
                    $b  = $pts[($i + 1) % $m];
                    $da = ($a[0] - $cx) * $dx + ($a[1] - $cy) * $dy - $t;
                    $db = ($b[0] - $cx) * $dx + ($b[1] - $cy) * $dy - $t;
                    if ($da >= 0) { $out[] = $a; }
                    if (($da >= 0) !== ($db >= 0)) {
                        $k     = $da / ($da - $db);
                        $out[] = [$a[0] + ($b[0] - $a[0]) * $k, $a[1] + ($b[1] - $a[1]) * $k];
                    }
                }
                return $out;
            };

            $body = '';
            for ($i = 0; $i < $n; $i++) {
                $p   = ($i + 0.5) / $n;
                $seg = $p * ($cnt - 1);
                $idx = min((int) floor($seg), $cnt - 2);
                $f   = $seg - $idx;
                $c1  = $rgb($stops[$idx]);
                $c2  = $rgb($stops[$idx + 1]);
                $col = sprintf('#%02x%02x%02x',
                    round($c1[0] + ($c2[0] - $c1[0]) * $f),
                    round($c1[1] + ($c2[1] - $c1[1]) * $f),
                    round($c1[2] + ($c2[2] - $c1[2]) * $f));
                $pg = $i === 0 ? $base : $clip($base, -$len / 2 + $len * $i / $n);
                if (count($pg) < 3) { continue; }
                $pts = implode(' ', array_map(fn($q) => round($q[0], 2) . ',' . round($q[1], 2), $pg));
                $body .= '<polygon points="' . $pts . '" fill="' . $col . '"/>';
            }
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $w . '" height="' . $h . '" viewBox="0 0 ' . $w . ' ' . $h . '">' . $body . $shapes . '</svg>';
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        };

        // ---------- Glass card (translucent white + border + prism shine) ----------
        $glass = function (float $w, float $h, float $r = 9, ?string $accent = null, float $op = 0.68) {
            $s  = '<svg xmlns="http://www.w3.org/2000/svg" width="' . $w . '" height="' . $h . '" viewBox="0 0 ' . $w . ' ' . $h . '">';
            $s .= '<rect x="0.5" y="0.5" width="' . ($w - 1) . '" height="' . ($h - 1) . '" rx="' . $r . '" ry="' . $r . '" fill="#ffffff" fill-opacity="' . $op . '" stroke="#b7cfe8" stroke-opacity="0.9" stroke-width="0.8"/>';
            $s .= '<polygon points="' . round($w * 0.62, 1) . ',1 ' . round($w * 0.74, 1) . ',1 ' . round($w * 0.46, 1) . ',' . ($h - 1) . ' ' . round($w * 0.34, 1) . ',' . ($h - 1) . '" fill="#ffffff" fill-opacity="0.30"/>';
            $s .= '<polygon points="' . round($w * 0.80, 1) . ',1 ' . round($w * 0.83, 1) . ',1 ' . round($w * 0.67, 1) . ',' . ($h - 1) . ' ' . round($w * 0.64, 1) . ',' . ($h - 1) . '" fill="#ffffff" fill-opacity="0.22"/>';
            if ($accent) {
                $s .= '<rect x="0" y="5" width="3.5" height="' . ($h - 10) . '" rx="1.7" fill="' . $accent . '"/>';
            }
            return 'data:image/svg+xml;base64,' . base64_encode($s . '</svg>');
        };

        // ---------- Chhote icons (SVG) ----------
        // $bg diya to colored circle ke andar white icon banta hai
        $icon = function (string $type, string $color = '#0d2b5e', ?string $bg = null) {
            $sa = 'fill="none" stroke="' . $color . '" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"';
            switch ($type) {
                case 'shield':
                    $g = '<path d="M12 2.5 L20 5.5 V11 C20 16 16.5 20 12 21.5 C7.5 20 4 16 4 11 V5.5 Z" ' . $sa . '/><path d="M8 11.5 L11 14.5 L16 8.5" ' . $sa . '/>';
                    break;
                case 'medal':
                    $g = '<circle cx="12" cy="9" r="5.5" ' . $sa . '/><path d="M8.5 13.5 L7 21.5 L12 19 L17 21.5 L15.5 13.5" ' . $sa . '/><path d="M9.8 9 L11.4 10.6 L14.4 7.4" ' . $sa . '/>';
                    break;
                case 'headset':
                    $g = '<path d="M4 14 V12 A8 8 0 0 1 20 12 V14" ' . $sa . '/><rect x="3" y="13" width="4" height="6" rx="1.5" ' . $sa . '/><rect x="17" y="13" width="4" height="6" rx="1.5" ' . $sa . '/><path d="M20 19 C20 21 17 21.5 14 21.5" ' . $sa . '/>';
                    break;
                case 'calendar':
                    $g = '<rect x="3.5" y="5" width="17" height="15.5" rx="2" ' . $sa . '/><path d="M3.5 10 H20.5 M8 3 V7 M16 3 V7" ' . $sa . '/><path d="M8 13.5 H8.1 M12 13.5 H12.1 M16 13.5 H16.1 M8 17 H8.1 M12 17 H12.1" ' . $sa . '/>';
                    break;
                case 'globe':
                    $g = '<circle cx="12" cy="12" r="9" ' . $sa . '/><ellipse cx="12" cy="12" rx="4" ry="9" ' . $sa . '/><path d="M3 12 H21 M4.5 7.5 H19.5 M4.5 16.5 H19.5" ' . $sa . '/>';
                    break;
                case 'pin':
                    $g = '<path d="M12 21 C7 15 5 12 5 9 A7 7 0 0 1 19 9 C19 12 17 15 12 21 Z" ' . $sa . '/><circle cx="12" cy="9" r="2.5" ' . $sa . '/>';
                    break;
                case 'phone':
                    $g = '<path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="' . $color . '"/>';
                    break;
                default:
                    $g = '';
            }
            $bgEl = $bg ? '<circle cx="12" cy="12" r="12" fill="' . $bg . '"/>' : '';
            $body = $bg ? '<g transform="translate(4.8 4.8) scale(0.6)">' . $g . '</g>' : $g;
            $svg  = '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24">' . $bgEl . $body . '</svg>';
            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        };

        // ---------- Image ko box me fit karne ka helper (object-fit DomPDF me nahi chalta) ----------
        // File missing / unreadable ho to null (placeholder dikhega)
        $fit = function ($path, $bw, $bh) {
            if (!$path) { return null; }
            if (preg_match('#^https?://#', $path)) { return [$bw, $bh]; }
            if (!is_file($path)) { return null; }
            $i = @getimagesize($path);
            if (!$i || $i[0] <= 0 || $i[1] <= 0) { return null; }
            $r = min($bw / $i[0], $bh / $i[1]);
            return [round($i[0] * $r, 1), round($i[1] * $r, 1)];
        };

        // ---------- Text ko fixed lines (max 2) me fit karne ka helper ----------
        // return: [clamped text, height(pt)] -> row kabhi lambi nahi hogi
        $fitText = function (string $t, int $cpl) use ($lh) {
            $t = mb_strimwidth($t, 0, max($cpl * 2 - 1, 3), '..');
            $n = min(2, max(1, (int) ceil(mb_strlen($t) / max($cpl, 1))));
            return [$t, $n * $lh, $n];
        };

        // ---------- Page background : light + rangeen glass blobs + prism shapes ----------
        $pageBg = $grad($PW, $PH, 150, ['#f8fbff', '#e7f2fc', '#f1f7fd'],
            '<circle cx="790" cy="50" r="170" fill="' . $teal . '" fill-opacity="0.20"/>'
          . '<circle cx="40" cy="330" r="150" fill="' . $blue . '" fill-opacity="0.14"/>'
          . '<circle cx="130" cy="580" r="170" fill="' . $orange . '" fill-opacity="0.22"/>'
          . '<circle cx="720" cy="590" r="150" fill="' . $red . '" fill-opacity="0.15"/>'
          . '<circle cx="430" cy="150" r="110" fill="' . $blue . '" fill-opacity="0.10"/>'
          . '<polygon points="0,0 250,0 0,210" fill="#ffffff" fill-opacity="0.45"/>'
          . '<polygon points="842,593 560,593 842,330" fill="' . $teal . '" fill-opacity="0.10"/>'
          . '<polygon points="300,0 372,0 130,593 58,593" fill="#ffffff" fill-opacity="0.30"/>'
          . '<polygon points="640,0 680,0 500,593 460,593" fill="' . $orange . '" fill-opacity="0.07"/>');

        $rainbow = $grad($PW, 5, 90, [$teal, $blue, $orange, $red]);
        $sloganBg = $grad(274, 46, 100, [$red, '#d6246e'],
            '<polygon points="120,0 150,0 100,46 70,46" fill="#ffffff" fill-opacity="0.18"/>'
          . '<polygon points="200,0 212,0 162,46 150,46" fill="#ffffff" fill-opacity="0.12"/>',
            [[16, 0], [274, 0], [274, 46], [0, 46]]);

        // ---------- Glass cards ----------
        $gHeader = $glass(814, 64, 12);
        $gBadge  = $glass(200, 54, 9, null, 0.85);
        $gBand   = $glass(814, 17, 8);
        $gTable  = $glass(396, 296, 10);
        $gStrip  = $glass(802, 58, 10);
        $gAbout  = $glass(500, 46, 10, $orange);
        $gCta    = $glass(802, 46, 12);

        // ---------- Icons ----------
        $ic = [
            'shield'   => $icon('shield', $teal),
            'medal'    => $icon('medal', $blue),
            'headset'  => $icon('headset', $orange),
            'calendar' => $icon('calendar', $red),
            'phone'    => $icon('phone', '#ffffff', $orange),
            'globe'    => $icon('globe', '#ffffff', $teal),
            'pin'      => $icon('pin', '#ffffff', $red),
        ];

        $badges = [
            ['shield',   'PREMIUM', 'QUALITY', false],
            ['medal',    'RELIABLE', 'PERFORMANCE', false],
            ['headset',  'CUSTOMER', 'SUPPORT', false],
            ['calendar', 'EFFECTIVE DATE:', strtoupper(now()->format('d M Y')), true],
        ];

        // ---------- Header band (company details) ----------
        $details = array_filter([
            $gst ? 'GSTIN: ' . $gst : null,
            $meta['address'] ?? null,
            !empty($meta['phone']) ? 'Ph: ' . $meta['phone'] : null,
            $meta['email'] ?? null,
        ]);
        $detailLine = mb_strimwidth(implode('   |   ', $details), 0, 165, '...');

        // Brand name ka size: naam lamba ho to 2 line me chhota font
        $nameSize = $nameLen <= 8 ? 28 : ($nameLen <= 11 ? 22 : ($nameLen <= 13 ? 18 : 15));
        $nameLH   = $nameLen <= 8 ? 30 : ($nameLen <= 11 ? 25 : ($nameLen <= 13 ? 21 : 17));

        // ---------- Brand column tabhi dikhao jab kisi product me brand ho ----------
        $brandOf  = fn($p) => optional($p->select_companies->first())->name ?? optional($p->companies->first())->name ?? '-';
        $hasBrand = $products->contains(fn($p) => $brandOf($p) !== '-');

        // ---------- Column widths (total = 396pt) ----------
        if ($isCustomer) {
            $cw = ['sl' => 16, 'part' => 60, 'brand' => $hasBrand ? 50 : 0, 'cat' => 56, 'mrp' => 44, 'sale' => 54];
        } else {
            $cw = ['sl' => 14, 'part' => 52, 'brand' => $hasBrand ? 40 : 0, 'cat' => 44, 'mrp' => 34, 'sale' => 42, 'foc' => 24];
        }
        $fixedW = array_sum($cw) + (!$isCustomer ? 48 : 0); // foc: 3 cols = 72 (24 upar already count hua)
        $cw['desc'] = 396 - $fixedW;
        $descCpl  = max((int) floor(($cw['desc'] - 6) / 4.7), 8);
        $catCpl   = max((int) floor(($cw['cat'] - 6) / 4.2), 5);
        $brandCpl = max((int) floor(($cw['brand'] - 6) / 4.2), 5);

        // ---------- Featured strip : har category ka ek product (photo wala) ----------
        $featured = $products
            ->groupBy(fn($p) => optional($p->categories->where('is_subcategory', false)->first())->name ?? 'Products')
            ->map(fn($g) => $g->first(fn($p) => $p->photo->first()) ?? $g->first())
            ->take(7);

        // ---------- Footer content ----------
        $aboutText = $meta['name'] . ' brings smart automotive accessories together under one roof - from infotainment systems and speakers to cameras, lights and more. '
                   . 'Every product is selected for genuine quality, accurate details and reliable performance, backed by responsive after-sales support for our customers and trade partners.';
        $slogan = $isCustomer
            ? ['DRIVE SMART', 'Genuine products at the best prices']
            : ['GROW YOUR BUSINESS', 'Best prices for our dealers'];
        $sloganWith = 'WITH ' . mb_strimwidth($brandUp, 0, 30, '') . '!';

        // ---------- Pages ----------
        $chunks    = $products->count() ? $products->chunk($perPage) : collect([collect()]);
        $pageCount = $chunks->count();
    @endphp
    <style>
        @page { size: A4 landscape; margin: 0; }
        html, body { margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; color: #0d2b5e; font-size: 7pt; }
        .page { position: relative; width: 842pt; height: 593pt; page-break-after: always; }
        .page.last { page-break-after: avoid; }
        .abs { position: absolute; }

        /* ---- Table (glass card upar se piche alag image me hai) ---- */
        table.tb { width: 396pt; border-collapse: separate; border-spacing: 0; table-layout: fixed; }
        table.tb th { background: #1f6fd0; color: #ffffff; font-size: 5.8pt; letter-spacing: 0.3pt; text-transform: uppercase; text-align: center; padding: 1pt 2pt; height: 15pt; border-left: 0.5pt solid #5b97e0; }
        table.tb th.h-sale { background: #f7941d; border-left: 0.5pt solid #f7941d; }
        table.tb th.h-foc { background: #0e9f8e; border-left: 0.5pt solid #0e9f8e; }
        table.tb th.h-foc2 { background: #14b8a6; border-left: 0.5pt solid #0e9f8e; }
        table.tb td { height: 20pt; padding: 1pt 3pt; font-size: 6.3pt; line-height: 1.2; border-bottom: 0.5pt solid #d6e5f3; vertical-align: middle; color: #0d2b5e; overflow: hidden; }
        table.tb tr.alt td { background: #eaf3fc; }
        .clip { overflow: hidden; }
        .c { text-align: center; }
        .sl { font-weight: bold; text-align: center; }
        .pn { font-weight: bold; color: #1e78d6; white-space: nowrap; }
        .pname { font-size: 6.5pt; font-weight: bold; color: #0d2b5e; line-height: 1.17; }
        .psub { font-size: 5.6pt; color: #5b6f84; line-height: 1.17; }
        .mrp { color: #4a5b6c; white-space: nowrap; text-align: center; }
        .sale { background: #f7941d; color: #ffffff; font-weight: bold; padding: 2.5pt 4pt; border-radius: 3pt; display: inline-block; white-space: nowrap; }
        .foc { color: #087f3f; text-align: center; white-space: nowrap; }
        .ok { color: #087f3f; font-weight: bold; text-align: center; white-space: nowrap; }
        .na { color: #e5322d; font-weight: bold; text-align: center; }
        .fcap { font-size: 6.2pt; font-weight: bold; color: #0d2b5e; letter-spacing: 0.3pt; }
    </style>
</head>
<body>

@foreach($chunks as $pi => $pageItems)
    @php
        $cols = [
            $pageItems->slice(0, $rowsPerCol)->values(),
            $pageItems->slice($rowsPerCol, $rowsPerCol)->values(),
        ];
    @endphp
    <div class="page {{ $loop->last ? 'last' : '' }}">
        <img class="abs" src="{{ $pageBg }}" style="top:0;left:0;width:842pt;height:593pt;">
        <img class="abs" src="{{ $rainbow }}" style="top:0;left:0;width:842pt;height:5pt;">

        {{-- ===================== HEADER (glass) ===================== --}}
        <img class="abs" src="{{ $gHeader }}" style="top:12pt;left:14pt;width:814pt;height:64pt;">

        <div class="abs" style="top:17pt;left:26pt;">
            <img src="{{ $meta['logo'] }}" style="width:58pt;max-height:54pt;">
        </div>

        <div class="abs" style="top:16pt;left:94pt;width:206pt;">
            <div style="font-size:{{ $nameSize }}pt;font-weight:bold;color:{{ $navy }};letter-spacing:1pt;line-height:{{ $nameLH }}pt;">{{ $brandUp }}</div>
            <div style="font-size:{{ $nameLen <= 11 ? 10 : 7 }}pt;color:{{ $navy }};line-height:1.25;margin-top:2pt;">{{ mb_strimwidth($meta['tagline'], 0, 70, '...') }}</div>
        </div>

        <div class="abs" style="top:20pt;left:304pt;width:1pt;height:46pt;background:#bcd0e6;"></div>

        <div class="abs" style="top:16pt;left:316pt;width:296pt;">
            <div style="font-size:20pt;font-weight:bold;color:{{ $navy }};line-height:27pt;">{{ $isCustomer ? 'CUSTOMER RATE LIST' : 'NEW RATE LIST' }}</div>
            <div style="font-size:20pt;font-weight:bold;color:{{ $orange }};line-height:27pt;">FOR ACCESSORIES</div>
        </div>

        {{-- Badges (2 x 2) --}}
        <img class="abs" src="{{ $gBadge }}" style="top:17pt;left:620pt;width:200pt;height:54pt;">
        @foreach($badges as $bi => $b)
            @php
                $bx = 620 + ($bi % 2) * 100 + 7;
                $by = 17 + intdiv($bi, 2) * 27 + 5;
            @endphp
            <img class="abs" src="{{ $ic[$b[0]] }}" style="top:{{ $by + 1 }}pt;left:{{ $bx }}pt;width:16pt;height:16pt;">
            <div class="abs" style="top:{{ $by }}pt;left:{{ $bx + 20 }}pt;width:76pt;font-size:5.8pt;font-weight:bold;line-height:1.35;color:{{ $navy }};">
                {{ $b[1] }}<br><span style="{{ $b[3] ? 'color:' . $red . ';' : '' }}">{{ $b[2] }}</span>
            </div>
        @endforeach

        {{-- Company details band --}}
        <img class="abs" src="{{ $gBand }}" style="top:82pt;left:14pt;width:814pt;height:17pt;">
        <div class="abs" style="top:87pt;left:26pt;width:680pt;font-size:6.5pt;color:{{ $navy }};letter-spacing:0.2pt;">{{ $detailLine }}</div>
        <div class="abs" style="top:87pt;left:714pt;width:104pt;text-align:right;font-size:6.5pt;font-weight:bold;color:#e07f00;">PAGE {{ $pi + 1 }} OF {{ $pageCount }}</div>

        {{-- ===================== TABLES (left + right) ===================== --}}
        @foreach($cols as $ci => $colItems)
            @if($colItems->count())
                @php $tx = $ci ? 436 : 20; @endphp
                <img class="abs" src="{{ $gTable }}" style="top:104pt;left:{{ $tx }}pt;width:396pt;height:296pt;">
                <div class="abs" style="top:106pt;left:{{ $tx }}pt;width:396pt;">
                    <table class="tb" cellspacing="0" cellpadding="0">
                        <colgroup>
                            <col style="width:{{ $cw['sl'] }}pt">
                            <col style="width:{{ $cw['part'] }}pt">
                            @if($hasBrand)<col style="width:{{ $cw['brand'] }}pt">@endif
                            <col style="width:{{ $cw['cat'] }}pt">
                            <col style="width:{{ $cw['desc'] }}pt">
                            <col style="width:{{ $cw['mrp'] }}pt">
                            <col style="width:{{ $cw['sale'] }}pt">
                            @if(!$isCustomer)
                                <col style="width:24pt"><col style="width:24pt"><col style="width:24pt">
                            @endif
                        </colgroup>
                        <thead>
                            @if($isCustomer)
                                <tr>
                                    <th style="width:{{ $cw['sl'] }}pt;height:30pt;">Sl.</th>
                                    <th style="width:{{ $cw['part'] }}pt;">Part Number</th>
                                    @if($hasBrand)<th style="width:{{ $cw['brand'] }}pt;">Brand</th>@endif
                                    <th style="width:{{ $cw['cat'] }}pt;">Category</th>
                                    <th style="width:{{ $cw['desc'] }}pt;">Product Description</th>
                                    <th style="width:{{ $cw['mrp'] }}pt;">MRP</th>
                                    <th class="h-sale" style="width:{{ $cw['sale'] }}pt;">Customer<br>Rate (Each)</th>
                                </tr>
                            @else
                                <tr>
                                    <th rowspan="2" style="width:{{ $cw['sl'] }}pt;">Sl.</th>
                                    <th rowspan="2" style="width:{{ $cw['part'] }}pt;">Part<br>Number</th>
                                    @if($hasBrand)<th rowspan="2" style="width:{{ $cw['brand'] }}pt;">Brand</th>@endif
                                    <th rowspan="2" style="width:{{ $cw['cat'] }}pt;">Category</th>
                                    <th rowspan="2" style="width:{{ $cw['desc'] }}pt;">Product Description</th>
                                    <th rowspan="2" style="width:{{ $cw['mrp'] }}pt;">MRP</th>
                                    <th rowspan="2" class="h-sale" style="width:{{ $cw['sale'] }}pt;">Sales Price<br>(Each)</th>
                                    <th colspan="3" class="h-foc" style="width:72pt;">FOC Qty</th>
                                </tr>
                                <tr>
                                    <th class="h-foc2" style="width:24pt;">Each</th>
                                    <th class="h-foc2" style="width:24pt;">Slab-1</th>
                                    <th class="h-foc2" style="width:24pt;">Slab-2</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @foreach($colItems as $ri => $product)
                                @php
                                    $category = $product->categories->where('is_subcategory', false)->first();
                                    $sub      = $product->categories->where('is_subcategory', true)->first() ?: optional($product->fitments->first())->vehicle?->subcategory;
                                    $vehicle  = optional($product->fitments->first())->vehicle;
                                    $slabs    = $product->focSlabs->values();
                                    $sale     = $isCustomer ? ($product->rate_2 ?? $product->price) : ($product->price_1 ?? $product->price);
                                    $extra    = implode(' - ', array_filter([optional($sub)->name, optional($vehicle)->name]));
                                    $sl       = $pi * $perPage + $ci * $rowsPerCol + $ri + 1;

                                    [$nameTxt, $nameH, $nameLines] = $fitText((string) $product->name, $descCpl);
                                    $showExtra = $extra && $nameLines === 1;
                                    $descH     = $showExtra ? 2 * $lh : $nameH;
                                    [$catTxt, $catH]     = $fitText((string) ($category->name ?? '-'), $catCpl);
                                    [$brandTxt, $brandH] = $fitText((string) $brandOf($product), $brandCpl);
                                    $code     = (string) ($product->item_code ?? '-');
                                    $partSize = mb_strlen($code) > 11 ? 5.1 : 6;
                                @endphp
                                <tr class="{{ $ri % 2 ? 'alt' : '' }}">
                                    <td class="sl">{{ $sl }}</td>
                                    <td class="pn" style="font-size:{{ $partSize }}pt;">{{ $code }}</td>
                                    @if($hasBrand)
                                        <td><div class="clip" style="height:{{ $brandH }}pt;">{{ $brandTxt }}</div></td>
                                    @endif
                                    <td><div class="clip" style="height:{{ $catH }}pt;">{{ $catTxt }}</div></td>
                                    <td>
                                        <div class="clip" style="height:{{ $descH }}pt;">
                                            <div class="pname">{{ $nameTxt }}</div>
                                            @if($showExtra)<div class="psub">{{ mb_strimwidth($extra, 0, $descCpl + 4, '...') }}</div>@endif
                                        </div>
                                    </td>
                                    <td class="mrp">{{ $cur }}{{ number_format((float) $product->price) }}</td>
                                    <td class="c">
                                        @if($sale)<span class="sale">{{ $cur }}{{ number_format((float) $sale) }}</span>@else — @endif
                                    </td>
                                    @if(!$isCustomer)
                                        <td class="foc">{{ $sale ? number_format((float) $sale) : '—' }}</td>
                                        <td class="{{ $slabs->get(0) ? 'ok' : 'na' }}">{{ $slabs->get(0) ? $slabs->get(0)->buy_qty . '+' . $slabs->get(0)->free_qty : 'N/A' }}</td>
                                        <td class="{{ $slabs->get(1) ? 'ok' : 'na' }}">{{ $slabs->get(1) ? $slabs->get(1)->buy_qty . '+' . $slabs->get(1)->free_qty : 'N/A' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        @endforeach

        {{-- ===================== FEATURED RANGE STRIP (glass) ===================== --}}
        @if($featured->count())
            <img class="abs" src="{{ $gStrip }}" style="top:424pt;left:20pt;width:802pt;height:58pt;">
            <div class="abs" style="top:424pt;left:20pt;width:802pt;height:58pt;">
                <table cellspacing="0" cellpadding="0" style="width:802pt;table-layout:fixed;border-collapse:collapse;">
                    <tr>
                        @foreach($featured as $catName => $fp)
                            @php
                                $fph   = $fp->photo->first();
                                $fpath = $fph ? $fph->getPath() : null;
                                $dim   = $fit($fpath, 96, 34);
                            @endphp
                            <td style="text-align:center;vertical-align:top;padding-top:5pt;{{ !$loop->last ? 'border-right:0.75pt solid #d3e2f1;' : '' }}">
                                <div class="fcap">{{ mb_strimwidth(strtoupper($catName), 0, 22, '..') }}</div>
                                @if($dim)
                                    <img src="{{ $fpath }}" style="width:{{ $dim[0] }}pt;height:{{ $dim[1] }}pt;margin-top:{{ round((34 - $dim[1]) / 2 + 3, 1) }}pt;">
                                @else
                                    <div style="font-size:6pt;color:#9aa6b2;margin-top:14pt;">No Image</div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                </table>
            </div>
        @endif

        {{-- ===================== FOOTER : ABOUT + SLOGAN ===================== --}}
        <img class="abs" src="{{ $gAbout }}" style="top:488pt;left:20pt;width:500pt;height:46pt;">
        <div class="abs" style="top:492pt;left:33pt;width:480pt;">
            <div style="font-size:6.5pt;font-weight:bold;color:#e07f00;letter-spacing:1.2pt;">ABOUT {{ mb_strimwidth($brandUp, 0, 34, '') }}</div>
            <div style="font-size:6pt;line-height:1.35;color:#33475b;margin-top:2pt;">{{ $aboutText }}</div>
        </div>

        <img class="abs" src="{{ $sloganBg }}" style="top:488pt;left:548pt;width:274pt;height:46pt;">
        <div class="abs" style="top:494pt;left:580pt;width:236pt;color:#ffffff;">
            <div style="font-size:12.5pt;font-weight:bold;letter-spacing:0.5pt;line-height:14pt;">{{ $slogan[0] }}</div>
            <div style="font-size:{{ mb_strlen($sloganWith) > 24 ? 7.5 : 8.5 }}pt;font-weight:bold;line-height:11pt;">{{ $sloganWith }}</div>
            <div style="font-size:5.8pt;color:#ffe6f0;margin-top:1pt;">{{ $slogan[1] }}</div>
        </div>

        {{-- ===================== FOOTER : CONTACT BAR (glass) ===================== --}}
        <img class="abs" src="{{ $gCta }}" style="top:540pt;left:20pt;width:802pt;height:46pt;">

        {{-- Call --}}
        <img class="abs" src="{{ $ic['phone'] }}" style="top:549pt;left:34pt;width:28pt;height:28pt;">
        <div class="abs" style="top:547pt;left:70pt;width:210pt;">
            <div style="font-size:6.2pt;letter-spacing:2pt;color:#e07f00;font-weight:bold;">CALL NOW</div>
            <div style="font-size:12.5pt;font-weight:bold;color:{{ $navy }};margin-top:1pt;">{{ $meta['phone'] }}</div>
        </div>
        <div class="abs" style="top:549pt;left:288pt;width:1pt;height:28pt;background:#c9dbee;"></div>

        {{-- Website + email --}}
        <img class="abs" src="{{ $ic['globe'] }}" style="top:549pt;left:298pt;width:28pt;height:28pt;">
        <div class="abs" style="top:547pt;left:334pt;width:160pt;">
            <div style="font-size:6.2pt;letter-spacing:2pt;color:#0e9f8e;font-weight:bold;">VISIT OUR WEBSITE</div>
            <div style="font-size:{{ mb_strlen($meta['website']) <= 22 ? 9.5 : 7.5 }}pt;font-weight:bold;color:{{ $navy }};margin-top:1pt;">{{ $meta['website'] }}</div>
            <div style="font-size:6.3pt;color:#4a5b6c;margin-top:1pt;">{{ mb_strimwidth($meta['email'] ?? '', 0, 40, '...') }}</div>
        </div>
        <div class="abs" style="top:549pt;left:500pt;width:1pt;height:28pt;background:#c9dbee;"></div>

        {{-- Registered office + GST --}}
        <img class="abs" src="{{ $ic['pin'] }}" style="top:549pt;left:510pt;width:28pt;height:28pt;">
        <div class="abs" style="top:545pt;left:546pt;width:146pt;">
            <div style="font-size:6pt;letter-spacing:2pt;color:{{ $red }};font-weight:bold;">REGISTERED OFFICE</div>
            <div style="font-size:6.3pt;line-height:1.3;color:{{ $navy }};margin-top:1pt;">{{ mb_strimwidth($meta['address'] ?? '', 0, 66, '...') }}</div>
            @if($gst)<div style="font-size:6.5pt;font-weight:bold;color:#e07f00;margin-top:1pt;">GSTIN: {{ $gst }}</div>@endif
        </div>
        <div class="abs" style="top:549pt;left:698pt;width:1pt;height:28pt;background:#c9dbee;"></div>

        {{-- Logo pill --}}
        <div class="abs" style="top:547pt;left:706pt;width:108pt;height:32pt;background:#ffffff;border:0.75pt solid #d3e2f1;border-radius:16pt;"></div>
        <img class="abs" src="{{ $meta['logo'] }}" style="top:551pt;left:712pt;width:30pt;max-height:24pt;">
        <div class="abs" style="top:{{ $nameLen <= 8 ? 555 : 552 }}pt;left:746pt;width:66pt;font-size:{{ $nameLen <= 8 ? 10 : 6.4 }}pt;font-weight:bold;color:{{ $navy }};line-height:1.2;letter-spacing:0.3pt;">
            {{ mb_strimwidth($brandUp, 0, 28, '') }}
        </div>
    </div>
@endforeach

</body>
</html>