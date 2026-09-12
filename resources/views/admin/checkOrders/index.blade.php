@extends('layouts.admin')
@section('content')
<div class="content">
    @can('check_order_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.check-orders.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.checkOrder.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('cruds.checkOrder.title_singular') }} {{ trans('global.list') }}
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover datatable datatable-CheckOrder">
                            <thead>
                                <tr>
                                    <th width="10"></th>
                                    <th>{{ trans('cruds.checkOrder.fields.id') }}</th>
                                    <th>{{ trans('cruds.checkOrder.fields.select_user') }}</th>
                                    <th>{{ trans('cruds.user.fields.phone') }}</th>
                                    <th>{{ trans('cruds.checkOrder.fields.select_product') }}</th>
                                    <th>{{ trans('cruds.checkOrder.fields.order_number') }}</th>
                                    <th>Products with Stock Check</th>
                                    <th>{{ trans('cruds.checkOrder.fields.total_amount') }}</th>
                                    <th>{{ trans('cruds.checkOrder.fields.payment_method') }}</th>
                                    <th>{{ trans('cruds.checkOrder.fields.payment_status') }}</th>
                                    <th>{{ trans('cruds.checkOrder.fields.billing_address') }}</th>
                                    <th>{{ trans('cruds.checkOrder.fields.placed_at') }}</th>
                                    <th>{{ trans('cruds.checkOrder.fields.order_status') }}</th>
                                    <th>{{ trans('cruds.checkOrder.fields.attachment') }}</th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($checkOrders as $key => $checkOrder)
                                    <tr data-entry-id="{{ $checkOrder->id }}">
                                        <td></td>
                                        <td>{{ $checkOrder->id ?? '' }}</td>
<td>
    @if($checkOrder->select_user)
        {{ $checkOrder->select_user->name }} (Retailer)
    @elseif($checkOrder->select_customer)
        {{ $checkOrder->select_customer->name }} (Customer)
    @else
        N/A
    @endif
</td>
<td>
    @if($checkOrder->select_user)
        {{ $checkOrder->select_user->phone }}
    @elseif($checkOrder->select_customer)
        {{ $checkOrder->select_customer->phone }}
    @else
        N/A
    @endif
</td>
                                        <td>
                                            @foreach($checkOrder->select_products as $item)
                                                <span class="label label-info label-many">{{ $item->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $checkOrder->order_number ?? '' }} -  {{ $checkOrder->order_status ?? '' }}</td>

                                        {{-- Product with Stock Check --}}
                                       <td>
    @php
        $decodedArray = json_decode($checkOrder->products, true);
        $products = [];

        // Safely handle possible double or single layer JSON structure
        if (is_array($decodedArray)) {
            if (isset($decodedArray[0]) && is_string($decodedArray[0])) {
                // Handle nested JSON (e.g., array with JSON string as first element)
                $products = json_decode($decodedArray[0], true) ?? [];
            } else {
                $products = $decodedArray;
            }
        }
    @endphp

    @forelse ($products as $product)
        @php
            $productId = $product['id'] ?? null;
            $orderQuantity = $product['quantity'] ?? 0;
            $stockAvailable = $productStocks[$productId]->quantity_available ?? 0;
        @endphp

        <div style="margin-bottom: 10px;">
            <strong>{{ $product['name'] ?? 'N/A' }}</strong> - Qnt. {{ $orderQuantity }}
            <br>

            @if ($productId)
                @if ($stockAvailable >= $orderQuantity)
                    <button class="btn btn-xs btn-success">Check Stock</button>
                    <span class="text-success">Available ({{ $stockAvailable }} in stock)</span>
                @else
                    <button class="btn btn-xs btn-danger">Check Stock</button>
                    <span class="text-danger">Not Available (Only {{ $stockAvailable }} in stock)</span>
                @endif
            @else
                <span class="text-warning">Invalid product</span>
            @endif
        </div>
    @empty
        <span class="text-muted">No products found</span>
    @endforelse
</td>

                                        

                                        <td>{{ $checkOrder->total_amount ?? '' }}</td>
                                        <td>{{ $checkOrder->payment_method ?? '' }}</td>
                                        <td>{{ $checkOrder->payment_status ?? '' }}</td>
                                        <td>{{ $checkOrder->billing_address ?? '' }}</td>
                                        <td>{{ $checkOrder->placed_at ?? '' }}</td>
                                        <td>{{ App\Models\CheckOrder::ORDER_STATUS_SELECT[$checkOrder->order_status] ?? '' }}</td>
                                        <td>
                                            @foreach($checkOrder->attachment as $media)
                                                @if(is_object($media)) <!-- Check if $media is an object -->
                                                    <a href="{{ $media->getUrl() }}" target="_blank">{{ trans('global.view_file') }}</a>
                                                @else
                                                    <!-- If it's just a file name or string, handle accordingly -->
                                                    <span>{{ $media }}</span>
                                                @endif
                                            @endforeach
                                        </td>


                                        
                                        <td>
                                            @can('check_order_show')
                                                <a class="btn btn-xs btn-primary" href="{{ route('admin.check-orders.show', $checkOrder->id) }}">
                                                    {{ trans('global.view') }}
                                                </a>
                                            @endcan

                                            @can('check_order_edit')
                                                <a class="btn btn-xs btn-info" href="{{ route('admin.check-orders.edit', $checkOrder->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                            @endcan

                                            @can('check_order_delete')
                                                <form action="{{ route('admin.check-orders.destroy', $checkOrder->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endcan
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
$(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    @can('check_order_delete')
    let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
    let deleteButton = {
        text: deleteButtonTrans,
        url: "{{ route('admin.check-orders.massDestroy') }}",
        className: 'btn-danger',
        action: function (e, dt, node, config) {
            var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id')
            });

            if (ids.length === 0) {
                alert('{{ trans('global.datatables.zero_selected') }}')
                return
            }

            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: { 'x-csrf-token': _token },
                    method: 'POST',
                    url: config.url,
                    data: { ids: ids, _method: 'DELETE' }
                }).done(function () { location.reload() })
            }
        }
    }
    dtButtons.push(deleteButton)
    @endcan

    $.extend(true, $.fn.dataTable.defaults, {
        orderCellsTop: true,
        order: [[ 1, 'desc' ]],
        pageLength: 100,
    });
    let table = $('.datatable-CheckOrder:not(.ajaxTable)').DataTable({ buttons: dtButtons })

    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})
</script>
@endsection
