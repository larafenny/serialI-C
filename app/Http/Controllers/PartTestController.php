<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\PartTest;
use Illuminate\Http\Request;

class PartTestController extends Controller
{
    public function addTestForPart($partSerialNumber, Request $request) {
        try {
            $validatedData = $request->validate([
                'result' => 'required|boolean'
            ]);

            $part = Part::where('Serial_Number', $partSerialNumber)->firstOrFail();
            $testResult = $validatedData['result'];

            PartTest::create([
                'Result' => $testResult,
                'Part_ID' => $part->ID
            ]);

            return response()->json(['messagge' => 'Test correctly added to part'], 201);
        } catch (Exception $e) {
            throw new Exception('Error while adding the test for part ' . $part->Serial_Number .
                ' Error: ' . $e->getMessage());
        }
    }
}
