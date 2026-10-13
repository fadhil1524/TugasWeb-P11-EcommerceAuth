<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6 space-y-4">
                @include('partials.flash')

                <p>
                    Halo, <strong>{{ auth()->user()->name }}</strong>!
                    Role Anda:
                    <span class="inline-block rounded bg-indigo-100 px-2 py-0.5 text-sm font-medium text-indigo-800">
                        {{ auth()->user()->role }}
                    </span>
                </p>

                <ul class="list-disc ml-6 space-y-1 text-indigo-700">
                    <li><a class="underline" href="{{ route('posts.index') }}">Posts (semua role)</a></li>

                    {{-- Menyembunyikan link hanya UX. Keamanan sebenarnya ada di middleware role. --}}
                    @if (auth()->user()->hasRole('admin', 'editor'))
                        <li><a class="underline" href="{{ route('manage.products.index') }}">Kelola Produk (admin &amp; editor)</a></li>
                    @endif
                    @if (auth()->user()->hasRole('admin'))
                        <li><a class="underline" href="{{ route('admin.index') }}">Panel Admin (khusus admin)</a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
