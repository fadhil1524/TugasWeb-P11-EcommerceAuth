<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Produk (admin &amp; editor)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Harga</th>
                            <th class="px-4 py-3">Stok</th>
                            <th class="px-4 py-3">Tag</th>
                            <th class="px-4 py-3">Terjual (baris order)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($products as $p)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $p->name }}</td>
                                <td class="px-4 py-3">{{ $p->category->name }}</td>
                                <td class="px-4 py-3">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                <td class="px-4 py-3">{{ $p->stock }}</td>
                                <td class="px-4 py-3">{{ $p->tags->pluck('name')->join(', ') }}</td>
                                <td class="px-4 py-3">{{ $p->order_items_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $products->links() }}</div>
        </div>
    </div>
</x-app-layout>
