<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class CatalogDownloadController extends Controller
{
    public function selector(string $type)
    {
        abort_unless(in_array($type, ['price-list', 'catalog'], true), 404);

        return view('downloads.selector', [
            'type'       => $type,
            'title'      => $type === 'price-list' ? 'Download Price List' : 'Download Catalog',
            'categories' => $this->categories(),
        ]);
    }

    public function download(Request $request, string $type)
    {
        abort_unless(in_array($type, ['price-list', 'catalog'], true), 404);

        $data = $request->validate([
            'download_scope'  => ['nullable', 'in:all,specific'],
            'categories'      => ['array'],
            'categories.*'    => ['integer'],
            'subcategories'   => ['array'],
            'subcategories.*' => ['integer'],
            'vehicles'        => ['array'],
            'vehicles.*'      => ['integer'],
        ]);

        $scope    = $data['download_scope'] ?? 'all';
        $products = $this->products($scope === 'specific' ? $data : [])->get();
        $role     = Auth::guard('customer')->check() ? 'customer' : 'retailer';
        $meta     = $this->companyMeta();

        if ($type === 'price-list') {
            $pdf = Pdf::loadView('downloads.price-list-pdf', compact('products', 'role', 'meta'))
                ->setPaper('a4', 'landscape');

            return $pdf->download('msv-' . $role . '-price-list-' . now()->format('Y-m-d') . '.pdf');
        }

        $pdf = Pdf::loadView('downloads.catalog-pdf', compact('products', 'role', 'meta'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('msv-catalog-' . now()->format('Y-m-d') . '.pdf');
    }

    private function categories(): Collection
    {
        return ProductCategory::with('subcategories.vehicles')
            ->where('is_subcategory', false)
            ->orderBy('name')
            ->get();
    }

    private function products(array $filters): Builder
    {
        $query = Product::with(['categories', 'fitments.category', 'fitments.vehicle.subcategory', 'focSlabs', 'media', 'companies', 'select_companies'])
            ->orderBy('item_code')
            ->orderBy('name');

        $categoryIds    = collect($filters['categories'] ?? [])->filter()->map(fn ($id) => (int) $id)->all();
        $subcategoryIds = collect($filters['subcategories'] ?? [])->filter()->map(fn ($id) => (int) $id)->all();
        $vehicleIds     = collect($filters['vehicles'] ?? [])->filter()->map(fn ($id) => (int) $id)->all();

        if ($categoryIds) {
            $query->whereHas('categories', fn ($q) => $q->whereIn('product_categories.id', $categoryIds));
        }

        if ($subcategoryIds) {
            $query->where(function ($q) use ($subcategoryIds) {
                $q->whereHas('categories', fn ($cat) => $cat->whereIn('product_categories.id', $subcategoryIds))
                    ->orWhereHas('fitments.vehicle', fn ($vehicle) => $vehicle->whereIn('subcategory_id', $subcategoryIds));
            });
        }

        if ($vehicleIds) {
            $query->whereHas('fitments', fn ($fitment) => $fitment->whereIn('vehicle_id', $vehicleIds));
        }

        return $query;
    }

    private function companyMeta(): array
    {
        return [
            'name'    => 'Maruti Suzuki Ventures',
            'tagline' => 'Perfect Fit Accessories for Every Vehicle',
            'phone'   => '7857868055',
            'email'   => 'support@marutisuzukiventures.online',
            'website' => config('app.url'),
            'address' => 'Patna, Bengaluru, Pune, Punjab',
            'logo'    => public_path('asset/img/msv-logo.png'),
        ];
    }
}
