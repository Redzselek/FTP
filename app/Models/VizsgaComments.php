<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VizsgaComments extends Model
{
    protected $table = 'comments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'id',
        'show_id',
        'user_id',
        'comment'
    ];
}