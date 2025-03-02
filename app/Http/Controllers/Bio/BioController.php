<?php

namespace App\Http\Controllers\Bio;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $bio = Auth::user();
        return Inertia::render('Bio', compact('bio'));
    }
}
