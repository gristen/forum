<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all()
        ->groupBy(fn($permission)=> explode('.', $permission->getRawOriginal('name'))[0]);

        return view('admin.roles.update', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        dump($request->permissions, $role);
        $role->permissions()->sync($request->permissions);
        //return redirect()->route('roles.index');
    }
}
