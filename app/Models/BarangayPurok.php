<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BarangayPurok extends Model
{
    use HasFactory;

    protected $fillable= [
        'brgyprk_id',
        'barangay',
        'purok'
    ];

    static function getAllBarangayPuroks()
    {
        $results = DB::table('barangay_puroks')
            ->get();

        $list = array();
        if(count($results)) {
            foreach ($results as $key => $item){
                $list[$item->barangay][] = $item->purok;
            }
        }

        return $list;
    }

    static function getBrgyPrkId($brgy, $prk)
    {
        $key = 0;
        $result = DB::table('barangay_puroks')
            ->where('barangay', '=', $brgy)
            ->where('purok', '=', $prk)
            ->first();

        if ($result) {
            $key = $result->brgyprk_id;
        }
        
        return $key;
    }

}
