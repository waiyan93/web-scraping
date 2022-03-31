<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'keyword',
        'total_advertisers', 
        'total_links', 
        'search_summary', 
        'web_content', 
        'csv_id'
    ];

    public function csv()
    {
        return $this->belongsTo(Csv::class);
    }
}
