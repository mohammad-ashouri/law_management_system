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
        'law_code',
        'session_code',
        'group_id',
        'topic_id',
        'title',
        'body',
        'approval_date',
        'issue_date',
        'promulgation_date',
        'keywords',
        'files_src',
        'adder',
    ];
    protected $hidden = [
        'adder',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
