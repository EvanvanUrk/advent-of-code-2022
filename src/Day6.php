<?php

namespace AoC;

class Day6 implements Solution
{
    public function part1(string $input): string
    {
        $input = trim($input);
        return $this->findUniqueString($input, 4) + 3;
    }

    public function part2(string $input): string
    {
        $input = trim($input);
        return $this->findUniqueString($input, 14) + 13;
    }

    private function findUniqueString(string $subject, int $length): int
    {
        $pos = 0;
        $uniqueCount = 0;
        while ($uniqueCount < $length && $pos < strlen($subject)) {
            $substr = substr($subject, $pos, $length);
            $pos += 1;
            $uniqueCount = count(array_unique(str_split($substr)));
        }

        return $pos;
    }
}
