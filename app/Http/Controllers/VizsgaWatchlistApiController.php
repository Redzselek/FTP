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

class VizsgaWatchlistApiController extends Controller
{

    function GetWatchlist(Request $request)
    {
        $watchlist = VizsgaWatchlist::where('user_id', Auth::id())->get();
        return response()->json($watchlist);
    }

    function AddWatchlist(Request $request, $showid)
    {
        $exists = VizsgaWatchlist::where('user_id', Auth::id())->where('show_id', $showid)->exists();

        if ($exists) {
            return response()->json(['message' => 'Show is already in your watchlist','status' => 'exists'], 409);
        }
        $watchlist = new VizsgaWatchlist();
        $watchlist->user_id = Auth::id();
        $watchlist->show_id = $showid;
        $watchlist->save();
        return response()->json(['message' => 'Show successfully added to your watchlist','status' => 'success']);
    }

    function RemoveWatchlist(Request $request, $showid)
    {
        VizsgaWatchlist::where('user_id', Auth::id())->where('show_id', $showid)->delete();
        return response()->json(['message' => 'Show successfully removed from your watchlist']);
    }
}