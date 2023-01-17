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
        return  Reading::all();   
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
    public function show($id)
    {
        //$readings = Reading::getServicePeriodReadings($id);

        //return $readings;
        //return $this->sendResponse($readings, 'Readings retrieved successfully.');
        return Reading::findOrFail($id);
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
