@extends('layouts.admin')

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | EDIT FORM DATA
    | Keep PHP transformations outside @json() so Blade's parser does not
    | encounter nested PHP arrays/closures inside JavaScript.
    |--------------------------------------------------------------------------
    */

    $selectedCategories = old(
        'categories',
        $product->categories
            ? $product->categories->pluck('id')->toArray()
            : []
    );

    $selectedTags = old(
        'tags',
        $product->tags
            ? $product->tags->pluck('id')->toArray()
            : []
    );

    $selectedCompanies = old(
        'select_companies',
        $product->select_companies
            ? $product->select_companies->pluck('id')->toArray()
            : []
    );

    /*
    |--------------------------------------------------------------------------
    | Godowns
    |--------------------------------------------------------------------------
    | The edit controller must pass $godowns = Godown::pluck('name', 'id').
    */
    $godownOptions = isset($godowns) ? $godowns : collect();

    /*
    |--------------------------------------------------------------------------
    | Existing media
    |--------------------------------------------------------------------------
    | IMPORTANT:
    | Existing media filenames are NOT submitted as photo[] values.
    | This prevents the update controller from trying to read an old image
    | from storage/tmp/uploads/.
    |--------------------------------------------------------------------------
    */

    $existingPhotoFiles = $product->getMedia('photo')
        ->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->file_name,
                'file_name' => $media->file_name,
                'preview' => $media->getUrl(),
                'preview_url' => $media->getUrl(),
                'size' => $media->size,
                'accepted' => true,
                'existing' => true,
            ];
        })
        ->values()
        ->toArray();

    $existingPhoto2Files = $product->getMedia('product_photo_2')
        ->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->file_name,
                'file_name' => $media->file_name,
                'preview' => $media->getUrl(),
                'preview_url' => $media->getUrl(),
                'size' => $media->size,
                'accepted' => true,
                'existing' => true,
            ];
        })
        ->values()
        ->toArray();

    $existingPhoto3Files = $product->getMedia('product_photo_3')
        ->map(function ($media) {
            return [
                'id' => $media->id,
                'name' => $media->file_name,
                'file_name' => $media->file_name,
                'preview' => $media->getUrl(),
                'preview_url' => $media->getUrl(),
                'size' => $media->size,
                'accepted' => true,
                'existing' => true,
            ];
        })
        ->values()
        ->toArray();

    /*
    |--------------------------------------------------------------------------
    | FOC / Scheme Slabs
    |--------------------------------------------------------------------------
    */
    $existingFocSlabs = isset($product->focSlabs)
        ? $product->focSlabs->map(function ($slab) {
            return [
                'id' => $slab->id ?? null,
                'slab_name' => $slab->slab_name ?? '',
                'buy_qty' => $slab->buy_qty ?? '',
                'free_qty' => $slab->free_qty ?? '',
            ];
        })->values()->toArray()
        : [];

    $oldFocSlabs = old('foc_slabs', []);
@endphp


<style>

/* =========================================================
   PRODUCT EDIT WIZARD
========================================================= */

.product-wizard {
    background: #f5f7fb;
    min-height: calc(100vh - 80px);
    padding: 25px 15px 45px;
}

.wizard-shell {
    max-width: 1400px;
    margin: 0 auto;
}


/* =========================================================
   HEADER
========================================================= */

.wizard-header {
    background: linear-gradient(
        135deg,
        #111827 0%,
        #1f2937 100%
    );

    color: #fff;

    border-radius: 18px 18px 0 0;

    padding: 25px 30px;

    position: relative;

    overflow: hidden;
}

.wizard-header::before {
    content: "";

    position: absolute;

    width: 300px;
    height: 300px;

    border-radius: 50%;

    background: rgba(255,255,255,.045);

    right: -100px;
    top: -150px;
}

.wizard-header::after {
    content: "";

    position: absolute;

    width: 160px;
    height: 160px;

    border-radius: 50%;

    background: rgba(255,255,255,.025);

    right: 120px;
    bottom: -110px;
}

.wizard-header h2 {
    margin: 0;

    font-size: 25px;

    font-weight: 700;

    position: relative;

    z-index: 2;
}

.wizard-header h2 i {
    margin-right: 8px;
}

.wizard-header p {
    margin: 7px 0 0;

    color: rgba(255,255,255,.72);

    font-size: 14px;

    position: relative;

    z-index: 2;
}


/* =========================================================
   PROGRESS BAR
========================================================= */

.wizard-progress {
    background: #fff;

    padding: 25px 30px;

    border-bottom:
        1px solid #e5e7eb;
}

.steps {
    display: flex;

    align-items: center;

    justify-content: space-between;

    position: relative;
}

.steps::before {
    content: "";

    position: absolute;

    left: 5%;
    right: 5%;

    top: 21px;

    height: 3px;

    background: #e5e7eb;

    z-index: 0;
}

.progress-line {
    position: absolute;

    left: 5%;

    top: 21px;

    height: 3px;

    width: 0%;

    background:
        linear-gradient(
            90deg,
            #2563eb,
            #7c3aed
        );

    z-index: 1;

    transition:
        width .45s ease;
}

.step-item {
    position: relative;

    z-index: 2;

    text-align: center;

    flex: 1;

    cursor: pointer;
}

.step-circle {
    width: 44px;
    height: 44px;

    border-radius: 50%;

    margin: 0 auto 8px;

    background: #fff;

    border: 3px solid #d1d5db;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 14px;

    font-weight: 700;

    color: #6b7280;

    transition:
        all .35s ease;
}

.step-item.active .step-circle {

    border-color: #2563eb;

    background: #2563eb;

    color: #fff;

    box-shadow:
        0 8px 25px
        rgba(37,99,235,.30);

    transform:
        scale(1.08);
}

.step-item.completed .step-circle {

    border-color: #16a34a;

    background: #16a34a;

    color: #fff;

    box-shadow:
        0 6px 18px
        rgba(22,163,74,.20);
}

.step-title {

    font-size: 12px;

    font-weight: 600;

    color: #6b7280;
}

.step-item.active .step-title {
    color: #2563eb;
}

.step-item.completed .step-title {
    color: #16a34a;
}


/* =========================================================
   BODY
========================================================= */

.wizard-body {

    background: #fff;

    padding: 30px;

    min-height: 560px;
}

.wizard-step {
    display: none;

    animation:
        wizardSlide .4s ease;
}

.wizard-step.active {
    display: block;
}

@keyframes wizardSlide {

    from {
        opacity: 0;

        transform:
            translateX(22px);
    }

    to {
        opacity: 1;

        transform:
            translateX(0);
    }
}


/* =========================================================
   SECTION HEADING
========================================================= */

.section-heading {

    display: flex;

    align-items: center;

    gap: 13px;

    margin-bottom: 25px;

    padding-bottom: 16px;

    border-bottom:
        1px solid #edf0f4;
}

