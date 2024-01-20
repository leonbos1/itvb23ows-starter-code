<?php

namespace helpers;

use insects\Insect;
use insects\Ant;
use insects\Beetle;
use insects\Grasshopper;
use insects\Queen;
use insects\Spider;

class InsectHelper
{
    /**
     * Returns an instance of the insect class based on the insect type
     * @param string $insect_type A single character representing the insect type
     * @return Insect An instance of the insect class
     */
    public static function getInsectInstance($insect_type): ?Insect
    {
        $insectType = strtoupper($insect_type);

        switch ($insectType) {
            case 'A':
                return new Ant();
            case 'B':
                return new Beetle();
            case 'G':
                return new Grasshopper();
            case 'Q':
                return new Queen();
            case 'S':
                return new Spider();
            default:
                return null;
        }
    }
}