<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    use HasFactory;
    protected $table = "types";
    protected $fillable = [
        'name',
        'status'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
