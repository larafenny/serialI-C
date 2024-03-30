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
}
