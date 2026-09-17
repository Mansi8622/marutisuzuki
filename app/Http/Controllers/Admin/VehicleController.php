<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Gate;

class VehicleController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('sub_category_access'), 403);
        return view('admin.vehicles.index', ['vehicles' => Vehicle::with('subcategory')->orderBy('name')->get()]);
    }
    public function create()
    {
        abort_if(Gate::denies('sub_category_create'), 403);
        return $this->form(new Vehicle);
    }
    public function edit(Vehicle $vehicle)
    {
        abort_if(Gate::denies('sub_category_edit'), 403);
        return $this->form($vehicle);
    }
    private function form(Vehicle $vehicle)
    {
        return view('admin.vehicles.form', ['vehicle' => $vehicle, 'companies' => ProductCategory::where('is_subcategory', true)->orderBy('name')->get()]);
    }
    private function data(Request $request): array
    {
        return $request->validate(['name' => ['required', 'string', 'max:255'], 'subcategory_id' => ['required', Rule::exists('product_categories', 'id')->where('is_subcategory', true)->whereNull('deleted_at')]]);
    }
    public function store(Request $request)
    {
        abort_if(Gate::denies('sub_category_create'), 403);
        Vehicle::create($this->data($request));
        return redirect()->route('admin.vehicles.index');
    }
    public function update(Request $request, Vehicle $vehicle)
    {
        abort_if(Gate::denies('sub_category_edit'), 403);
        $data = $this->data($request);
        if ($vehicle->subcategory_id != $data['subcategory_id']) {
            abort_if(\App\Models\ProductFitment::where('vehicle_id', $vehicle->id)->exists(), 422, 'This vehicle is used by products. Create a new vehicle to change its company.');
        }
        $vehicle->update($data);
        return redirect()->route('admin.vehicles.index');
    }
    public function destroy(Vehicle $vehicle)
    {
        abort_if(Gate::denies('sub_category_delete'), 403);
        $vehicle->delete();
        return back();
    }
}
