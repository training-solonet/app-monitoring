<?php

namespace App\Http\Controllers;

use App\Models\Iotikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Cache\RedisTaggedCache;

class IotpakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iotikans = Iotikan::all();
        return view('pembimbing/iotikan', [
            'iotikans' => $iotikans,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $record = Iotikan::where('id', $id);

        $time = $request->schedule;
        $interval = $request->interval;

        $record->update([
            'time' => $time,
            'interval' => $interval,
        ]);

        return redirect('iotikan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Iotikan::where('id', $id)->destroy();
        return redirect('iotikan');
    }

    public function device(Request $request)
    {
        $time = $request->schedule;
        $interval = $request->interval;

        Iotikan::create([
            'time' => $time,
            'interval' => $interval,
        ]);

        return redirect('iotikan');
    }

    public function sendSchedule()
    {
        $items = Iotikan::all()->map(function($item){
            return [
                'time' => substr($item->time, 0, 5),
                'interval' => $item->interval,
            ];
        });
        return response()->json($items);
    }

    public function receiveLogs(Request $request)
    {
        $log_message = $request->log_message;
        Log::create([
            'log_message' => $log_message,
        ]);
    }
}
