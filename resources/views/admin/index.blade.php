<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel Admin</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.flash')

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach ($stats as $label => $total)
                    <div class="bg-white shadow-sm sm:rounded-lg p-5 text-center">
                        <div class="text-3xl font-bold text-indigo-600">{{ $total }}</div>
                        <div class="text-gray-600">{{ $label }}</div>
                    </div>
                @endforeach
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-left text-gray-600">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Role</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($users as $u)
                            <tr>
                                <td class="px-4 py-3 font-medium">{{ $u->name }}</td>
                                <td class="px-4 py-3">{{ $u->email }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('admin.users.role', $u) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <select name="role" class="border-gray-300 rounded-md text-sm">
                                            @foreach (\App\Models\User::ROLES as $role)
                                                <option value="{{ $role }}" @selected($u->role === $role)>{{ $role }}</option>
                                            @endforeach
                                        </select>
                                        <button class="text-indigo-600 hover:underline">Simpan</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div>{{ $users->links() }}</div>
        </div>
    </div>
</x-app-layout>
