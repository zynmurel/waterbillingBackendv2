<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Billing;
use App\Models\Consumer;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function storePayment(StorePaymentRequest $request, $id){

        $payment = [
            "cashier_id" => $request->cashier_id,
            "consumer_id" => $request->consumer_id,
            "service_period_id" => $request->service_period_id,
            "date_paid" => $request->date_paid,
            "amount_paid" => $request->amount_paid
        ];
        $createdPayment = Payment::create($payment);
        $consumer = Consumer::where("consumer_id", $id)->update(["delinquent"=>0]);
        $previous_billing = Billing::where("consumer_id", $id)->where("service_period_id", $request->service_period_id)->pluck("previous_payment")[0];
        $number = collect([ floatval($previous_billing), floatval($request->amount_paid)]);
        $sum =$number->sum();
        $billing = Billing::where("consumer_id", $id)->where("service_period_id", $request->service_period_id)->update(["previous_payment"=>$sum]);

        return response()->json([
            "status"=>true,
            "message"=> "stored succesfully",
            "payment"=> $createdPayment,
            "consumer"=>$consumer,
            "billing"=>$sum,
            "prev"=>$previous_billing,
            "pres"=>$request->amount_paid
        ],200);
    }
}
