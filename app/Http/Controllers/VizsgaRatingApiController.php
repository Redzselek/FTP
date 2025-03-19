<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\VizsgaShows;
use App\Models\VizsgaRatings;
use App\Models\VizsgaComments;
use App\Models\VizsgaLevel;
use App\Models\VizsgaWatchlist;
use Exception;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cookie;

class VizsgaRatingApiController extends Controller{

    function GetRatings() {
        $ratings = VizsgaRatings::all();
        return response()->json($ratings); 
    }

    function AddRating(Request $request){
        $request->validate([
            'show_id' => 'required',
            'rating' => 'required   '
        ]);
        
        $rating = new VizsgaRatings();
        $rating->user_id = Auth::id();
        $rating->show_id = $request->show_id;
        $rating->rating = $request->rating;
        $rating->save();
        
        return response()->json(['success' => true, 'message' => 'Rating successfully added!']);
        
    }
    function asd(Request $request)
    {
        return Auth::id();
    }

    function DeleteRating(Request $request){
        $request->validate([
            'show_id' => 'required|exists:vizsga_shows,id'
        ]);
        $rating = VizsgaRatings::where('user_id', Auth::id())
            ->where('show_id', $request->show_id)
            ->first();
            
        if ($rating) {
            $rating->delete();
            return response()->json(['success' => true, 'message' => 'Rating successfully deleted!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Rating not found!']);
        }
    }
}