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
        "id",
        "first_name",
        "last_name",
        "middle_name",
        "phone",
        "gender",
        "birthday",
        "barangay",
        "purok",
        "household_no",
        "civil_status",
        "name_of_spouse",
        "usage_type",
        "first_reading",
        "serial_no",
        "brand",
        "date",
        "delinquent",
        "consumer_status",
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
