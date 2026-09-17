@php
 $availableSelections = $selectionProduct->availableFitments();
 $selectedFitment = $availableSelections->first(fn($f) => $f->category_id == request('category') && $f->vehicle_id == request('vehicle'));
@endphp
<div class="mb-3"><label for="product-fitment">Category / Company / Vehicle</label>
<select class="form-control" id="product-fitment" name="selection" required>
<option value="">Select your vehicle</option>
@foreach($availableSelections as $fitment)<option value="f:{{ $fitment->id }}" {{ $selectedFitment && $selectedFitment->id === $fitment->id ? 'selected' : '' }}>{{ $fitment->category->name }} / {{ $fitment->vehicle->subcategory->name }} / {{ $fitment->vehicle->name }}</option>@endforeach
@foreach($selectionProduct->categories->where('is_subcategory', false)->where('has_subcategories', false) as $plainCategory)<option value="c:{{ $plainCategory->id }}" {{ request('category') == $plainCategory->id ? 'selected' : '' }}>{{ $plainCategory->name }}</option>@endforeach
</select>
@error('fitment_id')<p class="text-danger">{{ $message }}</p>@enderror
</div>
<script>document.addEventListener('DOMContentLoaded',function(){const select=document.getElementById('product-fitment'),form=document.getElementById('pdAddToCartForm');if(!form)return;for(const name of ['fitment_id','category_id']){const input=document.createElement('input');input.type='hidden';input.name=name;form.appendChild(input);}function update(){const [type,id]=select.value.split(':');form.elements.fitment_id.value=type==='f'?id:'';form.elements.category_id.value=type==='c'?id:'';}select.addEventListener('change',update);form.addEventListener('submit',function(event){if(!select.value){event.preventDefault();select.reportValidity();select.focus();}});if(select.options.length===2&&!select.value)select.selectedIndex=1;update();});</script>