.section-icon {

    width: 44px;
    height: 44px;

    border-radius: 13px;

    display: flex;

    align-items: center;

    justify-content: center;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #7c3aed
        );

    color: #fff;

    font-size: 17px;

    flex-shrink: 0;

    box-shadow:
        0 7px 18px
        rgba(79,70,229,.20);
}

.section-heading h3 {

    margin: 0;

    font-size: 20px;

    font-weight: 700;

    color: #111827;
}

.section-heading p {

    margin: 3px 0 0;

    font-size: 13px;

    color: #6b7280;
}


/* =========================================================
   FIELD CARD
========================================================= */

.field-card {

    background: #fff;

    border:
        1px solid #e8ebf0;

    border-radius: 14px;

    padding: 18px;

    margin-bottom: 18px;

    transition:
        all .25s ease;
}

.field-card:hover {

    border-color:
        #c7d2fe;

    box-shadow:
        0 8px 25px
        rgba(15,23,42,.05);
}

.field-card > label {

    display: block;

    font-weight: 600;

    font-size: 14px;

    color: #374151;

    margin-bottom: 8px;
}

.field-card > label i {

    color: #2563eb;

    margin-right: 4px;
}


/* =========================================================
   REQUIRED *
========================================================= */

label.required::after {

    content: " *";

    color: #dc2626;

    font-size: 15px;

    font-weight: 800;

    margin-left: 3px;
}


/* =========================================================
   INPUTS
========================================================= */

.form-control {

    border-radius: 9px;

    min-height: 44px;

    border-color: #dfe3e8;

    box-shadow: none !important;

    font-size: 14px;
}

.form-control:focus {

    border-color: #6366f1;

    box-shadow:
        0 0 0 3px
        rgba(99,102,241,.08)
        !important;
}

textarea.form-control {

    min-height: auto;

    resize: vertical;
}


/* =========================================================
   SELECT2 - IMPORTANT WIDTH FIX
========================================================= */

.field-card .select2-container {

    width: 100% !important;

    max-width: 100% !important;
}

.select2-container {

    width: 100% !important;
}


/* Single select */

.select2-container--default
.select2-selection--single {

    width: 100% !important;

    height: 44px !important;

    min-height: 44px !important;

    border:
        1px solid #dfe3e8
        !important;

    border-radius:
        9px
        !important;

    background: #fff
        !important;
}

.select2-container--default
.select2-selection--single
.select2-selection__rendered {

    line-height: 42px !important;

    padding-left: 13px !important;

    padding-right: 38px !important;

    color: #374151 !important;
}

.select2-container--default
.select2-selection--single
.select2-selection__arrow {

    height: 42px !important;

    right: 8px !important;
}


/* Multiple select */

.select2-container--default
.select2-selection--multiple {

    width: 100% !important;

    min-height: 44px !important;

    border:
        1px solid #dfe3e8
        !important;

    border-radius:
        9px
        !important;

    padding:
        4px 7px
        !important;

    background: #fff
        !important;
}

.select2-container--default
.select2-selection--multiple:focus {

    border-color:
        #6366f1
        !important;
}


/* Selected tags */

.select2-container--default
.select2-selection--multiple
.select2-selection__choice {

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #4f46e5
        )
        !important;

    border:
        0
        !important;

    color:
        #fff
        !important;

    border-radius:
        6px
        !important;

    padding:
        4px 8px
        !important;

    margin-top:
        3px
        !important;

    font-size:
        12px
        !important;
}

.select2-container--default
.select2-selection--multiple
.select2-selection__choice__remove {

    color: #fff !important;

    border-right: 0 !important;

    margin-right: 5px !important;
}


/* =========================================================
   DROPDOWN - CRITICAL FIX
========================================================= */

/*
 * DO NOT use:
 *
 * dropdownAutoWidth: true
 *
 * DO NOT use:
 *
 * width: auto
 *
 * Dropdown is attached to .field-card using dropdownParent.
 */

.select2-container--open {

    width: 100% !important;

    max-width: 100% !important;
}

.select2-container--default
.select2-dropdown {

    box-sizing: border-box !important;

    width: 100% !important;

    min-width: 100% !important;

    max-width: 100% !important;

    border:
        1px solid #6366f1
        !important;

    border-radius:
        0 0 10px 10px
        !important;

    background: #fff !important;

    box-shadow:
        0 12px 30px
        rgba(15,23,42,.13)
        !important;

    z-index: 999999 !important;
}


/* Search */

.select2-search--dropdown {

    padding: 8px !important;

    background: #fff !important;
}

.select2-search--dropdown
.select2-search__field {

    width: 100% !important;

    box-sizing: border-box !important;

    height: 38px !important;

    border:
        1px solid #d1d5db
        !important;

    border-radius:
        7px
        !important;

    padding:
        7px 10px
        !important;

    outline: none !important;
}


/* Options */

.select2-results__option {

    padding:
        10px 12px
        !important;

    font-size:
        13px
        !important;
}

.select2-results__option--highlighted {

    background:
        #2563eb
        !important;

    color:
        #fff
        !important;
}


/* =========================================================
   FITMENT
========================================================= */

.fitment-note {

    display: flex;

    gap: 10px;

    align-items: center;

    background:
        #eff6ff;

    border:
        1px solid #bfdbfe;

    color:
        #1d4ed8;

    border-radius:
        10px;

    padding:
        12px 15px;

    margin-bottom:
        18px;

    font-size:
        13px;
}

.fitment-note i {

    font-size: 16px;

    flex-shrink: 0;
}

.fitment-wrapper {

    background:
        #f8fafc;

    border:
        1px solid #e5e7eb;

    border-radius:
        14px;

    padding:
        20px;

    min-height:
        180px;
}


/* =========================================================
   FOC SLABS
========================================================= */

.foc-slab-row {

    background:
        #f8fafc;

    padding:
        12px;

    border:
        1px solid #e5e7eb;

    border-radius:
        10px;

    margin-bottom:
        12px;

    animation:
        focFadeUp .25s ease;
}

.foc-slab-row label {

    font-size:
        12px;

    margin-bottom:
        5px;

    color:
        #6b7280;
}

@keyframes focFadeUp {

    from {

        opacity: 0;

        transform:
            translateY(8px);
    }

    to {

        opacity: 1;

        transform:
            translateY(0);
    }
}


/* =========================================================
   DROPZONE
========================================================= */

.dropzone {

    border:
        2px dashed #cbd5e1
        !important;

    border-radius:
        14px
        !important;

    background:
        #f8fafc
        !important;

    min-height:
        160px;

    transition:
        all .25s ease;
}

.dropzone:hover {

    border-color:
        #6366f1
        !important;

    background:
        #f5f3ff
        !important;
}

