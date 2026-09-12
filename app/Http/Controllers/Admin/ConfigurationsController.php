<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyConfigurationRequest;
use App\Http\Requests\StoreConfigurationRequest;
use App\Http\Requests\UpdateConfigurationRequest;
use App\Models\Configuration;
use App\Models\Tax;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('configuration_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configurations = Configuration::with(['defaulat_tax', 'created_by'])->get();

        return view('admin.configurations.index', compact('configurations'));
    }

    public function create()
    {
        abort_if(Gate::denies('configuration_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $defaulat_taxes = Tax::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.configurations.create', compact('defaulat_taxes'));
    }

    public function store(StoreConfigurationRequest $request)
    {
        $configuration = Configuration::create($request->all());

        return redirect()->route('admin.configurations.index');
    }

    public function edit(Configuration $configuration)
    {
        abort_if(Gate::denies('configuration_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $defaulat_taxes = Tax::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $configuration->load('defaulat_tax', 'created_by');

        return view('admin.configurations.edit', compact('configuration', 'defaulat_taxes'));
    }

    public function update(UpdateConfigurationRequest $request, Configuration $configuration)
    {
        $configuration->update($request->all());

        return redirect()->route('admin.configurations.index');
    }

    public function show(Configuration $configuration)
    {
        abort_if(Gate::denies('configuration_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuration->load('defaulat_tax', 'created_by');

        return view('admin.configurations.show', compact('configuration'));
    }

    public function destroy(Configuration $configuration)
    {
        abort_if(Gate::denies('configuration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuration->delete();

        return back();
    }

    public function massDestroy(MassDestroyConfigurationRequest $request)
    {
        $configurations = Configuration::find(request('ids'));

        foreach ($configurations as $configuration) {
            $configuration->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
