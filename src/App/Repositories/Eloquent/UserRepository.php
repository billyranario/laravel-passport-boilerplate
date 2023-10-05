<?php

namespace App\Repositories\Eloquent;

use App\Constants\RoleConstant;
use App\Dtos\UserDto;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Billyranario\ProstarterKit\App\Helpers\LoggerHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\{
    DB,
    Hash
};

class UserRepository implements UserRepositoryInterface
{
    /**
     * @var User $user
     */
    protected User $user;

    /**
     * UserRepository constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get paginated users.
     * @param UserDto $userDto
     * @return LengthAwarePaginator
     */
    public function paginate(UserDto $userDto): LengthAwarePaginator
    {
        $keyword = $userDto->getSearchKeyword();

        return $this->user
            ->with($userDto->getRelations())
            ->when($userDto->getIsArchive(), function (Builder $query) {
                return $query->onlyTrashed();
            })
            ->when(!empty($keyword), function (Builder $user) use ($keyword) {
                $user->where(function (Builder $query) use ($keyword) {
                    $query->where('firstname', 'like', "%$keyword%")
                        ->orWhere('lastname', 'like', "%$keyword%")
                        ->orWhere('email', 'like', "%$keyword%");
                });
            })
            ->where('role_id', '!=', RoleConstant::SUPERADMIN)
            ->orderBy($userDto->getOrderBy(), $userDto->getOrderDirection())
            ->paginate($userDto->getPerPage());
    }


    /**
     * Find user by id.
     * @param int $id
     * @param array $relations
     * @return User|null
     */
    public function findById(int $id, array $relations = []): ?User
    {
        return $this->user
            ->with($relations)
            ->find($id);
    }

    /**
     * Find by email.
     * @param string $email
     * @param array $relations
     * @return User|null
     */
    public function findByEmail(string $email, array $relations = []): ?User
    {
        return $this->user
            ->with($relations)
            ->where('email', $email)
            ->first();
    }

    /**
     * Create user.
     * @param array $data
     * @return User|bool
     */
    public function create(array $data): User|bool
    {
        try {
            DB::beginTransaction();

            $user = $this->user->create($data);

            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            LoggerHelper::logThrowError($th);
            return false;
        }
    }

    /**
     * Update user.
     * @param array $data
     * @param int $id
     * @return User|bool
     */
    public function update(array $data, int $id): User|bool
    {
        try {
            DB::beginTransaction();

            $user = $this->findById($id);
            $user->update($data);

            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            LoggerHelper::logThrowError($th);
            return false;
        }
    }

    /**
     * Bulk update users.
     * @param array $data
     * @param array $ids
     * @return bool
     */
    public function bulkUpdate(array $data, array $ids): bool
    {
        try {
            DB::beginTransaction();

            $user = $this->user->whereIn('id', $ids)->update($data);

            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            LoggerHelper::logThrowError($th);
            return false;
        }
    }

    /**
     * Bulk restore users.
     * @param array $ids
     * @return bool
     */
    public function bulkRestore(array $ids): bool
    {
        try {
            DB::beginTransaction();

            $user = $this->user->whereIn('id', $ids)->restore();

            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            LoggerHelper::logThrowError($th);
            return false;
        }
    }

    /**
     * Change Password
     * @param string $newPassword
     * @param int $id
     * @return User|bool
     */
    public function changePassword(string $newPassword, int $id): User|bool
    {
        try {
            DB::beginTransaction();

            $user = $this->findById($id);
            $user->update(['password' => Hash::make($newPassword)]);

            DB::commit();
            return $user;
        } catch (\Throwable $th) {
            DB::rollBack();
            LoggerHelper::logThrowError($th);
            return false;
        }
    }

    /**
     * Set user preferences.
     * @param array $data
     * @param int $id
     * @return User|bool
     */
    public function setPreferences(array $data, int $id): User|bool
    {
        try {
            DB::beginTransaction();

            $user = $this->findById($id);

            if ($user->preference) {
                $user->preference()->update(['settings' => $data]);
            } else {
                $user->preference()->create(['settings' => $data]);
            }

            DB::commit();
            return $user->load('preference');
        } catch (\Throwable $th) {
            DB::rollBack();
            LoggerHelper::logThrowError($th);
            return false;
        }
    }

    /**
     * Delete user.
     * @param int $id
     * @param bool $forceDelete
     * @return bool
     */
    public function delete(int $id, bool $forceDelete = false): bool
    {
        $user = $this->findById($id);

        if ($user) {
            if ($forceDelete) {
                return $user->forceDelete();
            }
            return $user->delete();
        }
        return false;
    }

    /**
     * Bulk delete users.
     * @param array $ids
     * @param bool $forceDelete
     * @return bool
     */
    public function bulkDelete(array $ids, bool $forceDelete = false): bool
    {
        $query = $this->user->whereIn('id', $ids);

        if ($forceDelete) {
            $deletedCount = $query->forceDelete();
        } else {
            $deletedCount = $query->delete();
        }

        return $deletedCount > 0;
    }

    /**
     * Restore archived user.
     * @param int $id
     * @return bool
     */
    public function restore(int $id): bool
    {
        $user = $this->findById($id);

        if ($user) {
            return $user->restore();
        }
        return false;
    }
}
