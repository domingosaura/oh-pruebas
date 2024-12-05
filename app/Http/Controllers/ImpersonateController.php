<?php
// app/Http/Controllers/ImpersonateController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    public function take($id)
    {
        $user = Auth::user();

        // Permitir impersonate solo a los usuarios con ID 4, 5 y 9
        if (in_array($user->id, [4, 5, 9])) {
            $toImpersonate = User::findOrFail($id);
            $user->impersonate($toImpersonate);
        }

        //return redirect()->route('home');
        return redirect()->route('dashboard');
    }

    public function leave()
    {
        $user = Auth::user();
        $user->leaveImpersonation();

        return redirect()->route('dashboard');
        //return redirect()->route('home');
    }
}