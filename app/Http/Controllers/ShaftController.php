<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Shaft};
use Illuminate\Support\Facades\DB;

class ShaftController extends Controller
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
    public function update(Request $request)
    {
        $shaft = Shaft::findOrFail($request->id);
        
        $shaft->update($request->all());  
        
        return back()->with([
            'message' => 'Вал обновлен',
            'shaft' => $shaft,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
