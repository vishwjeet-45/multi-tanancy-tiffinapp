<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    // Show the create vendor form
    public function create()
    {
        return Inertia::render('Vendors/Create');
    }

    // Handle the form submission
    public function store(Request $request)
    {
        $request->merge([
            'domain' => $request->domain . '.tiffinapp.test'
        ]);
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:domains,domain',
            'email' => 'required|email',
            'password' => ['required', Rules\Password::defaults()],
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store(
                'tenants/logos',
                'public'
            );
        }

        $tenant = Tenant::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'logo' => $logoPath,
        ]);

        $tenant->domains()->create([
            'domain' => $request->domain
        ]);
        // 🔹 END OF CODE

        return redirect()->back()->with('success', 'Vendor created successfully');
    }
}
