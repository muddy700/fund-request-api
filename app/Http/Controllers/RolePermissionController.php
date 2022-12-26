<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRolePermissionRequest;
use App\Models\Role;
use App\Models\RolePermission;
use Illuminate\Http\Request;

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

    public function manage(Request $request, $role_id)
    {
        try {
            $role = Role::without('rolePermissions')->find($role_id);

            if (!$role) {
                $error = 'No role found with id: ' . $role_id;

                return $this->sendError('manage', $error, 404, 1);
            }

            /**
             *
             * * Approach-1: Delete all existing permissions and create again.
             * * Approach-2: Send added and removed arrays in request body.
             * 
             * Delete existing permissions for this role.
             */
            RolePermission::where('role_id', $role->id)->delete();

            $new_permissions = $request['permission_ids'] ?? null;

            //Insert new permissions
            if ($new_permissions && count($new_permissions)) {
                foreach ($new_permissions as $id) {
                    RolePermission::create([
                        'role_id' => $role->id,
                        'permission_id' => $id
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
        $message = 'Failed to ' . $action . ($hasMany ? ' role-permissions.' : ' role-permission.');

        $content = ['message' => $message, 'data' => null, 'error' => $error,];

        return response()->json($content, $status);
    }

    // Send error response.
    public function sendSuccess($action, $data, $hasMany = 0)
    {
        $message = ($hasMany ? 'Role-permissions ' : 'Role-permission ') . $action . ' successfully.';

        $content = ['message' => $message, 'data' => $data, 'error' => null];

        return response()->json($content);
    }
}
