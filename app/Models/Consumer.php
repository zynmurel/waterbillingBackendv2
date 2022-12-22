<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Consumer extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_key",
        "first_name",
        "last_name",
        "middle_name",
        "gender",
        "birthday",
        "phone",
        "civil_status",
        "name_of_spouse",
        "brgyprk_id",
        "household_no",
        "first_reading",
        "usage_type",
        "serial_no",
        "brand",
        "status",
        "delinquent",
        "registered_at"
    ];

    static function getAllConsumers()
    {
        $results = DB::table('consumers')
            ->get();

        return $results;
    }
    protected $primaryKey = 'id';
}
