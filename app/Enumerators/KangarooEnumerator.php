<?php

namespace App\Enumerators;

class KangarooEnumerator
{
    const MALE = 'male';
    const FEMALE = 'female';
    const GENDERS = [
        self::MALE,
        self::FEMALE
    ];

    const FRIENDLY = 'friendly';
    const INDEPENDENT = 'independent';
    const FRIENDLINESS = [
        self::FRIENDLY,
        self::INDEPENDENT
    ];
}
