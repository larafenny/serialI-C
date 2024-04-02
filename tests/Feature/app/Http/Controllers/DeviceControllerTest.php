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

    public function testShouldCreateNewDevice2()
    {
        $response = $this->postJson('/device/create'); // Assumi che l'endpoint sia '/device/create'

        $response->assertStatus(201)
            ->assertJson([
                'message' => 'New device with serial number DEV99 successfully created.'
            ]);

        $this->assertDatabaseHas('devices', [
            'Serial_Number' => 'DEV99',
            'Status' => DeviceStatus::InLavorazione
        ]);
    }

    public function testShouldAddPartToDevice()
    {
        $deviceSerialNumber = 'DEV09';
        $request = new Request(['part_serial_number' => 'AB002']);

        $this->deviceController->addPartToDevice($deviceSerialNumber, $request);
    }

    public function testShouldGetDeviceInfo()
    {
        $deviceSerialNumber = 'DEV01';
        $this->deviceController->getDeviceInfo($deviceSerialNumber);

    }
}
