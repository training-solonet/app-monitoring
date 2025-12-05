<?php

namespace App\Http\Controllers;

use App\Models\Iotikan;
use App\Models\Log;
use Illuminate\Http\Request;

class IotpakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iotikans = Iotikan::all();
        $logs = Log::orderBy('created_at', 'desc')->get();

        return view('pembimbing/iotikan', [
            'iotikans' => $iotikans,
            'logs' => $logs,
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
        Iotikan::where('id', $id)->delete();

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
        $items = Iotikan::all()->map(function ($item) {
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
