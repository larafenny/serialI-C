<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Enums\DeviceStatus;
use App\Models\Part;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function createNewDevice()
    {
        try {
            $serialNumber = Device::generateDeviceSerialNumber();
            Device::create([
                'Serial_Number' => $serialNumber,
                'Status' => DeviceStatus::InLavorazione
            ]);

            return response()->json([
                'message' => 'New device with serial number ' . $serialNumber . ' succesfully created.'
            ],
                201);
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

    public function getDeviceInfo($deviceSerialNumber)
    {
        try {
            $device = Device::where('Serial_Number', $deviceSerialNumber)->firstOrFail();
            $parts = $device->parts()->get();

            $partsInfo = $parts->map(function($part) {
                return [
                    'ID' => $part->ID,
                    'Serial_number' => $part->Serial_Number,
                    'Component_ID' => $part->Component_ID,
                    'Device_SN' => $part->Device_SN,
                ];
            });

            return response()->json([
                'Device_Serial_Number' => $device->Serial_Number,
                'Device_Status' => $device->Status,
                'Parts_Info' => $partsInfo
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Device not found'], 404);
        } catch (Exception $e) {
            throw new Exception('Error retrieving info from device with serial number ' . $deviceSerialNumber .
                ' Error: ' . $e->getMessage());
        }
    }
}
