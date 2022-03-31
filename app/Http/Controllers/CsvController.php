<?php

namespace App\Http\Controllers;

use App\Models\Csv;
use Auth;
use Illuminate\Http\Request;

class CsvController extends Controller
{
    public function show($id)
    {
        $csv = Csv::withCount('results')->where('user_id', Auth::id())->findOrFail($id);

        $csv->update([
            'total_scraped_keywords' => $csv->results_count,
            'is_scraped' => $csv->total_keywords === $csv->results_count
        ]);

        $csv = $csv->fresh();

        return response()->json(['csv' => $csv], 200);
    }
}
