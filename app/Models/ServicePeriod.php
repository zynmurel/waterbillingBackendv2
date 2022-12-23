<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServicePeriod extends Model
{
    use HasFactory;

    protected $fillable = [
        "service_period_id",
        "service_period",
        "is_current"
    ];

    protected $primaryKey = 'service_period_id';

    static function getServicePeriodId($service_period)
    {
        $key = 0;
        $result = DB::table('service_periods')
            ->where('service_period', '=', $service_period)
            ->first();

        if ($result) {
            $key = $result->service_period_id;
        }
        
        return $key;
    }

    static function getAllServicePeriods()
    {
        $results = DB::table('service_periods')
            ->orderBy('service_period_id', 'DESC')
            ->get();

        $list = array();
        if(count($results)) {
            foreach ($results as $key => $item){
                $list[$item->service_period_id] = $item->service_period;
            }
        }

        return $list;
    }
}
