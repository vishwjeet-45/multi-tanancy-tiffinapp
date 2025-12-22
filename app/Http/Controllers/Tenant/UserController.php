<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
     public function index(Request $request)
    {
       if ($request->filled('search')) {
            $users = User::search($request->search)->paginate(10);
        } else {
            $users = User::latest()->paginate(10);
        }

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

    public function update(Request $request,User $user)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);
        $user->update([
            'name' => $request->name,
        ]);

        return redirect()->route('tenant.users.index');
    }

    public function destroy(User $user)
    {
        if (! $user) {
            return redirect()->back()->with('error', 'User not found');
        }
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully');
    }

}
