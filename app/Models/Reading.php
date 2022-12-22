<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Consumer;
use App\Models\ServicePeriod;

class Reading extends Model
{
    use HasFactory;

    protected $fillable = [
        "reader_id",
        "consumer_id",
        "service_period_id",
        "previous_reading",
        "present_reading",
        "reading_date"
    ];

    protected $primaryKey = 'reading_id';
    
    static function getAllReadings()
    {
        $results = DB::table('readings')
            ->get();

        foreach ($results as $key => $row) {
            $results[$key]->reading_date = date("F j, Y, g:i a", $row->reading_date);
        }

        return $results;
    }

    /**
     * Create new reading data.
     * Also called from DatabaseSeeder class.
     */
    static function addNewReading($row)
    {
        $row['reader_id'] = Consumer::getConsumerIdBasedFromEmail($row['reader']);
        $row['consumer_id'] = Consumer::getConsumerIdBasedFromEmail($row['consumer']);
        $row['service_period_id'] = ServicePeriod::getServicePeridId($row['service_period']);
        $fields = app(Reading::class)->getFillable();
        $reading = array();
        foreach ($fields as $field) {
            $reading[$field] = $row[$field];
        }
        $reading['reading_date'] = strtotime($reading['reading_date']);
        $saved = Reading::create($reading);
        $msg = array();
        $success = false;
        $success = false;
        if ($saved) {
            $success = true;
        }

        return $success;
    }

}
