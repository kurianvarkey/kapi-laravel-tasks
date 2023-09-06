<?php

namespace Kapi\Http\Controllers;

use Kapi\Models\User;
use Illuminate\Http\Response;
use Kapi\Services\UserService;
use Kapi\Events\User\Registered;
use Kapi\Traits\AppResponseTrait;
use App\Http\Controllers\Controller;
use Kapi\Http\Requests\User\StoreRequest;
use Kapi\Http\Requests\User\UpdateRequest;
use Kapi\Http\Resources\User\UserResource;
use Kapi\Http\Resources\User\UserCollection;

final class UserController extends Controller
{
    use AppResponseTrait;

    /**
     * Contructor
     */
    public function __construct(
        public UserService $userService
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(
            data: new UserCollection($this->userService->list())
        );
    }

    /**
     * Store a newly created user in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $user = $this->userService->store($request->validated());

        event(new Registered($user));

        return $this->sendResponse(
            data: new UserResource($user),
            httpStatus: Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified user.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->sendResponse(
            data: new UserResource($this->userService->findById($id))
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @return void
     */
    public function update(User $user, UpdateRequest $request)
    {
        $user = $this->userService->update($request->validated(), $user);

        return $this->sendResponse(
            data: new UserResource($user)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $this->userService->destroyById($id);

        return $this->sendResponse(
            data: null
        );
    }
}
