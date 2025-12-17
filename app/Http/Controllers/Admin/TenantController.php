<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;  // Change this line
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class TenantController extends Controller
{

    public function create()
    {
        return Inertia::render('Vendors/Create');
    }
    public function store(Request $request)
    {
        $request->merge([
            'domain' => $request->domain . '.tiffinapp.test'
        ]);
        $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|unique:domains,domain',
        ]);
        $tenant = Tenant::create([
            'id' => (string) Str::uuid(),
            'name' => $request->name,
        ]);

        $tenant->domains()->create([
            'domain' => $request->domain
        ]);

         return redirect()->back()->with('success', 'Vendor created successfully');
    }
}
