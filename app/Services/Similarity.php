<?php

namespace App\Services;

class Similarity
{

    public static function simple_Diff_Sim(int $course_level_1, int $course_level_2, $min_course_level = 100, $max_course_level = 700)
    {
        return 1 - (abs($course_level_1 - $course_level_2) / ($max_course_level - $min_course_level));
    }

    //   public static function euclidean(int $value_1, int $value_2)
    //   {
    //       return $numerator/$denominator;
    //   }

    public static function minMaxNorm(int $value_1, $min = 100, $max = 700)
    {
        $numerator = $value_1 - $min;
        $denominator = $max - $min;
        return $numerator / $denominator;
    }

    public static function OVRS_kernel($arr_1_range = [], $arr_2_range)
    {
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

    public static function OVRS($arr_1 = [], $arr_2 = [])
    {
        //if both arrays are identical return one 1
        if ($arr_1 === $arr_2) {
            return 1;
        }

        //else destructure the arrays into the four needed variables
        list($min_1, $max_1) = $arr_1;
        list($min_2, $max_2) = $arr_2;

        // if the arrays donot oerlap return 0
        if (!(($max_2 >= $min_1) && ($min_2 <= $max_1))) {
            return 0;
        }

        $arr_1_range = range($arr_1[0], $arr_1[1], env('BUDGET_PRICE_STEP', 20000));
        $arr_2_range = range($arr_2[0], $arr_2[1], env('BUDGET_PRICE_STEP', 20000));

        return Similarity::OVRS_kernel($arr_1_range, $arr_2_range);
    }

    public static function jaccard($arr_1 = [], $arr_2 = [])
    {
        $intersection = array_unique(array_intersect($arr_1, $arr_2));
        $union = array_unique(array_merge($arr_1, $arr_2));

        return count($intersection) / count($union);
    }
}
