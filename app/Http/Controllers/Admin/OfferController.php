<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OfferController extends Controller
{
    public function index() { abort_if(Gate::denies('offer_access'), Response::HTTP_FORBIDDEN, '403 Forbidden'); return view('admin.offers.index', ['offers' => Offer::latest()->get()]); }
    public function create() { abort_if(Gate::denies('offer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); return view('admin.offers.form', ['offer' => new Offer()]); }
    public function store(Request $request) { abort_if(Gate::denies('offer_create'), Response::HTTP_FORBIDDEN, '403 Forbidden'); Offer::create($this->data($request)); return redirect()->route('admin.offers.index')->with('success', 'Offer created.'); }
    public function edit(Offer $offer) { abort_if(Gate::denies('offer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden'); return view('admin.offers.form', compact('offer')); }
    public function update(Request $request, Offer $offer) { abort_if(Gate::denies('offer_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden'); $offer->update($this->data($request)); return redirect()->route('admin.offers.index')->with('success', 'Offer updated.'); }
    public function destroy(Offer $offer) { abort_if(Gate::denies('offer_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden'); $offer->delete(); return back(); }
    private function data(Request $request): array { return $request->validate(['title'=>'required|string|max:150','discount_percent'=>'required|numeric|min:0|max:100','minimum_order_amount'=>'nullable|numeric|min:0','starts_at'=>'nullable|date','ends_at'=>'nullable|date|after_or_equal:starts_at','is_active'=>'nullable|boolean']) + ['is_active' => $request->boolean('is_active')]; }
}
