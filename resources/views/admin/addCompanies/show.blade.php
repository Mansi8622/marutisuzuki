@extends('layouts.admin')
@section('content')
<div class="content">

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    {{ trans('global.show') }} {{ trans('cruds.addCompany.title') }}
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.add-companies.index') }}">
                                {{ trans('global.back_to_list') }}
                            </a>
                        </div>
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addCompany.fields.id') }}
                                    </th>
                                    <td>
                                        {{ $addCompany->id }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addCompany.fields.company_name') }}
                                    </th>
                                    <td>
                                        {{ $addCompany->company_name }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        {{ trans('cruds.addCompany.fields.company_logo') }}
                                    </th>
                                    <td>
                                        @foreach($addCompany->company_logo as $key => $media)
                                            <a href="{{ $media->getUrl() }}" target="_blank" style="display: inline-block">
                                                <img src="{{ $media->getUrl('thumb') }}">
                                            </a>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            <a class="btn btn-default" href="{{ route('admin.add-companies.index') }}">
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
                        <a href="#company_refunds" aria-controls="company_refunds" role="tab" data-toggle="tab">
                            {{ trans('cruds.refund.title') }}
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#select_company_products" aria-controls="select_company_products" role="tab" data-toggle="tab">
                            {{ trans('cruds.product.title') }}
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane" role="tabpanel" id="company_refunds">
                        @includeIf('admin.addCompanies.relationships.companyRefunds', ['refunds' => $addCompany->companyRefunds])
                    </div>
                    <div class="tab-pane" role="tabpanel" id="select_company_products">
                        @includeIf('admin.addCompanies.relationships.selectCompanyProducts', ['products' => $addCompany->selectCompanyProducts])
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection