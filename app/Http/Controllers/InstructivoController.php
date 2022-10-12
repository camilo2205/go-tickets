<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Wink\WinkPost;

class InstructivoController extends Controller
{
    public function index()
    {
        $posts = WinkPost::all();
        return view('posts.index', compact('posts'));
    }

    public function show($slug)
    {
        $post = WinkPost::where('slug', $slug)
        ->first();
        return view('posts.show', compact('post'));
    }
}
