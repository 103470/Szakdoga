<?php

namespace App\Services;

use App\Http\Controllers\BrandController;
use App\Http\Controllers\RareBrandController;

class BrandControllerDispatcher
{
    public static function dispatch($data, $method)
    {
        $controllerClass = $data['isRare'] 
            ? RareBrandController::class
            : BrandController::class;

        return app($controllerClass)->$method($data);

    }
}