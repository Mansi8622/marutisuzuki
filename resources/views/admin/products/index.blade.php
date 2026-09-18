@extends('layouts.admin')
@section('content')

{{-- ===== Scoped design tokens — AdminLTE se clash na ho isliye isolated wrapper ===== --}}
<style>
    .product-index-wrap {
        --pi-grad-start: #7C3AED;
        --pi-grad-end: #6366F1;
        --pi-ink: #1E1B2E;
        --pi-muted: #6B7280;
        --pi-border: rgba(124, 58, 237, 0.12);
        --pi-surface: #FFFFFF;
        --pi-surface-soft: rgba(124, 58, 237, 0.04);
        --pi-radius: 16px;
        --pi-radius-sm: 10px;
        --pi-shadow: 0 8px 28px rgba(99, 102, 241, 0.10);
        --pi-shadow-hover: 0 14px 36px rgba(99, 102, 241, 0.16);
        font-family: 'Inter', 'Outfit', -apple-system, sans-serif;
        color: var(--pi-ink);
    }

    /* ---------- Header banner ---------- */
    .product-index-wrap .pi-header {
        position: relative;
        overflow: hidden;
        border-radius: var(--pi-radius);
        padding: 28px 32px;
        margin-bottom: 22px;
        background: linear-gradient(135deg, var(--pi-grad-start) 0%, var(--pi-grad-end) 100%);
        box-shadow: var(--pi-shadow);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
    }
    .product-index-wrap .pi-header::before {
        content: "";
        position: absolute;
        top: -60px; right: -40px;
        width: 220px; height: 220px;
        background: rgba(255,255,255,0.08);
        border-radius: 50%;
    }
    .product-index-wrap .pi-header::after {
        content: "";
        position: absolute;
        bottom: -70px; left: 10%;
        width: 160px; height: 160px;
        background: rgba(255,255,255,0.06);
        border-radius: 50%;
    }
    .product-index-wrap .pi-header-text { position: relative; z-index: 1; }
    .product-index-wrap .pi-header-text h1 {
        font-family: 'Outfit', sans-serif;
        font-size: 24px;
        font-weight: 700;
        color: #fff;
        margin: 0 0 4px 0;
        letter-spacing: -0.02em;
    }
    .product-index-wrap .pi-header-text p {
        color: rgba(255,255,255,0.85);
        font-size: 13.5px;
        margin: 0;
    }
    .product-index-wrap .pi-add-btn {
        position: relative;
        z-index: 1;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.16);
        backdrop-filter: blur(6px);
        border: 1px solid rgba(255,255,255,0.35);
        color: #fff !important;
        font-weight: 600;
        font-size: 13.5px;
        padding: 10px 20px;
        border-radius: 999px;
        text-decoration: none !important;
        transition: all .2s ease;
    }
    .product-index-wrap .pi-add-btn:hover {
        background: #fff;
        color: var(--pi-grad-start) !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(0,0,0,0.15);
    }

    /* ---------- Glassmorphism card wrapper for table ---------- */
    .product-index-wrap .pi-card {
        background: var(--pi-surface);
        border: 1px solid var(--pi-border);
        border-radius: var(--pi-radius);
        box-shadow: var(--pi-shadow);
        overflow: hidden;
    }
    .product-index-wrap .pi-card-head {
        padding: 18px 24px;
        border-bottom: 1px solid var(--pi-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(90deg, var(--pi-surface-soft), transparent);
    }
    .product-index-wrap .pi-card-head h2 {
        font-family: 'Outfit', sans-serif;
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        color: var(--pi-ink);
    }
    .product-index-wrap .pi-card-body { padding: 18px 24px 24px; }

    /* ---------- Table restyle ---------- */
    .product-index-wrap table.datatable-Product {
        border-collapse: separate !important;
        border-spacing: 0;
        width: 100% !important;
    }
    .product-index-wrap table.datatable-Product thead th {
        background: var(--pi-surface-soft);
        color: var(--pi-grad-start);
        font-family: 'Outfit', sans-serif;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        border: none !important;
        border-bottom: 2px solid var(--pi-border) !important;
        padding: 12px 10px;
        white-space: nowrap;
    }
    .product-index-wrap table.datatable-Product tbody td {
        border: none !important;
        border-bottom: 1px solid var(--pi-border) !important;
        padding: 12px 10px;
        font-size: 13px;
        vertical-align: middle;
        color: var(--pi-ink);
    }
    .product-index-wrap table.datatable-Product tbody tr {
        transition: background .15s ease;
    }
    .product-index-wrap table.datatable-Product tbody tr:hover {
        background: var(--pi-surface-soft);
    }

    /* Pill badges instead of flat AdminLTE labels */
    .product-index-wrap .pi-badge {
        display: inline-block;
        padding: 3px 10px;
        margin: 2px 2px 2px 0;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
        background: rgba(124, 58, 237, 0.10);
        color: var(--pi-grad-start);
        border: 1px solid rgba(124, 58, 237, 0.18);
    }
    .product-index-wrap .pi-badge.pi-badge-alt {
        background: rgba(99, 102, 241, 0.10);
        color: var(--pi-grad-end);
        border-color: rgba(99, 102, 241, 0.18);
    }

    .product-index-wrap .pi-sku {
        font-family: 'Space Grotesk', monospace;
        font-size: 11.5px;
        background: var(--pi-surface-soft);
        padding: 3px 8px;
        border-radius: 6px;
        color: var(--pi-muted);
    }

    .product-index-wrap .pi-price { font-weight: 700; color: var(--pi-ink); }
    .product-index-wrap .pi-price small { color: var(--pi-muted); font-weight: 500; }

    .product-index-wrap .pi-thumb {
        width: 40px; height: 40px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid var(--pi-border);
        display: inline-block;
        margin-right: 4px;
        transition: transform .2s ease;
    }
    .product-index-wrap .pi-thumb:hover { transform: scale(1.6); z-index: 5; position: relative; box-shadow: var(--pi-shadow-hover); }

    /* Action buttons */
    .product-index-wrap .pi-actions { display: flex; gap: 6px; flex-wrap: nowrap; }
    .product-index-wrap .pi-btn {
        border: none;
        border-radius: 8px;
        padding: 6px 12px;
        font-size: 11.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all .15s ease;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .product-index-wrap .pi-btn-view { background: rgba(99,102,241,0.10); color: var(--pi-grad-end); }
    .product-index-wrap .pi-btn-view:hover { background: var(--pi-grad-end); color: #fff; }
    .product-index-wrap .pi-btn-edit { background: rgba(245,158,11,0.12); color: #B45309; }
    .product-index-wrap .pi-btn-edit:hover { background: #F59E0B; color: #fff; }
    .product-index-wrap .pi-btn-delete { background: rgba(239,68,68,0.10); color: #DC2626; }
    .product-index-wrap .pi-btn-delete:hover { background: #EF4444; color: #fff; }

    /* DataTables controls — inputs never dark, hamesha light rahega */
    .product-index-wrap .dataTables_wrapper .dataTables_filter input,
    .product-index-wrap .dataTables_wrapper .dataTables_length select {
        border: 1px solid var(--pi-border) !important;
        border-radius: var(--pi-radius-sm) !important;
        background: #fff !important;
        color: var(--pi-ink) !important;
        padding: 6px 12px !important;
        font-size: 13px;
    }
    .product-index-wrap .dataTables_wrapper .dataTables_filter input:focus,
    .product-index-wrap .dataTables_wrapper .dataTables_length select:focus {
        outline: none;
        border-color: var(--pi-grad-start) !important;
        box-shadow: 0 0 0 3px rgba(124,58,237,0.12);
    }
    .product-index-wrap .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, var(--pi-grad-start), var(--pi-grad-end)) !important;
        border: none !important;
        color: #fff !important;
        border-radius: 8px !important;
    }
    .product-index-wrap .dataTables_wrapper .dataTables_paginate .paginate_button {
        border-radius: 8px !important;
        border: 1px solid transparent !important;
        margin: 0 2px;
    }
    .product-index-wrap .dt-buttons .btn {
        border-radius: var(--pi-radius-sm) !important;
        font-weight: 600;
        font-size: 12.5px;
    }
</style>

<div class="content product-index-wrap">

    {{-- ===== Header banner: title + add button ===== --}}
    <div class="pi-header">
        <div class="pi-header-text">
            <h1>{{ trans('cruds.product.title_singular') }} {{ trans('global.list') }}</h1>
            <p>Saara product catalog ek jagah — search, filter aur manage karein.</p>
        </div>
        @can('product_create')
            <a class="pi-add-btn" href="{{ route('admin.products.create') }}">
                <i class="fa fa-plus"></i>
                {{ trans('global.add') }} {{ trans('cruds.product.title_singular') }}
            </a>
        @endcan
    </div>

    {{-- ===== Table card ===== --}}
    <div class="pi-card">
        <div class="pi-card-head">
            <h2><i class="fa fa-cubes" style="color:var(--pi-grad-start); margin-right:6px;"></i>{{ trans('cruds.product.title_singular') }} {{ trans('global.list') }}</h2>
        </div>
        <div class="pi-card-body">
            <div class="table-responsive">
                <table class="table datatable datatable-Product" style="width:100%">
                    <thead>
                        <tr>
                            <th width="10"></th>
                            <th>{{ trans('cruds.product.fields.id') }}</th>
                            <th>{{ trans('cruds.product.fields.name') }}</th>
                            <th>{{ trans('cruds.product.fields.category') }}</th>
                            <th>{{ trans('cruds.product.fields.tag') }}</th>
                            <th>{{ trans('cruds.product.fields.select_company') }}</th>
                            <th>{{ trans('cruds.product.fields.item_code') }}</th>
                            <th>{{ trans('cruds.product.fields.hsn_code') }}</th>
                            <th>{{ trans('cruds.product.fields.godown') }}</th>
                            <th>{{ trans('cruds.product.fields.description') }}</th>
                            <th>{{ trans('cruds.product.fields.price') }}</th>
                            <th>{{ trans('cruds.product.fields.discount') }}</th>
                            <th>{{ trans('cruds.product.fields.price_1') }}</th>
                            <th>{{ trans('cruds.product.fields.rate_2') }}</th>
                            <th>{{ trans('cruds.product.fields.rate_3') }}</th>
                            <th>{{ trans('cruds.product.fields.photo') }}</th>
                            <th>{{ trans('cruds.product.fields.product_photo_2') }}</th>
                            <th>{{ trans('cruds.product.fields.product_photo_3') }}</th>
                            <th>{{ trans('cruds.product.fields.sku') }}</th>
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $key => $product)
                            <tr data-entry-id="{{ $product->id }}">
                                <td></td>
                                <td>{{ $product->id ?? '' }}</td>
                                <td><strong>{{ $product->name ?? '' }}</strong></td>
                                <td>
                                    @foreach($product->categories as $key => $item)
                                        <span class="pi-badge">{{ $item->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($product->tags as $key => $item)
                                        <span class="pi-badge pi-badge-alt">{{ $item->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($product->select_companies as $key => $item)
                                        <span class="pi-badge">{{ $item->company_name }}</span>
                                    @endforeach
                                </td>
                                <td>{{ $product->item_code ?? '' }}</td>
                                <td>{{ $product->hsn_code ?? '' }}</td>
                                <td>
                                    {{ $product->godown->name ?? '' }}
                                    @if(($product->godown->capacity ?? null))
                                        <br><small style="color:var(--pi-muted)">Capacity: {{ $product->godown->capacity }}</small>
                                    @endif
                                </td>
                                <td>{{ $product->description ?? '' }}</td>
                                <td><span class="pi-price">₹{{ $product->price ?? '' }}</span></td>
                                <td>{{ $product->discount ?? '' }}</td>
                                <td>{{ $product->price_1 ?? '' }}</td>
                                <td>{{ $product->rate_2 ?? '' }}</td>
                                <td>{{ $product->rate_3 ?? '' }}</td>
                                <td>
                                    @foreach($product->photo as $key => $media)
                                        <a href="{{ $media->getUrl() }}" target="_blank">
                                            <img class="pi-thumb" src="{{ $media->getUrl('thumb') }}">
                                        </a>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach($product->product_photo_2 as $key => $media)
                                        <a href="{{ $media->getUrl() }}" target="_blank">
                                            <img class="pi-thumb" src="{{ $media->getUrl('thumb') }}">
                                        </a>
                                    @endforeach
                                </td>
                                <td>
                                    @if($product->product_photo_3)
                                        <a href="{{ $product->product_photo_3->getUrl() }}" target="_blank">
                                            <img class="pi-thumb" src="{{ $product->product_photo_3->getUrl('thumb') }}">
                                        </a>
                                    @endif
                                </td>
                                <td><span class="pi-sku">{{ $product->sku ?? '' }}</span></td>
                                <td>
                                    <div class="pi-actions">
                                        @can('product_show')
                                            <a class="pi-btn pi-btn-view" href="{{ route('admin.products.show', $product->id) }}">
                                                <i class="fa fa-eye"></i> {{ trans('global.view') }}
                                            </a>
                                        @endcan

                                        @can('product_edit')
                                            <a class="pi-btn pi-btn-edit" href="{{ route('admin.products.edit', $product->id) }}">
                                                <i class="fa fa-pencil"></i> {{ trans('global.edit') }}
                                            </a>
                                        @endcan

                                        @can('product_delete')
                                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <button type="submit" class="pi-btn pi-btn-delete">
                                                    <i class="fa fa-trash"></i> {{ trans('global.delete') }}
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

        @can('product_delete')
        // Bulk delete button — selected rows ko ek saath delete karne ke liye
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.products.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                });

                if (ids.length === 0) {
                    // Agar koi row select nahi hui to Hinglish alert
                    alert('{{ trans('global.datatables.zero_selected') }}')
                    return
                }

                if (confirm('{{ trans('global.areYouSure') }}')) {
                    $.ajax({
                        headers: { 'x-csrf-token': _token },
                        method: 'POST',
                        url: config.url,
                        data: { ids: ids, _method: 'DELETE' }
                    })
                    .done(function () {
                        // Delete successful — page reload taaki table fresh dikhe
                        location.reload()
                    })
                }
            }
        }
        dtButtons.push(deleteButton)
        @endcan

        $.extend(true, $.fn.dataTable.defaults, {
            orderCellsTop: true,
            order: [[1, 'desc']],
            pageLength: 10,        // Default sirf 10 rows dikhaye
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        });

        let table = $('.datatable-Product:not(.ajaxTable)').DataTable({ buttons: dtButtons })

        $('a[data-toggle="tab"]').on('shown.bs.tab click', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    })
</script>
@endsection