<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoleController extends Controller
{
    public function index(): View
    {
        $roles = Role::query()->withCount('users')->orderBy('name')->paginate(10);

        return view('admin.roles.index', [
            'roles' => $roles,
            'pageTitle' => 'Роли',
        ]);
    }

    public function create(): View
    {
        return view('admin.roles.create', [
            'pageTitle' => 'Новая роль',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:roles,slug'],
        ]);

        Role::query()->create($data);

        return redirect()->route('admin.roles.index')->with('status', 'Роль успешно создана.');
    }

    public function edit(Role $role): View
    {
        return view('admin.roles.edit', [
            'role' => $role,
            'pageTitle' => 'Редактирование роли',
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:roles,slug,' . $role->id],
        ]);

        if ($role->slug === Role::ADMIN && $data['slug'] !== Role::ADMIN) {
            return back()->withErrors(['slug' => 'Системную роль администратора нельзя переименовывать.'])->withInput();
        }

        $role->update($data);

        return redirect()->route('admin.roles.index')->with('status', 'Роль обновлена.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        if ($role->slug === Role::ADMIN) {
            return back()->withErrors(['role' => 'Системную роль администратора нельзя удалить.']);
        }

        if ($role->users()->exists()) {
            return back()->withErrors(['role' => 'Нельзя удалить роль, к которой привязаны пользователи.']);
        }

        $role->delete();

        return redirect()->route('admin.roles.index')->with('status', 'Роль удалена.');
    }
}
