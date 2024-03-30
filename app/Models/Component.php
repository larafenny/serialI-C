<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID';
    protected $table = 'Component';
    public $timestamps = false;

    protected $fillable = [
        'ID',
        'Part_Number',
        'Revision',
        'Description'
    ];
}
