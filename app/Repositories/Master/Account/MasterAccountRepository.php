<?php
namespace App\Repositories\Master\Account;
use App\Interface\Master\Account\MasterAccountInterface;
use App\Models\Role;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MasterAccountRepository implements MasterAccountInterface
{
    public function __construct(private User $userModel)
    {
    }

    public function index($params = [], $perPage)
    {
        $query = $this->userModel::with('role')
            ->whereHas('role', function ($q) {
                $q->where('is_system', false);
            })
            ->when(isset($params['search']), function ($q) use ($params) {
                $search = $params['search'];

                $q->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });

        return $query->paginate($perPage ?: 10);
    }

    public function store(array $data)
    {
        return $this->userModel::create($data);
    }

    public function show(string $id)
    {
        $user = $this->userModel::with('role')->find($id);

        if (!$user) {
            throw new HttpException(404, 'Account tidak ditemukan');
        }

        return $user;
    }

    public function update(string $id, array $data)
    {
        $user = $this->userModel::find($id);

        if (!$user) {
            throw new HttpException(404, 'Account tidak ditemukan');
        }

        // ponytail: password di hash manual di sini karena field nullable di update, cast hashed hanya jalan saat create
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return $user->fresh('role');
    }

    public function getRolesDropdown()
    {
        return Role::where('is_system', false)
            ->whereIn('slug', ['admin-cuni', 'superadmin'])
            ->get(['id', 'name', 'slug']);
    }
}