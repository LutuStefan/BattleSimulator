<?php

namespace App;

class StatusHelper {
    public static function calculateNewStatus(float $oldStatus, string $effect)
    {
        if($effect === '0') {
            return $oldStatus;
        }
        $operator = $effect[0];
        $statusModifier = (int) substr($effect, 1);

        switch ($operator) {
            case '*':
                return $oldStatus * $statusModifier;
            case '-':
                return $oldStatus - $statusModifier;
            case '/':
                return $oldStatus / $statusModifier;
            case '+':
                return $oldStatus + $statusModifier;
            default:
                return $oldStatus % $statusModifier;
        }
    }
}