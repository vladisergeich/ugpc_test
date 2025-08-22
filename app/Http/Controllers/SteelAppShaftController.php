<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{SteelShaftApplication,SteelShaftApplicationShaft, Shaft};
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;


class SteelAppShaftController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $steelApp = SteelShaftApplication::findOrFail($request->steelApp['id']);
        
        $lastSequence = SteelShaftApplicationShaft::where('steel_shaft_application_id', $steelApp->id)->max('sequence') ?? 0;

        $isHS = $request->ff == 'HS';
        
        $diameter = ($request->steelApp['format'] * ($isHS ? 1.0013 : 1)) / pi() - 0.4; 

        $now = now();
        
        // Создаем валы в модели Shaft
        $shafts = collect(range(1, $request->qty))->map(function($i) use ($request, $diameter, $isHS, $now) {
            return Shaft::updateOrCreate(
                ['code' => $request->shaftId + $i],
                [
                'diameter' => $diameter,
                'format' => $request->steelApp['format'],
                'ff' => $request->ff,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        });
        
        // Создаем записи в SteelShaftApplicationShaft
        $newShafts = $shafts->map(function($shaft, $index) use ($steelApp, $lastSequence, $request, $now, $isHS) {
            return [
                'steel_shaft_application_id' => $steelApp->id,
                'sequence' => $lastSequence + $index + 1,
                'shaft_id' => $shaft->id,
                'shaft_ra' => $isHS ? '1,3-1,8' : '',
                'shaft_rz' => $isHS ? '5,5-7,2' : '',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();
        
        SteelShaftApplicationShaft::insert($newShafts);
        
        return back()->with([
            'message' => 'Валы добавлены',
            'steelApp' => $steelApp->load('shafts'),
        ]);
    }

    public function destroy(Request $request)
    {
        Shaft::findOrFail($request->shaft_id)->delete();

        DB::transaction(function () use ($request) {
            SteelShaftApplicationShaft::where('steel_shaft_application_id', $request->steel_shaft_application_id)
                ->orderBy('sequence')
                ->get()
                ->each(fn($shaft, $index) => $shaft->update(['sequence' => $index + 1]));
        });

        return back()->with([
            'message' => 'Секция удалена',
            'steelApp' => SteelShaftApplication::with('shafts')
                ->findOrFail($request->steel_shaft_application_id),
        ]);
    }

    public function destroyAll(Request $request)
    {;

        $steelAppShafts = SteelShaftApplicationShaft::where('steel_shaft_application_id', $request->app['id'])->get();

        foreach ($steelAppShafts as $shaft) {
            Shaft::findOrFail($shaft->shaft_id)->delete();
        }

        return back()->with([
            'message' => 'Все валы удалены',
            'steelApp' => SteelShaftApplication::with('shafts')->findOrFail($request->app['id']),
        ]);
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

    /**
     * Remove the specified resource from storage.
     */
}
