<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // Get all Roles from the database
    public function index()
    {
        try {
            $roles = Role::all();

            return $this->sendSuccess('retrieved', $roles, 1);
        } catch (\Throwable $th) {
            return $this->sendError('retrieve', $th, 500, 1);
        }
    }

    // Create a new role
    public function store(StoreRoleRequest $request)
    {
        try {
            $payload = $request->all();

            // $user_id = '123';
            // $payload['created_by'] = $user_id;

            $role = Role::create($payload);

            return $this->sendSuccess('created', $role);
        } catch (\Throwable $th) {
            return $this->sendError('create', $th);
        }
    }

    // Get the role with a given id
    public function show($id)
    {
        try {
            $role = Role::find($id);

            if (is_null($role)) {
                $error = 'No role found with id: ' . $id;

                return $this->sendError('retrieve', $error, 404);
            }

            return $this->sendSuccess('retrieved', $role);
        } catch (\Throwable $th) {
            return $this->sendError('retrieve', $th);
        }
    }

    // Update the role with a given id
    public function update(Request $request, $id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                $error = 'No role found with id: ' . $id;

                return $this->sendError('update', $error, 404);
            }

            // Check for duplication
            $role_name = $request['name'] ?? null;
            $nameExist = Role::where('name', $role_name)->where('id', '!=', $id)->count();

            if ($nameExist) {
                $error = 'The role name has already been taken.';

                return $this->sendError('update', $error, 400);
            } else {
                $role->update($request->all());

                return $this->sendSuccess('updated', $role);
            }
        } catch (\Throwable $th) {
            return $this->sendError('update', $th);
        }
    }

    // Delete the role with a given id
    public function destroy($id)
    {
        try {
            $role = Role::find($id);

            if (!$role) {
                $error = 'No role found with id: ' . $id;

                return $this->sendError('delete', $error, 404);
            }

            $role->delete();
            return $this->sendSuccess('deleted', $role);
        } catch (\Throwable $th) {
            $this->sendError('delete', $th);
        }
    }

    // Send successful response
    public function sendError($action, $error, $status = 500, $hasMany = 0)
    {
        $message = 'Failed to ' . $action . ($hasMany ? ' roles.' : ' role.');

        $content = ['message' => $message, 'data' => null, 'error' => $error,];

        return response()->json($content, $status);
    }

    // Send error response
    public function sendSuccess($action, $data, $hasMany = 0)
    {
        $message = ($hasMany ? 'Roles ' : 'Role ') . $action . ' successfully.';

        $content = ['message' => $message, 'data' => $data, 'error' => null];

        return response()->json($content);
    }
}
