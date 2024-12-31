<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UserRequest;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

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

        $items = $query->paginate(12)->appends(['search' => $search]);
        $tableFields = ['name', 'email', 'roles'];
        $fields = array_merge(
            array_diff(Schema::getColumnListing('users'), ['id', 'password', 'remember_token', 'updated_at']),
            ['roles', 'permissions']
        );

        return view('pages.dashboard.users', compact('items', 'tableFields', 'fields'));
    }

    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $validatedData = $request->validated();

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]);
            $user->assignRole($validatedData['role']);
            DB::commit();
            return redirect()->route('dashboard')->with('success', 'User created successfully.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the user: ' . $e->getMessage());
        }
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $validatedData = $request->validated();

        try {
            DB::beginTransaction();

            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);

            if (isset($validatedData['password'])) {
                $user->update(['password' => Hash::make($validatedData['password'])]);
            }

            $user->syncRoles([$validatedData['role']]);

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
}

