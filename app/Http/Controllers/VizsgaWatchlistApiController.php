<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VizsgaWatchlist;

class VizsgaWatchlistApiController extends Controller
{

    function GetWatchlist(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        $watchlist = VizsgaWatchlist::where('watchlist.user_id', $user->id)
            ->join('shows', 'watchlist.show_id', '=', 'shows.id')
            ->select('watchlist.*', 'shows.title', 'shows.image_url')
            ->get();
            
        if ($watchlist->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your watchlist is empty'], 404);
        }
        
        return response()->json(['success' => true, 'data' => $watchlist]);
    }

    function AddWatchlist(Request $request, $showid)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        $exists = VizsgaWatchlist::where('user_id', $user->id)
            ->where('show_id', $showid)
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Show is already in your watchlist', 'status' => 'exists'], 409);
        }
        
        $watchlist = new VizsgaWatchlist();
        $watchlist->user_id = $user->id;
        $watchlist->show_id = $showid;
        $watchlist->save();
        
        return response()->json(['success' => true, 'message' => 'Show successfully added to your watchlist', 'status' => 'success']);
    }

    function RemoveWatchlist(Request $request, $showid)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        $watchlistItem = VizsgaWatchlist::where('user_id', $user->id)
            ->where('show_id', $showid)
            ->first();
            
        if (!$watchlistItem) {
            return response()->json(['success' => false, 'message' => 'Show not found in your watchlist'], 404);
        }
        
        $watchlistItem->delete();
        
        return response()->json(['success' => true, 'message' => 'Show successfully removed from your watchlist']);
    }

    function GetShow(Request $request, $showid) {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }
        
        $show = VizsgaWatchlist::where('user_id', $user->id)
            ->where('show_id', $showid)
            ->first();
            
        if (!$show) {
            return response()->json(['success' => false, 'message' => 'Show not found in your watchlist'], 404);
        }
        
        return response()->json(['success' => true, 'data' => $show]);
    }
}