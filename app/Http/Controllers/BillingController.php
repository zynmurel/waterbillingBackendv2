<?php

namespace App\Http\Controllers;

use App\Models\Billing;
use App\Models\Payment;
use App\Models\Reading;
use App\Models\ServicePeriod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $billing = Billing::where("consumer_id", $id)->orderBy("due_date","asc")->get();
        foreach ($billing as $bill) {
            $service_period = ServicePeriod::where('service_period_id', $bill['service_period_id'])->pluck("service_period");
            $bill['service_period'] = $service_period[0];
        }
        return response()->json([
            "data"=>"Go!",
            "billing"=>$billing
        ]);
    }

    public function showReadBillPayConsumer( $id ){
        $id = $id;
        $billing = Billing::where("consumer_id", $id)->orderBy('created_at', 'desc')->get();
        if($billing){
            foreach($billing as $bill){
                $service_period = ServicePeriod::where('service_period_id', $bill['service_period_id'])->pluck("service_period");
                $bill['service_period'] = $service_period[0];
                $bill["reading"] = Reading::where("consumer_id", $bill["consumer_id"])->where("service_period_id", $bill["service_period_id"])->get();
                $bill["payment"] = Payment::where("service_period_id", $bill["service_period_id"])->get();
                $bill["due_date"] = Carbon::parse($bill["due_date"])->format('F jS, Y');
                $bill["consumer_id"] =  str_pad($bill["consumer_id"], 6, '0', STR_PAD_LEFT);
                if(count($bill["reading"])!==0){
                    $bill["reading"] = $bill["reading"][0];
                }
                if(count($bill["payment"])!==0){
                    $bill["payment"] = $bill["payment"][0];
                    $date_paid = $bill["payment"]["date_paid"];
                    $bill["payment"]["date_paid"] = Carbon::parse($date_paid)->format('F jS, Y');
                }
            }
        }

        
        return response()->json([
            "data"=>"Go!",
            "billing"=>$billing
        ]);
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
