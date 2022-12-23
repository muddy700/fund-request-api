<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministratorRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $permissions = Permission::all();

            $role = Role::where('name', Role::ADMINISTRATOR)->first();

            if (is_null($role)) {
                $data = [
                    'name' => Role::ADMINISTRATOR,
                    'description' => 'Overall System Administrator'
                ];

                $role = Role::create($data);
            }

            if ($permissions->count() > 0) {
                // Update role-permissions
                // Detach then attach permissions again
            }
        });
    }
}
