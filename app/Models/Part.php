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
}
