<?php

namespace App\Http\Controllers;

use App\Models\Alarm;
use App\Models\Triger;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateAlarmRequest;

class AlarmController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'device_id' => 'required',
            'lat' => 'required',
            'long' => 'required',
        ]);

        $post =  Triger::create($data);

        return response()->json(['success' => true, 'data' => $post], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Alarm $alarm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAlarmRequest $request, Alarm $alarm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alarm $alarm)
    {
        //
    }
}
