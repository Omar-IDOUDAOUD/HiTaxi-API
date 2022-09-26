<?php

namespace App\Http\Controllers;

use App\Models\User;
use Faker\Core\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Nette\Utils\Random;
use PhpParser\Node\Expr\Cast\String_;


class MediaController extends Controller
{
    static public function generateFileName($file)
    {
        $fullfilename = $file->getClientOriginalName();
        $filename = pathinfo($fullfilename, PATHINFO_FILENAME);
        $fileextention =  pathinfo($fullfilename, PATHINFO_EXTENSION);
        return $filename . '_' . Random::generate(8, '0-9a-z') . '.' . $fileextention;
    }
    static public function storeFile($file, $fullfilenametostore, $storepath)
    {
        $file->storeAs($storepath, $fullfilenametostore);
    }

    static protected function flight_cartimage($image_name)
    {
        if (Storage::disk('local')->exists(STORAGE_FLIGHTS_CARTS_IMAGES_PATH . '/' . $image_name)) :
            $path = Storage::disk('local')->path(STORAGE_FLIGHTS_CARTS_IMAGES_PATH . '/' . $image_name);
            $content = file_get_contents($path);
            return response($content)->withHeaders([
                'Content-type' => mime_content_type($path)
            ]);
        endif;
        return response(['message' => 'file not found']);
    }

    protected function user_avatarimage()
    {
        $image_name = auth()->user()->avatar_image;
        if ($image_name) :
            if (Storage::disk('local')->exists(STORAGE_USER_AVATAR_IMAGE_PATH . '/' . $image_name)) :
                $path = Storage::disk('local')->path(STORAGE_USER_AVATAR_IMAGE_PATH . '/' . $image_name);
                $content = file_get_contents($path);
                return response($content)->withHeaders([
                    'Content-type' => mime_content_type($path)
                ]);
            endif;
        endif;
        return response(['message' => 'file not found']);
    }
}