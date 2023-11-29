<?php

namespace AoC;

class Day4 implements Solution
{
    public function part1(string $input): string
    {
        $overlapCount = 0;
        $pairs = Util::splitByLines($input);
        foreach ($pairs as $pair) {
            $assigned = explode(',', $pair);
            $assigned = array_map(function (string $sections) {
                $parts = explode('-', $sections);
                return [$parts[0], $parts[1]];
            }, $assigned);

            if ($this->isWithin($assigned[0], $assigned[1]) || $this->isWithin($assigned[1], $assigned[0])) {
                $overlapCount += 1;
            }
        }

        return $overlapCount;
    }

    public function part2(string $input): string
    {
        $overlapCount = 0;
        $pairs = Util::splitByLines($input);
        foreach ($pairs as $pair) {
            $assigned = explode(',', $pair);
            $assigned = array_map(function (string $sections) {
                $parts = explode('-', $sections);
                return [$parts[0], $parts[1]];
            }, $assigned);

            if ($this->hasOverlap($assigned[0], $assigned[1]) || $this->hasOverlap($assigned[1], $assigned[0])) {
                $overlapCount += 1;
            }
        }

        return $overlapCount;
    }

    public function isWithin(array $range1, array $range2) {
        return $range1[0] >= $range2[0] && $range1[1] <= $range2[1];
    }

    public function hasOverlap(array $range1, array $range2) {
        $a0 = $range1[0];
        $a1 = $range1[1];
        $b0 = $range2[0];
        $b1 = $range2[1];
       return ($a0 >= $b0 && $a0 <= $b1) || ($a1 >= $b0 && $a1 <= $b1);
    }
}
