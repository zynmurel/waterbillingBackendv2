<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Consumer;
use App\Models\ServicePeriod;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        "consumer_id",
        "service_period_id",
        "due_date",
        "previous_bill",
        "previous_payment",
        "present_bill"
    ];

    protected $primaryKey = 'billing_id';
    
    static function getAllBillings()
    {
        $results = DB::table('billings')
            ->get();

        foreach ($results as $key => $row) {
            $results[$key]->due_date = date("F j, Y, g:i a", $row->due_date);
        }

        return $results;
    }

    /**
     * Create new billing data.
     * Also called from DatabaseSeeder class.
     */
    static function addNewBilling($row)
    {
        $row['consumer_id'] = Consumer::getConsumerIdBasedFromEmail($row['consumer']);
        $row['service_period_id'] = ServicePeriod::getServicePeridId($row['service_period']);
        $fields = app(Billing::class)->getFillable();
        $billing = array();
        foreach ($fields as $field) {
            $billing[$field] = $row[$field];
        }
        $billing['due_date'] = strtotime($billing['due_date']);
        $saved = Billing::create($billing);
        $msg = array();
        $success = false;
        $success = false;
        if ($saved) {
            $success = true;
        }

        return $success;
    }

}
