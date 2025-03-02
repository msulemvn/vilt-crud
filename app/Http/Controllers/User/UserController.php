<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Symfony\Component\HttpFoundation\Response as myResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

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
    public function store(Request $request)
    {
        try {

            $userData = ["name" => $request->name, "email" => $request->email, "password" => Hash::make("password")];
            $user = User::create($userData);

            return response()->json(["status" => myResponse::$statusTexts[myResponse::HTTP_CREATED], "message" => "User created successfully", "data" => new UserResource($user)], myResponse::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json(["status" => myResponse::$statusTexts[myResponse::HTTP_BAD_REQUEST], "message" => "Integrity constraint violation"], myResponse::HTTP_BAD_REQUEST);
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

            return response()->json(["status" => myResponse::$statusTexts[myResponse::HTTP_OK], "message" => "User updated successfully", "data" => new UserResource($user)]);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => myResponse::$statusTexts[myResponse::HTTP_NOT_FOUND], "message" => "No query results for model"], myResponse::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            return response()->json(["status" => myResponse::$statusTexts[myResponse::HTTP_BAD_REQUEST], "message" => "Integrity constraint violation"], myResponse::HTTP_BAD_REQUEST);
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

            return response()->json(["status" => myResponse::$statusTexts[myResponse::HTTP_NO_CONTENT], "message" => "User deleted successfully"], myResponse::HTTP_NO_CONTENT);
        } catch (ModelNotFoundException $e) {
            return response()->json(["status" => myResponse::$statusTexts[myResponse::HTTP_NOT_FOUND], "message" => "No query results for model"], myResponse::HTTP_NOT_FOUND);
        }
    }
}
