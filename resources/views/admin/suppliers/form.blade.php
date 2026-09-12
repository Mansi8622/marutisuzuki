<form action="{{ isset($supplier) ? route('admin.suppliers.update', $supplier->id) : route('admin.suppliers.store') }}" 
    method="POST" 
    enctype="multipart/form-data">
  @csrf
  @if(isset($supplier))
      @method('PUT')
  @endif

  <div class="row" style="padding: 10px !important;">
      <div class="col-md-6 form-group">
          <label>Name</label>
          <input type="text" name="name" class="form-control" value="{{ $supplier->name ?? '' }}" required>
      </div>

      <div class="col-md-6 form-group">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="{{ $supplier->email ?? '' }}" required>
      </div>

      <div class="col-md-6 form-group">
          <label>Phone</label>
          <input type="text" name="phone" class="form-control" value="{{ $supplier->phone ?? '' }}" required>
      </div>

      <div class="col-md-6 form-group">
          <label>State</label>
          <input type="text" name="state" class="form-control" value="{{ $supplier->state ?? '' }}" required>
      </div>

      <div class="col-md-6 form-group">
          <label>City</label>
          <input type="text" name="city" class="form-control" value="{{ $supplier->city ?? '' }}" required>
      </div>

      <div class="col-md-6 form-group">
          <label>Pin Code</label>
          <input type="text" name="pin_code" class="form-control" value="{{ $supplier->pin_code ?? '' }}" required>
      </div>

      <div class="col-md-12 form-group">
          <label>Full Address</label>
          <textarea name="full_address" class="form-control" required>{{ $supplier->full_address ?? '' }}</textarea>
      </div>

      <div class="col-md-6 form-group">
          <label>GST Number</label>
          <input type="text" name="gst_number" class="form-control" value="{{ $supplier->gst_number ?? '' }}" required>
      </div>

      <div class="col-md-6 form-group">
          <label>Bank Name</label>
          <input type="text" name="bank_name" class="form-control" value="{{ $supplier->bank_name ?? '' }}" required>
      </div>

      <div class="col-md-6 form-group">
          <label>Account Number</label>
          <input type="text" name="account_number" class="form-control" value="{{ $supplier->account_number ?? '' }}" required>
      </div>

      <div class="col-md-6 form-group">
          <label>IFSC Code</label>
          <input type="text" name="ifsc_code" class="form-control" value="{{ $supplier->ifsc_code ?? '' }}" required>
      </div>

      <div class="col-md-6 form-group">
          <label>GST Document</label>
          <input type="file" name="gst_document" class="form-control">
          @if(isset($supplier) && $supplier->gst_document)
              <a href="{{ asset('storage/'.$supplier->gst_document) }}" target="_blank">View Current Document</a>
          @endif
      </div>


  </div>

