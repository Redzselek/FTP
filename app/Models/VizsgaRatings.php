<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Users;
use App\Models\VizsgaShows;

class VizsgaRatings extends Model
{
    protected $table = 'ratings';
    protected $primaryKey = 'id';
    public $timestamps = true;
    
    protected $fillable = [
        'rating',
        'user_id',
        'show_id',
    ];
    
    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    
    // Define the relationship with the Show model
    public function show()
    {
        return $this->belongsTo(VizsgaShows::class, 'show_id');
    }

    public static function averageRating($showId)
    {
        return self::where('show_id', $showId)->avg('rating');
    }
    
}