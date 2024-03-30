<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;
    protected $table = 'Device';
    protected $primaryKey = 'ID';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'Serial_Number',
        'Status'
    ];

    // This function returns an incremental serial number in format and range 'DEV00' - 'DEV99'
    public static function generateDeviceSerialNumber(): string
    {
        try {
            $lastSerialNumberDevice = self::latest('Serial_Number')->first();
            if(empty($lastSerialNumberDevice)) {
                return 'DEV00';
            }

            $lastNumber = (int)(substr($lastSerialNumberDevice->Serial_Number, -2));

            if($lastNumber >= 99) {
                throw new Exception('Reached max number of available serial numbers.
                Please contact system administrator');
            }

            return sprintf('DEV%02d',$lastNumber + 1);

        } catch (Exception $e) {
            throw new Exception(
                'Problem generating serial number for the new device. Error: ' .  $e->getMessage()
            );
        }
    }
}
