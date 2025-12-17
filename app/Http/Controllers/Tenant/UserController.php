<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     public function index()
    {
         $users = User::query()
        ->latest()
        ->paginate(1);
        return Inertia::render('Tenant/Users/Index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return Inertia::render('Tenant/Users/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('tenant.users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back();
    }
}
