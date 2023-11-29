<?php

namespace AoC;

class Day1 implements Solution
{
    private array $sortedCounts;

    public function part1(string $input): string
    {
        $lines = Util::splitByLines($input);
        $calorieCounts = [];
        $currentCount = 0;
        foreach ($lines as $line) {
            if ($line !== '') {
                $currentCount += (int) $line;
            } else {
                $calorieCounts[] = $currentCount;
                $currentCount = 0;
            }
        }

        sort($calorieCounts);
        $this->sortedCounts = $calorieCounts;

        return end($this->sortedCounts);
    }

    public function part2(string $input): string
    {
        return array_sum(array_slice($this->sortedCounts, -3));
    }
}
