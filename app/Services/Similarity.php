<?php

namespace App\Services;

class Similarity
{
    public static function simple_Diff_Sim(int|float|null $value_1, int|float|null $value_2, $min = 100, $max = 700): float
    {
        $value_1 = (float) ($value_1 ?? $min);
        $value_2 = (float) ($value_2 ?? $min);
        $denominator = (float) ($max - $min);

        if ($denominator == 0.0) {
            return 0.0;
        }

        return 1 - (abs($value_1 - $value_2) / $denominator);
    }

    public static function minMaxNorm(int|float|null $value, $min = 100, $max = 700): float
    {
        $value = (float) ($value ?? $min);
        $numerator = $value - $min;
        $denominator = $max - $min;

        if ($denominator == 0.0) {
            return 0.0;
        }

        return $numerator / $denominator;
    }

    public static function OVRS_kernel($arr_1_range = [], $arr_2_range = []): float 
    {
        if (empty($arr_1_range) && empty($arr_2_range)) {
            return 1.0;
        }

        $intersection = array_unique(array_intersect($arr_1_range, $arr_2_range));
        $arr_1_diff = count(array_diff($arr_1_range, $intersection));
        $arr_2_diff = count(array_diff($arr_2_range, $intersection));
        $_intersection = count($intersection);

        if ($arr_1_diff === 0) {
            $ovrs = $_intersection / ($arr_2_diff + $_intersection);
        } else if ($arr_2_diff === 0) {
            $ovrs = $_intersection / ($arr_1_diff + $_intersection);
        } else {
            $ovrs = $_intersection / (min($arr_2_diff, $arr_1_diff) + $_intersection);
        }

        return $ovrs;
    }

    public static function OVRS($arr_1 = [], $arr_2 = []): float 
    {
        //if both arrays are identical return one 1
        if ($arr_1 === $arr_2) {
            return 1;
        }

        if (
            count($arr_1) < 2 ||
            count($arr_2) < 2 ||
            is_null($arr_1[0]) ||
            is_null($arr_1[1]) ||
            is_null($arr_2[0]) ||
            is_null($arr_2[1])
        ) {
            return 0;
        }

        //else destructure the arrays into the four needed variables
        list($min_1, $max_1) = $arr_1;
        list($min_2, $max_2) = $arr_2;

        // if the arrays do not overlap return 0
        if (!(($max_2 >= $min_1) && ($min_2 <= $max_1))) {
            return 0;
        }

        $arr_1_range = range($arr_1[0], $arr_1[1], env('BUDGET_PRICE_STEP', 20000));
        $arr_2_range = range($arr_2[0], $arr_2[1], env('BUDGET_PRICE_STEP', 20000));

        return Similarity::OVRS_kernel($arr_1_range, $arr_2_range);
    }

    public static function jaccard($arr_1 = [], $arr_2 = []): float 
    {
        $intersection = array_unique(array_intersect($arr_1, $arr_2));
        $union = array_unique(array_merge($arr_1, $arr_2));

        if (count($union) === 0) {
            return 1.0;
        }

        return count($intersection) / count($union);
    }
}
