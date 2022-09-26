<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;


define('STORAGE_USER_AVATAR_IMAGE_PATH','users/avatar_images');
define('STORAGE_FLIGHTS_CARTS_IMAGES_PATH','public/flights/carts_images');

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
