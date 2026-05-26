<?php

namespace App\Http\Controllers;

class PhotoController extends Controller
{
    public function index()
    {
        return view('camera');
    }
}
