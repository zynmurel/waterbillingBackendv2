<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBillingReadingRequest;
use App\Http\Requests\StoreReadingRequest;
use App\Models\BarangayPurok;
use App\Models\Billing;
use App\Models\Consumer;
use App\Models\Payment;
use App\Models\Reading;
use App\Models\ServicePeriod;
use App\Models\Settings;
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
            $read["consumer_id"] =  str_pad($read["consumer_id"], 6, '0', STR_PAD_LEFT);
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

    public function inquirecomment($id)
    {  $consumer = Consumer::where("consumer_id", $id)->get()[0];
        $consumer["barangay"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("barangay")[0];
        $consumer["purok"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("purok")[0];
        $consumer["consumer_name"] = $consumer["first_name"]." ".$consumer["middle_name"]." ".$consumer["last_name"];
        $reading = Reading::where("consumer_id", $id)->latest()->first();
        $consumer["consumer_id"] = str_pad($consumer["consumer_id"], 6, '0', STR_PAD_LEFT);
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
        
        return response()->json([
            "status"=>true,
            "message"=> "Inquire is found",
            "id"=>$id,
            "billing"=>$consumer
        ],200);
    }
    public function inquire($id)
    {  $consumer = Consumer::where("consumer_id", $id)->get()[0];
        $consumer["barangay"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("barangay")[0];
        $consumer["purok"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("purok")[0];
        $consumer["consumer_name"] = $consumer["first_name"]." ".$consumer["middle_name"]." ".$consumer["last_name"];
        $billingtoloop = Billing::where("consumer_id", $id)->orderBy('created_at', 'desc')->get();
        $reading = [];
        $consumer['balance']=0;
        $balance = Billing::where('consumer_id', $id)->where('previous_payment', '!=', '0')->latest()->first();
        if($balance){
            $consumer['balance'] = $balance->previous_bill+$balance->penalty+$balance->present_bill - $balance->previous_payment;
        }
        $consumer["consumer_id"] = str_pad($consumer["consumer_id"], 6, '0', STR_PAD_LEFT);
        $consumer["service_period"] = null;
        $consumer["reading"] = $reading;
        $consumer["billing"] = null;
        $consumer["payment"] = null;
        $readings= "";
        if($billingtoloop){
            foreach($billingtoloop as $bl){
                if(!$bl->previous_payment){
                    $reading[] = $bl;
                    $readings = Reading::where('consumer_id', $bl->consumer_id)->where('service_period_id',$bl->service_period_id)->get()[0];
                    $bl['total_cuM'] = $readings['present_reading'] - $readings['previous_reading'];
                    $bl['service_period'] = ServicePeriod::where('service_period_id', $bl->service_period_id)->pluck("service_period")[0];
                }else{
                    break;
                }
            }
        }

        return response()->json([
            "status"=>true,
            "message"=> "Inquire is found",
            "id"=>$id,
            "billing"=>$consumer,
            "listofbill" =>$reading,
            "sampleread" => $readings,
            'bal' =>$consumer['balance'],
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

    public function collectionReports($year, $month)
    {
        $collectionReport =[];
        $service_period_id = ServicePeriod::where("service_period", $year."-".$month)->pluck("service_period_id")[0];
        if($service_period_id){
        $collectionReport["totalBilling"] = Billing::where("service_period_id", $service_period_id)->count();
        $collectionReport["totalPayments"] = Payment::where("service_period_id", $service_period_id)->count();
        $collectionReport["service_period_id"] = ServicePeriod::where("service_period", $year."-".$month)->pluck("service_period")[0];
        $collectionReport["totalCollection"] = Payment::where('service_period_id', $service_period_id)->sum("amount_paid");
        }

        
        return response()->json([
            "status"=>true,
            "message"=> "Collection Report is found",
            "collectionReport"=>$collectionReport,
            "consumers" => ReadingsController::consumerReport()
        ],200);
    }
    public function consumerReport()
    {
        $consumerReport = [];
        $consumerReport["totalConsumers"] = Consumer::all()->count();
        $consumerReport["totalDelinquent"] = Consumer::where("delinquent", 1)->count();
        $consumerReport["totalDisconnected"] = Consumer::where("status", "Disconnected")->count();
        $consumerReport["totalConnected"] = Consumer::where("status", "Connected")->count();
        
        return response()->json([
            "consumerReport"=>$consumerReport
        ],200);
    }
    public function toReadConsumers(){
        $consumers = Consumer::where("status" ,'Connected')->get();
        $dateAfter = Carbon::now()->subMonth()->format('Y')."-".Carbon::now()->subMonth()->shortEnglishMonth;
        $service_period_id = ServicePeriod::where("service_period", $dateAfter)->pluck("service_period_id")[0];
        $service_period = ServicePeriod::where("service_period_id", $service_period_id)->pluck("service_period")[0];
        foreach($consumers as $consumer){
            $consumer["service_period_id"] = null;
            $consumer["consumer_id"] = str_pad($consumer["consumer_id"], 6, '0', STR_PAD_LEFT);
            $consumer["barangay"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("barangay")[0];
            $consumer["purok"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("purok")[0];
            $reading  = Reading::where("consumer_id", $consumer["consumer_id"])->latest()->first();
            $consumer["service_period_id_to_be"] = $service_period_id;
            $consumer["service_period"] = $service_period;
            if($reading){
                $consumer["service_period_id"] = $reading["service_period_id"];
            }
        }
        return json_decode($consumers);
    }
    public function toReadConsumersByBarangay($barangay, $purok){
        $brgyprk = BarangayPurok::where("barangay", $barangay)->where("purok", $purok)->pluck("brgyprk_id")[0];
        $dateAfter = Carbon::now()->subMonth()->format('Y')."-".Carbon::now()->subMonth()->shortEnglishMonth;
        $service_period_id = ServicePeriod::where("service_period", $dateAfter)->pluck("service_period_id")[0];
        $consumers = Consumer::where("brgyprk_id", $brgyprk)->where("status" ,'Connected')->get();
        $service_period = ServicePeriod::where("service_period_id", $service_period_id)->pluck("service_period")[0];
        $topush = [];
        foreach($consumers as $consumer){
            $consumer["service_period_id"] = null;
            $consumer["consumer_id"] = str_pad($consumer["consumer_id"], 6, '0', STR_PAD_LEFT);
            $consumer["barangay"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("barangay")[0];
            $consumer["purok"] = BarangayPurok::where("brgyprk_id", $consumer["brgyprk_id"])->pluck("purok")[0];
            $reading  = Reading::where("consumer_id", $consumer["consumer_id"])->latest()->first();
            $consumer["service_period_id_to_be"] = $service_period_id;
            $consumer["service_period"] = $service_period;
            $consumer["service_period_id"] = "";
            $consumer["reading_id"] = "";
            $consumer["previous_reading"] = "";
            $consumer["present_reading"] = $consumer['first_reading'];
            $consumer["reading_date"] = "";
            $consumer["reading_latest"] = null;
            $consumer["reading_img"] = "";
            $consumer["usage_type"] = $consumer->usage_type;
            if($reading){
                $consumer["service_period_id"] = $reading["service_period_id"];
                $consumer["reading_id"] = $reading['reading_id'];
                $consumer["previous_reading"] = $reading['previous_reading'];
                $consumer["present_reading"] = $reading['present_reading'];
                $consumer["reading_date"] = $reading['reading_date'];
            }
            $ifReaded = Reading::where("consumer_id", $consumer["consumer_id"])->where("service_period_id", $service_period_id)->latest()->first();
            if(!$ifReaded){
                $topush[] = $consumer;
            }

            
        }
        return $topush;
    }
    public function generateDelinquents(){
        $consumers = Consumer::where('status', '!=', 'Archive')->get();
        $billing=[];
        $delinquents = [];
        foreach($consumers as $consumer){
            $storedbill = [];
            $billing = Billing::where('consumer_id', $consumer->consumer_id)->orderBy('created_at', 'desc')->get();
            if($billing){
                $count = 0;
                foreach($billing as $bill){
                    //echo $bill->billing_id.'-'.$count." ";
                    if(!$bill->previous_payment){
                        $count = $count+1;
                        if($count==2){
                            $update = Consumer::where('consumer_id', $bill->consumer_id)->update(['delinquent'=> 1]);
                            $delinquentConsumerData = Consumer::where('consumer_id', $bill->consumer_id)->get()[0];
                            $delinquents[] = $delinquentConsumerData;
                            break;
                        }
                    }else{
                        break;
                    }
                }
                foreach($billing as $bill){
                    //echo $bill->billing_id.'-'.$count." ";
                    if(!$bill->previous_payment){
                        $penaltypercent = Settings::where("setting_key", 2)->pluck("setting_value")[0];
                        $consumer = Consumer::where('consumer_id', $bill->consumer_id)->get()[0];
                        if($consumer->delinquent===1){
                            $updatebill = Billing::where('billing_id', $bill->billing_id)->update(['penalty'=>$bill->present_bill*($penaltypercent/100)]);
                            $storedbill[] = Billing::where('billing_id', $bill->billing_id)->get()[0];
                        }
                    }else{
                        break;
                    }
                }
            }
            $reverse_stored_bill = array_reverse($storedbill);
            $bill_count = 0;
            foreach($reverse_stored_bill as $index => $bill){
                $bill_prev = Billing::where('billing_id', $bill->billing_id)->pluck('previous_bill')[0];
                $bill_penalty = Billing::where('billing_id', $bill->billing_id)->pluck('penalty')[0];
                $bill_pres = Billing::where('billing_id', $bill->billing_id)->pluck('present_bill')[0];
                if($index===0){
                    Billing::where('billing_id', $bill->billing_id)->update(['previous_bill'=>$bill_prev]);
                    echo $bill_prev."-prev ";
                }else{
                    Billing::where('billing_id', $bill->billing_id)->update(['previous_bill'=>$bill_count]);
                    echo $bill_count."-count ";
                }
                $bill_count = $bill_pres + $bill_penalty + $bill_prev;
            }
        }
        return response()->json([
            "delinquents"=>$delinquents,
        ],200);
    }
    public function findBillReading($id){
        $billread["bill"] = Billing::where("consumer_id", $id)->latest()->first();
        $billread["read"] = Reading::where("consumer_id", $id)->latest()->first();
        $billread["due_date"] = Settings::where("setting_key", 1)->pluck("setting_value")[0];
        $billread["penalty"] = Settings::where("setting_key", 2)->pluck("setting_value")[0];
        $json_setting = Settings::where("setting_key", 3)->pluck("setting_value")[0];
        $billread["cubic_rates"] = json_decode($json_setting);
        return response()->json($billread);
    }

    public function storeBillReading(StoreBillingReadingRequest $request)
    {
        $reading = [
        "reader_id" => $request->reader_id,
        "consumer_id" =>$request->consumer_id,
        "service_period_id" =>$request->service_period_id,
        "previous_reading" =>$request->previous_reading,
        "present_reading" =>$request->present_reading,
        "reading_date" => $request->reading_date,
        "proof_image"=>$request->proof_image
    ];
    $bill = Billing::where("consumer_id", $request->consumer_id)->latest()->first();
    $penalty = Settings::where("setting_key", 2)->pluck("setting_value")[0];
    $isDelinquent = Consumer::where("consumer_id", $request->consumer_id)->pluck("delinquent")[0];
    $isPenalty = 0;
    if($isDelinquent!==0){
        $isPenalty = ($penalty/100)*$request->present_bill;
    }
        $prevbill = 0;
        if($bill){
            $prevbill = ($bill->previous_bill-$bill->previous_payment)+$bill->penalty+$bill->present_bill;
        }
        $billing =[
            "consumer_id" =>$request->consumer_id,
            "service_period_id" =>$request->service_period_id,
            "due_date"=>$request->due_date,
            "previous_bill"=>$prevbill,
            "previous_payment"=>0,
            "penalty"=>$isPenalty,
            "present_bill"=>$request->present_bill
        ];
        $proceed = Reading::where('consumer_id', $request->consumer_id)->where('service_period_id', $request->service_period_id)->get();
        if(count($proceed)===0){
            $createdReading = Reading::create($reading);
            $createdBilling = Billing::create($billing);
        }
        return response()->json([
            "status"=>$billing,
            "message"=> "stored succesfully",
            //"reading"=>$createdReading,
            //"billing"=>$createdBilling,
            //"reading"=>$createdReading,
        ],200);
    }
    public function storeBillReadingMobile(StoreBillingReadingRequest $request)
    {
        $reading = [
        "reader_id" => $request->reader_id,
        "consumer_id" =>$request->consumer_id,
        "service_period_id" =>$request->service_period_id,
        "previous_reading" =>$request->previous_reading,
        "present_reading" =>$request->present_reading,
        "reading_date" => $request->reading_date
    ];
        $billing =[
            "consumer_id" =>$request->consumer_id,
            "service_period_id" =>$request->service_period_id,
            "due_date"=>$request->due_date,
            "previous_bill"=>$request->previous_bill,
            "previous_payment"=>$request->previous_payment,
            "penalty"=>$request->penalty,
            "present_bill"=>$request->present_bill
        ];
        $createdReading = Reading::create($reading);
        $createdBilling = Billing::create($billing);

        return response()->json([
            "status"=>true,
            "message"=> "stored succesfully",
            "reading"=>$createdReading,
            "billing"=>$createdBilling,
            "reading"=>$createdReading
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
