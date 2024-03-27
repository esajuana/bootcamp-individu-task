<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Traits\JsonResponseTrait;


class UserController extends Controller
{
    use JsonResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
        return $this->showResponse($user->toArray());    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::with('image')->find($id);

        if ($user) {
            return UserResource::make($user)->withDetail();
        } else {
            return response()->json(['message' => 'Category tidak ditemukan'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $validated = $request->validated();
        $user = User::find($id);
        $validated['password'] = bcrypt($validated['password']);

        if (!$user) {
            return response()->json(['message' => 'user tidak ditemukan'], 404);
        }

        $user->update($validated);
        $user->updateImage($request);

        return response()->json(['message' => 'Category berhasil diupdate', 'user' => $user]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
        //
    }
}
