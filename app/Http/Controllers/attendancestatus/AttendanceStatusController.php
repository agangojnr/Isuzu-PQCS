<?php

namespace App\Http\Controllers\attendancestatus;

use App\Models\attendancestatus\Attendance_status;
use Illuminate\Http\Request;

class AttendanceStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array(
            'test'=>"testing",
        );
        return view('status.index')->with($data);
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
     * @param  \App\Models\Attendance_status  $attendance_status
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance_status $attendance_status)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance_status  $attendance_status
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance_status $attendance_status)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance_status  $attendance_status
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance_status $attendance_status)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance_status  $attendance_status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance_status $attendance_status)
    {
        //
    }
}