.dropzone .dz-message {

    color:
        #6b7280;
}


/* Existing image */

.dz-preview {

    margin:
        10px !important;
}

.dz-image {

    border-radius:
        10px !important;
}


/* =========================================================
   REVIEW
========================================================= */

.review-box {

    background:
        #f8fafc;

    border:
        1px solid #e5e7eb;

    border-radius:
        14px;

    padding:
        20px;
}

.review-row {

    display:
        flex;

    justify-content:
        space-between;

    align-items:
        center;

    gap:
        20px;

    padding:
        12px 0;

    border-bottom:
        1px solid #e5e7eb;
}

.review-row:last-child {

    border-bottom:
        0;
}

.review-label {

    color:
        #6b7280;

    font-size:
        13px;
}

.review-value {

    font-weight:
        600;

    color:
        #111827;

    text-align:
        right;
}


/* =========================================================
   FOOTER
========================================================= */

.wizard-footer {

    background:
        #fff;

    border-top:
        1px solid #edf0f4;

    padding:
        18px 30px;

    display:
        flex;

    justify-content:
        space-between;

    align-items:
        center;

    border-radius:
        0 0 18px 18px;
}

.step-counter {

    color:
        #6b7280;

    font-size:
        13px;
}

.btn-wizard {

    min-width:
        125px;

    height:
        44px;

    border-radius:
        10px;

    font-weight:
        600;

    border:
        0;

    transition:
        all .25s ease;
}

.btn-wizard:hover {

    transform:
        translateY(-2px);
}

.btn-back {

    background:
        #f3f4f6;

    color:
        #374151;
}

.btn-next {

    color:
        #fff;

    background:
        linear-gradient(
            135deg,
            #2563eb,
            #7c3aed
        );

    box-shadow:
        0 7px 18px
        rgba(79,70,229,.22);
}

.btn-save {

    color:
        #fff;

    background:
        linear-gradient(
            135deg,
            #059669,
            #16a34a
        );

    box-shadow:
        0 7px 18px
        rgba(22,163,74,.20);
}


/* =========================================================
   ERROR
========================================================= */

.is-invalid {

    border-color:
        #dc2626 !important;
}

.text-danger {

    font-size:
        12px;
}


/* =========================================================
   MOBILE
========================================================= */

@media(max-width:767px) {

    .product-wizard {

        padding:
            10px 8px 30px;
    }

    .wizard-header,
    .wizard-progress,
    .wizard-body,
    .wizard-footer {

        padding:
            18px;
    }

    .wizard-header h2 {

        font-size:
            21px;
    }

    .step-title {

        display:
            none;
    }

    .step-circle {

        width:
            38px;

        height:
            38px;
    }

    .steps::before,
    .progress-line {

        top:
            18px;
    }

    .wizard-footer {

        gap:
            10px;

        flex-wrap:
            wrap;
    }

    .btn-wizard {

        min-width:
            105px;
    }

    .review-row {

        align-items:
            flex-start;

        flex-direction:
            column;

        gap:
            4px;
    }

    .review-value {

        text-align:
            left;
    }
}


/* Extra Select2 safety for Bootstrap/AdminLTE layouts */
.field-card .select2-container,
.field-card .select2-container--default {
    width: 100% !important;
    max-width: 100% !important;
}

.field-card .select2-container--open {
    width: 100% !important;
}

.field-card .select2-dropdown {
    width: 100% !important;
    min-width: 100% !important;
    max-width: 100% !important;
}

.select2-container--open .select2-dropdown {
    box-sizing: border-box !important;
}

.select2-container--open {
    z-index: 999999 !important;
}

</style>


<div class="product-wizard">

<div class="wizard-shell">


{{-- =========================================================
     HEADER
========================================================= --}}

<div class="wizard-header">

    <h2>

        <i class="fas fa-edit"></i>

        Edit Product

    </h2>

    <p>

        Update product information, vehicle fitment,
        pricing, media and schemes step-by-step.

    </p>

</div>


{{-- =========================================================
     PROGRESS
========================================================= --}}

<div class="wizard-progress">

<div class="steps">

    <div
        class="progress-line"
        id="progressLine">
    </div>


    <div
        class="step-item active"
        data-step="1">

        <div class="step-circle">

            <i class="fas fa-info"></i>

        </div>

        <div class="step-title">
            Basic Info
        </div>

    </div>


    <div
        class="step-item"
        data-step="2">

        <div class="step-circle">

            <i class="fas fa-sitemap"></i>

        </div>

        <div class="step-title">
            Category & Vehicle
        </div>

    </div>


    <div
        class="step-item"
        data-step="3">

        <div class="step-circle">

            <i class="fas fa-tags"></i>

        </div>

        <div class="step-title">
            Pricing & Stock
        </div>

    </div>


    <div
        class="step-item"
        data-step="4">

        <div class="step-circle">

            <i class="fas fa-images"></i>

        </div>

        <div class="step-title">
            Media & Scheme
        </div>

    </div>


    <div
        class="step-item"
        data-step="5">

        <div class="step-circle">

            <i class="fas fa-check"></i>

        </div>

        <div class="step-title">
            Review
        </div>

    </div>

</div>

</div>


{{-- =========================================================
     FORM
========================================================= --}}

<form
    method="POST"
    action="{{ route('admin.products.update', [$product->id]) }}"
    enctype="multipart/form-data"
    id="productEditForm">

@method('PUT')

@csrf


<div class="wizard-body">


{{-- =========================================================
     STEP 1 - BASIC
========================================================= --}}

<div
    class="wizard-step active"
    data-step="1">


<div class="section-heading">

    <div class="section-icon">

        <i class="fas fa-box"></i>

    </div>

    <div>

        <h3>
            Basic Product Information
        </h3>

        <p>
            Update the primary details of this product.
        </p>

    </div>

</div>


<div class="row">


{{-- Product Name --}}

<div class="col-md-4">

<div class="field-card">

<label
    class="required"
    for="name">

    <i class="fas fa-box"></i>

    Product Name

</label>


<input
    class="form-control"
    type="text"
    name="name"
    id="name"
    value="{{ old('name', $product->name) }}"
    required>


@error('name')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


{{-- Item Code --}}

<div class="col-md-4">

<div class="field-card">

<label
    class="required"
    for="item_code">

    <i class="fas fa-barcode"></i>

    Item Code

</label>


<input
    class="form-control"
    type="text"
    name="item_code"
    id="item_code"
    value="{{ old('item_code', $product->item_code) }}"
    required>


@error('item_code')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


{{-- HSN --}}

<div class="col-md-4">

<div class="field-card">

<label
    class="required"
    for="hsn_code">

    <i class="fas fa-file-invoice"></i>

    HSN Code

</label>


<input
    class="form-control"
    type="text"
    name="hsn_code"
    id="hsn_code"
    value="{{ old('hsn_code', $product->hsn_code) }}"
    required>


