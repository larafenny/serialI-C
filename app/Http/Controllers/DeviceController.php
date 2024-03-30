<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Enums\DeviceStatus;
use Exception;

class DeviceController extends Controller
{
    public function createNewDevice(): string
    {
        try {
            $serialNumber = Device::generateDeviceSerialNumber();
            Device::create([
                'Serial_Number' => $serialNumber,
                'Status' => DeviceStatus::InLavorazione
            ]);

            return response()->json(['New device with serial number ' . $serialNumber . ' succesfully created.'], 201);
        } catch (Exception $e) {
            throw new Exception('Error during creation of new device. Error: ' . $e->getMessage());
        }
    }
}
