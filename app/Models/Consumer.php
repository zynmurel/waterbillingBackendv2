<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\BarangayPurok;

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

    protected $primaryKey = 'id';

    static function getAllConsumers()
    {
        $results = DB::table('consumers')
            ->get();
        foreach ($results as $key => $row) {
            $results[$key]->consumer_id = str_pad($row->consumer_id, 10, '0', STR_PAD_LEFT);
            $bgry_prk = BarangayPurok::getBrgyPrkData($row->brgyprk_id);
            $results[$key]->barangay = $bgry_prk['barangay'];
            $results[$key]->purok = $bgry_prk['purok'];
        }    
        
        return $results;
    }
}
