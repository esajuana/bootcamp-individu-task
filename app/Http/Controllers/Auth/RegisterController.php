<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator; 

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048', 
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Simpan gambar jika diunggah
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('photos');
        }

        $input = $request->only('name', 'email', 'password');
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

         // Jika ada gambar yang diunggah, tambahkan gambar ke pengguna baru
         if (isset($photoPath)) {
            $user->addImage($photoPath);
        }

         $responseData = [
            'success' => true,
            'data' => $user,
        ];

        if (isset($photoPath)) {
            $responseData['photo_url'] = asset('storage/' . $photoPath);
        }

        return response()->json($responseData, 201);
    }

}
