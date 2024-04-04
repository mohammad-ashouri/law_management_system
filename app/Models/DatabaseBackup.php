<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatabaseBackup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'database_backups';
    protected $fillable = ['filename', 'creator'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    public function creatorInfo()
    {
        return $this->belongsTo(User::class, 'creator', 'id');
    }
}
