<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Illuminate\Support\Str;
use Inertia\Inertia;

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
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|alpha_dash|unique:domains,domain',
        ]);

        $tenant = Tenant::create([
            'id' => (string) Str::uuid(),
            'data' => 'Vishwjeet Tiffin',
        ]);

        $tenant->domains()->create([
            'domain' => 'vishwjeet.tiffinapp.test',
        ]);
        // 🔹 END OF CODE

        return redirect()->back()->with('success', 'Vendor created successfully');
    }
}
