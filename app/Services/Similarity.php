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
        // If both ranges are identical, return perfect similarity.
        if ($arr_1 === $arr_2) {
            return 1.0;
        }

        if (
            count($arr_1) < 2 ||
            count($arr_2) < 2 ||
            is_null($arr_1[0]) ||
            is_null($arr_1[1]) ||
            is_null($arr_2[0]) ||
            is_null($arr_2[1])
        ) {
            return 0.0;
        }

        $min_1 = (int) $arr_1[0];
        $max_1 = (int) $arr_1[1];
        $min_2 = (int) $arr_2[0];
        $max_2 = (int) $arr_2[1];

        if ($min_1 > $max_1) {
            [$min_1, $max_1] = [$max_1, $min_1];
        }

        if ($min_2 > $max_2) {
            [$min_2, $max_2] = [$max_2, $min_2];
        }

        if ($max_2 < $min_1 || $min_2 > $max_1) {
            return 0.0;
        }

        $step = (int) env('BUDGET_PRICE_STEP', 20000);
        if ($step <= 0) {
            $step = 1;
        }

        $points_1 = (int) floor(($max_1 - $min_1) / $step) + 1;
        $points_2 = (int) floor(($max_2 - $min_2) / $step) + 1;

        if ($points_1 <= 0 || $points_2 <= 0) {
            return 0.0;
        }

        $overlap_min = max($min_1, $min_2);
        $overlap_max = min($max_1, $max_2);

        $mod_1 = (($min_1 % $step) + $step) % $step;
        $mod_2 = (($min_2 % $step) + $step) % $step;

        // Different arithmetic progressions cannot intersect.
        if ($mod_1 !== $mod_2) {
            return 0.0;
        }

        $offset = (($mod_1 - (($overlap_min % $step) + $step) % $step) + $step) % $step;
        $first_intersection = $overlap_min + $offset;

        if ($first_intersection > $overlap_max) {
            return 0.0;
        }

        $intersection_points = (int) floor(($overlap_max - $first_intersection) / $step) + 1;

        if ($intersection_points <= 0) {
            return 0.0;
        }

        $arr_1_diff = $points_1 - $intersection_points;
        $arr_2_diff = $points_2 - $intersection_points;

        if ($arr_1_diff <= 0) {
            return $intersection_points / $points_2;
        }

        if ($arr_2_diff <= 0) {
            return $intersection_points / $points_1;
        }

        return $intersection_points / (min($arr_2_diff, $arr_1_diff) + $intersection_points);
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
