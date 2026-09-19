@extends('custom.master')

@section('content')
<section class="dashboard py-5" style="background:#f5f7fb;">
    <div class="container">
        <div class="row">
            @include('custom.sidebar')
            <div class="col-lg-9 mb-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div>
                                <h3 class="mb-1">{{ $title }}</h3>
                                <p class="text-muted mb-0">Select all products or choose category, subcategory and vehicle.</p>
                            </div>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Back</a>
                        </div>

                        <form method="POST" action="{{ route('downloads.generate', $type) }}">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label class="w-100 p-3 border rounded bg-light">
                                        <input type="radio" name="download_scope" value="all" checked class="me-2 scope-radio">
                                        All Items
                                    </label>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="w-100 p-3 border rounded bg-light">
                                        <input type="radio" name="download_scope" value="specific" class="me-2 scope-radio">
                                        Specific Selection
                                    </label>
                                </div>
                            </div>

                            <div id="specificFilters" class="d-none">
                                @foreach($categories as $category)
                                    <div class="border rounded mb-3 p-3 bg-white">
                                        <label class="fw-bold d-flex align-items-center gap-2">
                                            <input type="checkbox" name="categories[]" value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                        @if($category->subcategories->isNotEmpty())
                                            <div class="row mt-3">
                                                @foreach($category->subcategories as $subcategory)
                                                    <div class="col-md-6 mb-3">
                                                        <div class="border rounded p-3 h-100">
                                                            <label class="fw-semibold d-flex align-items-center gap-2">
                                                                <input type="checkbox" name="subcategories[]" value="{{ $subcategory->id }}">
                                                                {{ $subcategory->name }}
                                                            </label>
                                                            @if($subcategory->vehicles->isNotEmpty())
                                                                <div class="mt-2 ps-3">
                                                                    @foreach($subcategory->vehicles as $vehicle)
                                                                        <label class="d-block small text-muted mb-1">
                                                                            <input type="checkbox" name="vehicles[]" value="{{ $vehicle->id }}" class="me-1">
                                                                            {{ $vehicle->name }}
                                                                        </label>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <button type="submit" class="btn primary-bg text-white px-4 py-2">
                                Download {{ $type === 'price-list' ? 'Price List PDF' : 'Catalog PDF' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const filters = document.getElementById('specificFilters');
    document.querySelectorAll('.scope-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            filters.classList.toggle('d-none', this.value !== 'specific');
        });
    });
});
</script>
@endsection
