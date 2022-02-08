<?php

namespace App\Http\Controllers\imports;

use App\Models\Imports;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ImportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('imports.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('imports.create');
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
     * @param  \App\Models\Imports  $imports
     * @return \Illuminate\Http\Response
     */
    public function show(Imports $imports)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Imports  $imports
     * @return \Illuminate\Http\Response
     */
    public function edit(Imports $imports)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Imports  $imports
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Imports $imports)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Imports  $imports
     * @return \Illuminate\Http\Response
     */
    public function destroy(Imports $imports)
    {
        //
    }
}
