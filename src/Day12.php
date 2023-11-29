<?php

namespace AoC;

use AoC\Day12\Map;
use AoC\Day12\Pathfinder;
use AoC\Day12\Point;
use Exception;

class Day12 implements Solution
{
    private Map $map;

    public function part1(string $input): string
    {
        $this->map = $map = new Map($input);
        $pathfinder = new Pathfinder($map);
        $pathfinder->calcDistancesFromStart();

        return $pathfinder->getDistanceFromStartToEnd() ?? 'End not found';
    }

    public function part2(string $input): string
    {
        $pathfinder = new Pathfinder($this->map);
        $end = $this->map->getEnd();
        $pathfinder->calcDistancesFromPoint($this->map->get($end['x'], $end['y']), true);

        $closestLowPointDist = null;
        foreach (range(0, $this->map->getW() - 1) as $x) {
            foreach (range(0, $this->map->getH() - 1) as $y) {
                $point = $this->map->get($x, $y);
                if ($point->z > 0) {
                    continue;
                }

                $dist = $pathfinder->getDistanceFromStartToPoint($point);
                if (null === $dist) {
                    continue;
                }

                if (null === $closestLowPointDist || $dist <= $closestLowPointDist) {
                    $closestLowPointDist = $dist;
                }
            }
        }

        return $closestLowPointDist;
    }
}
