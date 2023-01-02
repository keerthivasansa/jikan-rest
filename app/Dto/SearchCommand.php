<?php

namespace App\Dto;

use App\Casts\EnumCast;
use App\Enums\SortDirection;
use Spatie\Enum\Laravel\Rules\EnumRule;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\Validation\Alpha;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Size;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

class SearchCommand extends Data
{
    /**
     * The search keywords
     * @var string|Optional
     */
    #[Max(255), StringType]
    public string|Optional $q;

    #[IntegerType, Min(1)]
    public int|Optional $limit;

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
