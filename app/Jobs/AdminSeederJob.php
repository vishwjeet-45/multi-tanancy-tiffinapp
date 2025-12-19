<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\{User,Tenant};
use Illuminate\Support\Facades\Hash;
use Stancl\Tenancy\Tenancy;

class AdminSeederJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
   protected Tenant $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle(Tenancy $tenancy): void
    {
        // 🔥 Switch to tenant DB
        $tenancy->initialize($this->tenant);

        if (User::where('email', $this->tenant?->email)->exists()) {
            return;
        }

        User::create([
            'name' => $this->tenant?->name ?? 'Admin',
            'email' => $this->tenant?->email ?? 'admin@admin.com',
            'password' => $this->tenant?->password ?? Hash::make('password'),
        ]);

        // Optional cleanup
        $tenancy->end();
    }
}
