<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::where('id', '!=', auth()->id())->get());
    }

    
    public function user(Request $request) {
        return response()->json($request->user());
    }

    public function authUser(Request $request)
    {
        if (Auth::check()) {
            return response()->json($request->user());
        }

        return response()->json(null, 401); // Return 401 if not authenticated
    }


    public function logout(Request $request)
{
    // Logout user and remove tokens
    $request->user()->tokens()->delete();

    // Invalidate session & regenerate token
    auth()->guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json(['message' => 'Logged out successfully'], 200);
}


}
