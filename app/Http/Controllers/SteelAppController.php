<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{SteelShaftApplication,Vendor,Designer,Mailing};
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Exports\AppSteelExport;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\NotificationService;

class SteelAppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $steelApps = SteelShaftApplication::with('engraver')->get();


        return Inertia::render('SteelApp/Index', [
            'steelApps' => $steelApps
        ]);
    }

    public function sendApp(Request $request)
    {
        $steelApp = SteelShaftApplication::with('shafts.shaft','engraver')->findOrFail($request->id);

        $this->notificationService->sendSteelApp($steelApp);
    
        return back()->with([
            'steelApp' => $steelApp->load('shafts.shaft'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $newApp = SteelShaftApplication::create([
            'create_date'   => now()->toDateString(),
            'note'         => 'Номер цилиндра набить с двух сторон со смещением 45°',
        ]);


        return redirect()->route('steelApp.show', [
            'steelApp' => $newApp->id,
        ]);
    }

    public function update(Request $request)
    {
        $steelApp = SteelShaftApplication::findOrFail($request->id);
        $steelApp->update($request->all()); 
        
        return back()->with([
            'message' => 'Секция удалена',
            'steelApp' => $steelApp->load('shafts.shaft'),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'steelApp.id' => 'required|exists:steel_shaft_applications,id',
        ]);
    
        $file = $request->file('file');
        $steelApp = SteelShaftApplication::findOrFail($request->input('steelApp.id'));
    
        $uuid = Str::uuid();
        $filename = $file->getClientOriginalName();
        $storedPath = $file->storeAs("public/uploads", $uuid . '_' . $filename);
        $relativePath = str_replace("public/", "", $storedPath);
    
        $attachments = $steelApp->attachments ?? [];
    
        $attachments[] = [
            'id' => $uuid,
            'name' => $filename,
            'path' => $relativePath,
            'type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => auth()->id(),
            'uploaded_at' => now()->toISOString(),
        ];
    
        $steelApp->attachments = $attachments;
        $steelApp->save();
    
        return response()->json($steelApp);
    }
    

    public function deleteFile(Request $request, SteelShaftApplication $steelApp)
    {
        $steelApp = SteelShaftApplication::findOrFail($request->app['id']);

        $uuid = $request->input('file_id');
        $attachments = collect($steelApp->attachments ?? []);
        
    
        $target = $attachments->firstWhere('id', $uuid);
        if (!$target) {
            return response()->json(['message' => 'Файл не найден'], 404);
        }
    
        // удалить файл с диска
        if (Storage::disk('public')->exists($target['path'])) {
            Storage::disk('public')->delete($target['path']);
        }
    
        // удалить из jsonb массива
        $steelApp->attachments = $attachments->reject(fn($f) => $f['id'] === $uuid)->values();
        $steelApp->save();
    
        return back()->with([
            'message' => 'Файл удалён',
            'steelApp' => $steelApp->fresh()
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(SteelShaftApplication $steelApp)
    {
        return Inertia::render('SteelApp/Show', [
            'steelApp' => $steelApp->load('shafts.shaft','engraver'),
            'vendors' => Vendor::all(),
            'designers' => Designer::orderBy('name')->get(),
        ]);
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
    public function destroy(string $id)
    {
        //
    }
}
