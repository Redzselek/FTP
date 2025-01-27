<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BingoController extends Controller
{
    public function bingo() {
        return view('bingo/bingo');
    }
}