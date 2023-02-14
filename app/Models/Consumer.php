<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\BarangayPurok;

class Consumer extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
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

    protected $primaryKey = 'user_id';

    static function getAllConsumers()
    {
        $results = DB::table('consumers')
            ->get();
        foreach ($results as $key => $row) {
            $results[$key]->user_type = User::where("user_id", $results[$key]->user_id)->pluck("user_type")[0];
            $results[$key]->consumer_id = str_pad($row->consumer_id, 6, '0', STR_PAD_LEFT);
            $bgry_prk = BarangayPurok::getBrgyPrkData($row->brgyprk_id);
            $results[$key]->barangay = $bgry_prk['barangay'];
            $results[$key]->purok = $bgry_prk['purok'];
        }    
        
        return $results;
    }

    /**
     * Create new user and consumer data.
     * Also called from DatabaseSeeder class.
     */
    static function addNewConsumer($row)
    {
        # Populate users table
        $row['email'] = preg_replace('/\s+/', '', strtolower($row['email']));
        $fields = app(User::class)->getFillable();
        $user = array();
        foreach ($fields as $field) {
            $user[$field] = $row[$field];
        }
        $user['password'] = Hash::make($user['password']);
        $saved = User::create($user);
        $msg = array();
        $success['user'] = false;
        $success['consumer'] = false;
        if ($saved) {
            $success['user'] = true;
            # Populate consumers table
            $row['user_id'] = User::getUserKey($user['email']);
            $row['brgyprk_id'] = BarangayPurok::getBrgyPrkId($row['barangay'], $row['purok']);
            $row['birthday'] = strtotime($row['birthday']);
            $fields = app(Consumer::class)->getFillable();
            $consumer = array();
            foreach ($fields as $field) {
                $consumer[$field] = $row[$field];
            }
            $consumer['delinquent'] = $consumer['delinquent'] == 'TRUE' ? true : false;
            $saved = Consumer::create($consumer);
            if ($saved) {
                $success['consumer'] = true;
            }
        }

        return $success;
    }

    static function getConsumerIdBasedFromEmail($email)
    {
        $key = 0;
        $result = DB::table('users')
            ->select(['consumer_id'])
            ->leftJoin('consumers', 'users.user_id', '=', 'consumers.user_id')
            ->where('email', '=', $email)
            ->first();

        if ($result) {
            $key = $result->consumer_id;
        }
        
        return $key;
    }



}
