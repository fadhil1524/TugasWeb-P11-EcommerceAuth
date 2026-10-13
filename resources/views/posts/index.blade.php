<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Posts</h2>
            <a href="{{ route('posts.create') }}" class="text-indigo-600 underline">+ Post Baru</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Judul</th>
                            <th class="px-4 py-3">Penulis</th>
                            <th class="px-4 py-3">Dibuat</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse ($posts as $post)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $post->title }}</td>
                                <td class="px-4 py-3">{{ $post->user->name }}</td>
                                <td class="px-4 py-3">{{ $post->created_at->diffForHumans() }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        {{-- @can hanya mengatur tampilan; keamanan asli ada di PostPolicy via Gate::authorize --}}
                                        @can('update', $post)
                                            <a href="{{ route('posts.edit', $post) }}" class="text-indigo-600 hover:underline">Edit</a>
                                        @endcan
                                        @can('delete', $post)
                                            <form method="POST" action="{{ route('posts.destroy', $post) }}"
                                                  onsubmit="return confirm('Hapus post ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="text-red-600 hover:underline">Hapus</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada post.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">{{ $posts->links() }}</div>
        </div>
    </div>
</x-app-layout>
