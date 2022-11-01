<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
class AppSettingsController extends Controller
{

    /*
     * Generate firebase js
     */
    public function generateFirebase()
    {
        return response()->view('vendor.notifications.gen_firebase')->header('Content-Type', 'application/javascript');
    }
}
