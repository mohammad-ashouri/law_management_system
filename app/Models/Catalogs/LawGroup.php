<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LawGroup extends Model
{
    use HasFactory;

    protected $table = "law_groups";
    protected $fillable = [
        'name',
        'status'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
