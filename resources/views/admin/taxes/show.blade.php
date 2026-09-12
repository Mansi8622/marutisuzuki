@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.tax.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.taxes.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.tax.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $tax->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.tax.fields.name') }}
                                    </th>
                                    <td>
                                        {{ $tax->name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.tax.fields.tax_rate') }}
                                    </th>
                                    <td>
                                        {{ $tax->tax_rate }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.tax.fields.status') }}
                                    </th>
                                    <td>
                                        {{ App\Models\Tax::STATUS_SELECT[$tax->status] ?? '' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.taxes.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.relatedData') }}
                </div>
                <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
                    <li role="presentation">
                        <a href="#defaulat_tax_configurations" aria-controls="defaulat_tax_configurations" role="tab" data-toggle="tab">
                            {{ trans('cruds.configuration.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="defaulat_tax_configurations">
                        @includeIf('admin.taxes.relationships.defaulatTaxConfigurations', ['configurations' => $tax->defaulatTaxConfigurations])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection