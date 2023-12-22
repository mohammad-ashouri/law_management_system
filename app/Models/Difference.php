<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Difference extends Model
{
    use HasFactory;
    protected $table = "differences";
    protected $fillable = [
        'law_id',
        'type',
        'old',
        'new',
        'editor',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    public function lawInfo()
    {
        return $this->belongsTo(Law::class,'law_id','id');
    }
    public function editorInfo()
    {
        return $this->belongsTo(User::class,'editor','id');
    }
}
