<?php

namespace App\Traits;

trait WeightFormatter
{
    public function formatWeight($weight)
    {
        $weight = (int) $weight;
        if ($weight >= 1000) {
            return ($weight / 1000) . ' kg';
        }
        return $weight . ' gram';
    }
}
