@extends('layouts.admin')

@section('styles')
<style>
    :root {
        --uw-primary: #7C3AED;
        --uw-primary-light: #A78BFA;
        --uw-accent: #6366F1;
        --uw-bg: #F8F7FF;
        --uw-card-bg: #FFFFFF;
        --uw-border: #E9E5FB;
        --uw-input-bg: #F9FAFB;
        --uw-text: #1F2937;
        --uw-muted: #6B7280;
        --uw-success: #10B981;
        --uw-danger: #EF4444;
        --uw-radius: 16px;
        --uw-shadow: 0 10px 30px rgba(124, 58, 237, 0.08);
    }

    .user-wizard-wrap {
        background: linear-gradient(135deg, #F8F7FF 0%, #F0F4FF 100%);
        padding: 32px 16px;
        border-radius: 24px;
        font-family: 'Inter', sans-serif;
    }

    .user-wizard-wrap .panel {
        background: transparent;
        border: none;
        box-shadow: none;
    }

    .uw-header {
        text-align: center;
        margin-bottom: 32px;
    }

    .uw-header h2 {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        font-size: 28px;
        color: var(--uw-text);
        margin-bottom: 6px;
        background: linear-gradient(135deg, var(--uw-primary), var(--uw-accent));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .uw-header p {
        color: var(--uw-muted);
        font-size: 14px;
        margin: 0;
    }

    /* Stepper */
    .uw-stepper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        max-width: 760px;
        margin: 0 auto 36px;
        position: relative;
    }

    .uw-step-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
        z-index: 2;
    }

    .uw-step-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid var(--uw-border);
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        color: var(--uw-muted);
        transition: all 0.25s ease;
    }

    .uw-step-item.active .uw-step-circle {
        background: linear-gradient(135deg, var(--uw-primary), var(--uw-accent));
        border-color: transparent;
        color: #fff;
        box-shadow: 0 6px 16px rgba(124, 58, 237, 0.35);
    }

    .uw-step-item.completed .uw-step-circle {
        background: var(--uw-success);
        border-color: transparent;
        color: #fff;
    }

    .uw-step-label {
        margin-top: 8px;
        font-size: 12px;
        font-weight: 500;
        color: var(--uw-muted);
        text-align: center;
        max-width: 90px;
    }

    .uw-step-item.active .uw-step-label {
        color: var(--uw-primary);
        font-weight: 700;
    }

    .uw-stepper::before {
        content: '';
        position: absolute;
        top: 21px;
        left: 5%;
        right: 5%;
        height: 2px;
        background: var(--uw-border);
        z-index: 1;
    }

    .uw-stepper-fill {
        position: absolute;
        top: 21px;
        left: 5%;
        height: 2px;
        background: linear-gradient(90deg, var(--uw-primary), var(--uw-accent));
        z-index: 1;
        transition: width 0.3s ease;
        width: 0%;
    }

    /* Card */
    .uw-card {
        background: var(--uw-card-bg);
        border-radius: var(--uw-radius);
        box-shadow: var(--uw-shadow);
        border: 1px solid var(--uw-border);
        max-width: 820px;
        margin: 0 auto;
        overflow: hidden;
    }

    .uw-card-body {
        padding: 36px 40px;
    }

    .uw-step-pane {
        display: none;
        animation: uwFadeIn 0.35s ease;
    }

    .uw-step-pane.active {
        display: block;
    }

    @keyframes uwFadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .uw-pane-title {
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
        font-size: 18px;
        color: var(--uw-text);
        margin-bottom: 4px;
    }

    .uw-pane-sub {
        color: var(--uw-muted);
        font-size: 13px;
        margin-bottom: 24px;
    }

    /* Form fields */
    .uw-form-group {
        margin-bottom: 20px;
    }

    .uw-form-group label {
        font-weight: 600;
        font-size: 13px;
        color: var(--uw-text);
        margin-bottom: 6px;
        display: inline-block;
    }

    .uw-form-group label.required::after {
        content: ' *';
        color: var(--uw-danger);
    }

    .uw-form-group .form-control,
    .uw-form-group .form-control.select2 {
        background: var(--uw-input-bg);
        border: 1.5px solid var(--uw-border);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        color: var(--uw-text);
        height: auto;
        box-shadow: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .uw-form-group .form-control:focus {
        border-color: var(--uw-primary);
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.12);
        background: #fff;
    }

    .uw-form-group.has-error .form-control {
        border-color: var(--uw-danger);
        background: #FEF2F2;
    }

    .uw-help {
        font-size: 12px;
        color: var(--uw-muted);
        margin-top: 4px;
    }

    .uw-error {
        font-size: 12px;
        color: var(--uw-danger);
        margin-top: 4px;
        display: block;
    }

    .uw-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .uw-row .uw-form-group {
        flex: 1 1 220px;
    }

    .uw-checkbox-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--uw-input-bg);
        border: 1.5px solid var(--uw-border);
        border-radius: 10px;
        padding: 12px 14px;
    }

    .uw-checkbox-pill label {
        margin: 0;
        font-weight: 500;
    }

    .uw-vendor-row {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .uw-vendor-row .uw-form-group {
        flex: 1;
        margin-bottom: 0;
    }

    .uw-btn-generate {
        background: #fff;
        border: 1.5px solid var(--uw-primary-light);
        color: var(--uw-primary);
        border-radius: 10px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
        margin-top: 26px;
        transition: all 0.2s ease;
    }

    .uw-btn-generate:hover {
        background: var(--uw-primary);
        color: #fff;
    }

    .select-all, .deselect-all {
        cursor: pointer;
        border-radius: 8px !important;
        background: var(--uw-input-bg) !important;
        color: var(--uw-primary) !important;
        border: 1px solid var(--uw-border) !important;
        font-weight: 600;
    }

    /* Upload zone (final step) */
    .uw-upload-box {
        border: 2px dashed var(--uw-primary-light);
        border-radius: 14px;
        background: linear-gradient(135deg, #FAF9FF, #F5F3FF);
        min-height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .uw-upload-box:hover {
        border-color: var(--uw-primary);
        background: #F3F0FF;
    }

    .uw-upload-box .dz-message {
        color: var(--uw-muted);
        font-size: 13px;
        font-weight: 500;
    }

    /* Footer nav */
    .uw-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 22px 40px;
        border-top: 1px solid var(--uw-border);
        background: #FCFBFF;
    }

    .uw-btn {
        border-radius: 10px;
        padding: 11px 24px;
        font-weight: 600;
        font-size: 14px;
        border: none;
        transition: all 0.2s ease;
    }

    .uw-btn-prev {
        background: #fff;
        border: 1.5px solid var(--uw-border);
        color: var(--uw-muted);
    }

    .uw-btn-prev:hover {
        border-color: var(--uw-primary-light);
        color: var(--uw-primary);
    }

    .uw-btn-next,
    .uw-btn-submit {
        background: linear-gradient(135deg, var(--uw-primary), var(--uw-accent));
        color: #fff;
        box-shadow: 0 6px 16px rgba(124, 58, 237, 0.28);
    }

    .uw-btn-next:hover,
    .uw-btn-submit:hover {
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.4);
        transform: translateY(-1px);
        color: #fff;
    }

    .uw-btn-invisible {
        visibility: hidden;
    }
</style>
@include('admin.users.partials.wizard-styles')
@endsection

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="user-wizard-wrap">

                <div class="uw-header">
                    <h2>{{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}</h2>
                    <p>Naya vendor account chand steps me set karein</p>
                </div>

                <!-- Stepper -->
                <div class="uw-stepper" id="uwStepper">
                    <div class="uw-stepper-fill" id="uwStepperFill"></div>
                    <div class="uw-step-item active" data-step-index="1">
                        <div class="uw-step-circle">1</div>
                        <div class="uw-step-label">Basic Info</div>
                    </div>
                    <div class="uw-step-item" data-step-index="2">
                        <div class="uw-step-circle">2</div>
                        <div class="uw-step-label">Business Details</div>
                    </div>
                    <div class="uw-step-item" data-step-index="3">
                        <div class="uw-step-circle">3</div>
                        <div class="uw-step-label">Bank Details</div>
                    </div>
                    <div class="uw-step-item" data-step-index="4">
                        <div class="uw-step-circle">4</div>
                        <div class="uw-step-label">Vendor &amp; Access</div>
                    </div>
                    <div class="uw-step-item" data-step-index="5">
                        <div class="uw-step-circle">5</div>
                        <div class="uw-step-label">Documents</div>
                    </div>
                </div>

                <div class="uw-card">
                    <form id="userWizard" method="POST" action="{{ route("admin.users.store") }}" enctype="multipart/form-data">
                        @csrf
                        <div class="uw-card-body">

                            {{-- STEP 1: Basic Info --}}
                            <div class="uw-step-pane active" data-step="1">
                                <div class="uw-pane-title">Basic Information</div>
                                <div class="uw-pane-sub">User ka naam, contact aur login details</div>

                                <div class="uw-form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                                    @if($errors->has('name'))
                                        <span class="uw-error" role="alert">{{ $errors->first('name') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.name_helper') }}</span>
                                </div>

                                <div class="uw-row">
                                    <div class="uw-form-group {{ $errors->has('email') ? 'has-error' : '' }}">
                                        <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                                        <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}" required>
                                        @if($errors->has('email'))
                                            <span class="uw-error" role="alert">{{ $errors->first('email') }}</span>
                                        @endif
                                        <span class="uw-help">{{ trans('cruds.user.fields.email_helper') }}</span>
                                    </div>

                                    <div class="uw-form-group {{ $errors->has('phone') ? 'has-error' : '' }}">
                                        <label class="required" for="phone">{{ trans('cruds.user.fields.phone') }}</label>
                                        <input class="form-control" type="text" name="phone" id="phone" value="{{ old('phone', '') }}" required>
                                        @if($errors->has('phone'))
                                            <span class="uw-error" role="alert">{{ $errors->first('phone') }}</span>
                                        @endif
                                        <span class="uw-help">{{ trans('cruds.user.fields.phone_helper') }}</span>
                                    </div>
                                </div>

                                <div class="uw-form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                                    <input class="form-control" type="password" name="password" id="password" required>
                                    @if($errors->has('password'))
                                        <span class="uw-error" role="alert">{{ $errors->first('password') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.password_helper') }}</span>
                                </div>

                                <div class="uw-form-group {{ $errors->has('approved') ? 'has-error' : '' }}">
                                    <div class="uw-checkbox-pill">
                                        <input type="hidden" name="approved" value="0">
                                        <input type="checkbox" name="approved" id="approved" value="1" {{ old('approved', 0) == 1 ? 'checked' : '' }}>
                                        <label for="approved">{{ trans('cruds.user.fields.approved') }}</label>
                                    </div>
                                    @if($errors->has('approved'))
                                        <span class="uw-error" role="alert">{{ $errors->first('approved') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.approved_helper') }}</span>
                                </div>
                            </div>

                            {{-- STEP 2: Business Details --}}
                            <div class="uw-step-pane" data-step="2">
                                <div class="uw-pane-title">Business Details</div>
                                <div class="uw-pane-sub">Business identity aur registration info</div>

                                <div class="uw-form-group {{ $errors->has('business_name') ? 'has-error' : '' }}">
                                    <label class="required" for="business_name">{{ trans('cruds.user.fields.business_name') }}</label>
                                    <input class="form-control" type="text" name="business_name" id="business_name" value="{{ old('business_name', '') }}" required>
                                    @if($errors->has('business_name'))
                                        <span class="uw-error" role="alert">{{ $errors->first('business_name') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.business_name_helper') }}</span>
                                </div>

                                <div class="uw-form-group {{ $errors->has('business_type') ? 'has-error' : '' }}">
                                    <label class="required">{{ trans('cruds.user.fields.business_type') }}</label>
                                    <select class="form-control" name="business_type" id="business_type" required>
                                        <option value disabled {{ old('business_type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                        @foreach(App\Models\User::BUSINESS_TYPE_SELECT as $key => $label)
                                            <option value="{{ $key }}" {{ old('business_type', 'Select Business Type') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('business_type'))
                                        <span class="uw-error" role="alert">{{ $errors->first('business_type') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.business_type_helper') }}</span>
                                </div>

                                <div class="uw-row">
                                    <div class="uw-form-group {{ $errors->has('gst_number') ? 'has-error' : '' }}">
                                        <label for="gst_number">{{ trans('cruds.user.fields.gst_number') }}</label>
                                        <input class="form-control" type="text" name="gst_number" id="gst_number" value="{{ old('gst_number', '') }}">
                                        @if($errors->has('gst_number'))
                                            <span class="uw-error" role="alert">{{ $errors->first('gst_number') }}</span>
                                        @endif
                                        <span class="uw-help">{{ trans('cruds.user.fields.gst_number_helper') }}</span>
                                    </div>

                                    <div class="uw-form-group {{ $errors->has('pan_number') ? 'has-error' : '' }}">
                                        <label for="pan_number">{{ trans('cruds.user.fields.pan_number') }}</label>
                                        <input class="form-control" type="text" name="pan_number" id="pan_number" value="{{ old('pan_number', '') }}">
                                        @if($errors->has('pan_number'))
                                            <span class="uw-error" role="alert">{{ $errors->first('pan_number') }}</span>
                                        @endif
                                        <span class="uw-help">{{ trans('cruds.user.fields.pan_number_helper') }}</span>
                                    </div>
                                </div>

                                <div class="uw-form-group {{ $errors->has('business_address') ? 'has-error' : '' }}">
                                    <label for="business_address">{{ trans('cruds.user.fields.business_address') }}</label>
                                    <textarea class="form-control ckeditor" name="business_address" id="business_address">{!! old('business_address') !!}</textarea>
                                    @if($errors->has('business_address'))
                                        <span class="uw-error" role="alert">{{ $errors->first('business_address') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.business_address_helper') }}</span>
                                </div>

                                <div class="uw-form-group {{ $errors->has('license_details') ? 'has-error' : '' }}">
                                    <label for="license_details">{{ trans('cruds.user.fields.license_details') }}</label>
                                    <input class="form-control" type="text" name="license_details" id="license_details" value="{{ old('license_details', '') }}">
                                    @if($errors->has('license_details'))
                                        <span class="uw-error" role="alert">{{ $errors->first('license_details') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.license_details_helper') }}</span>
                                </div>
                            </div>

                            {{-- STEP 3: Bank Details --}}
                            <div class="uw-step-pane" data-step="3">
                                <div class="uw-pane-title">Bank Details</div>
                                <div class="uw-pane-sub">Payout ke liye bank account info</div>

                                <div class="uw-row">
                                    <div class="uw-form-group {{ $errors->has('bank_name') ? 'has-error' : '' }}">
                                        <label for="bank_name">{{ trans('cruds.user.fields.bank_name') }}</label>
                                        <input class="form-control" type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', '') }}">
                                        @if($errors->has('bank_name'))
                                            <span class="uw-error" role="alert">{{ $errors->first('bank_name') }}</span>
                                        @endif
                                        <span class="uw-help">{{ trans('cruds.user.fields.bank_name_helper') }}</span>
                                    </div>

                                    <div class="uw-form-group {{ $errors->has('account_holder_name') ? 'has-error' : '' }}">
                                        <label for="account_holder_name">{{ trans('cruds.user.fields.account_holder_name') }}</label>
                                        <input class="form-control" type="text" name="account_holder_name" id="account_holder_name" value="{{ old('account_holder_name', '') }}">
                                        @if($errors->has('account_holder_name'))
                                            <span class="uw-error" role="alert">{{ $errors->first('account_holder_name') }}</span>
                                        @endif
                                        <span class="uw-help">{{ trans('cruds.user.fields.account_holder_name_helper') }}</span>
                                    </div>
                                </div>

                                <div class="uw-row">
                                    <div class="uw-form-group {{ $errors->has('account_number') ? 'has-error' : '' }}">
                                        <label for="account_number">{{ trans('cruds.user.fields.account_number') }}</label>
                                        <input class="form-control" type="text" name="account_number" id="account_number" value="{{ old('account_number', '') }}">
                                        @if($errors->has('account_number'))
                                            <span class="uw-error" role="alert">{{ $errors->first('account_number') }}</span>
                                        @endif
                                        <span class="uw-help">{{ trans('cruds.user.fields.account_number_helper') }}</span>
                                    </div>

                                    <div class="uw-form-group {{ $errors->has('ifsc_code') ? 'has-error' : '' }}">
                                        <label for="ifsc_code">{{ trans('cruds.user.fields.ifsc_code') }}</label>
                                        <input class="form-control" type="text" name="ifsc_code" id="ifsc_code" value="{{ old('ifsc_code', '') }}">
                                        @if($errors->has('ifsc_code'))
                                            <span class="uw-error" role="alert">{{ $errors->first('ifsc_code') }}</span>
                                        @endif
                                        <span class="uw-help">{{ trans('cruds.user.fields.ifsc_code_helper') }}</span>
                                    </div>
                                </div>
                            </div>

                            {{-- STEP 4: Vendor & Access --}}
                            <div class="uw-step-pane" data-step="4">
                                <div class="uw-pane-title">Vendor &amp; Access</div>
                                <div class="uw-pane-sub">Vendor ID, role aur account status</div>

                                <div class="uw-vendor-row">
                                    <div class="uw-form-group {{ $errors->has('vendor') ? 'has-error' : '' }}">
                                        <label class="required" for="vendor">{{ trans('cruds.user.fields.vendor') }}</label>
                                        <input class="form-control" type="text" name="vendor" id="vendor" value="{{ old('vendor', '') }}" required>
                                        @if($errors->has('vendor'))
                                            <span class="uw-error" role="alert">{{ $errors->first('vendor') }}</span>
                                        @endif
                                        <span class="uw-help">{{ trans('cruds.user.fields.vendor_helper') }}</span>
                                    </div>
                                    <button type="button" class="uw-btn-generate" onclick="generateVendorId()">Generate Vendor ID</button>
                                </div>

                                <script>
                                    // Vendor ID auto-generate — 4 letters + 4 digits
                                    function generateVendorId() {
                                        const letters = Array.from({ length: 4 }, () => String.fromCharCode(65 + Math.floor(Math.random() * 26))).join('');
                                        const numbers = Array.from({ length: 4 }, () => Math.floor(Math.random() * 10)).join('');
                                        const vendorId = letters + numbers;
                                        document.getElementById('vendor').value = vendorId;
                                    }
                                </script>

                                <div class="uw-form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                                    <label class="required">{{ trans('cruds.user.fields.status') }}</label>
                                    <select class="form-control" name="status" id="status" required>
                                        <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                        @foreach(App\Models\User::STATUS_SELECT as $key => $label)
                                            <option value="{{ $key }}" {{ old('status', 'Select Status') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('status'))
                                        <span class="uw-error" role="alert">{{ $errors->first('status') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.status_helper') }}</span>
                                </div>

                                <div class="uw-form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                                    <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                                    <div style="padding-bottom: 6px">
                                        <span class="btn btn-xs select-all">{{ trans('global.select_all') }}</span>
                                        <span class="btn btn-xs deselect-all">{{ trans('global.deselect_all') }}</span>
                                    </div>
                                    <select class="form-control select2" name="roles[]" id="roles" multiple required>
                                        @foreach($roles as $id => $role)
                                            <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $role }}</option>
                                        @endforeach
                                    </select>
                                    @if($errors->has('roles'))
                                        <span class="uw-error" role="alert">{{ $errors->first('roles') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.roles_helper') }}</span>
                                </div>
                            </div>

                            {{-- STEP 5: Documents (moved to last) --}}
                            <div class="uw-step-pane" data-step="5">
                                <div class="uw-pane-title">Documents &amp; KYC</div>
                                <div class="uw-pane-sub">Verification ke liye documents upload karein — sabse last step</div>

                                <div class="uw-form-group {{ $errors->has('kyc_documents_front') ? 'has-error' : '' }}">
                                    <label for="kyc_documents_front">{{ trans('cruds.user.fields.kyc_documents_front') }}</label>
                                    <div class="needsclick dropzone uw-upload-box" id="kyc_documents_front-dropzone"></div>
                                    @if($errors->has('kyc_documents_front'))
                                        <span class="uw-error" role="alert">{{ $errors->first('kyc_documents_front') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.kyc_documents_front_helper') }}</span>
                                </div>

                                <div class="uw-form-group {{ $errors->has('kyc_documents_back') ? 'has-error' : '' }}">
                                    <label for="kyc_documents_back">{{ trans('cruds.user.fields.kyc_documents_back') }}</label>
                                    <div class="needsclick dropzone uw-upload-box" id="kyc_documents_back-dropzone"></div>
                                    @if($errors->has('kyc_documents_back'))
                                        <span class="uw-error" role="alert">{{ $errors->first('kyc_documents_back') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.kyc_documents_back_helper') }}</span>
                                </div>

                                <div class="uw-form-group {{ $errors->has('business_registration_certificate') ? 'has-error' : '' }}">
                                    <label for="business_registration_certificate">{{ trans('cruds.user.fields.business_registration_certificate') }}</label>
                                    <div class="needsclick dropzone uw-upload-box" id="business_registration_certificate-dropzone"></div>
                                    @if($errors->has('business_registration_certificate'))
                                        <span class="uw-error" role="alert">{{ $errors->first('business_registration_certificate') }}</span>
                                    @endif
                                    <span class="uw-help">{{ trans('cruds.user.fields.business_registration_certificate_helper') }}</span>
                                </div>
                            </div>

                        </div>

                        <div class="uw-footer">
                            <button type="button" class="uw-btn uw-btn-prev uw-btn-invisible" id="uwPrevBtn">Previous</button>
                            <button type="button" class="uw-btn uw-btn-next" id="uwNextBtn">Next Step</button>
                            <button class="uw-btn uw-btn-submit" type="submit" id="uwSubmitBtn" style="display:none">
                                {{ trans('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('admin.users.partials.wizard-script')
<script>
    // ---- Step navigation (naya wizard engine) ----
    $(document).ready(function () {
        var uwTotalSteps = 5;
        var uwCurrentStep = 1;

        function uwUpdateStepper() {
            $('.uw-step-item').each(function () {
                var idx = parseInt($(this).data('step-index'));
                $(this).removeClass('active completed');
                if (idx < uwCurrentStep) {
                    $(this).addClass('completed');
                } else if (idx === uwCurrentStep) {
                    $(this).addClass('active');
                }
            });
            var fillPercent = ((uwCurrentStep - 1) / (uwTotalSteps - 1)) * 90;
            $('#uwStepperFill').css('width', fillPercent + '%');
        }

        function uwShowStep(step) {
            $('.uw-step-pane').removeClass('active');
            $('.uw-step-pane[data-step="' + step + '"]').addClass('active');

            $('#uwPrevBtn').toggleClass('uw-btn-invisible', step === 1);

            if (step === uwTotalSteps) {
                $('#uwNextBtn').hide();
                $('#uwSubmitBtn').show();
            } else {
                $('#uwNextBtn').show();
                $('#uwSubmitBtn').hide();
            }
            uwUpdateStepper();
        }

        function uwValidateCurrentStep() {
            var valid = true;
            $('.uw-step-pane[data-step="' + uwCurrentStep + '"]').find('[required]').each(function () {
                if (!this.reportValidity()) {
                    valid = false;
                    return false;
                }
            });
            return valid;
        }

        $('#uwNextBtn').on('click', function () {
            if (!uwValidateCurrentStep()) return;
            if (uwCurrentStep < uwTotalSteps) {
                uwCurrentStep++;
                uwShowStep(uwCurrentStep);
            }
        });

        $('#uwPrevBtn').on('click', function () {
            if (uwCurrentStep > 1) {
                uwCurrentStep--;
                uwShowStep(uwCurrentStep);
            }
        });

        // Clicking directly on a completed stepper circle jaata hai us step par
        $('.uw-step-item').on('click', function () {
            var target = parseInt($(this).data('step-index'));
            if (target < uwCurrentStep) {
                uwCurrentStep = target;
                uwShowStep(uwCurrentStep);
            }
        });

        uwShowStep(uwCurrentStep);
    });
</script>

<script>
    $(document).ready(function () {
        function SimpleUploadAdapter(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter = function (loader) {
                return {
                    upload: function () {
                        return loader.file
                            .then(function (file) {
                                return new Promise(function (resolve, reject) {
                                    var xhr = new XMLHttpRequest();
                                    xhr.open('POST', '{{ route('admin.users.storeCKEditorImages') }}', true);
                                    xhr.setRequestHeader('x-csrf-token', window._token);
                                    xhr.setRequestHeader('Accept', 'application/json');
                                    xhr.responseType = 'json';

                                    var genericErrorText = `Couldn't upload file: ${file.name}.`;
                                    xhr.addEventListener('error', function () { reject(genericErrorText) });
                                    xhr.addEventListener('abort', function () { reject() });
                                    xhr.addEventListener('load', function () {
                                        var response = xhr.response;
                                        if (!response || xhr.status !== 201) {
                                            return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                                        }
                                        $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');
                                        resolve({ default: response.url });
                                    });

                                    if (xhr.upload) {
                                        xhr.upload.addEventListener('progress', function (e) {
                                            if (e.lengthComputable) {
                                                loader.uploadTotal = e.total;
                                                loader.uploaded = e.loaded;
                                            }
                                        });
                                    }

                                    var data = new FormData();
                                    data.append('upload', file);
                                    data.append('crud_id', '{{ $user->id ?? 0 }}');
                                    xhr.send(data);
                                });
                            })
                    }
                };
            }
        }

        var allEditors = document.querySelectorAll('.ckeditor');
        for (var i = 0; i < allEditors.length; ++i) {
            ClassicEditor.create(
                allEditors[i], {
                    extraPlugins: [SimpleUploadAdapter]
                }
            );
        }
    });
</script>

<script>
    var uploadedKycDocumentsFrontMap = {}
    Dropzone.options.kycDocumentsFrontDropzone = {
        url: '{{ route('admin.users.storeMedia') }}',
        maxFilesize: 20, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
            size: 20,
            width: 4096,
            height: 4096
        },
        success: function (file, response) {
            $('form').append('<input type="hidden" name="kyc_documents_front[]" value="' + response.name + '">')
            uploadedKycDocumentsFrontMap[file.name] = response.name
        },
        removedfile: function (file) {
            file.previewElement.remove()
            var name = ''
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name
            } else {
                name = uploadedKycDocumentsFrontMap[file.name]
            }
            $('form').find('input[name="kyc_documents_front[]"][value="' + name + '"]').remove()
        },
        init: function () {
@if(isset($user) && $user->kyc_documents_front)
            var files = {!! json_encode($user->kyc_documents_front) !!}
                for (var i in files) {
                var file = files[i]
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="kyc_documents_front[]" value="' + file.file_name + '">')
            }
@endif
        },
        error: function (file, response) {
            var message
            if ($.type(response) === 'string') {
                message = response
            } else {
                message = response.errors.file
            }
            file.previewElement.classList.add('dz-error')
            var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
            for (var _i = 0, _len = _ref.length; _i < _len; _i++) {
                _ref[_i].textContent = message
            }
        }
    }
</script>

<script>
    Dropzone.options.kycDocumentsBackDropzone = {
        url: '{{ route('admin.users.storeMedia') }}',
        maxFilesize: 20, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        maxFiles: 1,
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
            size: 20,
            width: 4096,
            height: 4096
        },
        success: function (file, response) {
            $('form').find('input[name="kyc_documents_back"]').remove()
            $('form').append('<input type="hidden" name="kyc_documents_back" value="' + response.name + '">')
        },
        removedfile: function (file) {
            file.previewElement.remove()
            if (file.status !== 'error') {
                $('form').find('input[name="kyc_documents_back"]').remove()
                this.options.maxFiles = this.options.maxFiles + 1
            }
        },
        init: function () {
@if(isset($user) && $user->kyc_documents_back)
            var file = {!! json_encode($user->kyc_documents_back) !!}
                this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="kyc_documents_back" value="' + file.file_name + '">')
            this.options.maxFiles = this.options.maxFiles - 1
@endif
        },
        error: function (file, response) {
            var message
            if ($.type(response) === 'string') {
                message = response
            } else {
                message = response.errors.file
            }
            file.previewElement.classList.add('dz-error')
            var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
            for (var _i = 0, _len = _ref.length; _i < _len; _i++) {
                _ref[_i].textContent = message
            }
        }
    }
</script>

<script>
    var uploadedBusinessRegistrationCertificateMap = {}
    Dropzone.options.businessRegistrationCertificateDropzone = {
        url: '{{ route('admin.users.storeMedia') }}',
        maxFilesize: 20, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        addRemoveLinks: true,
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
            size: 20,
            width: 4096,
            height: 4096
        },
        success: function (file, response) {
            $('form').append('<input type="hidden" name="business_registration_certificate[]" value="' + response.name + '">')
            uploadedBusinessRegistrationCertificateMap[file.name] = response.name
        },
        removedfile: function (file) {
            file.previewElement.remove()
            var name = ''
            if (typeof file.file_name !== 'undefined') {
                name = file.file_name
            } else {
                name = uploadedBusinessRegistrationCertificateMap[file.name]
            }
            $('form').find('input[name="business_registration_certificate[]"][value="' + name + '"]').remove()
        },
        init: function () {
@if(isset($user) && $user->business_registration_certificate)
            var files = {!! json_encode($user->business_registration_certificate) !!}
                for (var i in files) {
                var file = files[i]
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="business_registration_certificate[]" value="' + file.file_name + '">')
            }
@endif
        },
        error: function (file, response) {
            var message
            if ($.type(response) === 'string') {
                message = response
            } else {
                message = response.errors.file
            }
            file.previewElement.classList.add('dz-error')
            var _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
            for (var _i = 0, _len = _ref.length; _i < _len; _i++) {
                _ref[_i].textContent = message
            }
        }
    }
</script>
@endsection