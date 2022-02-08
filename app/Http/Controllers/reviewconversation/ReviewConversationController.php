<?php

namespace App\Http\Controllers\reviewconversation;

use App\Http\Controllers\Controller;
use App\Models\reviewconversation\Review_conversation;
use App\Models\attendancestatus\Attendance_status;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Brian2694\Toastr\Facades\Toastr;

class ReviewConversationController extends Controller
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
        $validator = Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            Toastr::error('Sorry! All fields are required.');
            return back();
        }

        try{
            DB::beginTransaction();
            $review = new Review_conversation;
            $review->user_id = auth()->user()->id;
            $review->statusid = $request->input('statusid');
            $review->sender = $request->input('sender');
            $review->message = $request->input('message');

            $review->save();

            $status = Attendance_status::find($request->input('statusid'));
            $status->status_name = $request->input('status');
            $status->save();

            DB::commit();
            Toastr::success('Record sent to review successfully','Sent');
            return back();
        }
        catch(\Exception $e){
            DB::rollBack();
            \Log::emergency("File:" . $e->getFile(). "Line:" . $e->getLine(). "Message:" . $e->getMessage());

            Toastr::error('Error occured, approval failed!','Error');
            return  $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review_conversation  $review_conversation
     * @return \Illuminate\Http\Response
     */
    public function show(Review_conversation $review_conversation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review_conversation  $review_conversation
     * @return \Illuminate\Http\Response
     */
    public function edit(Review_conversation $review_conversation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review_conversation  $review_conversation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review_conversation $review_conversation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review_conversation  $review_conversation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review_conversation $review_conversation)
    {
        //
    }
}
