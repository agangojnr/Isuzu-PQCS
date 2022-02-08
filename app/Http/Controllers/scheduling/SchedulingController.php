<?php

namespace App\Http\Controllers\scheduling;

use App\Models\scheduling\Scheduling;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SchedulingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('scheduling.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('scheduling.create');
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
     * @param  \App\Models\scheduling\Scheduling  $scheduling
     * @return \Illuminate\Http\Response
     */
    public function show(Scheduling $scheduling)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\scheduling\Scheduling  $scheduling
     * @return \Illuminate\Http\Response
     */
    public function edit(Scheduling $scheduling)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\scheduling\Scheduling  $scheduling
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Scheduling $scheduling)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\scheduling\Scheduling  $scheduling
     * @return \Illuminate\Http\Response
     */
    public function destroy(Scheduling $scheduling)
    {
        //
    }
}
