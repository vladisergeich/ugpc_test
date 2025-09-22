<?php

namespace App\Services;

use App\Support\ValueObjects\DocumentAttachment;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\ArrayToXml\ArrayToXml;

class DocumentService
{
    public function createXmlAttachment(array $data, string $filename, string $rootElement = 'root'): DocumentAttachment
    {
        $xml = ArrayToXml::convert($data, $rootElement);

        return new DocumentAttachment($filename, $xml, 'application/xml');
    }

    public function createPdfAttachment(string $view, array $data, string $filename): DocumentAttachment
    {
        $pdf = Pdf::loadView($view, $data)->output();

        return new DocumentAttachment($filename, $pdf, 'application/pdf');
    }
}

