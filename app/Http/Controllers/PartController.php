<?php

namespace App\Http\Controllers;

use App\Models\Part;

class PartController extends Controller
{
    public function removePartFromDevice($partSerialNumber)
    {
        try {
            $part = Part::where('Serial_Number', $partSerialNumber)->firstOrFail();
            $part->Device_SN = null;
            $part->save();

            return response()->json(['messagge' => 'Part removed successfully from device'], 201);
        } catch (Exception $e) {
            throw new Exception('Error removing part from device. Part serial number: ' . $part->Serial_Number .
                ' Error: ' . $e->getMessage());
        }
    }
}
