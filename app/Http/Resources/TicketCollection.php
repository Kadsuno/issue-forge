<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/** @extends ResourceCollection<int, \App\Models\Ticket> */
final class TicketCollection extends ResourceCollection
{
    public $collects = TicketResource::class;

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
        ];
    }
}
