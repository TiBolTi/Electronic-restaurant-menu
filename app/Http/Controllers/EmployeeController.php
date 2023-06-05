<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use Spatie\Permission\Models\Role;


class EmployeeController extends Controller
{
    public function index()
    {

        $users = User::paginate(15);
        $roles = Role::all();


        return view('employees.index', compact('users', 'roles'));
    }

    public function clients()
    {
        $roles = Role::all();
        $users = User::paginate(15);
        return view('employees.clients', compact('users','roles'));
    }

    public function roleUpdate(Request $request)
    {
        $user = User::find($request->id);

        // Открепите текущие роли пользователя (если необходимо)
        $user->syncRoles([]);

        // Присвоить новую роль пользователю
        $user->assignRole($request->role);

        // Возвращаем успешный ответ или выполняем дополнительные действия
        return response()->json(['success' => true]);
    }
}
