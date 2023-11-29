<?php

namespace AoC;

use AoC\Day14\Map;
use AoC\Day14\State;

class Day14 implements Solution
{
    const SPAWN = [500, 0];

    private Map $map;

    public function part1(string $input): string
    {
        $paths = Util::splitByLines($input);
        $paths = array_map(
            fn(string $path) => array_map(
                fn(string $point) => array_map(
                    fn(string $n) => (int) $n,
                    explode(',', trim($point))
                ),
                explode(' -> ', trim($path))
            ),
            $paths
        );

        $xBounds = Util::getBounds($paths, 0);
        $yBounds = Util::getBounds($paths, 1);

        $this->map = $map = new Map($xBounds['min'], $xBounds['max'], 0, $yBounds['max']);
        foreach ($paths as $path) {
            $map->setPath($path, State::Rock);
        }

        do {
            $mapStr = (string) $map;
            $this->dropSand($map, self::SPAWN[0], self::SPAWN[1]);
        } while ((string) $map !== $mapStr);

        return $map->getStateCount(State::Sand);
    }

    public function part2(string $input): string
    {
        $map = $this->map;

        $yFloor = $map->getYMax() + 2;
        $height = abs($map->getYMax() - $map->getYMin());
        $map->setPath([
            [self::SPAWN[0] - $height - 2, $yFloor],
            [self::SPAWN[0] + $height + 2, $yFloor]
        ], State::Rock);

        while ($map->get(self::SPAWN[0], self::SPAWN[1]) !== State::Sand) {
            $this->dropSand($map, self::SPAWN[0], self::SPAWN[1]);
        }

        return $map->getStateCount(State::Sand);
    }

    private function dropSand(Map $map, int $x, int $y)
    {
        if ($map->get($x, $y) !== State::Open) {
            return;
        }

        do {
            $possibleMoves = $this->getNextMoves($map, $x, $y);

            foreach ([1, 0, 2] as $priority) {
                $checkMove = $possibleMoves[$priority];
                if ($checkMove === State::Open) {
                    $x += $priority - 1;
                    $y += 1;
                    break;
                }
            }

            $openCount = count(
                array_filter(
                    $possibleMoves,
                    fn(?State $state) => $state === State::Open
                )
            );
        } while ($openCount > 0 && $y <= $map->getYMax());

        $map->set($x, $y, State::Sand);
    }

    private function getNextMoves(Map $map, int $x, int $y): array
    {
        return array_map(
            fn(int $i) => $map->get($x + $i, $y + 1),
            range(-1, 1),
        );
    }
}
