<?php

namespace App\Dto;

use App\Casts\EnumCast;
use App\Contracts\DataRequest;
use App\Enums\ProducerOrderByEnum;
use App\Http\Resources\V4\ProducerCollection;
use App\Rules\Attributes\EnumValidation;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Optional;

/**
 * @implements DataRequest<ProducerCollection>
 */
final class ProducersSearchCommand extends SearchCommand implements DataRequest
{
    #[MapInputName("order_by"), MapOutputName("order_by"),
        WithCast(EnumCast::class, ProducerOrderByEnum::class), EnumValidation(ProducerOrderByEnum::class)]
    public ProducerOrderByEnum|Optional $orderBy;
}
