<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()  { return view('admin.users.index', ['users' => User::paginate(20)]); }
    public function create() { return view('admin.users.form', ['user' => new User]); }
    public function store(Request $request)
    {
        User::create(array_merge($request->validate(['name'=>'required','email'=>'required|email|unique:users','password'=>'required|min:8|confirmed','phone'=>'nullable','role'=>'required|in:admin,user','is_active'=>'boolean']), ['password' => Hash::make($request->password)]));
        return redirect()->route('admin.users.index')->with('success','User ditambahkan.');
    }
    public function edit(User $user)  { return view('admin.users.form', compact('user')); }
    public function update(Request $request, User $user)
    {
        $data = $request->validate(['name'=>'required','email'=>'required|email|unique:users,email,'.$user->id,'phone'=>'nullable','role'=>'required|in:admin,user','is_active'=>'boolean']);
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return redirect()->route('admin.users.index')->with('success','User diperbarui.');
    }
    public function destroy(User $user) { $user->delete(); return redirect()->route('admin.users.index')->with('success','User dihapus.'); }
}
