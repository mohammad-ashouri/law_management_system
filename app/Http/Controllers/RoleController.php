<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:لیست نقش ها', ['only' => ['index']]);
        $this->middleware('permission:ایجاد نقش', ['only' => ['create', 'store']]);
        $this->middleware('permission:ویرایش نقش', ['only' => ['update']]);
        $this->middleware('permission:نمایش جزئیات نقش', ['only' => ['edit']]);
        $this->middleware('permission:حذف نقش', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $roles = Role::orderBy('name', 'asc')->paginate(10);
        return view('Catalogs.Roles.index', compact('roles'));
    }
    public function create()
    {
        $permission = Permission::get();
        return view('Catalogs.Roles.create', compact('permission'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|exists:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return redirect()->route('Roles.index')
            ->with('success', 'نقش کاربری با موفقیت ایجاد شد.');
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('Catalogs.Roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('Catalogs.Roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return redirect()->route('Roles.index')
            ->with('success', 'نقش کاربری با موفقیت ویرایش شد.');
    }
}