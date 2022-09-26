<?php

namespace App\Http\Controllers;

use App\Models\User;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Random;

class AuthController extends Controller
{

    private const REGESTER_RULES =  [
        'full_name' => 'required | string | max:255',
        'email' => 'required | string | email | max:255 | unique:users',
        'password' => 'required | string | min:8 | confirmed',
        'role' => 'required | string',
        'tel' => 'required | string | min:8',
        'avatar_image' => ' image|nullable|max:5999', 
        'traveles_type' => 'required | string', 
        'typical_place_one' => 'nullable | string', 
        'typical_place_two' => 'nullable  | string',  
    ];
    private const LOGIN_RULES =  [
        'email' => 'required | string | email ',
        'password' => 'required | string | min:8'
    ];
    private const UPDATE_RULES =  [
        'full_name' => 'required | string | max:255',
        'email' => 'required | string | email | max:255 ',
        'tel' => 'required | string | min:8',
        'avatar_image' => 'image|nullable|max:5999',
        'traveles_type' => 'required | string', 
        'typical_place_one' => 'nullable | string', 
        'typical_place_two' => 'nullable  | string',  
    ];

    protected function regester(Request $req)
    {
        $valid = $req->validate(self::REGESTER_RULES);


        $avatarimagefile = $req->file('avatar_image');
        $avatarimagefilename = $avatarimagefile ?  MediaController::generateFileName($avatarimagefile) : null;

        $valid['password'] = Hash::make($valid['password']);
        $valid['avatar_image'] = $avatarimagefilename;

        try {
            $user = User::create($valid);
            if ($req->hasFile('avatar_image')) :
                MediaController::storeFile($avatarimagefile, $avatarimagefilename, STORAGE_USER_AVATAR_IMAGE_PATH);
            endif;
        } catch (\Throwable $th) {
            return response([
                'message' => 'fail'
            ]);
        }
        $token = $user->createToken('userToken')->plainTextToken;

        return response([
            'message' => 'success',
            'user' => $user,
            'token' => $token
        ]);
    }
    protected function login(Request $req)
    {  
        $valid = $req->validate(self::LOGIN_RULES);
        $user = User::where('email', $valid['email'])->first();
        $pass = Hash::check($valid['password'], $user->password ?? null);
        if (!$user || !$pass) :
            return response(['message' => 'email or password incorrect']);
        else :
            $token = $user->createToken('userToken')->plainTextToken; ///---
            return response()->json([
                'message' => 'success',
                'user' => $user,
                'token' => $token
            ]);
        endif;
    }
    protected function logout()
    {
        auth()->user()->tokens()->delete();
        return response([
            'message' => 'success'
        ]);
    }


    protected function destroyAccount($id = null)
    {
        $id = auth()->user()->id;
        $this->logout();
        $user = User::find($id);
        //delete avatar image
            Storage::delete(STORAGE_USER_AVATAR_IMAGE_PATH . '/' . $user->avatar_image);

        //delete db row
        $user->delete();
        return response([
            'message' => 'success',
        ]);
    }

    protected function update(Request $req)
    {
        $valid = $req->validate(self::UPDATE_RULES);
        $user = User::where("id", auth()->user()->id);
        $avatarimagefile = $req->file('avatar_image');
        $newavatarimagefilename = $avatarimagefile ? MediaController::generateFileName($avatarimagefile) : null;
        $oldavatarimagefilename =  $user->get('avatar_image')->first()->avatar_image;
        $valid['avatar_image'] = $newavatarimagefilename; 
        try {
            // $user->full_name = $valid['full_name'];
            // $user->email = $valid['email'];
            // $user->tel = $valid['tel'];
            // $user->role = $valid['role'];
            // $user->avatar_image = $avatarimagefilename;
            // $user->save();
            $user->update($valid);
            Storage::delete(STORAGE_USER_AVATAR_IMAGE_PATH . '/' . $oldavatarimagefilename);
            if ($req->hasFile('avatar_image')) :
                MediaController::storeFile($avatarimagefile, $newavatarimagefilename, STORAGE_USER_AVATAR_IMAGE_PATH);
            endif;
        } catch (\Throwable $th) {
            return response([
                'message' => 'fail'
            ]);
        }
        return response([
            'message' => 'success',
            'user' => $user->get()->first()
        ]);
    }
}       