@error('hsn_code')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


{{-- SKU --}}

<div class="col-md-4">

<div class="field-card">

<label
    class="required"
    for="sku">

    <i class="fas fa-fingerprint"></i>

    SKU

</label>


<input
    class="form-control"
    type="text"
    name="sku"
    id="sku"
    value="{{ old('sku', $product->sku) }}"
    required>


@error('sku')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


</div>

</div>


{{-- =========================================================
     STEP 2 - CATEGORY
========================================================= --}}

<div
    class="wizard-step"
    data-step="2">


<div class="section-heading">

    <div class="section-icon">

        <i class="fas fa-sitemap"></i>

    </div>

    <div>

        <h3>
            Category & Vehicle Fitment
        </h3>

        <p>
            Configure product category and compatible vehicles.
        </p>

    </div>

</div>


<div class="fitment-note">

    <i class="fas fa-info-circle"></i>

    <span>

        Existing selections are preserved.
        New sub-categories and vehicle models
        should be selected manually.

    </span>

</div>


<div class="row">


{{-- CATEGORY --}}

<div class="col-md-4">

<div class="field-card">

<label for="categories">

    <i class="fas fa-folder"></i>

    Category

</label>


<select
    class="form-control select2"
    name="categories[]"
    id="categories"
    multiple>



@foreach(
    $categories
    as $id => $category
)

<option
    value="{{ $id }}"

    {{
        in_array(
            $id,
            $selectedCategories
        )
        ? 'selected'
        : ''
    }}>

    {{ $category }}

</option>

@endforeach

</select>


@error('categories')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


{{-- TAGS --}}

<div class="col-md-4">

<div class="field-card">

<label for="tags">

    <i class="fas fa-hashtag"></i>

    Tags

</label>




<select
    class="form-control select2"
    name="tags[]"
    id="tags"
    multiple>

@foreach(
    $tags
    as $id => $tag
)

<option
    value="{{ $id }}"

    {{
        in_array(
            $id,
            $selectedTags
        )
        ? 'selected'
        : ''
    }}>

    {{ $tag }}

</option>

@endforeach

</select>

</div>

</div>


{{-- VEHICLE COMPANY --}}

<div class="col-md-4">

<div class="field-card">

<label
    class="required"
    for="select_companies">

    <i class="fas fa-car"></i>

    Vehicle Company

</label>




<select
    class="form-control select2"
    name="select_companies[]"
    id="select_companies"
    multiple
    required>

@foreach(
    $select_companies
    as $id => $select_company
)

<option
    value="{{ $id }}"

    {{
        in_array(
            $id,
            $selectedCompanies
        )
        ? 'selected'
        : ''
    }}>

    {{ $select_company }}

</option>

@endforeach

</select>


@error('select_companies')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


</div>


{{-- FITMENT --}}

<div class="field-card">

<div
    style="
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:8px;
    ">

    <i
        class="fas fa-car-side"
        style="
            color:#2563eb;
            font-size:18px;
        ">
    </i>

    <h4
        style="
            margin:0;
            font-weight:700;
        ">

        Vehicle Fitment

    </h4>

</div>


<p
    class="text-muted"
    style="
        font-size:13px;
        margin-bottom:16px;
    ">

    Select the vehicle models for which
    this product is available.

</p>


<div
    class="fitment-wrapper"
    id="fitmentStep">

    @include(
        'admin.products.fitments'
    )

</div>

</div>


</div>


{{-- =========================================================
     STEP 3 - PRICING
========================================================= --}}

<div
    class="wizard-step"
    data-step="3">


<div class="section-heading">

    <div class="section-icon">

        <i class="fas fa-tags"></i>

    </div>

    <div>

        <h3>
            Pricing & Inventory
        </h3>

        <p>
            Update stock location and applicable prices.
        </p>

    </div>

</div>


<div class="row">


{{-- GODOWN --}}

<div class="col-md-4">

<div class="field-card">

<label
    class="required"
    for="godown_id">

    <i class="fas fa-building"></i>

    Godown

</label>


<select
    class="form-control select2"
    name="godown_id"
    id="godown_id"
    required>

<option value="">
    Select Godown
</option>


@foreach(
    $godownOptions
    as $id => $godown
)

<option
    value="{{ $id }}"

    {{
        (string) old(
            'godown_id',
            $product->godown_id
        ) === (string) $id
        ? 'selected'
        : ''
    }}>

    {{ $godown }}

</option>

@endforeach

</select>


@error('godown_id')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


{{-- QUANTITY --}}

<div class="col-md-4">

<div class="field-card">

<label
    class="required"
    for="quantity">

    <i class="fas fa-cubes"></i>

    Quantity

</label>


<input
    class="form-control"
    type="number"
    name="quantity"
    id="quantity"
    value="{{ old('quantity', $product->quantity) }}"
    min="0"
    required>


@error('quantity')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


{{-- PRICE --}}

<div class="col-md-4">

<div class="field-card">

<label
    class="required"
    for="price">

    <i class="fas fa-rupee-sign"></i>

    Price

</label>


<input
    class="form-control"
    type="number"
    name="price"
    id="price"
    value="{{ old('price', $product->price) }}"
    step="0.01"
    min="0"
    required>


@error('price')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


{{-- DISCOUNT --}}

<div class="col-md-4">

<div class="field-card">

<label for="discount">

    <i class="fas fa-percent"></i>

    Discount

</label>


<input
    class="form-control"
    type="number"
    name="discount"
    id="discount"
    value="{{ old('discount', $product->discount) }}"
    step="1">

</div>

</div>


{{-- PRICE 1 --}}

<div class="col-md-4">

<div class="field-card">

<label
    class="required"
    for="price_1">

    <i class="fas fa-money-bill"></i>

    Dealer / Base Price

</label>


<input
    class="form-control"
    type="number"
    name="price_1"
    id="price_1"
    value="{{ old('price_1', $product->price_1) }}"
    step="0.01"
    min="0"
    required>


@error('price_1')

<div class="text-danger mt-1">
    {{ $message }}
</div>

@enderror

</div>

</div>


{{-- CUSTOMER PRICE --}}

<div class="col-md-4">

<div class="field-card">

<label for="rate_2">

    <i class="fas fa-user"></i>

    Customer Price

</label>


<input
    class="form-control"
    type="number"
    name="rate_2"
    id="rate_2"
    value="{{ old('rate_2', $product->rate_2) }}"
    step="0.01"
    min="0">

</div>

</div>


{{-- CORPORATE PRICE --}}

<div class="col-md-4">

<div class="field-card">

<label for="rate_3">

    <i class="fas fa-briefcase"></i>

    Corporate Price

</label>


