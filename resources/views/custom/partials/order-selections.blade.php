@php
 $selectionLines = json_decode($selectionOrder->products ?? '[]', true) ?? [];
 if(isset($selectionLines[0]) && is_string($selectionLines[0])) $selectionLines = json_decode($selectionLines[0], true) ?? [];
@endphp
@foreach($selectionLines as $selectionLine)
@if(is_array($selectionLine) && !empty($selectionLine['selections']))
<div><strong>{{ $selectionLine['name'] ?? '' }}</strong>@foreach($selectionLine['selections'] as $selection)@include('custom.partials.selection')@endforeach</div>
@endif
@endforeach
