<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Enums\DeviceStatus;
use App\Models\Part;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeviceController extends Controller
{
    public function createNewDevice(): Response
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


    /* This function adds a part to a specific device.
     The part must be available (not already related to another device) and its last test must be passed */
    public function addPartToDevice($deviceSerialNumber, Request $request)
    {
        try {
            $partSerialNumber = $request->input('part_serial_number');
            $part = Part::where('Serial_Number', $partSerialNumber)->firstOrFail();

            if (!$part->isAvailable()) {
                return response()->json(['messagge' => 'Part is not available. You can\'t use it for device'], 400);
            }
            if (!$part->hasPassedLastTest()) {
                return response()->json(['messagge' => 'Part has not passed last test. You can\'t use it for device'], 400);
            }

            $part->Device_SN = $deviceSerialNumber;
            $part->save();

            return response()->json(['messagge' => 'Part succesfully addedd to device with serial number: ' .
                $deviceSerialNumber], 201);
        } catch (Exception $e) {
            throw new Exception('Error while adding the part ' . $part->Serial_Number .
                ' to the device '. $deviceSerialNumber . ' Error: ' . $e->getMessage());
        }
    }
}
