<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\SteelShaftApplication;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class AppSteelExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected SteelShaftApplication $application;

    public function __construct(SteelShaftApplication $application)
    {
        $this->application = $application;
    }

    public function collection(): Collection
    {
        return collect($this->application->shafts)->map(function ($shaft) {
            return [
                $this->application->id,
                $shaft->sequence,
                $shaft->shaft->code,
                $shaft->ff,
                $shaft->diameter,
                $shaft->shaft_ra,
                $shaft->shaft_rz,
                $this->application->engraver->name,
            ];
        });
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