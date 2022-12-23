<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinanceRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // Array for storing permissions of a Finance Officer.
            $permissions = [];

            // Resources that Finance Officer can access.
            $resources = ['fundrequest', 'user'];

            // Actions that Finance Officer can perform on above resources.
            $actions = ['list', 'view'];

            // Create permissions for Finance Officer.
            foreach ($resources as $resource) {
                foreach ($actions as $action) {
                    // Remove spaces
                    $action = str_replace(' ', '', $action);
                    $resource = str_replace(' ', '', $resource);

                    // Add items into permissions array.
                    array_push($permissions, $action . '-' . $resource);
                }
            }

            // Get stored permissions of a Finance Officer.
            if (count($permissions) > 0) {
                $permissions = Permission::whereIn('name', $permissions)->get();
            }

            // Get details of a Finance Role.
            $role = Role::where('name', Role::FINANCE)->first();

            if (is_null($role)) {
                $data = [
                    'name' => Role::FINANCE,
                    'description' => 'Finance Officer'
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
