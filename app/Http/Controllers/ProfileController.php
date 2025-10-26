<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $transactions = $request->user()
            ->purchases()
            ->with('course')
            ->orderByDesc('purchased_at')
            ->orderByDesc('id')
            ->get();

        return view('profile.show', [
            'user' => $request->user(),
            'transactions' => $transactions,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => [
                    'required',
                    'string',
                    'email:filter',
                    'max:255',
                    Rule::unique('users')->ignore($user->id),
                ],
                'password' => ['nullable', 'string', 'min:8', 'max:255', 'confirmed'],
            ],
            [],
            [
                'name' => __('Имя'),
                'email' => __('Email'),
                'password' => __('Пароль'),
            ]
        );

        $user->name = $validated['name'];
        $user->email = strtolower($validated['email']);

        if (! empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()
            ->route('profile.show')
            ->with('status', __('Профиль успешно обновлён.'));
    }
}
