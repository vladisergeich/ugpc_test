<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transfer,Shaft,Designer,Warehouse};
use Inertia\Inertia;
use App\Services\NotificationService;
use App\Services\DocumentService;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{

    protected $notificationService;
    protected $documentService;

    public function __construct(NotificationService $notificationService, DocumentService $documentService)
    {
        $this->notificationService = $notificationService;
        $this->documentService = $documentService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $transfers = Transfer::all();

        return Inertia::render('Transfer/Index', [
            'transfers' => $transfers->load('sender','recipient'),
        ]);
    }

    public function confirmTransfer(Request $request)
    {
        $transfer = Transfer::findOrFail($request->id);
        $this->notificationService->confirmTransfer($transfer);

        $transfer->update(['status' => 'confirmed']);

        return back()->with([
            'transfer' => $transfer->load('shafts.shaft'),
        ]);
    }

    public function postApp(Request $request)
    {
        $transfer = Transfer::with('shafts.shaft')->findOrFail($request->id);
        $this->notificationService->postTransfer($transfer);
    
        \DB::transaction(function () use ($transfer) {
            foreach ($transfer->shafts as $shaft) {
                if ($shaft->shaft) {
                    $shaft->shaft->update(['warehouse_id' => $shaft->recipient_id]);
                }
            }
    
            $transfer->update(['status' => 'completed']);
        });
    
        return redirect()->back()->with([
            'transfer' => $transfer->load('shafts.shaft'),
        ]);
    }

    public function upakList(Request $request)
    {   

        $transfer = Transfer::with('shafts.shaft', 'shafts.engravingOrder','sender','recipient')->findOrFail($request->id);

        $pdfData = [
            'transfer' => $transfer,
        ];
    
        $pdf = $this->documentService->generatePdf('pdf.transfer_upak_list', $pdfData);
    
        // 💡 нужно пробросить переменную внутрь use()
        return response()->streamDownload(function () use ($pdf, $transfer) {
            echo $pdf;
        }, 'Перемещение-' . $transfer->id . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $transfer = Transfer::create([
            'create_date' => now(),
        ]);

        return redirect()->route('transfers.show', [
            'transfer' => $transfer,
        ]);
    }

    public function getShafts(Request $request)
    {
        $query = Shaft::query();
    
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('code', 'like', "%{$search}%")
                  ->orWhere('ff', 'like', "%{$search}%")
                  ->orWhere('format', 'like', "%{$search}%");
        }
    
        return $query->paginate(10);
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
    public function show(Transfer $transfer)
    {
        return Inertia::render('Transfer/Show', [
            'transfer' => $transfer->load('shafts.shaft'),
            'designers' => Designer::orderBy('name')->get(),
            'warehouses' => Warehouse::all(),
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
    public function update(Request $request)
    {
        $transfer = Transfer::findOrFail($request->id);
        $transfer->update($request->all()); 
        
        return back()->with([
            'message' => 'Секция удалена',
            'transfer' => $transfer->load('shafts.shaft'),
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
