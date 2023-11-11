<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SuperadminController extends Controller
{
    public function showLoginForm()
    {
        return view('superadmin.login');
    }
    public function dashboard()
    {

        $users = User::orderBy('id', 'DESC')->paginate(6);
        $products = Product::where('status','active')->orderBy('id', 'DESC')->paginate(6);
        $store=Store::where('status','active')->orderBy('id', 'DESC')->paginate(6);

        return view('superadmin.dashboard', ['users' => $users,'store'=>$store,'products'=>$products]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->type === 'superadmin') {
                // User has the superadmin role, redirect to the appropriate page
                return redirect()->route('superadmin.dashboard')->with('success', 'Login successful.');
            } else {
                // User does not have the required role, logout and redirect back to the login page
                Auth::logout();
                return redirect()->route('login')->with('error', 'You do not have permission to access this page.');
            }
        } else {
            // Invalid credentials, redirect back to the login page with an error message
            return redirect()->route('login')->with('error', 'Invalid email or password.');
        }
    }
}