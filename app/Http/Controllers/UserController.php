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

    public function authUser()
    {
        $userId = Auth::id(); // Get the authenticated user ID
        return response()->json($userId);
    }
}
