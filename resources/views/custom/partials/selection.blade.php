<small class="d-block text-muted" style="display:block">
@if(!empty($selection['category_name']))Category: {{ $selection['category_name'] }}@endif
@if(!empty($selection['subcategory_name'])) / Company: {{ $selection['subcategory_name'] }}@endif
@if(!empty($selection['vehicle_name'])) / Vehicle: {{ $selection['vehicle_name'] }}@endif
@if(isset($selection['quantity'])) / Qty: {{ $selection['quantity'] }}@endif
</small>
