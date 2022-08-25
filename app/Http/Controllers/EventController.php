<?php

namespace App\Http\Controllers;

use App\Models\event;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class EventController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $record =  event::all();
        if ($record->isNotEmpty()) {
            $message = $record->count() . " Records Found.";
            return $this->sendResponse($record, $message);
        }
        return $this->sendError('Error Occurred', ['No Records Found']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function activeEvents()
    {
        //
        $now = date('Y-m-d H:i:s');
        $record = event::where([['start_at', '<=', $now], ['end_at', '>=', $now]])->get();
        if ($record->isNotEmpty()) {
            $message = $record->count() . " Records Found.";
            return $this->sendResponse($record, $message);
        }
        return $this->sendError('Error Occurred', ['No Records Found']);
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
        $validator = Validator::make($request->all(), [
            'slug' => ['required', 'alpha_dash', 'unique:App\Models\event,slug'],
            'start_at' => ['required', 'date_format:Y-m-d H:i:s', 'after:today'],
            'end_at' => ['required', 'date_format:Y-m-d H:i:s', 'after_or_equal:start_at'],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $status = event::create($request->all());

        if ($status) {
            return $this->sendResponse($status, "Record Created Successfully");
        } else {
            return $this->sendError('Error Occurred', ['Unable to Create Record']);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\event  $event
     * @return \Illuminate\Http\Response
     */
    public function show(event $event)
    {
        //
        return $this->sendResponse($event, "Record Available");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\event  $event
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, event $event)
    {
        //
        return $request->all();
        $record = event::updateOrCreate(
            ['id' =>  $event->id],
            [$request->all()]
        );

        if ($record) {
            return $this->sendResponse($record, "Record Successfully Updated");
        } else {
            return $this->sendError('Error Occurred', ['Unable to Update Record']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\event  $event
     * @return \Illuminate\Http\Response
     */
    public function destroy(event $event)
    {
        //
        $status = $event->delete();
        if ($status) {
            return $this->sendResponse($status, "Record Deleted Successfully");
        } else {
            return $this->sendError('Error Occurred', ['Unable to Delete Record']);
        }
    }

    /**
     * Seed a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function seed(Request $request)
    {
        $events = event::factory()->count(5)->create();
        if ($events) {
            return $this->sendResponse($events, "Record Created Successfully");
        } else {
            return $this->sendError('Error Occurred', ['Unable to create Record']);
        }
    }
}