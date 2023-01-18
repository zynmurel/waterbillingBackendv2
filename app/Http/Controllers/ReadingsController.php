<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReadingRequest;
use App\Models\Reading;
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
    public function readingBillingsPayments($id)
    {
        //$readings = Reading::getServicePeriodReadings($id);

        //return $readings;
        //return $this->sendResponse($readings, 'Readings retrieved successfully.');

        //GET ALL READINGS , BILLINGS (Decending)
        $latestReadingBilling = [[
            "consumer_id"=> "0000000001",
            "bill"=> 18, 
            "penalty" => 0,
            "reader_id" => 12,
            "reading" =>  10,
            "reading_id" =>  5,
            "service_period" => "2022-December",
            "total_reading" => 4
        ], 
        [
            "consumer_id"=> "0000000001",
            "bill"=> 18, 
            "penalty" => 12,
            "reader_id" => 12,
            "reading" =>  10,
            "reading_id" =>  4,
            "service_period" => "2022-November",
            "total_reading" => 4
        ]];
        return response()->json([
            "status"=>true,
            "message"=> "Consumer Bill Record is found",
            "newReading"=>$latestReadingBilling,
        ],200);
    }

    public function inquire($id)
    {
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
            "id"=>$id
        ],200);
    }
    
    public function meterReadings($id)
    {
        $latestReadingBilling = [
            ['yiyle' => "Maui",'name' => "akdn"],
            ['tiew' => "Huhu", 'name' => "Maui"]
        ];
        return response()->json([
            "status"=>true,
            "message"=> "Meter Readings is found",
            "newReading"=>$latestReadingBilling,
        ],200);
    }

    public function reports($id)
    {
        $latestReadingBilling = [
            ['yiyle' => "Maui",'name' => "akdn"],
            ['tiew' => "Huhu", 'name' => "Maui"]
        ];
        return response()->json([
            "status"=>true,
            "message"=> "Reports is found",
            "newReading"=>$latestReadingBilling,
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
