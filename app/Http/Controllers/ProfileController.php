<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __invoke(Request $request): View
    {
        $request->user() ?? abort(403);

        return view('profile.show', [
            'user' => $request->user(),
        ]);
    }
}
