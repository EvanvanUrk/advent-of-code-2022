<?php

namespace AoC;

class Day8 implements Solution
{
    public function part1(string $input): string
    {
        $map = Util::splitByLines($input);
        $map = array_map(fn(string $line) => str_split($line), $map);

        $visibleCount = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $col) {
                if (
                    $this->checkVisibleInDirection($map, $x, $y, 1, 0)
                    || $this->checkVisibleInDirection($map, $x, $y, -1, 0)
                    || $this->checkVisibleInDirection($map, $x, $y, 0, 1)
                    || $this->checkVisibleInDirection($map, $x, $y, 0, -1)
                ) {
                    $visibleCount += 1;
                }
            }
        }

        return $visibleCount;
    }

    public function part2(string $input): string
    {
        $map = Util::splitByLines($input);
        $map = array_map(fn(string $line) => str_split($line), $map);

        $currentScore = 0;
        foreach ($map as $y => $row) {
            foreach ($row as $x => $col) {
                $scores = [
                    $this->getScoreInDirection($map, $x, $y, 1, 0),
                    $this->getScoreInDirection($map, $x, $y, -1, 0),
                    $this->getScoreInDirection($map, $x, $y, 0, 1),
                    $this->getScoreInDirection($map, $x, $y, 0, -1)
                ];

                $newScore = array_product($scores);
                if ($newScore > $currentScore) {
                    $currentScore = $newScore;
                }
            }
        }

        return $currentScore;
    }

    private function checkVisibleInDirection(array $map, int $x, int $y, int $stepX, int $stepY): bool
    {
        if ($stepX === 0 && $stepY === 0) {
            return false;
        }

        $treeHeight = $map[$y][$x];

        $width = count($map[0]);
        $height = count($map);

        if ($x === 0 || $x === ($width - 1) || $y === 0 || $y === ($height - 1)) {
            return true;
        }

        $curX = $x + $stepX;
        $curY = $y + $stepY;

        while ($curX < $width && $curX >= 0 && $curY < $height && $curY >= 0) {
            if ($map[$curY][$curX] >= $treeHeight) {
                return false;
            }

            $curX += $stepX;
            $curY += $stepY;
        }

        return true;
    }

    private function getScoreInDirection(array $map, int $x, int $y, int $stepX, int $stepY): int
    {
        if ($stepX === 0 && $stepY === 0) {
            return 0;
        }

        $treeHeight = $map[$y][$x];

        $width = count($map[0]);
        $height = count($map);

        $curX = $x + $stepX;
        $curY = $y + $stepY;

        $seenTrees = [];

        while ($curX < $width && $curX >= 0 && $curY < $height && $curY >= 0) {
            if ($map[$curY][$curX] < $treeHeight) {
                $seenTrees[] = $map[$curY][$curX];
            }

            if ($map[$curY][$curX] >= $treeHeight) {
                $seenTrees[] = $map[$curY][$curX];
                break;
            }

            $curX += $stepX;
            $curY += $stepY;
        }

        return count($seenTrees);
    }
}
