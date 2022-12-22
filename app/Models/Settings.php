<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        "settings_id",
        "setting_type",
        "setting_value"
    ];

    protected $primaryKey = 'settings_id';

    static function getSettings()
    {
        $results = DB::table('settings')
            ->get();
        
        if ($result) {
            foreach ($results as $key => $row) {
                $data[$row->setting_type] = $row->setting_value;
            }
            if (isset($data['cubic_rates'])) {
                $data['cubic_rates'] = json_decode($data['cubic_rates'], true);
            }
        }
        
        return $results;
    }
}
