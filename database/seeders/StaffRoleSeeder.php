<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // Array for storing permissions of a Staff.
            $permissions = [];

            // Resources that Staff can access.
            $resources = ['fundrequest', 'user'];

            // Actions that Staff can perform on above resources.
            $actions = ['view'];

            // Create permissions for Staff.
            foreach ($resources as $resource) {
                foreach ($actions as $action) {
                    // Remove spaces
                    $action = str_replace(' ', '', $action);
                    $resource = str_replace(' ', '', $resource);

                    // Add items into permissions array.
                    array_push($permissions, $action . '-' . $resource);
                }
            }

            // Get stored permissions of a Staff.
            if (count($permissions) > 0) {
                $permissions = Permission::whereIn('name', $permissions)->get();
            }

            // Get details of a Staff Role.
            $role = Role::where('name', Role::STAFF)->first();

            if (is_null($role)) {
                $data = [
                    'name' => Role::STAFF,
                    'description' => 'Staff'
                ];

                $role = Role::create($data);
            }

            if (count($permissions) > 0) {
                // Update role-permissions
                // Detach then attach permissions again
            }
        });
    }
}
