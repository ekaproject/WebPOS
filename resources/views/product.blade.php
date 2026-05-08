<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>{{ $product->name }} | {{ $publicSettings['store_name'] ?? 'Toko' }}</title>
    @include('partials.vite-assets')
</head>
<body class="font-body bg-surface-container">
@include('partials.navbar', ['active' => 'home', 'showSearch' => false, 'hideAuthLink' => true])

<main class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-2xl border p-6">
        <div class="flex gap-6 flex-col md:flex-row">
            <div class="w-full md:w-1/3">
                @if($product->image)
                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}" class="w-full h-auto object-contain rounded-lg" />
                @else
                    <div class="w-full h-64 bg-gray-100 flex items-center justify-center rounded-lg">
                        <span class="material-symbols-outlined text-6xl text-gray-300">inventory_2</span>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-2xl font-bold mb-2">{{ $product->name }}</h1>
                <p class="text-sm text-on-surface-variant mb-3">Kategori: {{ $product->category?->name ?? '-' }}</p>
                <p class="text-2xl font-bold text-primary mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                <p class="text-sm text-on-surface-variant mb-3">Stok: <strong>{{ $product->stock }}</strong> {{ $product->unit }}</p>
                @if($product->description)
                    <div class="mb-4">
                        <h3 class="font-semibold">Deskripsi</h3>
                        <p class="text-sm text-on-surface">{!! nl2br(e($product->description)) !!}</p>
                    </div>
                @endif

                <a href="{{ url()->previous() ?: route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-surface-container text-sm">
                    <span class="material-symbols-outlined">arrow_back</span>
                    Kembali
                </a>
            </div>
        </div>
    </div>
</main>

</body>
</html>
