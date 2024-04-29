<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserQuery;
use App\Http\Requests\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $filter = new UserQuery();
        $queryItems = $filter ->transform($request);

        if ($queryItems === null) {
            return  UserResource::collection(User::paginate(2));
        }else{
            return UserResource::collection(User::where($queryItems)->get());
        }

    }

  
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->all());
        $res = [
            'message' => 'User created successfully',
            'status' => 200,
            'data' => new UserResource($user)
        ];
        
        return $res;
    }

    /**
     * Display the specified resource.
     */
     public function show($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $res = [
            'status' => 200,
            'data' => new UserResource($user)
            ];
            return $res;
        } catch (ModelNotFoundException $e) {
            $res = [
                'message' => 'User not found',
                'status' => 404,
            ];

            return $res;
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request,$user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $user->update($request->all());
            $res = [
                'message' => 'Updated user successfully',
                'status' => 200,
                'data' => new UserResource($user)
            ];
            return $res;
        } catch (ModelNotFoundException $e) {
             $res = [
                'message' => 'User not found',
                'status' => 404,
            ];
            return $res; 

        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $user->delete();
            $res = [
                'message' => 'User Deleted successfully',
                'status' => 200,
                'data' => new UserResource($user)
            ];
            return $res;

        } catch (ModelNotFoundException $e) {
             $res = [
                'message' => 'User not found',
                'status' => 404,
            ];
            return $res; 
        }
    }

}
