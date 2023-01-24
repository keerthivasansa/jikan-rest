<?php

namespace App\Dto;

use App\Casts\EnumCast;
use App\Dto\Concerns\HasLimitParameter;
use App\Dto\Concerns\HasPageParameter;
use App\Enums\SortDirection;
use Spatie\Enum\Laravel\Rules\EnumRule;
use Spatie\LaravelData\Attributes\Validation\Alpha;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class SearchCommand extends Data
{
    use HasLimitParameter, HasPageParameter;

    /**
     * The search keywords
     * @var string|Optional
     */
    #[Max(255), StringType]
    public string|Optional $q;

    #[WithCast(EnumCast::class, SortDirection::class)]
    public SortDirection|Optional $sort;

    #[Size(1), StringType, Alpha]
    public string|Optional $letter;

    public static function rules(): array
    {
        return [
            "sort" => [new EnumRule(SortDirection::class)]
        ];
    }
}
