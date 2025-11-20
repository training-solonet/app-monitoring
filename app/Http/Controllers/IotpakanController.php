<?php

namespace App\Http\Controllers;

use App\Models\Iotikan;
use App\Models\Network;
use Illuminate\Http\Request;

class IotpakanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $iotikans = Iotikan::all();
        $network = Network::first();

        return view('pembimbing/iotikan', [
            'iotikans' => $iotikans,
            'network' => $network,
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

    public function network(Request $request)
    {
        $network = Network::first();

        $ssid = $request->get('ssid');
        $password = $request->get('password');

        if ($network) {
            $network->update([
                'ssid' => $ssid,
                'password' => $password,
            ]);
        } else {
            Network::create([
                'ssid' => $ssid,
                'password' => $password,
            ]);
        }

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
}
