@if(!$isSubCategory)
@php($selectedCompanies = array_map('strval', old('subcategories', isset($productCategory) ? $productCategory->subcategories->pluck('id')->all() : [])))
<div class="form-group"><input type="hidden" name="has_subcategories" value="0"><label><input id="has-subcategories" type="checkbox" name="has_subcategories" value="1" {{ old('has_subcategories', $productCategory->has_subcategories ?? false) ? 'checked' : '' }}> Add sub-categories / companies</label></div>
<div id="company-cards" class="row">
@forelse(\App\Models\ProductCategory::where('is_subcategory', true)->with('vehicles')->orderBy('name')->get() as $company)
<div class="col-md-4"><div class="panel panel-default"><div class="panel-body"><label><input type="checkbox" name="subcategories[]" value="{{ $company->id }}" {{ in_array((string)$company->id, $selectedCompanies, true) ? 'checked' : '' }}> {{ $company->name }}</label><p>{{ $company->vehicles->pluck('name')->implode(', ') ?: 'No vehicles yet — add them in Vehicle Name.' }}</p></div></div></div>
@empty <p>Create companies in Sub Categories first.</p> @endforelse
</div>
@foreach($errors->all() as $error)<p class="text-danger">{{ $error }}</p>@endforeach
<script>document.addEventListener('DOMContentLoaded', function(){const toggle=document.getElementById('has-subcategories'), cards=document.getElementById('company-cards');function update(){cards.style.display=toggle.checked?'':'none';cards.querySelectorAll('input').forEach(el=>el.disabled=!toggle.checked);}toggle.addEventListener('change',update);update();});</script>
@else
<p class="help-block">Create a vehicle company here, for example Toyota or Suzuki. Add its models in Vehicle Name.</p>
@endif
