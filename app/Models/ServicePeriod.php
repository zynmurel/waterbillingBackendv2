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
        "bill_generated"
    ];

    protected $primaryKey = 'service_period_id';

    static function getServicePeridId($service_period)
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
}
