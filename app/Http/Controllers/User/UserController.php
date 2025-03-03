<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response as symfonyResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use App\Http\Requests\User\StoreUserRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response
    {
        $users = User::where("id", "!=", Auth::id())->get();
        $users = UserResource::collection($users);
        return Inertia::render("Users", compact("users"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        try {

            $userData = ["name" => $request->name, "email" => $request->email, "password" => Hash::make($request->password)];
            $user = User::create($userData);

            return apiResponse(message: "User created successfully", data: new UserResource($user), statusCode: symfonyResponse::HTTP_CREATED);
        } catch (QueryException $e) {
            return apiResponse(errors: ["Integrity constraint violation"], statusCode: symfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Inertia\Response
     */
    public function show(string $id): Response
    {
        return Inertia::render("users/show", [
            "user" => User::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $user = User::findOrFail($id);
            $userData = ["name" => $request->name, "email" => $request->email];
            $user->update($userData);

            return apiResponse(message: "User updated successfully", data: new UserResource($user));
        } catch (ModelNotFoundException $e) {
            return apiResponse(errors: ["No query results for model"], statusCode: symfonyResponse::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return apiResponse(errors: ["Integrity constraint violation"], statusCode: symfonyResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->delete();

            return apiResponse(message: "User deleted successfully", statusCode: symfonyResponse::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return apiResponse(data: ["No query results for model"], statusCode: symfonyResponse::HTTP_NOT_FOUND);
        }
    }
}
