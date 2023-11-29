<?php

namespace AoC;

class Day9 implements Solution
{
    public function part1(string $input): string
    {
        $cmds = Util::splitByLines($input);
        $cmds = array_map(fn(string $cmd) => trim($cmd), $cmds);

        $headX = 0;
        $headY = 0;
        $tailX = 0;
        $tailY = 0;
        $visitedByTail = [];

        foreach ($cmds as $cmd) {
            $parts = explode(' ', $cmd);
            $dir = $parts[0];
            $steps = $parts[1];

            while ($steps > 0) {
                $prevHeadX = $headX;
                $prevHeadY = $headY;

                [$headX, $headY] = $this->doStep([$headX, $headY], $dir);

                if ($this->shouldTailMove([$headX, $headY], [$tailX, $tailY])) {
                    $tailX = $prevHeadX;
                    $tailY = $prevHeadY;
                }

                $key = 'x' . $tailX . 'y' . $tailY;
                $visitedByTail[$key] = true;

                $steps -= 1;
            }
        }

        return count($visitedByTail);
    }

    public function part2(string $input): string
    {
        $cmds = Util::splitByLines($input);
        $cmds = array_map(fn(string $cmd) => trim($cmd), $cmds);

        $rope = [];
        $length = 10;
        for ($i = 0; $i < $length; $i += 1) {
            $rope[] = [0, 0];
        }

        $visitedByTail = [];

        foreach ($cmds as $cmd) {
            $parts = explode(' ', $cmd);
            $dir = $parts[0];
            $steps = $parts[1];

            while ($steps > 0) {
                $rope[0] = $this->doStep($rope[0], $dir);
                foreach (array_slice($rope, 1) as $idx => $head) {
                    // $idx starts at 0 without preserving keys in array_slice, so current is idx + 1
                    $rope[$idx + 1] = $this->doTailStep($rope[$idx], $rope[$idx + 1]);
                }

                $tail = $rope[$length - 1];
                $key = 'x' . $tail[0] . 'y' . $tail[1];
                $visitedByTail[$key] = true;

                $steps -= 1;
            }
        }

        return count($visitedByTail);
    }

    private function doStep(array $pos, string $dir): array
    {
        switch ($dir) {
            case 'U':
                $pos[1] -= 1;
                break;
            case 'D':
                $pos[1] += 1;
                break;
            case 'L':
                $pos[0] -= 1;
                break;
            case 'R':
                $pos[0] += 1;
                break;
            default:
                break;
        }

        return $pos;
    }

    private function shouldTailMove(array $head, array $tail): bool
    {
        $distX = abs($head[0] - $tail[0]);
        $distY = abs($head[1] - $tail[1]);
        return (($distX + $distY) > 2 || $distX >= 2 || $distY >= 2);
    }

    private function doTailStep(array $head, array $tail): array
    {
        $distX = abs($head[0] - $tail[0]);
        $distY = abs($head[1] - $tail[1]);

        if ($distX < 2 && $distY < 2) {
            return $tail;
        }

        $x = $tail[0];
        $y = $tail[1];
        if ($x !== $head[0]) {
            $x = $x < $head[0] ? $x + 1 : $x - 1;
        }
        if ($y !== $head[1]) {
            $y = $y < $head[1] ? $y + 1 : $y - 1;
        }

        return [$x, $y];
    }

    private function display(array $min, array $max, array $rope) {
        $display = [];
        for ($y = $min[1]; $y < $max[1]; $y += 1) {
            $display[$y] = [];
            for ($x = $min[0]; $x < $max[0]; $x += 1) {
                $display[$y][$x] = '.';
            }
        }

        foreach (array_reverse($rope, true) as $idx => $part) {
            $display[$part[1]][$part[0]] = $idx;
        }

        foreach ($display as $row) {
            echo implode('', $row) . PHP_EOL;
        }
    }
}
