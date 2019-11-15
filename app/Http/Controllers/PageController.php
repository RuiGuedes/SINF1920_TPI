<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Auction;
use App\Game;
use App\Category;

class PageController extends Controller
{
    public function index() {
        return view('welcome');
    }
}
