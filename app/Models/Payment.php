<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\Models\Consumer;
use App\Models\ServicePeriod;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        "cashier_id",
        "consumer_id",
        "date_paid",
        "amount_paid"
    ];

    protected $primaryKey = 'payment_id';
    
    static function getAllPayments()
    {
        $results = DB::table('payments')
            ->get();

        foreach ($results as $key => $row) {
            $results[$key]->date_paid = date("F j, Y, g:i a", $row->date_paid);
        }

        return $results;
    }

    /**
     * Create new payment data.
     * Also called from DatabaseSeeder class.
     */
    static function addNewPayment($row)
    {
        $row['cashier_id'] = Consumer::getConsumerIdBasedFromEmail($row['cashier']);
        $row['consumer_id'] = Consumer::getConsumerIdBasedFromEmail($row['consumer']);
        $fields = app(Payment::class)->getFillable();
        $payment = array();
        foreach ($fields as $field) {
            $payment[$field] = $row[$field];
        }
        $payment['date_paid'] = strtotime($payment['date_paid']);
        $saved = Payment::create($payment);
        $msg = array();
        $success = false;
        $success = false;
        if ($saved) {
            $success = true;
        }

        return $success;
    }

}
