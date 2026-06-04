<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\DoctorLoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{

    public function store(DoctorLoginRequest $request): RedirectResponse
    {
        if (Auth::guard('doctor')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ])) {

            $request->session()->regenerate();

            return redirect()->route('dashboard.doctor');
        }

        return back()->withErrors([
            'email' => trans('Dashboard/auth.failed'),
        ]);
    }
    public function destroy(Request $request)
    {
        Auth::guard('doctor')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