<input
    class="form-control"
    type="number"
    name="rate_3"
    id="rate_3"
    value="{{ old('rate_3', $product->rate_3) }}"
    step="0.01"
    min="0">

</div>

</div>


</div>

</div>


{{-- =========================================================
     STEP 4 - MEDIA & FOC
========================================================= --}}

<div
    class="wizard-step"
    data-step="4">


<div class="section-heading">

    <div class="section-icon">

        <i class="fas fa-images"></i>

    </div>

    <div>

        <h3>
            Media & FOC Scheme
        </h3>

        <p>
            Manage product images and free quantity schemes.
        </p>

    </div>

</div>


{{-- MEDIA --}}

<div class="field-card">

<h4
    style="
        font-weight:700;
        margin-top:0;
        margin-bottom:6px;
    ">

    <i
        class="fas fa-camera"
        style="color:#2563eb;">
    </i>

    Product Images

</h4>


<p
    class="text-muted"
    style="
        font-size:13px;
        margin-bottom:20px;
    ">

    Existing images are preserved unless
    you remove them.

</p>


<div class="row">


{{-- MAIN IMAGE --}}

<div class="col-md-4">

<label class="required">

    Main Product Image

</label>


<div
    class="needsclick dropzone"
    id="photo-dropzone">

</div>

</div>


{{-- IMAGE 2 --}}

<div class="col-md-4">

<label>

    Product Image 2

</label>


<div
    class="needsclick dropzone"
    id="product_photo_2-dropzone">

</div>

</div>


{{-- IMAGE 3 --}}

<div class="col-md-4">

<label>

    Product Image 3

</label>


<div
    class="needsclick dropzone"
    id="product_photo_3-dropzone">

</div>

</div>


</div>

</div>


{{-- FOC --}}

<div class="field-card">

<h4
    style="
        font-weight:700;
        margin-top:0;
    ">

    <i
        class="fas fa-gift"
        style="color:#16a34a;">
    </i>

    FOC / Scheme Slabs

</h4>


<p
    class="text-muted"
    style="
        font-size:13px;
    ">

    Example:
    <strong>Buy 15 → Free 1</strong>

</p>


<div
    id="focSlabWrapper">
</div>


<button
    type="button"
    id="addFocSlab"
    class="btn btn-primary btn-sm">

    <i class="fas fa-plus"></i>

    Add FOC Slab

</button>

</div>


</div>


{{-- =========================================================
     STEP 5 - REVIEW
========================================================= --}}

<div
    class="wizard-step"
    data-step="5">


<div class="section-heading">

    <div class="section-icon">

        <i class="fas fa-check"></i>

    </div>

    <div>

        <h3>
            Description & Final Review
        </h3>

        <p>
            Review your changes before updating.
        </p>

    </div>

</div>


{{-- DESCRIPTION --}}

<div class="field-card">

<label for="description">

    <i class="fas fa-align-left"></i>

    Product Description

</label>


<textarea
    class="form-control"
    name="description"
    id="description"
    rows="7">{{ old('description', $product->description) }}</textarea>

</div>


{{-- REVIEW --}}

<div class="review-box">

<h4
    style="
        margin-top:0;
        font-weight:700;
        margin-bottom:15px;
    ">

    <i
        class="fas fa-clipboard-check"
        style="color:#16a34a;">
    </i>

    Ready to Update Product

</h4>


<div class="review-row">

    <span class="review-label">
        Product Name
    </span>

    <span
        class="review-value"
        id="reviewName">
        —
    </span>

</div>


<div class="review-row">

    <span class="review-label">
        Item Code
    </span>

    <span
        class="review-value"
        id="reviewItemCode">
        —
    </span>

</div>


<div class="review-row">

    <span class="review-label">
        Price
    </span>

    <span
        class="review-value"
        id="reviewPrice">
        —
    </span>

</div>


<div class="review-row">

    <span class="review-label">
        Quantity
    </span>

    <span
        class="review-value"
        id="reviewQuantity">
        —
    </span>

</div>


<div class="review-row">

    <span class="review-label">
        Godown
    </span>

    <span
        class="review-value"
        id="reviewGodown">
        —
    </span>

</div>


<div class="review-row">

    <span class="review-label">
        Vehicle Company
    </span>

    <span
        class="review-value"
        id="reviewCompany">
        —
    </span>

</div>


</div>

</div>


</div>


{{-- =========================================================
     FOOTER
========================================================= --}}

<div class="wizard-footer">

<div class="step-counter">

    Step

    <strong id="currentStepText">
        1
    </strong>

    of 5

</div>


<div>


<button
    type="button"
    id="prevBtn"
    class="btn btn-wizard btn-back"
    style="display:none;">

    <i class="fas fa-arrow-left"></i>

    Back

</button>


<button
    type="button"
    id="nextBtn"
    class="btn btn-wizard btn-next">

    Next

    <i class="fas fa-arrow-right"></i>

</button>


<button
    type="submit"
    id="saveBtn"
    class="btn btn-wizard btn-save"
    style="display:none;">

    <i class="fas fa-save"></i>

    Update Product

</button>


</div>

</div>


</form>

</div>

</div>


@endsection


@section('scripts')


{{-- =========================================================
     SELECT2
     IMPORTANT:
     dropdownAutoWidth intentionally NOT used.
========================================================= --}}

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | CATEGORY
    |--------------------------------------------------------------------------
    */

    $('#categories').select2({

        width: '100%',

        placeholder:
            'Select Category',

        closeOnSelect:
            false,

        allowClear:
            true,

        dropdownParent:
            $('#categories')
                .closest('.field-card')

    });


    /*
    |--------------------------------------------------------------------------
    | TAGS
    |--------------------------------------------------------------------------
    */

    $('#tags').select2({

        width: '100%',

        placeholder:
            'Select Tags',

        closeOnSelect:
            false,

        allowClear:
            true,

        dropdownParent:
            $('#tags')
                .closest('.field-card')

    });


    /*
    |--------------------------------------------------------------------------
    | VEHICLE COMPANY
    |--------------------------------------------------------------------------
    */

    $('#select_companies').select2({

        width: '100%',

        placeholder:
            'Select Vehicle Company',

        closeOnSelect:
            false,

        allowClear:
            true,

        dropdownParent:
            $('#select_companies')
                .closest('.field-card')

    });


    /*
    |--------------------------------------------------------------------------
    | GODOWN
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | dropdownAutoWidth is NOT used.
    |
    | dropdownParent keeps the dropdown inside
    | the Godown field card.
    |
    */

    $('#godown_id').select2({

        width: '100%',

        placeholder:
            'Select Godown',

        allowClear:
            true,

        dropdownParent:
            $('#godown_id')
                .closest('.field-card')

    });


});

</script>


{{-- =========================================================
     WIZARD
========================================================= --}}

<script>

