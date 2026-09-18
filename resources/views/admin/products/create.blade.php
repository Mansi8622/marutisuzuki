@extends('layouts.admin')

@section('content')

<style>
    /* =========================================================
   PROFESSIONAL SELECT2 FIX
========================================================= */

    .select2-container {
        width: 100% !important;
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 44px !important;
        width: 100% !important;
        border: 1px solid #dfe3e8 !important;
        border-radius: 9px !important;
        padding: 5px 8px !important;
        background: #fff !important;
    }

    .select2-container--default.select2-container--focus
    .select2-selection--multiple {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,.08) !important;
    }

    .select2-container--default
    .select2-selection--single {
        height: 44px !important;
        width: 100% !important;
        border: 1px solid #dfe3e8 !important;
        border-radius: 9px !important;
    }

    .select2-container--default
    .select2-selection--single
    .select2-selection__rendered {
        line-height: 42px !important;
        padding-left: 13px !important;
    }

    .select2-container--default
    .select2-selection--single
    .select2-selection__arrow {
        height: 42px !important;
    }

    /* Dropdown itself */
    .select2-container--open .select2-dropdown {
        min-width: 100% !important;
        width: auto !important;
        border-radius: 0 0 10px 10px !important;
        border-color: #6366f1 !important;
        box-shadow: 0 12px 30px rgba(15,23,42,.12) !important;
        z-index: 99999 !important;
    }

    /* Multiple selected items */
    .select2-container--default
    .select2-selection--multiple
    .select2-selection__choice {
        background: linear-gradient(
            135deg,
            #2563eb,
            #4f46e5
        ) !important;

        border: 0 !important;
        color: #fff !important;
        border-radius: 6px !important;
        padding: 4px 8px !important;
        margin-top: 3px !important;
    }

    /* Search box */
    .select2-container--default
    .select2-search--dropdown
    .select2-search__field {
        width: 100% !important;
        border-radius: 7px !important;
        border: 1px solid #d1d5db !important;
        padding: 8px 10px !important;
    }

    /* Options */
    .select2-results__option {
        padding: 10px 12px !important;
        font-size: 13px !important;
    }

    .select2-results__option--highlighted {
        background: #2563eb !important;
    }

    /* =========================================================
    REQUIRED STAR
    ========================================================= */

    label.required::after {
        content: " *";
        color: #dc2626;
        font-weight: 800;
        margin-left: 3px;
    }

    /* Select2 required labels */
    label.required {
        display: block;
    }

    /* Error state */
    .is-invalid,
    .form-control.is-invalid {
        border-color: #dc2626 !important;
    }

    /* Field card label */
    .field-card > label {
        display: block;
        font-size: 14px;
        margin-bottom: 8px;
    }
    /* =========================================================
       PRODUCT CREATE WIZARD
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

    /* Header */
    .wizard-header {
        background: linear-gradient(135deg, #111827, #1f2937);
        color: #fff;
        border-radius: 18px 18px 0 0;
        padding: 25px 30px;
        position: relative;
        overflow: hidden;
    }

    .wizard-header:before {
        content: "";
        position: absolute;
        width: 260px;
        height: 260px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
        right: -80px;
        top: -130px;
    }

    .wizard-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        position: relative;
    }

    .wizard-header p {
        margin: 7px 0 0;
        opacity: .72;
        position: relative;
    }

    /* Progress */
    .wizard-progress {
        background: #fff;
        padding: 22px 30px;
        border-bottom: 1px solid #e5e7eb;
    }

    .steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .steps:before {
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
        background: linear-gradient(90deg, #2563eb, #7c3aed);
        z-index: 1;
        transition: width .45s ease;
    }

    .step-item {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
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
        transition: all .35s ease;
    }

    .step-item.active .step-circle {
        border-color: #2563eb;
        background: #2563eb;
        color: #fff;
        box-shadow: 0 8px 25px rgba(37,99,235,.28);
        transform: scale(1.08);
    }

    .step-item.completed .step-circle {
        border-color: #16a34a;
        background: #16a34a;
        color: #fff;
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

    /* Body */
    .wizard-body {
        background: #fff;
        padding: 30px;
        min-height: 550px;
    }

    .wizard-step {
        display: none;
        animation: slideIn .4s ease;
    }

    .wizard-step.active {
        display: block;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 13px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #edf0f4;
    }

    .section-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: #fff;
        font-size: 17px;
    }

    .section-heading h3 {
        margin: 0;
        font-size: 19px;
        font-weight: 700;
        color: #111827;
    }

    .section-heading p {
        margin: 3px 0 0;
        font-size: 12px;
        color: #6b7280;
    }

    /* Cards */
    .field-card {
        background: #fff;
        border: 1px solid #e8ebf0;
        border-radius: 14px;
        padding: 18px;
        margin-bottom: 18px;
        transition: all .25s ease;
    }

    .field-card:hover {
        border-color: #c7d2fe;
        box-shadow: 0 8px 25px rgba(15,23,42,.05);
    }

    .field-card label {
        font-weight: 600;
        color: #374151;
        margin-bottom: 7px;
    }

    .form-control {
        border-radius: 9px;
        min-height: 42px;
        border-color: #dfe3e8;
        box-shadow: none !important;
    }

    .form-control:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99,102,241,.08) !important;
    }

    /* Fitment */
    .fitment-wrapper {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
        min-height: 180px;
    }

    .fitment-note {
        display: flex;
        gap: 10px;
        align-items: center;
        background: #eff6ff;
        border: 1px solid #bfdbfe;
        color: #1d4ed8;
        border-radius: 10px;
        padding: 12px 15px;
        margin-bottom: 18px;
        font-size: 13px;
    }

    /* FOC */
    .foc-slab-row {
        background: #f8fafc;
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        margin-bottom: 10px;
        animation: fadeUp .25s ease;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Upload */
    .dropzone {
        border: 2px dashed #cbd5e1 !important;
        border-radius: 14px !important;
        background: #f8fafc !important;
        min-height: 160px;
        transition: all .25s ease;
    }

    .dropzone:hover {
        border-color: #6366f1 !important;
        background: #f5f3ff !important;
    }

    /* Footer */
    .wizard-footer {
        background: #fff;
        border-top: 1px solid #edf0f4;
        padding: 18px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 0 0 18px 18px;
    }

    .btn-wizard {
        min-width: 125px;
        height: 44px;
        border-radius: 10px;
        font-weight: 600;
        border: 0;
        transition: all .25s ease;
    }

    .btn-wizard:hover {
        transform: translateY(-2px);
    }

    .btn-next {
        color: #fff;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        box-shadow: 0 7px 18px rgba(79,70,229,.22);
    }

    .btn-back {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-save {
        color: #fff;
        background: linear-gradient(135deg, #059669, #16a34a);
        box-shadow: 0 7px 18px rgba(22,163,74,.2);
    }

    .step-counter {
        color: #6b7280;
        font-size: 13px;
    }

    /* Review */
    .review-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 20px;
    }

    .review-row {
        display: flex;
        justify-content: space-between;
        padding: 11px 0;
        border-bottom: 1px solid #e5e7eb;
    }

    .review-row:last-child {
        border-bottom: 0;
    }

    .review-label {
        color: #6b7280;
    }

    .review-value {
        font-weight: 600;
        color: #111827;
    }

    /* Mobile */
    @media(max-width: 767px) {
        .wizard-body,
        .wizard-footer,
        .wizard-progress,
        .wizard-header {
            padding: 18px;
        }

        .step-title {
            display: none;
        }

        .step-circle {
            width: 38px;
            height: 38px;
        }

        .steps:before,
        .progress-line {
            top: 18px;
        }

        .wizard-footer {
            gap: 10px;
        }

        .btn-wizard {
            min-width: 105px;
        }
    }
</style>

<div class="product-wizard">

    <div class="wizard-shell">

        <div class="wizard-header">
            <h2>
                <i class="fas fa-box-open"></i>
                Create New Product
            </h2>
            <p>
                Add product information, vehicle fitment, pricing, media and schemes step-by-step.
            </p>
        </div>

        {{-- ================= PROGRESS ================= --}}
        <div class="wizard-progress">

            <div class="steps">

                <div class="progress-line" id="progressLine"></div>

                <div class="step-item active" data-step="1">
                    <div class="step-circle">
                        <i class="fas fa-info"></i>
                    </div>
                    <div class="step-title">Basic Info</div>
                </div>

                <div class="step-item" data-step="2">
                    <div class="step-circle">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="step-title">Category & Vehicle</div>
                </div>

                <div class="step-item" data-step="3">
                    <div class="step-circle">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="step-title">Pricing & Stock</div>
                </div>

                <div class="step-item" data-step="4">
                    <div class="step-circle">
                        <i class="fas fa-images"></i>
                    </div>
                    <div class="step-title">Media & Scheme</div>
                </div>

                <div class="step-item" data-step="5">
                    <div class="step-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="step-title">Review</div>
                </div>

            </div>
        </div>

        <form method="POST"
              action="{{ route('admin.products.store') }}"
              enctype="multipart/form-data"
              id="productCreateForm">

            @csrf

            <div class="wizard-body">

                {{-- =====================================================
                     STEP 1
                ====================================================== --}}
                <div class="wizard-step active" data-step="1">

                    <div class="section-heading">
                        <div class="section-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <h3>Basic Product Information</h3>
                            <p>Enter the primary details of your product.</p>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="field-card">
                                <label class="required" for="name">
                                    Product Name
                                </label>

                                <input class="form-control"
                                       type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', '') }}"
                                       required>

                                @if($errors->has('name'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('name') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="field-card">
                                <label class="required" for="item_code">
                                    Item Code
                                </label>

                                <input class="form-control"
                                       type="text"
                                       name="item_code"
                                       id="item_code"
                                       value="{{ old('item_code', '') }}"
                                       required>

                                @if($errors->has('item_code'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('item_code') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="field-card">
                                <label class="required" for="hsn_code">
                                    HSN Code
                                </label>

                                <input class="form-control"
                                       type="text"
                                       name="hsn_code"
                                       id="hsn_code"
                                       value="{{ old('hsn_code', '') }}"
                                       required>

                                @if($errors->has('hsn_code'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('hsn_code') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     STEP 2
                ====================================================== --}}
                <div class="wizard-step" data-step="2">

                    <div class="section-heading">
                        <div class="section-icon">
                            <i class="fas fa-sitemap"></i>
                        </div>
                        <div>
                            <h3>Category & Vehicle Fitment</h3>
                            <p>Select exactly where this product belongs.</p>
                        </div>
                    </div>

                    <div class="fitment-note">
                        <i class="fas fa-info-circle"></i>

                        <span>
                            Sub-categories and vehicle models are now
                            <strong>unchecked by default</strong>.
                            Select only the required fitments.
                        </span>
                    </div>

                    <div class="row">

                        {{-- Category --}}
                        <div class="col-md-4">

                            <div class="field-card">

                                <label for="categories">
                                    <i class="fas fa-folder"></i>
                                    Category
                                </label>

                                <select class="form-control select2"
                                        name="categories[]"
                                        id="categories"
                                        multiple>

                                    @foreach($categories as $id => $category)

                                        <option value="{{ $id }}"
                                            {{ in_array($id, old('categories', [])) ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>

                                    @endforeach

                                </select>

                                @if($errors->has('categories'))
                                    <span class="help-block text-danger">
                                        {{ $errors->first('categories') }}
                                    </span>
                                @endif

                            </div>

                        </div>


                        {{-- Tags --}}
                        <div class="col-md-4">

                            <div class="field-card">

                                <label for="tags">
                                    <i class="fas fa-hashtag"></i>
                                    Tags
                                </label>

                                <select class="form-control select2"
                                        name="tags[]"
                                        id="tags"
                                        multiple>

                                    @foreach($tags as $id => $tag)

                                        <option value="{{ $id }}"
                                            {{ in_array($id, old('tags', [])) ? 'selected' : '' }}>
                                            {{ $tag }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>


                        {{-- Company --}}
                        <div class="col-md-4">

                            <div class="field-card">

                                <label class="required" for="select_companies">
                                    <i class="fas fa-car"></i>
                                    Vehicle Company
                                </label>

                                <select class="form-control select2"
                                        name="select_companies[]"
                                        id="select_companies"
                                        multiple
                                        required>

                                    @foreach($select_companies as $id => $select_company)

                                        <option value="{{ $id }}"
                                            {{ in_array($id, old('select_companies', [])) ? 'selected' : '' }}>
                                            {{ $select_company }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>

                        </div>

                    </div>


                    {{-- Fitments --}}
                    <div class="field-card">

                        <h4 style="margin-top:0;font-weight:700;">
                            <i class="fas fa-car-side text-primary"></i>
                            Vehicle Fitment
                        </h4>

                        <p class="text-muted" style="font-size:13px;">
                            Select category/sub-category and then choose the
                            required vehicle models.
                        </p>

                        <div class="fitment-wrapper" id="fitmentStep">

                            {{-- Existing fitment system --}}
                            @include('admin.products.fitments')

                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     STEP 3
                ====================================================== --}}
                <div class="wizard-step" data-step="3">

                    <div class="section-heading">
                        <div class="section-icon">
                            <i class="fas fa-tags"></i>
                        </div>
                        <div>
                            <h3>Inventory & Pricing</h3>
                            <p>Configure stock quantity and all applicable prices.</p>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-4">
                            <div class="field-card">

                                <label class="required" for="godown_id">
                                    <i class="fas fa-building"></i>
                                    Godown
                                </label>

                                <select class="form-control select2"
                                        name="godown_id"
                                        id="godown_id"
                                        required>

                                    @foreach($godowns as $id => $godown)

                                        <option value="{{ $id }}"
                                            {{ old('godown_id', $product->godown_id ?? '') == $id ? 'selected' : '' }}>
                                            {{ $godown }}
                                        </option>

                                    @endforeach

                                </select>

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="field-card">

                                <label class="required" for="quantity">
                                    <i class="fas fa-cubes"></i>
                                    Quantity
                                </label>

                                <input class="form-control"
                                       type="number"
                                       name="quantity"
                                       id="quantity"
                                       value="{{ old('quantity', '') }}"
                                       min="0"
                                       required>

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="field-card">

                                <label class="required" for="price">
                                    <i class="fas fa-rupee-sign"></i>
                                    Price
                                </label>

                                <input class="form-control"
                                       type="number"
                                       name="price"
                                       id="price"
                                       value="{{ old('price', '') }}"
                                       step="0.01"
                                       min="0"
                                       required>

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="field-card">

                                <label for="discount">
                                    <i class="fas fa-percent"></i>
                                    Discount
                                </label>

                                <input class="form-control"
                                       type="number"
                                       name="discount"
                                       id="discount"
                                       value="{{ old('discount', '') }}"
                                       step="1"
                                       min="0">

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="field-card">

                                <label class="required" for="price_1">
                                    <i class="fas fa-money-bill"></i>
                                    Dealer / Base Price
                                </label>

                                <input class="form-control"
                                       type="number"
                                       name="price_1"
                                       id="price_1"
                                       value="{{ old('price_1', '') }}"
                                       step="0.01"
                                       min="0"
                                       required>

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="field-card">

                                <label for="rate_2">
                                    <i class="fas fa-user"></i>
                                    Customer Price
                                </label>

                                <input class="form-control"
                                       type="number"
                                       name="rate_2"
                                       id="rate_2"
                                       value="{{ old('rate_2', '') }}"
                                       step="0.01"
                                       min="0">

                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="field-card">

                                <label for="rate_3">
                                    <i class="fas fa-briefcase"></i>
                                    Corporate Price
                                </label>

                                <input class="form-control"
                                       type="number"
                                       name="rate_3"
                                       id="rate_3"
                                       value="{{ old('rate_3', '') }}"
                                       step="0.01"
                                       min="0">

                            </div>
                        </div>

                    </div>

                </div>


                {{-- =====================================================
                     STEP 4
                ====================================================== --}}
                <div class="wizard-step" data-step="4">

                    <div class="section-heading">
                        <div class="section-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <div>
                            <h3>Product Media & FOC Scheme</h3>
                            <p>Upload product images and configure free quantity slabs.</p>
                        </div>
                    </div>


                    {{-- Images --}}
                    <div class="field-card">

                        <h4 style="font-weight:700;margin-top:0;">
                            <i class="fas fa-camera text-primary"></i>
                            Product Images
                        </h4>

                        <div class="row">

                            <div class="col-md-4 form-group">
                                <label class="required">
                                    Main Product Image
                                </label>

                                <div class="needsclick dropzone"
                                     id="photo-dropzone">
                                </div>
                            </div>


                            <div class="col-md-4 form-group">

                                <label>
                                    Product Image 2
                                </label>

                                <div class="needsclick dropzone"
                                     id="product_photo_2-dropzone">
                                </div>

                            </div>


                            <div class="col-md-4 form-group">

                                <label>
                                    Product Image 3
                                </label>

                                <div class="needsclick dropzone"
                                     id="product_photo_3-dropzone">
                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- FOC --}}
                    <div class="field-card">

                        <h4 style="font-weight:700;margin-top:0;">
                            <i class="fas fa-gift text-success"></i>
                            FOC Scheme Slabs
                        </h4>

                        <p class="text-muted">
                            Example: Buy 15 → Free 1
                        </p>

                        <div id="focSlabWrapper"></div>

                        <button type="button"
                                id="addFocSlab"
                                class="btn btn-primary btn-sm">

                            <i class="fas fa-plus"></i>
                            Add FOC Slab

                        </button>

                    </div>

                </div>


                {{-- =====================================================
                     STEP 5
                ====================================================== --}}
                <div class="wizard-step" data-step="5">

                    <div class="section-heading">

                        <div class="section-icon">
                            <i class="fas fa-check"></i>
                        </div>

                        <div>
                            <h3>Description & Final Review</h3>
                            <p>Review your product before saving.</p>
                        </div>

                    </div>


                    <div class="field-card">

                        <label for="description">
                            Product Description
                        </label>

                        <textarea class="form-control"
                                  name="description"
                                  id="description"
                                  rows="7">{{ old('description') }}</textarea>

                    </div>


                    <div class="review-box">

                        <h4 style="margin-top:0;font-weight:700;">
                            <i class="fas fa-clipboard-check text-success"></i>
                            Ready to Create Product
                        </h4>

                        <div class="review-row">
                            <span class="review-label">Product Name</span>
                            <span class="review-value"
                                  id="reviewName">—</span>
                        </div>

                        <div class="review-row">
                            <span class="review-label">Item Code</span>
                            <span class="review-value"
                                  id="reviewItemCode">—</span>
                        </div>

                        <div class="review-row">
                            <span class="review-label">Price</span>
                            <span class="review-value"
                                  id="reviewPrice">—</span>
                        </div>

                        <div class="review-row">
                            <span class="review-label">Quantity</span>
                            <span class="review-value"
                                  id="reviewQuantity">—</span>
                        </div>

                        <div class="review-row">
                            <span class="review-label">Vehicle Company</span>
                            <span class="review-value"
                                  id="reviewCompany">—</span>
                        </div>

                    </div>

                </div>

            </div>


            {{-- =====================================================
                 FOOTER
            ====================================================== --}}
            <div class="wizard-footer">

                <div class="step-counter">
                    Step <strong id="currentStepText">1</strong> of 5
                </div>

                <div>

                    <button type="button"
                            id="prevBtn"
                            class="btn btn-wizard btn-back"
                            style="display:none;">

                        <i class="fas fa-arrow-left"></i>
                        Back

                    </button>

                    <button type="button"
                            id="nextBtn"
                            class="btn btn-wizard btn-next">

                        Next
                        <i class="fas fa-arrow-right"></i>

                    </button>

                    <button type="submit"
                            id="saveBtn"
                            class="btn btn-wizard btn-save"
                            style="display:none;">

                        <i class="fas fa-check-circle"></i>
                        Create Product

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

@endsection


@section('scripts')

{{-- ============================================================
     WIZARD JS
============================================================ --}}
<script>

(function () {

    let currentStep = 1;
    const totalSteps = 5;

    const form = document.getElementById('productCreateForm');
    const nextBtn = document.getElementById('nextBtn');
    const prevBtn = document.getElementById('prevBtn');
    const saveBtn = document.getElementById('saveBtn');
    const progressLine = document.getElementById('progressLine');

    function showStep(step) {

        currentStep = step;

        document.querySelectorAll('.wizard-step').forEach(function (el) {

            el.classList.remove('active');

            if (parseInt(el.dataset.step) === step) {
                el.classList.add('active');
            }

        });


        document.querySelectorAll('.step-item').forEach(function (el) {

            const itemStep = parseInt(el.dataset.step);

            el.classList.remove('active', 'completed');

            if (itemStep === step) {
                el.classList.add('active');
            }

            if (itemStep < step) {
                el.classList.add('completed');
            }

        });


        const progress = ((step - 1) / (totalSteps - 1)) * 90;

        progressLine.style.width = progress + '%';


        document.getElementById('currentStepText').innerText = step;


        prevBtn.style.display =
            step === 1 ? 'none' : 'inline-block';

        nextBtn.style.display =
            step === totalSteps ? 'none' : 'inline-block';

        saveBtn.style.display =
            step === totalSteps ? 'inline-block' : 'none';


        if (step === totalSteps) {
            updateReview();
        }

        window.scrollTo({
            top: document.querySelector('.wizard-shell').offsetTop - 20,
            behavior: 'smooth'
        });
    }


    function validateCurrentStep() {

        const activeStep =
            document.querySelector('.wizard-step.active');

        if (!activeStep) {
            return true;
        }

        const fields =
            activeStep.querySelectorAll(
                'input[required], select[required], textarea[required]'
            );

        let valid = true;

        fields.forEach(function(field) {

            if (!field.checkValidity()) {

                field.reportValidity();
                valid = false;

                field.classList.add('is-invalid');

                setTimeout(function() {
                    field.classList.remove('is-invalid');
                }, 1800);
            }

        });

        return valid;
    }


    nextBtn.addEventListener('click', function () {

        if (!validateCurrentStep()) {
            return;
        }

        if (currentStep < totalSteps) {
            showStep(currentStep + 1);
        }

    });


    prevBtn.addEventListener('click', function () {

        if (currentStep > 1) {
            showStep(currentStep - 1);
        }

    });


    document.querySelectorAll('.step-item').forEach(function(item) {

        item.addEventListener('click', function() {

            const target =
                parseInt(this.dataset.step);

            /*
             * Only allow going back to completed steps.
             * Prevent skipping required fields.
             */
            if (target < currentStep) {
                showStep(target);
            }

        });

    });


    function updateReview() {

        const name =
            document.getElementById('name')?.value || '—';

        const itemCode =
            document.getElementById('item_code')?.value || '—';

        const price =
            document.getElementById('price')?.value || '—';

        const quantity =
            document.getElementById('quantity')?.value || '—';


        const companies =
            $('#select_companies option:selected')
                .map(function() {
                    return $(this).text().trim();
                })
                .get()
                .join(', ');


        document.getElementById('reviewName').innerText = name;

        document.getElementById('reviewItemCode').innerText =
            itemCode;

        document.getElementById('reviewPrice').innerText =
            price;

        document.getElementById('reviewQuantity').innerText =
            quantity;

        document.getElementById('reviewCompany').innerText =
            companies || '—';
    }


    showStep(1);

})();

</script>


{{-- ============================================================
     HSN RANDOM CODE
============================================================ --}}
<script>

function generateRandomWord(length) {

    const chars =
        "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

    let result = "";

    for (let i = 0; i < length; i++) {

        result += chars.charAt(
            Math.floor(Math.random() * chars.length)
        );

    }

    return result;
}


document
    .getElementById("hsn_code")
    ?.addEventListener("focus", function () {

        if (!this.value) {
            this.value = generateRandomWord(6);
        }

    });

</script>


{{-- ============================================================
     FOC SLABS
============================================================ --}}
<script>

(function(){

    let focIndex = 0;

    const wrapper =
        document.getElementById('focSlabWrapper');

    const addButton =
        document.getElementById('addFocSlab');


    function addFocRow(
        slabName,
        buyQty,
        freeQty
    ) {

        const row =
            document.createElement('div');

        row.className =
            'row foc-slab-row';


        row.innerHTML = `

            <div class="col-md-4">

                <input type="text"
                       name="foc_slabs[${focIndex}][slab_name]"
                       class="form-control"
                       placeholder="Slab Name"
                       value="${slabName || ''}">

            </div>


            <div class="col-md-3">

                <input type="number"
                       name="foc_slabs[${focIndex}][buy_qty]"
                       class="form-control"
                       placeholder="Buy Quantity"
                       min="1"
                       value="${buyQty || ''}">

            </div>


            <div class="col-md-3">

                <input type="number"
                       name="foc_slabs[${focIndex}][free_qty]"
                       class="form-control"
                       placeholder="Free Quantity"
                       min="1"
                       value="${freeQty || ''}">

            </div>


            <div class="col-md-2">

                <button type="button"
                        class="btn btn-danger remove-foc-slab">

                    <i class="fas fa-trash"></i>

                </button>

            </div>
        `;


        wrapper.appendChild(row);

        focIndex++;

    }


    addButton.addEventListener(
        'click',
        function() {
            addFocRow('', '', '');
        }
    );


    wrapper.addEventListener(
        'click',
        function(e) {

            const btn =
                e.target.closest('.remove-foc-slab');

            if (btn) {

                btn.closest('.foc-slab-row').remove();

            }

        }
    );


    const oldFocSlabs =
        @json(old('foc_slabs', []));


    if (
        oldFocSlabs &&
        oldFocSlabs.length
    ) {

        oldFocSlabs.forEach(function(slab) {

            addFocRow(
                slab.slab_name,
                slab.buy_qty,
                slab.free_qty
            );

        });

    }

})();

</script>


{{-- ============================================================
     FITMENT DEFAULT UNCHECK
============================================================ --}}
<script>

(function () {

    /*
     * IMPORTANT:
     *
     * All dynamically generated sub-category
     * and vehicle checkboxes should remain unchecked
     * by default.
     */

    const fitmentArea =
        document.getElementById('fitmentStep');

    if (!fitmentArea) {
        return;
    }


    function uncheckFitments() {

        /*
         * We intentionally target checkboxes only.
         * This does NOT touch select2 or other inputs.
         */
        fitmentArea
            .querySelectorAll('input[type="checkbox"]')
            .forEach(function (checkbox) {

                checkbox.checked = false;

                checkbox.removeAttribute('checked');

            });

    }


    /*
     * Initial state
     */
    uncheckFitments();


    /*
     * If fitments are loaded dynamically
     * through AJAX / DOM rendering, observe
     * newly created checkboxes.
     */
    const observer =
        new MutationObserver(function(mutations) {

            let checkboxAdded = false;

            mutations.forEach(function(mutation) {

                mutation.addedNodes.forEach(function(node) {

                    if (
                        node.nodeType === 1 &&
                        (
                            node.matches?.(
                                'input[type="checkbox"]'
                            ) ||
                            node.querySelector?.(
                                'input[type="checkbox"]'
                            )
                        )
                    ) {
                        checkboxAdded = true;
                    }

                });

            });


            if (checkboxAdded) {

                /*
                 * Small delay allows the fitment
                 * rendering code to finish first.
                 */
                setTimeout(
                    uncheckFitments,
                    50
                );

            }

        });


    observer.observe(
        fitmentArea,
        {
            childList: true,
            subtree: true
        }
    );


    /*
     * When category selection changes,
     * reset dynamically generated fitments.
     */
    $('#categories').on(
        'change',
        function() {

            setTimeout(
                uncheckFitments,
                100
            );

        }
    );

})();

</script>


{{-- ============================================================
     DROPZONE - MAIN PHOTO
============================================================ --}}
<script>

var uploadedPhotoMap = {};

Dropzone.options.photoDropzone = {

    url: '{{ route('admin.products.storeMedia') }}',

    maxFilesize: 20,

    acceptedFiles:
        '.jpeg,.jpg,.png,.gif,.webp',

    addRemoveLinks: true,

    headers: {
        'X-CSRF-TOKEN':
            "{{ csrf_token() }}"
    },

    params: {
        size: 20,
        width: 4096,
        height: 4096
    },

    success: function(file, response) {

        $('form').append(
            '<input type="hidden" name="photo[]" value="' +
            response.name +
            '">'
        );

        uploadedPhotoMap[file.name] =
            response.name;
    },

    removedfile: function(file) {

        file.previewElement.remove();

        var name = '';

        if (
            typeof file.file_name !== 'undefined'
        ) {
            name = file.file_name;
        } else {
            name =
                uploadedPhotoMap[file.name];
        }

        $('form')
            .find(
                'input[name="photo[]"][value="' +
                name +
                '"]'
            )
            .remove();
    },

    init: function() {

        @if(isset($product) && $product->photo)

            var files =
                {!! json_encode($product->photo) !!};

            for (var i in files) {

                var file = files[i];

                this.options.addedfile.call(
                    this,
                    file
                );

                this.options.thumbnail.call(
                    this,
                    file,
                    file.preview ??
                    file.preview_url
                );

                file.previewElement
                    .classList
                    .add('dz-complete');

                $('form').append(
                    '<input type="hidden" name="photo[]" value="' +
                    file.file_name +
                    '">'
                );
            }

        @endif

    },

    error: function(file, response) {

        var message =
            typeof response === 'string'
                ? response
                : response.errors.file;

        file.previewElement
            .classList
            .add('dz-error');

        var nodes =
            file.previewElement
                .querySelectorAll(
                    '[data-dz-errormessage]'
                );

        nodes.forEach(function(node) {
            node.textContent = message;
        });

    }

};

</script>


{{-- ============================================================
     DROPZONE - PHOTO 2
============================================================ --}}
<script>

var uploadedProductPhoto2Map = {};

Dropzone.options.productPhoto2Dropzone = {

    url: '{{ route('admin.products.storeMedia') }}',

    maxFilesize: 20,

    acceptedFiles:
        '.jpeg,.jpg,.png,.gif,.webp',

    addRemoveLinks: true,

    headers: {
        'X-CSRF-TOKEN':
            "{{ csrf_token() }}"
    },

    params: {
        size: 20,
        width: 4096,
        height: 4096
    },

    success: function(file, response) {

        $('form').append(
            '<input type="hidden" name="product_photo_2[]" value="' +
            response.name +
            '">'
        );

        uploadedProductPhoto2Map[file.name] =
            response.name;
    },

    removedfile: function(file) {

        file.previewElement.remove();

        var name = '';

        if (
            typeof file.file_name !== 'undefined'
        ) {
            name = file.file_name;
        } else {
            name =
                uploadedProductPhoto2Map[file.name];
        }

        $('form')
            .find(
                'input[name="product_photo_2[]"][value="' +
                name +
                '"]'
            )
            .remove();
    },

    init: function() {

        @if(isset($product) && $product->product_photo_2)

            var files =
                {!! json_encode($product->product_photo_2) !!};

            for (var i in files) {

                var file = files[i];

                this.options.addedfile.call(
                    this,
                    file
                );

                this.options.thumbnail.call(
                    this,
                    file,
                    file.preview ??
                    file.preview_url
                );

                file.previewElement
                    .classList
                    .add('dz-complete');

                $('form').append(
                    '<input type="hidden" name="product_photo_2[]" value="' +
                    file.file_name +
                    '">'
                );
            }

        @endif

    },

    error: function(file, response) {

        var message =
            typeof response === 'string'
                ? response
                : response.errors.file;

        file.previewElement
            .classList
            .add('dz-error');

        var nodes =
            file.previewElement
                .querySelectorAll(
                    '[data-dz-errormessage]'
                );

        nodes.forEach(function(node) {
            node.textContent = message;
        });

    }

};

</script>


{{-- ============================================================
     DROPZONE - PHOTO 3
============================================================ --}}
<script>

Dropzone.options.productPhoto3Dropzone = {

    url: '{{ route('admin.products.storeMedia') }}',

    maxFilesize: 20,

    acceptedFiles:
        '.jpeg,.jpg,.png,.gif,.webp',

    maxFiles: 1,

    addRemoveLinks: true,

    headers: {
        'X-CSRF-TOKEN':
            "{{ csrf_token() }}"
    },

    params: {
        size: 20,
        width: 4096,
        height: 4096
    },

    success: function(file, response) {

        $('form')
            .find(
                'input[name="product_photo_3"]'
            )
            .remove();

        $('form').append(
            '<input type="hidden" name="product_photo_3" value="' +
            response.name +
            '">'
        );

    },

    removedfile: function(file) {

        file.previewElement.remove();

        if (file.status !== 'error') {

            $('form')
                .find(
                    'input[name="product_photo_3"]'
                )
                .remove();

            this.options.maxFiles =
                this.options.maxFiles + 1;
        }

    },

    init: function() {

        @if(isset($product) && $product->product_photo_3)

            var file =
                {!! json_encode($product->product_photo_3) !!};

            this.options.addedfile.call(
                this,
                file
            );

            this.options.thumbnail.call(
                this,
                file,
                file.preview ??
                file.preview_url
            );

            file.previewElement
                .classList
                .add('dz-complete');

            $('form').append(
                '<input type="hidden" name="product_photo_3" value="' +
                file.file_name +
                '">'
            );

            this.options.maxFiles =
                this.options.maxFiles - 1;

        @endif

    },

    error: function(file, response) {

        var message =
            typeof response === 'string'
                ? response
                : response.errors.file;

        file.previewElement
            .classList
            .add('dz-error');

        var nodes =
            file.previewElement
                .querySelectorAll(
                    '[data-dz-errormessage]'
                );

        nodes.forEach(function(node) {
            node.textContent = message;
        });

    }

};

</script>

@endsection