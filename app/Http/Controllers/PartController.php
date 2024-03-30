<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PartController extends Controller
{

    /* This function adds a part to a specific device.
     The part must be available (not already related to another device) and its last test must be passed */
    public function addPartToDevice($deviceSerialNumber, Request $request)
    {
    }
}
