<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function logout(Request $request)
    {

        Auth::logout();;
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    public function show_login()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $user = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::attempt($user)) {
            $request->session()->regenerate();
            return redirect('/users');
        }
        return back()->withErrors(['email' => 'email or password is wrong',])->onlyInput('email');
    }
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {

        if (auth()->user()->role === 'admin' && $user->role === 'admin' && auth()->id() !== $user->id) {
            abort(403);
        }

        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403);
        }

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {

        if (auth()->user()->role === 'admin' && $user->role === 'admin' && auth()->id() !== $user->id) {
            abort(403);
        }


        if (auth()->user()->role !== 'admin' && auth()->id() !== $user->id) {
            abort(403);
        }

        $data = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|unique:users,phone,' . $user->id,
            'role'  => 'sometimes',
        ]);

        if (auth()->user()->role !== 'admin') {
            unset($data['role']);
        }

        $user->update($data);

        return redirect()->route('users.index');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }
        if (auth()->id() === $user->id) {
            abort(403);
        }
        if ($user->role === 'admin') {
            abort(403);
        }
        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
    }
}
