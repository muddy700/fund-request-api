<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $roles = Role::all();

            return $this->sendSuccess('retrieved', $roles, 1);
        } catch (\Throwable $th) {
            return $this->sendError('retrieve', $th, 500, 1);
        }
    }

    /**
     * Store a newly created resource in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Update the specified resource in database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
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

    public function sendError($action, $error, $status = 500, $hasMany = 0)
    {
        $message = 'Failed to ' . $action . ($hasMany ? ' roles.' : ' role.');

        $content = ['message' => $message, 'data' => null, 'error' => $error,];

        return response()->json($content, $status);
    }

    public function sendSuccess($action, $data, $hasMany = 0)
    {
        $message = ($hasMany ? 'Roles ' : 'Role ') . $action . ' successfully.';

        $content = ['message' => $message, 'data' => $data, 'error' => null];

        return response()->json($content);
    }
}
