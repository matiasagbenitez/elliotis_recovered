<?php

namespace App\Http\Services;

class RandomNumberService
{
    public static function highProbability()
    {
        // 80% probability
        $rand = rand(0, 100);

        if ($rand <= 70) {
            return true;
        } else {
            return false;
        }

    }

}
