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
        $row['service_period_id'] = ServicePeriod::getServicePeriodId($row['service_period']);
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

    static function getServicePeriodReadings($service_period_id)
    {
        // Get saved license settings for the customer, update defaultValue if available
        $fields = [
            'readings.previous_reading',
            'readings.present_reading',
            'readings.reading_date',
            'consumers.first_name',
            'consumers.middle_name', 
            'consumers.last_name',
            'consumers.delinquent',
            'barangay',
            'purok',
        ];
        $results = DB::table('readings')
            ->select($fields)
            ->leftJoin('consumers', 'readings.consumer_id', '=', 'consumers.consumer_id')
            ->leftJoin('barangay_puroks', 'consumers.brgyprk_id', '=', 'barangay_puroks.brgyprk_id')
            ->where('yh_customers.service_period_id', '=', $service_period_id);
            
        return $results;

    }

}
