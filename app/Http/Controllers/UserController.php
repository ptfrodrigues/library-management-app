<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $search = $request->input('search');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $query = User::query();

        if ($user->hasRole('admin')) {
            $query->with('roles');
        } elseif ($user->hasRole('manager')) {
            $query->role(['librarian', 'member'])->with('roles');
        } elseif ($user->hasRole('librarian')) {
            $query->role('member')->with('roles');
        } else {
            abort(403, 'Unauthorized action.');
        }

        if ($search) {
            $query->where(function ($q) use ($search, $user) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");

                if ($user->can('edit_users')) {
                    $q->orWhereHas('roles', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', "%{$search}%");
                    });
                }
            });
        }

        $items = $query->paginate(10)->appends(['search' => $search]);
        $tableFields = ['name', 'email', 'roles'];
        $fields = array_merge(
            array_diff(Schema::getColumnListing('users'), ['id', 'password', 'remember_token', 'updated_at', 'deleted_at']),
            ['roles', 'permissions']
        );

        return view('pages.dashboard.users', compact('items', 'tableFields', 'fields'));
    }

    public function show(User $user)
    {
        $this->authorize('view', $user);

        $user->load('roles', 'permissions');
        return $user;
    }

    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'roles' => 'required|array',
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $roles = Role::whereIn('name', $validatedData['roles'])->get();
        $user->assignRole($roles);

        return response()->json($user->load('roles'), 201);
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('edit_users', User::class);
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            if (isset($validatedData['password'])) {
                $validatedData['password'] = Hash::make($validatedData['password']);
            }

            $user->update($validatedData);
            if (isset($validatedData['role'])) {
                $user->syncRoles([$validatedData['role']]);
            }

            DB::commit();
            return redirect()->route('dashboard.users')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred while updating the user.');
        }
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $user->delete();

        return redirect()->route('dashboard.users')->with('success', 'User deleted successfully.');

    }

    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->authorize('restore', $user);

        $user->restore();

        return response()->json($user->load('roles'));
    }

    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $user);

        $user->forceDelete();

        return response()->json(['message' => 'User permanently deleted']);
    }
}

