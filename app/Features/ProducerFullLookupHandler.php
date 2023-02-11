<?php

namespace App\Features;

use App\Dto\ProducerFullLookupCommand;
use App\Http\Resources\V4\ProducerFullResource;
use App\Support\CachedData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

/**
 * @extends ItemLookupHandler<ProducerFullLookupCommand, JsonResponse>
 */
final class ProducerFullLookupHandler extends ItemLookupHandler
{
    public function requestClass(): string
    {
        return ProducerFullLookupCommand::class;
    }

    protected function resource(CachedData $results): JsonResource
    {
        return new ProducerFullResource($results);
    }
}
