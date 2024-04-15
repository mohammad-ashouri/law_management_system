<?php

namespace App\Models;

use App\Models\Catalogs\Approver;
use App\Models\Catalogs\LawGroup;
use App\Models\Catalogs\Topic;
use App\Models\Catalogs\Type;
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
        'approver_id',
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
    public function group()
    {
        return $this->belongsTo(LawGroup::class,'group_id','id');
    }
    public function type()
    {
        return $this->belongsTo(Type::class,'type_id','id');
    }
    public function approver()
    {
        return $this->belongsTo(Approver::class,'approver_id','id');
    }
    public function topic()
    {
        return $this->belongsTo(Topic::class,'topic_id','id');
    }
    public function adderInfo()
    {
        return $this->belongsTo(User::class,'adder','id');
    }
}
