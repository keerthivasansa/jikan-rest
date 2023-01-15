<?php

namespace App\Dto;

use Illuminate\Http\JsonResponse;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Optional;

/**
 * @extends LookupDataCommand<JsonResponse>
 */
final class AnimeEpisodesLookupCommand extends LookupDataCommand
{
    #[Numeric]
    public int|Optional $page;
}
