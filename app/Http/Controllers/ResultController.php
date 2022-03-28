<?php

namespace App\Http\Controllers;

use App\Models\Result;
use Illuminate\Support\Facades\Auth;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::query()
            ->where('user_id', Auth::id())
            ->paginate(10);
        
        return view('results.index', compact('results'));
    }

    public function show($id)
    {
        $result = Result::query()
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return view('results.show', compact('result')); 
    }

    public function search()
    {
        $results = Result::query()
            ->where('user_id', Auth::id())
            ->when($keyword = request('keyword'), function($query, $keyword) {
                $query->where('keyword', 'like', "{$keyword}%");
            })
            ->paginate(10);
        
        $results->appends(['keyword' => request('keyword')]);
            
        return view('results.search', compact('results'));
    }
}
