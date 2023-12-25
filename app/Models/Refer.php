<?php

namespace App\Models;

use App\Models\Catalogs\LawGroup;
use App\Models\Catalogs\ReferType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Refer extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "refers";
    protected $fillable = [
        'law_from',
        'law_to',
        'type',
        'adder',
    ];
    protected $hidden = [
        'adder',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function typeInfo()
    {
        return $this->belongsTo(ReferType::class,'type','id');
    }
    public function lawInfo()
    {
        return $this->belongsTo(Law::class,'law_from','id');
    }
}
