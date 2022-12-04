<?php

namespace Database\Factories;

use App\Testing\JikanDataGenerator;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Character;
use MongoDB\BSON\UTCDateTime;


class CharacterFactory extends Factory
{
    use JikanDataGenerator;

    protected $model = Character::class;

    public function definition()
    {
        $mal_id = $this->createMalId();
        $url = $this->createUrl($mal_id, "character");

        return [
            "mal_id" => $mal_id,
            "url" => $url,
            "images" => [],
            "name" => $this->faker->name(),
            "name_kanji" => "岡",
            "nicknames" => [],
            "favorites" => $this->faker->randomDigitNotNull(),
            "about" => "test",
            "createdAt" => new UTCDateTime(),
            "modifiedAt" => new UTCDateTime(),
            "request_hash" => sprintf("request:%s:%s", "v4", sha1("http://localhost-test/v4/character/" . $mal_id))
        ];
    }
}
