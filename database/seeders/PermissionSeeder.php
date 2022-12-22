<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    protected $permissions = [];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $resources = ['user', 'permission'];

        $actions = [
            'list', 'view', 'create', 'edit', 'delete', 'export', 'import', 'search',
        ];

        // Create permissions for each resource.
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                // Remove spaces
                $action = str_replace(' ', '', $action);
                $resource = str_replace(' ', '', $resource);

                // Add item into permissions array
                array_push($this->permissions, [
                    'resource' => Str::title($resource),
                    'name' => $action . '-' . $resource,
                    'description' => Str::title($action . ' ' . $resource)
                ]);
            }
        }

        // Other permissions
        $others = [
            [
                'resource' => 'Report',
                'name' => 'view-report',
                'description' => 'View Report',
            ],
            [
                'resource' => 'User',
                'name' => 'manage-user',
                'description' => 'Manage User',
            ]

        ];

        // Merge resources-permissions with other-permissions
        foreach ($others as $permission) {
            array_push($this->permissions, $permission);
        }

        // Store Permissions
        DB::transaction(function () {
            foreach ($this->permissions as $permission) {
                // Create filter for checking if permission already exists.
                $filter = ['name' => $permission['name']];

                Permission::updateOrCreate($filter, $permission);
            }
        });
    }
}
