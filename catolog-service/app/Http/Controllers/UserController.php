<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\UserResource;
use App\Http\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try{
            $users = $this->userService->getAll();

            return new DefaultResource(
                UserResource::collection($users),
                true,
                200,
                'Users fetched successfully.',
            );
            
        }catch(\Exception $e){
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
        
    }

    public function show($id)
    {
        try{
            $user = $this->userService->getById($id);

            return new DefaultResource(
                new UserResource($user),
                true,
                200,
                'User fetched successfully.',
            );
        }catch(\Exception $e){
            return new DefaultResource(null, false, 500, $e->getMessage());
        }

    }

    public function store(UserRequest $request)
    {
        try{
            $user = $this->userService->create($request->validated());

            return new DefaultResource(
                new UserResource($user),
                true,
                201,
                'User created successfully.',
            );
        }catch(\Exception $e){
            return new DefaultResource(null, false, 500, $e->getMessage());
        }
       
    }
}
