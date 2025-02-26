<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard() {
        $users = User::where('role', '!=', 'admin')->get();


        return view('admin.dashboard', ['users' => $users]);
    }
    public function changeRole(Request $request, $id) {
        $user = User::findOrFail($id);
        $user->role = $request->role;
        $user->save();

        return redirect()->route('admin.dashboard')->with('success', 'User role updated successfully!');
    }

}

