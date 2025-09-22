<?php

namespace App\Domain\Engraving\Support;

use App\Models\EngravingOrder;
use App\Services\DocumentService;
use App\Support\ValueObjects\DocumentAttachment;

class AttachmentBuilder
{
    public function __construct(private readonly DocumentService $documentService)
    {
    }

    /**
     * @return array{application: DocumentAttachment, shaftApplication: DocumentAttachment, pdf: DocumentAttachment}
     */
    public function buildApplicationAttachments(EngravingOrder $engravingOrder): array
    {
        $xmlDataApp = $engravingOrder->order
            ?->items
            ->pluck('xml_data_app')
            ->filter()
            ->collapse()
            ->all() ?? [];

        $xmlDataShaftApp = $engravingOrder->engravingOrderShaft
            ->pluck('xml_data_shaft_app')
            ->filter()
            ->collapse()
            ->all();

        return [
            'application' => $this->documentService->createXmlAttachment($xmlDataApp, '1.xml'),
            'shaftApplication' => $this->documentService->createXmlAttachment($xmlDataShaftApp, '2.xml'),
            'pdf' => $this->documentService->createPdfAttachment(
                'pdf.invoice',
                ['engravingOrder' => $engravingOrder],
                $engravingOrder->pdf_name
            ),
        ];
    }

    public function buildShaftInfoAttachment(EngravingOrder $engravingOrder): DocumentAttachment
    {
        $xmlArray = $engravingOrder->engravingOrderShaft
            ->whereNotNull('shaft_id')
            ->pluck('xml_data_shaft')
            ->filter()
            ->collapse()
            ->all();

        return $this->documentService->createXmlAttachment($xmlArray, '-ROTOParam.xml', 'EskoParam');
    }

    public function buildStreamInfoAttachment(EngravingOrder $engravingOrder): DocumentAttachment
    {
        $xmlArray = $engravingOrder->layoutStreams
            ->pluck('xml_data_streams')
            ->filter()
            ->collapse()
            ->all();

        return $this->documentService->createXmlAttachment($xmlArray, '+ROTOVerst.xml', 'EskoVerst');
    }
}
