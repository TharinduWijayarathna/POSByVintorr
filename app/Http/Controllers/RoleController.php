<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * index
     * To open the Role view
     *
     * @return void
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->email == 'Vintorr@gmail.com') {
            return Inertia::render('Configurations/Roles/index');

            return response()->json(['status' => 'success']);
        } else {
            $response['alert-danger'] = 'You do not have permission to read role permissions.';

            return redirect()->route('dashboard')->with($response);
        }
    }

    /**
     * edit
     * To open the role editor
     *
     * @param  mixed  $id
     * @return void
     */
    public function edit(int $id)
    {
        $user = Auth::user();
        if ($user->email == 'Vintorr@gmail.com') {
            $response['roleId'] = $id;

            return Inertia::render('Configurations/Roles/edit', $response);
        } else {
            $response['alert-danger'] = 'You do not have permission to view role permissions.';

            return redirect()->route('dashboard')->with($response);
        }
    }

    /**
     * get
     * To get role data
     *
     * @return void
     */
    public function get(int $role_id)
    {
        $user = Auth::user();
        if ($user->email == 'Vintorr@gmail.com') {
            return $role = Role::find($role_id);
        } else {
            $response['alert-danger'] = 'You do not have permission to view role permissions.';

            return redirect()->route('dashboard')->with($response);
        }
    }
}
