<?php

namespace App\Exports;

use App\Models\Shipment;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ShipmentExport implements FromView
{
    protected $shafts;
    protected $shaftrequest;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data,$shaftrequest)
    {
        $this->shafts = $data;
        $this->shaftrequest = $shaftrequest;
    }

    public function view(): View
    {
        return view('ugpc.excel.shipment',[
            'shafts' => $this->shafts,
            'shaftrequest' => $this->shaftrequest,
        ]);
    }
}

