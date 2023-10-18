<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Law extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "laws";
    protected $fillable = [
        'name',
        'status'
    ];
    protected $hidden = [
        'adder',
        'added_date',
        'editor',
        'edited_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
