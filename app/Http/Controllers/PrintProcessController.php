<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{ProfilePrintProcessColor, Profile};
use Illuminate\Support\Facades\DB;

class PrintProcessController extends Controller
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
        $profile = Profile::findOrFail($request->id);

        $lastSequence = ProfilePrintProcessColor::where('profile_id', $profile->id)->max('sequence') ?? 0;

        ProfilePrintProcessColor::create([
            'profile_id' => $profile->id,
            'sequence' => $lastSequence + 1,
        ]);

        return back()->with([
            'profile' => $profile->load('vendor','primary','secondary','fingerPrintColors','printProcessColors'),
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
    public function update(Request $request)
    {
        $section = ProfilePrintProcessColor::findOrFail($request->id);
        
        $profile = Profile::find($request->profile_id);
        $section->update($request->all());
        
        
        return back()->with([
            'profile' => $profile->load('vendor','primary','secondary','fingerPrintColors','printProcessColors'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        ProfilePrintProcessColor::where('id', $request->id)->delete();

        DB::transaction(function () use ($request) {
            ProfilePrintProcessColor::where('profile_id', $request->profile_id)
                ->orderBy('sequence')
                ->get()
                ->each(fn($section, $index) => $section->update(['sequence' => $index + 1]));
        });

        $profile = Profile::find($request->profile_id);

        return back()->with([
            'profile' => $profile->load('vendor','primary','secondary','fingerPrintColors','printProcessColors'),
        ]);
    }
}
