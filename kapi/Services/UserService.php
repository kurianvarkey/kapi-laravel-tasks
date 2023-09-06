<?php

namespace Kapi\Services;

use Exception;
use Kapi\Models\User;
use Kapi\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class UserService extends ServiceBase
{
    public function __construct(
        public User $userModel
    ) {
    }

    /**
     * store
     *
     * @param array $data
     * @return User
     */
    public function store(array $data): ?User
    {
        $data["api_key"] = Str::uuid();

        return $this->doDbTransaction(
            callback: function () use ($data) {
                $user = $this->userModel->create($this->userModel->getFillableData($data));
                $user->contact()->create((new Contact())->getFillableData($data));

                return $user;
            }
        );
    }

    /**
     * update
     *
     * @param array $data
     * @param User $user
     * @return User
     */
    public function update(array $data, $user): ?User
    {
        if (!$user instanceof User) {
            throw new Exception('Second parameter should be an user object', 0);
        }

        return $this->doDbTransaction(
            callback: function () use($user, $data) {
                $user->fillData($data)->save();
                $user->contact()->update((new Contact())->getFillableData($data));

                return $user;
            }
        );
    }

    /**
     * list
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->userModel->with(['jobTitle', 'contact'])->paginate();
    }

    /**
     * findById
     *
     * @param integer $id
     * @return User|null
     */
    public function findById(int $id): ?User
    {
        return $this->userModel->with(['jobTitle', 'contact'])->findOrFail($id);
    }

    /**
     * destroyById
     *
     * @param integer $id
     * @return boolean
     */
    public function destroyById(int $id): bool
    {
        return $this->userModel->findOrFail($id)->delete();
    }
}
