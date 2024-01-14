<?php

include_once dirname(__FILE__) . "/../rules/moveHelper.php";

interface Insect
{
    public function getPossibleMoves($board, $from);
}


/**
 * Returns an instance of the insect class based on the insect type
 * @param string $insect_type A single character representing the insect type
 * @return Insect An instance of the insect class
 */
function getInsectInstance($insect_type): ?Insect
{
    $insectType = strtoupper($insect_type);

    $mapping = [
        'A' => 'Ant',
        'B' => 'Beetle',
        'G' => 'Grasshopper',
        'Q' => 'Queen',
        'S' => 'Spider'
    ];

    if (array_key_exists($insectType, $mapping)) {
        $insectClass = $mapping[$insectType];

        return new $insectClass();
    } else {
        return null;
    }
}