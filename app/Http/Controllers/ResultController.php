<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function show($id)
    {
        $result = Auth::user()->results()->findOrFail($id);

        return view('results.show', compact('result')); 
    }
}
