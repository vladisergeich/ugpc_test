<?php

namespace App\Services;

use Spatie\ArrayToXml\ArrayToXml;
use Barryvdh\DomPDF\Facade\Pdf;

class DocumentService
{
    public function generateXml(array $data): string
    {
        return ArrayToXml::convert($data);
    }

    public function generatePdf(string $view, array $data): string
    {
        return Pdf::loadView($view, $data)->output();
    }
}

