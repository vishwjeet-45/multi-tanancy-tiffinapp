<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;

return [

    'tenant_model' => \App\Models\Tenant::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,
    'domain_model' => Domain::class,

    'central_domains' => [
        'tiffinapp.test',
        '127.0.0.1'
    ],

    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
    ],

    'database' => [
        'central_connection' => 'mysql',

        'template_tenant_connection' => 'tenant_template',

        'prefix' => 'tenant_',
        'suffix' => '',

        'managers' => [
            'mysql' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
        ],
    ],

    'routes' => true,

    'migration_parameters' => [
        '--force' => true,
        '--path' => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],
];
