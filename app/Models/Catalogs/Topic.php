<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    protected $table = "topics";
    protected $fillable = [
        'name',
        'status'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
