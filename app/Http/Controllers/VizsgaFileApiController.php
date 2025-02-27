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

class VizsgaFileApiController extends Controller{
    function UploadShow(Request $request) {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        
        $data = $request->all();
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:2048',
        ]);
        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $destinationPath = public_path('uploads');
            $request->file('file')->move($destinationPath, $fileName);

            $upload = new VizsgaShows();
            $upload->img_title = $data['title'];
            $upload->img_description = $data['description'];
            $upload->img_category = $data['category'];
            $upload->img_type = $data['type'];
            $upload->img_season = $data['season'];
            $upload->img_episode = $data['episode'];
            $upload->img_url = $fileName;
            $upload->user_id = $user->id;
            $user->rating = null;

            $upload->save();
            return back()
                ->with('success', 'Fájl sikeresen feltöltve.');
        }
        return back()->withErrors('A fájl feltöltése nem sikerült.');
    }
}