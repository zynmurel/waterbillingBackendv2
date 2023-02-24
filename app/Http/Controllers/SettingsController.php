<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCubicRateUpdateRequest;
use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $settings = Settings::getSettings();
        $json_setting = Settings::where("setting_key", 3)->pluck("setting_value")[0];

        return response()->json([
            "status"=>true,
            "message"=> "Collection Report is found",
            "collectionReport"=>$settings,
            "cubic_rates" => $json_setting
        ],200);
    }

    public function updateSettings(StoreCubicRateUpdateRequest $request, $id)
    {
        $setting = [
            "setting_value" => $request->setting_value
        ];
        Settings::where('setting_key', $id) ->update($setting);
        return response()->json([
            "data"=>"Settings updated!",
            "consumer"=>$setting
        ]);
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
