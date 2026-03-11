<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controllers\HasMiddleware; // موجودة عندك
use Illuminate\Routing\Controllers\Middleware;    // موجودة عندك

class UserController extends Controller implements HasMiddleware // 1. أضفنا implements هنا
{
    // 2. حذفنا __construct واستخدمنا الدالة الجديدة middleware
    public static function middleware(): array
    {
        return [
            new Middleware(function ($request, $next) {
                if (!auth()->check() || !auth()->user()->isAdmin()) {
                    abort(403);
                }
                return $next($request);
            }),
        ];
    }

    public function index()
    {
        $users = User::withTrashed()->latest()->paginate(20);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create', ['roles' => User::$roleLabels]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:200'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', Rule::in(['admin','editor','viewer'])],
        ]);
        $data['password'] = Hash::make($data['password']);
        User::create($data);
        return redirect()->route('users.index')->with('success', 'تم إنشاء المستخدم.');
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user, 'roles' => User::$roleLabels]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'      => ['required', 'string', 'max:200'],
            'email'     => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'      => ['required', Rule::in(['admin','editor','viewer'])],
            'is_active' => ['boolean'],
            'password'  => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');
        $user->update($data);
        return redirect()->route('users.index')->with('success', 'تم تحديث بيانات المستخدم.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'لا يمكنك حذف حسابك الخاص.');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'تم تعطيل المستخدم.');
    }
}
