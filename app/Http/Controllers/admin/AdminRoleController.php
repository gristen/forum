<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;


class AdminRoleController extends Controller
{
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function edit(Role $role)
    {
        $selectedPermissions = old('permissions',$role->permissions->pluck('id')->all());
        $permissions = Permission::all()
        ->groupBy(fn($permission)=> explode('.', $permission->getRawOriginal('name'))[0]);
        /*$permission[$key][]*/
        debugbar()->info($permissions);
        return view('admin.roles.update', compact('role', 'permissions','selectedPermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->permissions()->sync($request->permissions);
        return redirect()
            ->route('roles.index')
            ->with('success', 'Права доступа успешно обновлены!');
    }
}
