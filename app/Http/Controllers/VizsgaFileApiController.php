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
            'file' => 'required|image|mimes:jpg,jpeg,png,webp|max:15360'
        ]);
        
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        
        $data = $request->all();
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,webp|max:15360',
        ]);
        if ($request->file()) {
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $destinationPath = public_path('uploads/vizsgaremek/');
            $request->file('file')->move($destinationPath, $fileName);

            $upload = new VizsgaShows();
            $upload->show_title = $data['title'];
            $upload->show_description = $data['description'];
            $upload->show_category = $data['category'];
            $upload->show_type = $data['type'];
            $upload->show_season = $data['season'];
            $upload->show_episode = $data['episode'];
            $upload->show_url = $fileName;
            $upload->user_id = $user->id;
            // $user->rating = null;

            $upload->save();
            return back()
                ->with('success', 'Successfully uploaded.');
        }
        return back()->withErrors('Failed to upload.');
    }

    function UpdateShow(Request $request) {
        $request->validate([
            'id' => 'required|exists:teliprojekt_musorok,id',
            'title' => 'required|string|max:200',
            'description' => 'required|string',
            'category' => 'required|string|max:20',
            'type' => 'required|in:film,sorozat',
            'season' => 'required|integer|nullable',
            'episode' => 'required|integer|nullable',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:15360',
        ]);

        $show = VizsgaShows::where('id', $request->id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $show->title = $request->title;
        $show->description = $request->description;
        $show->category = $request->category;
        $show->type = $request->type;
        $show->season = $request->season;
        $show->episode = $request->episode;
        $show->file = $request->file;

        if ($request->hasFile('file')) {
            // Kép törlés
            $oldImagePath = public_path('uploads/vizsgaremek/' . $show->img_url);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Új kép feltöltés
            $fileName = time() . '_' . $request->file->getClientOriginalName();
            $request->file('file')->move(public_path('uploads/vizsgaremek/'), $fileName);
            $show->kep_url = $fileName;
        }

        $show->save();

        return response()->json(['success' => true, 'message' => 'Show successfully updated!']);
    }

    function DeleteShow(Request $request, $id) {
        $user = $request->user();
        $show = VizsgaShows::find($id);
        if ($show->user_id == $user->id) {
            $show->delete();
            return back()
                ->with('success', 'Succesfully deleted.');
        }
        return back()->withErrors('Failed to delete.');
    }
}