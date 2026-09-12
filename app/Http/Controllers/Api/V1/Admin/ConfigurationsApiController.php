<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfigurationRequest;
use App\Http\Requests\UpdateConfigurationRequest;
use App\Http\Resources\Admin\ConfigurationResource;
use App\Models\Configuration;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigurationsApiController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('configuration_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConfigurationResource(Configuration::with(['defaulat_tax', 'created_by'])->get());
    }

    public function store(StoreConfigurationRequest $request)
    {
        $configuration = Configuration::create($request->all());

        return (new ConfigurationResource($configuration))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Configuration $configuration)
    {
        abort_if(Gate::denies('configuration_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new ConfigurationResource($configuration->load(['defaulat_tax', 'created_by']));
    }

    public function update(UpdateConfigurationRequest $request, Configuration $configuration)
    {
        $configuration->update($request->all());

        return (new ConfigurationResource($configuration))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Configuration $configuration)
    {
        abort_if(Gate::denies('configuration_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $configuration->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
