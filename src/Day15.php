<?php

namespace AoC;

class Day15 implements Solution
{
    private array $parsed;

    public function part1(string $input): string
    {
        $this->parsed = [$sensors, $occupied] = $this->parse($input);

//        $targetRow = 10;
        $targetRow = 2000000;
        $scanned = $this->getScannedSpacesForRow($sensors, $targetRow);
        $count = array_sum(array_map(fn(array $range) => abs(($range[1] + 1) - $range[0]), $scanned));
        if (true === array_key_exists($targetRow, $occupied)) {
            $count -= count($occupied[$targetRow]);
        }
        return $count;
    }

    public function part2(string $input): string
    {
        [$sensors, $occupied] = $this->parsed;

        $min = 0;
        $max = 4000000;
        $mult = 4000000;
        $y = $min;
        while ($y <= $max) {
            $scanned = $this->getScannedSpacesForRow($sensors, $y);
            if (count($scanned) > 1) {
                return (($scanned[0][1] + 1) * $mult) + $y;
            }

            $y += 1;
        }

        return 'oops';
    }

    private function getScannedSpacesForRow(array $sensors, int $targetRow): array
    {
        $scannedRanges = [];
        foreach ($sensors as $sensor) {
            $x = $sensor[0];
            $y = $sensor[1];
            $beacon = $sensor[2];

            $manhattanDist = abs($x - $beacon[0]) + abs($y - $beacon[1]);
            if ($y + $manhattanDist >= $targetRow && $y - $manhattanDist <= $targetRow) {
                $range = $manhattanDist - (abs($y - $targetRow));
                $scannedRanges[] = [$x - $range, $x + $range];
            }
        }

        return $this->mergeOverlap($scannedRanges);
    }

    private function mergeOverlap(array $arr): array
    {
        usort($arr, fn(array $a, array $b) => $a[0] - $b[0]);

        $merged = [];
        $curMin = null;
        $curMax = null;
        foreach ($arr as $range) {
            if (null === $curMin && null === $curMax) {
                $curMin = $range[0];
                $curMax = $range[1];
                continue;
            }

            if ($range[0] > $curMax) {
                $merged[] = [$curMin, $curMax];
                $curMin = $range[0];
                $curMax = $range[1];
                continue;
            }

            if ($range[1] > $curMax) {
                $curMax = $range[1];
            }
        }
        $merged[] = [$curMin, $curMax];
        return $merged;
    }

    private function parse(string $input): array
    {
        $lines = Util::splitByLines($input);
        $sensors = [];
        $beacons = [];
        foreach ($lines as $line) {
            $coords = explode('=', $line);
            array_shift($coords);
            $coords = array_map(
                fn(string $str) => (int) $str,
                $coords
            );
            $beacons[] = $beacon = [$coords[2], $coords[3]];
            $sensors[] = [$coords[0], $coords[1], $beacon];
        }

        $occupied = [];
        foreach (array_merge($beacons, $sensors) as $point) {
            if (false === array_key_exists($point[1], $occupied)) {
                $occupied[$point[1]] = [];
            }
            $occupied[$point[1]][$point[0]] = true;
        }

        return [$sensors, $occupied];
    }
}
