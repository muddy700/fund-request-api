<?php

namespace App\Http\Controllers;

use App\Http\Requests\ManageRolePermissionRequest;
use App\Http\Requests\StoreRolePermissionRequest;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolePermissionController extends Controller
{

    // Create new role-permission record.
    public function store(StoreRolePermissionRequest $request)
    {
        try {
            $payload = $request->all();

            // $user_id = '123';
            // $payload['created_by'] = $user_id;

            $role_permission = RolePermission::create($payload);

            return $this->sendSuccess('created', $role_permission->load(['role', 'permission', 'creator']));
        } catch (\Throwable $th) {
            return $this->sendError('create', $th);
        }
    }

    public function manage(ManageRolePermissionRequest $request, $role_id)
    {
        try {
            $flag = (int)$request->flag ?? null;
            $active_permissions = $request->active_permissions ?? null;

            $added_permissions = $request->added_permissions ?? null;
            $removed_permissions = $request->removed_permissions ?? null;

            $role = Role::without('rolePermissions')->find($role_id);
            if (!$role) {
                $error = 'No role found with id: ' . $role_id;

                return $this->sendError('manage', $error, 404, 1);
            }

            /**
             *  There are two ways of managing permissoions.
             * 
             * * Approach-1: Delete all existing permissions for this role and create again as sent in 'active_permissions' array from request body.
             * * Approach-2: Remove and add permissions as sent in removed_permissions and added_permissions arrays respectively from request body.
             */

            if ($flag == 1 && !is_null($active_permissions)) {
                return $this->useApproachOne($role, $active_permissions);
            } else if ($flag == 2 && (!is_null($added_permissions) || !is_null($removed_permissions))) {
                return $this->useApproachTwo($role, $removed_permissions, $added_permissions);
            } else {
                $error = 'Oops..!, Something is wrong with your request. Check it and try again buddy!. 😎';

                return $this->sendError('manage', $error, 400, 1);
            }
        } catch (\Throwable $th) {
            return $this->sendError('manage', $th, 500, 1);
        }
    }

    public function useApproachOne($role, $active_permissions)
    {
        try {
            if ($active_permissions && count($active_permissions)) {
                DB::transaction(function () use ($role, $active_permissions) {
                    // Delete all existing permissions for this role.
                    RolePermission::where('role_id', $role->id)->delete();

                    // Insert all selected permissions again for this role.
                    foreach ($active_permissions as $permission_id) {
                        RolePermission::create([
                            'role_id' => $role->id,
                            'permission_id' => $permission_id
                        ]);
                    }
                });

                // Lazy Eager Load relations
                $role = $role->load('rolePermissions.permission:id,resource,name,description');

                return $this->sendSuccess('managed', $role, 1);
            } else {
                // The 'active_permissions' array is empty.
                $data = ['developerMessage' => 'You did not pass anything in the active_permissions array. 🌚'];

                return $this->sendSuccess('managed', $data, 1);
            }
        } catch (\Throwable $th) {
            return $this->sendError('manage', $th, 500, 1);
        }
    }

    public function useApproachTwo($role, $removed_permissions, $added_permissions)
    {
        try {
            if ($removed_permissions && count($removed_permissions)) {
                // Delete only removed permissions for this role.

                RolePermission::whereIn('permission_id', $removed_permissions)->delete();
            }

            if ($added_permissions && count($added_permissions)) {
                // Insert only added permissions for this role.

                foreach ($added_permissions as $permission_id) {
                    RolePermission::create([
                        'role_id' => $role->id,
                        'permission_id' => $permission_id
                    ]);
                }
            }

            // Lazy Eager Load relations
            $role = $role->load('rolePermissions.permission:id,resource,name,description');

            return $this->sendSuccess('managed', $role, 1);
        } catch (\Throwable $th) {
            return $this->sendError('manage', $th, 500, 1);
        }
    }

    // Delete the role-permission with a given id.
    public function destroy($id)
    {
        try {
            $item = RolePermission::find($id);

            if (!$item) {
                $error = 'No role-permission found with id: ' . $id;

                return $this->sendError('delete', $error, 404);
            }

            $item->delete();
            return $this->sendSuccess('deleted', $item);
        } catch (\Throwable $th) {
            $this->sendError('delete', $th);
        }
    }

    // Send successful response.
    public function sendError($action, $error, $status = 500, $hasMany = 0)
    {
        $message = 'Failed to ' . $action . ($hasMany ? ' role-permissions.' : ' role-permission.') . ' 😭';

        $content = ['message' => $message, 'data' => null, 'error' => $error,];

        return response()->json($content, $status);
    }

    // Send error response.
    public function sendSuccess($action, $data, $hasMany = 0)
    {
        $message = ($hasMany ? 'Role-permissions ' : 'Role-permission ') . $action . ' successfully. ✅';

        $content = ['message' => $message, 'data' => $data, 'error' => null];

        return response()->json($content);
    }
}
