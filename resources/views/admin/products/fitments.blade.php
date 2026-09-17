@php
 $fitmentCategories = \App\Models\ProductCategory::where('is_subcategory', false)->with('subcategories.vehicles')->get();
 $savedFitments = old('fitments', isset($product) ? $product->fitments->map(fn($f) => $f->category_id.':'.$f->vehicle_id)->all() : []);
 $initialCategories = array_map('strval', old('categories', isset($product) ? $product->categories->pluck('id')->all() : []));
 $restoreFitments = session()->hasOldInput() || isset($product);
@endphp
<div class="form-group" style="clear:both"><label>Available companies and vehicles</label><p>Select the vehicles for which this product is available.</p>
@foreach($fitmentCategories as $fitmentCategory)
@if($fitmentCategory->has_subcategories)
<div class="fitment-category panel panel-default" data-category="{{ $fitmentCategory->id }}" style="display:none"><div class="panel-heading">{{ $fitmentCategory->name }}</div><div class="panel-body row">
@foreach($fitmentCategory->subcategories as $company)<div class="col-md-4"><div class="panel panel-default"><div class="panel-heading"><label><input type="checkbox" class="fitment-company"> {{ $company->name }}</label></div><div class="panel-body">
@forelse($company->vehicles as $vehicle)<label style="display:block"><input type="checkbox" name="fitments[]" value="{{ $fitmentCategory->id.':'.$vehicle->id }}" {{ in_array($fitmentCategory->id.':'.$vehicle->id, $savedFitments) ? 'checked' : '' }}> {{ $vehicle->name }}</label>@empty <p>Add vehicles to this company first.</p>@endforelse
</div></div></div>@endforeach
</div></div>@endif
@endforeach
@error('fitments')<p class="text-danger">{{ $message }}</p>@enderror
</div>
<script>document.addEventListener('DOMContentLoaded',function(){
 const select=document.getElementById('categories');const initialized=new Set(@json($restoreFitments ? $initialCategories : []));
 function update(){const ids=Array.from(select.selectedOptions).map(o=>o.value);document.querySelectorAll('.fitment-category').forEach(card=>{const active=ids.includes(card.dataset.category);card.style.display=active?'':'none';card.querySelectorAll('input').forEach(input=>{input.disabled=!active;if(active&&!initialized.has(card.dataset.category))input.checked=true;});if(active)initialized.add(card.dataset.category);});syncCompanies();}
 function syncCompanies(){document.querySelectorAll('.fitment-company').forEach(c=>{const boxes=Array.from(c.closest('.panel').querySelectorAll('input[name="fitments[]"]'));c.checked=boxes.length>0&&boxes.every(b=>b.checked);c.indeterminate=boxes.some(b=>b.checked)&&!c.checked;});}
 document.querySelectorAll('.fitment-company').forEach(c=>c.addEventListener('change',()=>{c.closest('.panel').querySelectorAll('input[name="fitments[]"]').forEach(b=>b.checked=c.checked);}));
 document.querySelectorAll('input[name="fitments[]"]').forEach(b=>b.addEventListener('change',syncCompanies));
 if(window.jQuery)jQuery(select).on('change',update);else select.addEventListener('change',update);update();
});</script>
