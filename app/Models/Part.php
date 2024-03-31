<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID';
    protected $table = 'Part';
    public $timestamps = false;

    protected $fillable = [
        'ID',
        'Serial_Number',
        'Component_ID',
        'Device_SN'
    ];

    public function device() {
        return $this->belongsTo(Device::class, 'Device_SN', 'Serial_Number');
    }

    public function hasPassedLastTest(): bool
    {
        $lastTest = $this->partTests()->latest('id')->first();
        if (!$lastTest || !$lastTest->Result) {
            return false;
        }

        return true;
    }

    public function isAvailable(): bool
    {
        if (!$this->Device_SN) {
            return true;
        }
        return false;
    }

    public function partTests() {
        return $this->hasMany(PartTest::class, 'Part_ID');
    }
}
