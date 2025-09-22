<?php

namespace App\Support\ValueObjects;

use Illuminate\Http\Client\PendingRequest;

class DocumentAttachment
{
    public function __construct(
        public readonly string $filename,
        public readonly string $contents,
        public readonly string $mimeType
    ) {
    }

    public function attachTo(PendingRequest $request, string $name = 'attachment'): PendingRequest
    {
        return $request->attach($name, $this->contents, $this->filename);
    }
}
