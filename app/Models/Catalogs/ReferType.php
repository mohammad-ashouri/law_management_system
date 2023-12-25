<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferType extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "refer_types";
    protected $fillable = [
        'name',
        'status'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
