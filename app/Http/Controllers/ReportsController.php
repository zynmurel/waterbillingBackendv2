<?php

namespace App\Http\Controllers;

use App\Models\BarangayPurok;
use App\Models\Billing;
use App\Models\Consumer;
use App\Models\ServicePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ReportsController extends Controller
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
    public function getPaymentReports($year, $month)
    {
        $service_period_id = 0;
        $payment_reports = [];
        $get_service_period_id = ServicePeriod::where("service_period", $year."-".$month)->pluck('service_period_id');
        if(count($get_service_period_id)!=0){
            $service_period_id = $get_service_period_id[0];
                $allpayment = Billing::where("service_period_id", $service_period_id)->get();
                if(count($allpayment)){
                    foreach($allpayment as $payment){
                        $consumer = Consumer::where('consumer_id', $payment->consumer_id)->get();
                        $barangay = BarangayPurok::where('brgyprk_id', $consumer[0]->brgyprk_id)->pluck('barangay')[0];
                        $payment['consumer_name'] = $consumer[0]->first_name.' '.Str::substr($consumer[0]->middle_name, 0, 1).'. '.$consumer[0]->last_name;
                        $payment['consumer_id'] = $consumer[0]->consumer_id;
                        $payment['barangay'] = $barangay;
                    }
                }
                $payment_reports= collect($allpayment)->sortBy('consumer_id')->sortBy('barangay')->values()->all();
        }
        return response()->json([
            "payment_reports"=>$payment_reports,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
