<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Reading extends Model
{
    use HasFactory;

    protected $fillable = [
      "reading_id",
      "reader_id",
      "consumerId",
      "reading",
      "date",
      "bill",
      "penalty",
      "total_reading",
      "date_paid",
      "due_date",
      "service_period"
        ];
    
        static function getAllReadings()
    {
        $results = DB::table('readings')
            ->get();

        return $results;
    }
        protected $primaryKey = 'reading_id';
}
