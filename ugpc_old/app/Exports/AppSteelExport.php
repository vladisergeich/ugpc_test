<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\BDGP\SteelShaftApplicationComposition;
use App\Models\BDGP\SteelShaftApplication;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;


class AppSteelExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected $documentNumber;

    public function __construct($documentNumber)
    {
        $this->documentNumber = $documentNumber;
    }


    public function query()
    {
        return SteelShaftApplicationComposition::query()
            ->join('steel_shaft_applications','steel_shaft_applications.document_number','steel_shaft_application_compositions.document_number')
            ->where('steel_shaft_application_compositions.document_number', $this->documentNumber)
            ->select('steel_shaft_application_compositions.document_number','steel_shaft_application_compositions.shaft_number', 'steel_shaft_application_compositions.shaft_id', 'steel_shaft_application_compositions.ff', 'steel_shaft_application_compositions.diameter', 'steel_shaft_application_compositions.shaft_ra', 'steel_shaft_application_compositions.shaft_rz','steel_shaft_applications.engraver');
    }

    public function headings(): array
    {
        return [
            '№ Документа',
            '№ Вала',
            'ID вала',
            'FF',
            'Диаметр',
            'RA',
            'RZ',
            'Гравировщик',
        ];
    }
}
