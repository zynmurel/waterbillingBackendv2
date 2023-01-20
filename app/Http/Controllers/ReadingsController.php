<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReadingRequest;
use App\Models\BarangayPurok;
use App\Models\Billing;
use App\Models\Consumer;
use App\Models\Payment;
use App\Models\Reading;
use App\Models\ServicePeriod;
use Carbon\Carbon;
use Dotenv\Store\File\Reader;
use Illuminate\Http\Request;

class ReadingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $readings =  Reading::all();

        return response()->json([
            "status"=>true,
            "newReading"=>$readings
        ],200); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreReadingRequest $request)
    {
        $reading = Reading::create($request->all());

        return response()->json([
            "status"=>true,
            "message"=> "Reading is added succesfully",
            "newReading"=>$reading,
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id customer id
     * @return \Illuminate\Http\Response
     */

    public function showByServicePeriod($month, $year)
    {
        $service_period_id = ServicePeriod::where("service_period", $year."-".$month)->pluck('service_period_id');
        $readings = Reading::where('service_period_id', $service_period_id[0])->get();
        foreach($readings as $read){
            $read["consumer"]= Consumer::where('consumer_id', $read["consumer_id"])->get()[0];
            $read["consumer_name"] = $read["consumer"]["first_name"]." ".$read["consumer"]["middle_name"]." ".$read["consumer"]["last_name"];
            $read["barangay"] = BarangayPurok::where("brgyprk_id", $read["consumer"]["brgyprk_id"])->pluck("barangay")[0];
            $read["purok"] = BarangayPurok::where("brgyprk_id", $read["consumer"]["brgyprk_id"])->pluck("purok")[0];
            $read["consumer_id"] =  str_pad($read["consumer_id"], 10, '0', STR_PAD_LEFT);
        }
        return response()->json([
            "status"=>true,
            "message"=> "Date is found",
            "newReading"=>$readings
        ],200);
    }

    public function readingBillingsPayments($id)
    {
        //$readings = Reading::getServicePeriodReadings($id);

        //return $readings;
        //return $this->sendResponse($readings, 'Readings retrieved successfully.');

        //GET ALL READINGS , BILLINGS (Decending)
        $latestReadingBilling = [[
            "billing_id"=> 1,
            "consumer_id"=> 27,
            "service_period"=> "2022-December",
            "due_date"=> 1645401600,
            "previous_bill"=> 114,
            "previous_payment"=> 206,
            "penalty"=> 39,
            "present_bill"=> 92,
            "created_at"=> "2023-01-17T16:41:14.000000Z",
            "updated_at"=> "2023-01-17T16:41:14.000000Z"
        ], 
        [
            "billing_id"=> 1,
            "consumer_id"=> 27,
            "service_period"=> "2022-November",
            "due_date"=> 1645401600,
            "previous_bill"=> 114,
            "previous_payment"=> 206,
            "penalty"=> 39,
            "present_bill"=> 92,
            "created_at"=> "2023-01-17T16:41:14.000000Z",
            "updated_at"=> "2023-01-17T16:41:14.000000Z"
        ]];
        return response()->json([
            "status"=>true,
            "message"=> "Consumer Bill Record is found",
            "billing"=>$latestReadingBilling,
        ],200);
    }

    public function inquire($id)
    {  $consumer = Consumer::where("consumer_id", $id)->get()[0];
        $consumer["barangay"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("barangay")[0];
        $consumer["purok"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("purok")[0];
        $consumer["consumer_name"] = $consumer["first_name"]." ".$consumer["middle_name"]." ".$consumer["last_name"];
        $reading = Reading::where("consumer_id", $id)->latest()->first();
        $consumer["consumer_id"] = str_pad($consumer["consumer_id"], 10, '0', STR_PAD_LEFT);
        $consumer["service_period"] = null;
        $consumer["reading"] = $reading;
        $consumer["billing"] = null;
        $consumer["payment"] = null;

        if($reading) {
            $billing = Billing::where("consumer_id", $consumer["consumer_id"])->where("service_period_id",$reading["service_period_id"])->get();
            $payment = Payment::where("consumer_id", $consumer["consumer_id"])->where("service_period_id",$reading["service_period_id"])->get();
            $service_period = ServicePeriod::where("service_period_id", $reading["service_period_id"])->pluck("service_period")[0];
            $consumer["service_period"] = $service_period;
            $consumer["billing"] = $billing;
            $consumer["payment"] = $payment;
        }
        
        $latestReadingBilling = [
            "consumer_id" => "0000000001",
            "consumer_name" => "Andrei Chatto",
            "barangay" => "Cantalid",
            "usage_type"=>"Residential",
            "purok" => 2,
            "service_period" => "2022-January",
            "prev_reading" => 10,
            "present_reading" => 21,
            "prev_bill" =>  10,
            "penalty" => 25,
            "present_bill" =>  5,
            "total_reading" => 4,
            "status"=>"Connected"
        ];
        return response()->json([
            "status"=>true,
            "message"=> "Inquire is found",
            "newReading"=>$latestReadingBilling,
            "id"=>$id,
            "billing"=>$consumer
        ],200);
    }
    
    public function meterReadings($id)
    {
        $meterReading = [
            [
                "reading_id" =>1,
                "consumer_id" => "0000000001",
                "consumer_name" => "Andrei Chatto",
                "barangay" => "Cantalid",
                "purok" => 2,
                "service_period" => "2022-January",
                "prev_reading" => 10,
                "present_reading" => 21
            ],
            [
                "reading_id" =>2,
                "consumer_id" => "0000000002",
                "consumer_name" => "Eric Maglajos",
                "barangay" => "Boctol",
                "purok" => 3,
                "service_period" => "2022-January",
                "prev_reading" => 15,
                "present_reading" => 26
            ]
        ];
        return response()->json([
            "status"=>true,
            "message"=> "Meter Readings is found",
            "newReading"=>$meterReading,
        ],200);
    }

    public function reports($id)
    {
        $reports =  [
                "report_id"=>2,
                "service_period"=>"2022-February",
                "totalConsumers"=>1000,
                "totalDelinquent"=>20,
                "totalCollection"=>40204
        ];
        
        return response()->json([
            "status"=>true,
            "message"=> "Reports is found",
            "newReading"=>$reports,
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
