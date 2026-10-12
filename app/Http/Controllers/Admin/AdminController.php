<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends BaseController
{
    protected function ensureAdmin()
    {
        $user = auth()->user();
        if (!$user || $user->role !== 'admin') {
            return $this->errorResponse('Forbidden. Hanya Administrator yang dapat mengakses modul ini.', 403);
        }
        return null;
    }

    public function index(Request $request)
    {
        $guard = $this->ensureAdmin();
        if ($guard) return $guard;

        $query = User::with('manager');

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('role')) {
            $query->where('role', $request->get('role'));
        }

        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 10);

        $users = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse(
            data: [
                'current_page' => $users->currentPage(),
                'data' => collect($users->items())->map(fn($u) => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                    'manager_id' => $u->manager_id,
                    'manager_name' => $u->manager ? $u->manager->name : null,
                    'created_at' => $u->created_at->toIso8601String(),
                ]),
                'per_page' => $users->perPage(),
                'to' => $users->lastItem(),
                'total' => $users->total(),
            ],
            message: 'Daftar pengguna berhasil diambil'
        );
    }

    public function store(StoreUserRequest $request)
    {
        $guard = $this->ensureAdmin();
        if ($guard) return $guard;

        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return $this->successResponse(
            data: [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'manager_id' => $user->manager_id,
                'created_at' => $user->created_at->toIso8601String(),
            ],
            message: 'Pengguna berhasil dibuat',
            code: 201
        );
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $guard = $this->ensureAdmin();
        if ($guard) return $guard;

        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse('Data pengguna tidak ditemukan.', 404);
        }

        $data = $request->validated();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $this->successResponse(
            data: [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'manager_id' => $user->manager_id,
                'updated_at' => $user->updated_at->toIso8601String(),
            ],
            message: 'Data pengguna berhasil diperbarui'
        );
    }
}