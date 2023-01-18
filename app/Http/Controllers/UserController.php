<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserEmailRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin =  User::where('user_type', 'Admin')->get();
        $reader =  User::where('user_type', 'Reader')->get();
        $cashier =  User::where('user_type', 'Cashier')->get();

        return response()->json([
            "status"=>true,
            "admin"=>$admin,
            "cashier"=>$cashier,
            "reader"=>$reader
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
    public function update(StoreUserEmailRequest $request, $id)
    {
        $newemail = [
            "email" => $request->email
        ];
        User::where('user_id', $id) ->update($newemail);
        return response()->json([
            "data"=>"email updated!",
            "consumer"=>$newemail
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
