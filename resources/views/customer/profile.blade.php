@extends('layouts.frontend')

@section('content')
<section class="dashboard py-5">
    <div class="row">
        <div class="col-lg-9">
            <h2 class="alert alert-danger"><b>Profile Settings</b></h2>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
        
            <form action="{{ route('custom.delivery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
            <div class="row p-3">
                <!-- Country Input -->
                <div class="form-group col-lg-6 p-2">
                    <label for="country">Country</label>
                    <input type="text" name="country" id="country" class="form-control" value="India" required readonly>
                    @error('country')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- State Input -->
                <div class="form-group col-lg-6 p-2">
                    <label for="state">State</label>
                    <input type="text" name="state" id="state" class="form-control" value="{{ $address->state ?? '' }}" required>
                    @error('state')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- City Input -->
                <div class="form-group col-lg-6 p-2">
                    <label for="city">City</label>
                    <input type="text" name="district" id="city" class="form-control" value="{{ $address->district ?? '' }}" required>
                    @error('city')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Pincode Input -->
                <div class="form-group col-lg-6 p-2">
                    <label for="pincode">Pincode</label>
                    <input type="text" name="pin_code" id="pincode" class="form-control" value="{{ $address->pin_code ?? '' }}" required>
                    @error('pincode')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Profile Photo Upload -->
                <div class="form-group col-lg-12 p-2">
                    <label for="profile_photo">Profile Photo</label>
                    <input type="file" name="profile_photo" id="profile_photo" class="form-control">
                    @error('profile_photo')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
        
                    @if(isset($customer->profile_photo))
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $customer->profile_photo) }}" alt="Profile Photo" width="100">
                        </div>
                    @endif
                </div>
                <div class="form-group col-lg-12 p-2">
                    <label for="address">Full Address</label>
                    <textarea name="full_address" id="address" cols="20" rows="10" class="form-control">{{ $address->full_address ?? '' }}</textarea>
                </div>
                <div class="col-lg-12 p-2">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </div>    
            </form>
        </div>
    </div>
</section>
@endsection
