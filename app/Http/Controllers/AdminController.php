<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Post;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'Users'    => User::count(),
            'Produk'   => Product::count(),
            'Order'    => Order::count(),
            'Post'     => Post::count(),
        ];

        $users = User::orderBy('name')->paginate(10);

        return view('admin.index', compact('stats', 'users'));
    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(User::ROLES)],
        ]);

        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun sendiri.');
        }

        $user->role = $data['role']; // role tidak di $fillable -> di-set eksplisit
        $user->save();

        return back()->with('success', "Role {$user->name} diubah menjadi {$user->role}.");
    }
}
