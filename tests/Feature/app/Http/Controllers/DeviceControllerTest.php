<?php

namespace Tests\Feature\app\Http\Controllers;

use App\Http\Controllers\DeviceController;
use App\Models\Device;
use App\Models\Enums\DeviceStatus;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DeviceControllerTest extends TestCase
{
    use RefreshDatabase;
    protected DeviceController $deviceController;

    public function setUp():void {
        parent::setUp();
        $this->deviceController = new DeviceController();

        Schema::dropIfExists('Device');
        Schema::create('Device', function(Blueprint $table) {
            $table->string('Serial_Number')->primary();
            $table->integer('Status');
        });
    }

    public function testShouldCreateNewDevice()
    {
        $newDeviceResponse = $this->deviceController->createNewDevice();
        $newDevice = Device::where('Serial_Number', 'DEV00')->first();

        $this->assertEquals($newDeviceResponse->getStatusCode(), 201);
        $this->assertEquals(
            json_decode($newDeviceResponse->getContent(), true),
            ['message' => 'New device with serial number DEV00 succesfully created.']);
        $this->assertEquals($newDevice->Serial_Number, 'DEV00');
        $this->assertEquals($newDevice->Status, 0);
    }

    public function testShouldCreateNewDeviceThrowApi()
    {
       $response = $this->post('/api/device/create');

        $this->assertDatabaseHas('Device', [
            'Serial_Number' => 'DEV00',
            'Status' => 0
        ]);
        $this->assertEquals($response->getStatusCode(), 201);
        $this->assertEquals(
            json_decode($response->getContent(), true),
            ['message' => 'New device with serial number DEV00 succesfully created.']);
    }
}
