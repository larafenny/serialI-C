<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartTest extends Model
{
    use HasFactory;

    protected $primaryKey = 'ID';

    protected $table = 'Part_Test';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'Result',
        'Part_ID'
    ];

    public function part() {
        return $this->belongsTo(Part::class, 'Part_ID');
    }
}
