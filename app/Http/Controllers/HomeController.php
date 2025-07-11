<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke() : View
    {
        return view('home', [

            'posts' => Post::query()
                ->latest('published_at')
                ->published()
                ->limit(12)
                ->get(),

        ]);
    }
}