(function () {

    let currentStep = 1;

    const totalSteps = 5;


    const nextBtn =
        document.getElementById(
            'nextBtn'
        );


    const prevBtn =
        document.getElementById(
            'prevBtn'
        );


    const saveBtn =
        document.getElementById(
            'saveBtn'
        );


    const progressLine =
        document.getElementById(
            'progressLine'
        );


    function showStep(step) {


        currentStep = step;


        /*
        |--------------------------------------------------------------------------
        | Show step
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.wizard-step'
            )
            .forEach(
                function (element) {

                    element.classList
                        .remove('active');


                    if (
                        parseInt(
                            element.dataset.step
                        ) === step
                    ) {

                        element.classList
                            .add('active');

                    }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Progress steps
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.step-item'
            )
            .forEach(
                function (element) {

                    const itemStep =
                        parseInt(
                            element.dataset.step
                        );


                    element.classList
                        .remove(
                            'active',
                            'completed'
                        );


                    if (
                        itemStep === step
                    ) {

                        element.classList
                            .add('active');

                    }


                    if (
                        itemStep < step
                    ) {

                        element.classList
                            .add('completed');

                    }

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Progress line
        |--------------------------------------------------------------------------
        */

        const progress =
            ((step - 1) /
            (totalSteps - 1)) * 90;


        progressLine.style.width =
            progress + '%';


        /*
        |--------------------------------------------------------------------------
        | Counter
        |--------------------------------------------------------------------------
        */

        document
            .getElementById(
                'currentStepText'
            )
            .innerText = step;


        /*
        |--------------------------------------------------------------------------
        | Buttons
        |--------------------------------------------------------------------------
        */

        prevBtn.style.display =
            step === 1
                ? 'none'
                : 'inline-block';


        nextBtn.style.display =
            step === totalSteps
                ? 'none'
                : 'inline-block';


        saveBtn.style.display =
            step === totalSteps
                ? 'inline-block'
                : 'none';


        /*
        |--------------------------------------------------------------------------
        | Review
        |--------------------------------------------------------------------------
        */

        if (
            step === totalSteps
        ) {

            updateReview();

        }


        /*
        |--------------------------------------------------------------------------
        | Scroll
        |--------------------------------------------------------------------------
        */

        const wizard =
            document.querySelector(
                '.wizard-shell'
            );


        if (wizard) {

            wizard.scrollIntoView({
                behavior:
                    'smooth',
                block:
                    'start'
            });

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Validate current step
    |--------------------------------------------------------------------------
    */

    function validateCurrentStep() {

        const activeStep =
            document.querySelector(
                '.wizard-step.active'
            );


        if (!activeStep) {

            return true;

        }


        const fields =
            activeStep.querySelectorAll(
                'input[required], select[required], textarea[required]'
            );


        for (
            let i = 0;
            i < fields.length;
            i++
        ) {

            const field =
                fields[i];


            if (
                !field.checkValidity()
            ) {

                field.reportValidity();

                return false;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Select2 required validation
        |--------------------------------------------------------------------------
        */

        const company =
            document.getElementById(
                'select_companies'
            );


        if (
            currentStep === 2 &&
            company &&
            company.required
        ) {

            const values =
                $('#select_companies')
                    .val();


            if (
                !values ||
                values.length === 0
            ) {

                $('#select_companies')
                    .select2('open');

                return false;

            }

        }


        return true;

    }


    /*
    |--------------------------------------------------------------------------
    | NEXT
    |--------------------------------------------------------------------------
    */

    nextBtn.addEventListener(
        'click',
        function () {


            if (
                !validateCurrentStep()
            ) {

                return;

            }


            if (
                currentStep <
                totalSteps
            ) {

                showStep(
                    currentStep + 1
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | BACK
    |--------------------------------------------------------------------------
    */

    prevBtn.addEventListener(
        'click',
        function () {

            if (
                currentStep > 1
            ) {

                showStep(
                    currentStep - 1
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Previous completed steps
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.step-item'
        )
        .forEach(
            function (item) {

                item.addEventListener(
                    'click',
                    function () {

                        const target =
                            parseInt(
                                this.dataset.step
                            );


                        if (
                            target < currentStep
                        ) {

                            showStep(
                                target
                            );

                        }

                    }
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | REVIEW DATA
    |--------------------------------------------------------------------------
    */

    function updateReview() {


        const name =
            document.getElementById(
                'name'
            )?.value || '—';


        const itemCode =
            document.getElementById(
                'item_code'
            )?.value || '—';


        const price =
            document.getElementById(
                'price'
            )?.value || '—';


        const quantity =
            document.getElementById(
                'quantity'
            )?.value || '—';


        const godown =
            $('#godown_id option:selected')
                .text()
                .trim();


        const companies =
            $('#select_companies option:selected')
                .map(
                    function () {

                        return $(this)
                            .text()
                            .trim();

                    }
                )
                .get()
                .join(', ');


        document
            .getElementById(
                'reviewName'
            )
            .innerText =
                name;


        document
            .getElementById(
                'reviewItemCode'
            )
            .innerText =
                itemCode;


        document
            .getElementById(
                'reviewPrice'
            )
            .innerText =
                price;


        document
            .getElementById(
                'reviewQuantity'
            )
            .innerText =
                quantity;


        document
            .getElementById(
                'reviewGodown'
            )
            .innerText =
                godown || '—';


        document
            .getElementById(
                'reviewCompany'
            )
            .innerText =
                companies || '—';

    }


    /*
    |--------------------------------------------------------------------------
    | Initial
    |--------------------------------------------------------------------------
    */

    showStep(1);

})();

</script>


{{-- =========================================================
     FOC SLABS
========================================================= --}}

<script>

(function () {


    let focIndex = 0;


    const wrapper =
        document.getElementById(
            'focSlabWrapper'
        );


    const addButton =
        document.getElementById(
            'addFocSlab'
        );


    if (
        !wrapper ||
        !addButton
    ) {

        return;

    }


    /*
    |--------------------------------------------------------------------------
    | Escape HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        return String(value ?? '')
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );

    }


    /*
    |--------------------------------------------------------------------------
    | Add Row
    |--------------------------------------------------------------------------
    */

    function addFocRow(
        slabName,
        buyQty,
        freeQty
    ) {


        const row =
            document.createElement(
                'div'
            );


        row.className =
            'row foc-slab-row';


        row.innerHTML = `

            <div class="col-md-4">

                <label>
                    Slab Name
                </label>

                <input
                    type="text"
                    name="foc_slabs[${focIndex}][slab_name]"
                    class="form-control"
                    placeholder="Example: Buy 15 Free 1"
                    value="${escapeHtml(slabName)}"
                >

            </div>


            <div class="col-md-3">

                <label>
                    Buy Quantity
                </label>

                <input
                    type="number"
                    name="foc_slabs[${focIndex}][buy_qty]"
                    class="form-control"
                    placeholder="Buy Qty"
                    min="1"
                    value="${escapeHtml(buyQty)}"
                >

            </div>


            <div class="col-md-3">

                <label>
                    Free Quantity
                </label>

                <input
                    type="number"
                    name="foc_slabs[${focIndex}][free_qty]"
                    class="form-control"
                    placeholder="Free Qty"
                    min="1"
                    value="${escapeHtml(freeQty)}"
                >

            </div>


            <div class="col-md-2">

                <label>
                    &nbsp;
                </label>

                <button
                    type="button"
                    class="btn btn-danger remove-foc-slab"
                    style="
                        height:44px;
                        width:100%;
                        border-radius:9px;
                    ">

                    <i class="fas fa-trash"></i>

                </button>

            </div>

        `;


        wrapper.appendChild(row);


        focIndex++;

    }


    /*
    |--------------------------------------------------------------------------
    | Add New Slab
    |--------------------------------------------------------------------------
    */

    addButton.addEventListener(
        'click',
        function () {

            addFocRow(
                '',
                '',
                ''
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Delete Slab
    |--------------------------------------------------------------------------
    */

    wrapper.addEventListener(
        'click',
        function (event) {


            const button =
                event.target.closest(
                    '.remove-foc-slab'
                );


            if (!button) {

                return;

            }


            const row =
                button.closest(
                    '.foc-slab-row'
                );


            if (row) {

                row.style.opacity =
                    '0';

                row.style.transform =
                    'translateX(20px)';

                row.style.transition =
                    '.2s ease';


                setTimeout(
                    function () {

                        row.remove();

                    },
                    200
                );

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Existing Slabs
    |--------------------------------------------------------------------------
    */

    const existingFocSlabs =
        @json($existingFocSlabs);


    /*
    |--------------------------------------------------------------------------
    | Validation Old Slabs
    |--------------------------------------------------------------------------
    */

    const oldFocSlabs =
        @json($oldFocSlabs);


    /*
    |--------------------------------------------------------------------------
    | If validation failed:
    | show old submitted slabs.
    |--------------------------------------------------------------------------
    */

    if (
        Array.isArray(oldFocSlabs) &&
        oldFocSlabs.length > 0
    ) {


        oldFocSlabs.forEach(
            function (slab) {

                addFocRow(

                    slab.slab_name ??
                    '',

                    slab.buy_qty ??
                    '',

                    slab.free_qty ??
                    ''

                );

            }
        );


    } else {


        /*
        |--------------------------------------------------------------------------
        | Otherwise show database slabs.
        |--------------------------------------------------------------------------
        */

        if (
            Array.isArray(existingFocSlabs)
        ) {


            existingFocSlabs.forEach(
                function (slab) {

                    addFocRow(

                        slab.slab_name ??
                        '',

                        slab.buy_qty ??
                        '',

                        slab.free_qty ??
                        ''

                    );

                }
            );

        }

    }


})();

</script>


{{-- =========================================================
     MEDIA HELPER
========================================================= --}}

<script>

function appendHiddenInput(
    name,
    value
) {


    if (
        !value
    ) {

        return;

    }


    const form =
        document.getElementById(
            'productEditForm'
        );


    const input =
        document.createElement(
            'input'
        );


    input.type =
        'hidden';


    input.name =
        name;


    input.value =
        value;


    input.dataset.dynamicMediaInput =
        '1';


    form.appendChild(
        input
    );

}


function removeHiddenInput(
    name,
    value
) {


    $('#productEditForm')
        .find(
            'input[name="' +
            name +
            '"][value="' +
            value +
            '"]'
        )
        .remove();

}

</script>


{{-- =========================================================
     MAIN PHOTO DROPZONE
========================================================= --}}

<script>

var uploadedPhotoMap = {};


Dropzone.options.photoDropzone = {


    url:
        '{{ route('admin.products.storeMedia') }}',


    maxFilesize:
        20,


    acceptedFiles:
        '.jpeg,.jpg,.png,.gif,.webp',


    addRemoveLinks:
        true,


    headers: {

        'X-CSRF-TOKEN':
            "{{ csrf_token() }}"

    },


    params: {

        size:
            20,

        width:
            4096,

        height:
            4096

    },


    success:
        function (
            file,
            response
        ) {


            appendHiddenInput(
                'photo[]',
                response.name
            );


            uploadedPhotoMap[
                file.name
            ] =
                response.name;

        },


    removedfile:
        function (file) {


            if (
                file.previewElement
            ) {

                file.previewElement
                    .remove();

            }


            /*
            |--------------------------------------------------------------------------
            | New uploaded image
            |--------------------------------------------------------------------------
            */

            if (
                uploadedPhotoMap[
                    file.name
                ]
            ) {


                removeHiddenInput(

                    'photo[]',

                    uploadedPhotoMap[
                        file.name
                    ]

                );


                delete
                    uploadedPhotoMap[
                        file.name
                    ];

            }


            /*
            |--------------------------------------------------------------------------
            | Existing image
            |--------------------------------------------------------------------------
            */

            if (
                file.existingMediaId
            ) {


                removeHiddenInput(

                    'existing_photo_ids[]',

                    file.existingMediaId

                );

            }

        },


    init:
        function () {


            var dz =
                this;


            /*
            |--------------------------------------------------------------------------
            | Existing images
            |--------------------------------------------------------------------------
            */

            var existingFiles =
                @json($existingPhotoFiles);


            existingFiles.forEach(
                function (file) {


                    file.existingMediaId =
                        file.id;


                    dz.options
                        .addedfile
                        .call(
                            dz,
                            file
                        );


                    dz.options
                        .thumbnail
                        .call(
                            dz,
                            file,
                            file.preview
                        );


                    if (
                        file.previewElement
                    ) {

                        file.previewElement
                            .classList
                            .add(
                                'dz-complete'
                            );

                    }


                    appendHiddenInput(

                        'existing_photo_ids[]',

                        file.id

                    );

                }
            );

        },


    error:
        function (
            file,
            response
        ) {


            var message =
                typeof response ===
                'string'
                    ? response
                    : (
                        response?.errors?.file
                        || 'Upload failed.'
                    );


            if (
                file.previewElement
            ) {

                file.previewElement
                    .classList
                    .add(
                        'dz-error'
                    );


                var nodes =
                    file.previewElement
                        .querySelectorAll(
                            '[data-dz-errormessage]'
                        );


                nodes.forEach(
                    function (node) {

                        node.textContent =
                            message;

                    }
                );

            }

        }

};

</script>


{{-- =========================================================
     PRODUCT PHOTO 2
========================================================= --}}

<script>

var uploadedProductPhoto2Map = {};


Dropzone.options.productPhoto2Dropzone = {


    url:
        '{{ route('admin.products.storeMedia') }}',


    maxFilesize:
        20,


    acceptedFiles:
        '.jpeg,.jpg,.png,.gif,.webp',


    addRemoveLinks:
        true,


    headers: {

        'X-CSRF-TOKEN':
            "{{ csrf_token() }}"

    },


    params: {

        size:
            20,

        width:
            4096,

        height:
            4096

    },


    success:
        function (
            file,
            response
        ) {


            appendHiddenInput(

                'product_photo_2[]',

                response.name

            );


            uploadedProductPhoto2Map[
                file.name
            ] =
                response.name;

        },


    removedfile:
        function (file) {


            if (
                file.previewElement
            ) {

                file.previewElement
                    .remove();

            }


            /*
            |--------------------------------------------------------------------------
            | New file
            |--------------------------------------------------------------------------
            */

            if (
                uploadedProductPhoto2Map[
                    file.name
                ]
            ) {


                removeHiddenInput(

                    'product_photo_2[]',

                    uploadedProductPhoto2Map[
                        file.name
                    ]

                );


                delete
                    uploadedProductPhoto2Map[
                        file.name
                    ];

            }


            /*
            |--------------------------------------------------------------------------
            | Existing file
            |--------------------------------------------------------------------------
            */

            if (
                file.existingMediaId
            ) {


                removeHiddenInput(

                    'existing_product_photo_2_ids[]',

                    file.existingMediaId

                );

            }

        },


    init:
        function () {


            var dz =
                this;


            var existingFiles =
                @json($existingPhoto2Files);


            existingFiles.forEach(
                function (file) {


                    file.existingMediaId =
                        file.id;


                    dz.options
                        .addedfile
                        .call(
                            dz,
                            file
                        );


                    dz.options
                        .thumbnail
                        .call(
                            dz,
                            file,
                            file.preview
                        );


                    if (
                        file.previewElement
                    ) {

                        file.previewElement
                            .classList
                            .add(
                                'dz-complete'
                            );

                    }


                    appendHiddenInput(

                        'existing_product_photo_2_ids[]',

                        file.id

                    );

                }
            );

        },


    error:
        function (
            file,
            response
        ) {


            var message =
                typeof response ===
                'string'
                    ? response
                    : (
                        response?.errors?.file
                        || 'Upload failed.'
                    );


            if (
                file.previewElement
            ) {

                file.previewElement
                    .classList
                    .add(
                        'dz-error'
                    );


                var nodes =
                    file.previewElement
                        .querySelectorAll(
                            '[data-dz-errormessage]'
                        );


                nodes.forEach(
                    function (node) {

                        node.textContent =
                            message;

                    }
                );

            }

        }

};

</script>


{{-- =========================================================
     PRODUCT PHOTO 3
========================================================= --}}

<script>

Dropzone.options.productPhoto3Dropzone = {


    url:
        '{{ route('admin.products.storeMedia') }}',


    maxFilesize:
        20,


    acceptedFiles:
        '.jpeg,.jpg,.png,.gif,.webp',


    maxFiles:
        1,


    addRemoveLinks:
        true,


    headers: {

        'X-CSRF-TOKEN':
            "{{ csrf_token() }}"

    },


    params: {

        size:
            20,

        width:
            4096,

        height:
            4096

    },


    success:
        function (
            file,
            response
        ) {


            $('#productEditForm')
                .find(
                    'input[name="product_photo_3"]'
                )
                .remove();


            appendHiddenInput(

                'product_photo_3',

                response.name

            );

        },


    removedfile:
        function (file) {


            if (
                file.previewElement
            ) {

                file.previewElement
                    .remove();

            }


            /*
            |--------------------------------------------------------------------------
            | New image
            |--------------------------------------------------------------------------
            */

            $('#productEditForm')
                .find(
                    'input[name="product_photo_3"]'
                )
                .remove();


            /*
            |--------------------------------------------------------------------------
            | Existing image
            |--------------------------------------------------------------------------
            */

            if (
                file.existingMediaId
            ) {

                $('#productEditForm')
                    .find(
                        'input[name="existing_product_photo_3_id"]'
                    )
                    .remove();

            }

        },


    init:
        function () {


            var dz =
                this;


            var existingFiles =
                @json($existingPhoto3Files);


            if (
                existingFiles.length
            ) {


                var file =
                    existingFiles[0];


                file.existingMediaId =
                    file.id;


                dz.options
                    .addedfile
                    .call(
                        dz,
                        file
                    );


                dz.options
                    .thumbnail
                    .call(
                        dz,
                        file,
                        file.preview
                    );


                if (
                    file.previewElement
                ) {

                    file.previewElement
                        .classList
                        .add(
                            'dz-complete'
                        );

                }


                appendHiddenInput(

                    'existing_product_photo_3_id',

                    file.id

                );


                // Keep maxFiles at 1 so the existing image can be removed
                // and replaced with a new upload.

            }

        },


    error:
        function (
            file,
            response
        ) {


            var message =
                typeof response ===
                'string'
                    ? response
                    : (
                        response?.errors?.file
                        || 'Upload failed.'
                    );


            if (
                file.previewElement
            ) {

                file.previewElement
                    .classList
                    .add(
                        'dz-error'
                    );


                var nodes =
                    file.previewElement
                        .querySelectorAll(
                            '[data-dz-errormessage]'
                        );


                nodes.forEach(
                    function (node) {

                        node.textContent =
                            message;

                    }
                );

            }

        }

};

</script>


{{-- =========================================================
     FINAL SELECT2 WIDTH SAFETY FIX
========================================================= --}}

<script>

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | Recalculate Select2 width when wizard changes step
    |--------------------------------------------------------------------------
    */

    $('#productEditForm')
        .on(
            'click',
            '#nextBtn, #prevBtn',
            function () {

                setTimeout(
                    function () {

                        $('#categories')
                            .select2('close');

                        $('#tags')
                            .select2('close');

                        $('#select_companies')
                            .select2('close');

                        $('#godown_id')
                            .select2('close');

                    },
                    100
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | Prevent dropdown from becoming viewport width
    |--------------------------------------------------------------------------
    */

    $(document)
        .on(
            'select2:open',
            function () {


                setTimeout(
                    function () {


                        $('.select2-container--open')
                            .each(
                                function () {

                                    const dropdown =
                                        $(this)
                                            .find(
                                                '.select2-dropdown'
                                            );


                                    const containerWidth =
                                        $(this)
                                            .outerWidth();


                                    if (
                                        containerWidth
                                    ) {

                                        dropdown.css({

                                            width:
                                                containerWidth +
                                                'px',

                                            minWidth:
                                                containerWidth +
                                                'px',

                                            maxWidth:
                                                containerWidth +
                                                'px'

                                        });

                                    }

                                }
                            );


                    },
                    0
                );

            }
        );

});

</script>

@endsection