<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateConsumerRequest;
use App\Http\Requests\UpdateUserConsumer;
use App\Models\Consumer;
use App\Http\Resources\ConsumersResource;
use App\Models\BarangayPurok;
use App\Models\User;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ConsumersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return  Consumer::getAllConsumers();   
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
        $body = $request->getContent();
        $input = json_decode($body, true);

        $newConsumer =Consumer::addNewConsumer($input); 

        return response()->json([
            "data"=>$input
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
        return Consumer::findOrFail($id);
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
    public function update(UpdateConsumerRequest $conreq, $id)
    {
        $brgyprk = BarangayPurok::getBrgyPrkId($conreq->barangay, $conreq->purok);
        $consumer = [
            "first_name" => $conreq->first_name,
            "last_name" => $conreq->last_name,
            "middle_name" => $conreq->middle_name,
            "gender" => $conreq->gender,
            "birthday" => strtotime($conreq->birthday),
            "phone" => $conreq->phone,
            "civil_status" => $conreq->civil_status,
            "name_of_spouse" => $conreq->name_of_spouse,
            "brgyprk_id" => $brgyprk,
            "household_no" => $conreq->household_no,
            "first_reading" => $conreq->first_reading,
            "usage_type" => $conreq->usage_type,
            "serial_no" => $conreq->serial_no,
            "brand" => $conreq->brand,
            "status" => $conreq->status,
            "delinquent" => $conreq->delinquent,
            "registered_at" => $conreq->registered_at
        ];
        Consumer::where('user_id', $id) ->update($consumer);
        return response()->json([
            "data"=>"Go!",
            "consumer"=>$consumer
        ]);
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
