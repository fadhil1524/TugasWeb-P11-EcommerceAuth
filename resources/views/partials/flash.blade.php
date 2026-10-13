@if (session('success'))
    <div class="mb-4 rounded border-l-4 border-green-500 bg-green-50 p-4 text-green-800" role="alert">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="mb-4 rounded border-l-4 border-red-500 bg-red-50 p-4 text-red-800" role="alert">
        {{ session('error') }}
    </div>
@endif
