<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AkunController extends Controller
{
    public function index()
    {
        $users = User::paginate(5); // Paginate with a maximum of 5 users per page
        return view("admin.index", compact('users')); // Create a Blade view for the index page
    }
    public function create()
    {
        return view('admin.create'); // Create a Blade view for the form
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required|string|max:100',
            'email' => 'required|string|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        // Create a new user with a fixed role of 'petugas'
        User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'petugas', // Fixed role
        ]);

        // Redirect or return a response
        return redirect()->route('admin.index')->with('success', 'Akun berhasil dibuat.');
    }


    public function edit($id)
    {
        $users = User::findOrFail($id);
        return view('admin.edit', compact('users')); // Create a Blade view for the edit form
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'username' => 'required|string|max:100',
        'email' => 'required|string|email|max:100|unique:users,email,' . $id,
        'password' => 'nullable|string|min:8',
    ]);

    $user = User::findOrFail($id);
    $user->username = $request->username;
    $user->email = $request->email;

    if ($request->password) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('admin.index')->with('success', 'Akun berhasil diperbarui.');
}


    public function destroyAkun($id)
{
    // Temukan pengguna berdasarkan ID
    $users = User::findOrFail(id: $id);

    // Hapus pengguna
    $users->delete();

    // Redirect atau kembalikan respons
    return redirect()->route('admin.index')->with('success', 'Akun berhasil dihapus.');
}

}
