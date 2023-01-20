<?php

namespace App\Dto;


use App\Casts\EnumCast;
use App\Enums\MangaForumFilterEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Optional;
use Spatie\Enum\Laravel\Rules\EnumRule;
use Spatie\LaravelData\Attributes\WithCast;

/**
 * @extends LookupDataCommand<JsonResponse>
 */
final class MangaForumLookupCommand extends LookupDataCommand
{
    #[WithCast(EnumCast::class, MangaForumFilterEnum::class)]
    public MangaForumFilterEnum|Optional $filter;

    public static function rules(): array
    {
        return [
            "filter" => [new EnumRule(MangaForumFilterEnum::class)]
        ];
    }
}
