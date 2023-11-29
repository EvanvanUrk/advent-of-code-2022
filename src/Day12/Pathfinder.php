<?php

namespace AoC\Day12;

use Exception;

class Pathfinder
{
    public function __construct(
        private readonly Map $map,
        private array $distances = [],
        private array $route = []
    ) {
        $this->initDistances();
    }

    public function initDistances(): void
    {
        foreach (range(0, $this->map->getH()) as $y) {
            foreach (range(0, $this->map->getW()) as $x) {
                $this->distances[$this->getPointKey($x, $y)] = null;
            }
        }
    }

    public function calcDistancesFromStart(): void
    {
        $start = $this->map->getStart();
        $this->traverse([$this->map->get($start['x'], $start['y'])]);
    }

    public function calcDistancesFromPoint(Point $point, bool $reverse): void
    {
        $this->traverse([$point], null, $reverse);
    }

    private function traverse(array $route = [], ?Point $end = null, bool $reverse = false): void
    {
        $currentLen = count($route) - 1;
        if ($currentLen < 0) {
            throw new Exception('Route needs to start with at least 1 point');
        }

        $current = end($route);
        $this->distances[$this->getPointKey($current->x, $current->y)] = $currentLen;

        if (null !== $end && $current->x === $end->x && $current->y === $end->y) {
            $this->route = $route;
            return;
        }

        $moveset = $this->map->getPossibleMoves($current, $reverse);
        if (null !== $end) {
            $moveset = $this->sortMoves($moveset, $end);
        } else {
            $moveset = array_map(fn(Point $point) => [$point], $moveset);
        }

        // traverse, skipping paths when the current path is already longer
        foreach ($moveset as $moves) {
            foreach ($moves as $move) {
                $key = $this->getPointKey($move->x, $move->y);
                if (null === $this->distances[$key]
                    || $currentLen + 1 < $this->distances[$key]) {
                    $this->traverse([...$route, $move], $end, $reverse);
                }
            }
        }
    }

    public function getDistanceFromStartToEnd(): ?int
    {
        $end = $this->map->getEnd();
        return $this->distances[$this->getPointKey($end['x'], $end['y'])];
    }

    public function getDistanceFromStartToPoint(Point $point): ?int
    {
        return $this->distances[$this->getPointKey($point->x, $point->y)];
    }

    public function display(int $padTo = 4): string
    {
        $str = '';
        foreach (range(0, $this->map->getH() - 1) as $y) {
            foreach (range(0, $this->map->getW() - 1) as $x) {
                $char = $this->map->getZMapR()[$this->map->get($x, $y)->z];
                if (['x' => $x, 'y' => $y] === $this->map->getStart()) { $char = 'S'; }
                if (['x' => $x, 'y' => $y] === $this->map->getEnd()) { $char = 'E'; }
                $str .= str_pad($char, $padTo, ' ', STR_PAD_LEFT);
            }
            $str .= PHP_EOL;

            foreach (range(0, $this->map->getW() - 1) as $x) {
                $str .= str_pad($this->distances[$this->getPointKey($x, $y)] ?? '#', $padTo, ' ', STR_PAD_LEFT);
            }
            $str .= PHP_EOL . PHP_EOL;
        }

        return $str;
    }

    public function getRoute(): array
    {
        return $this->route;
    }

    private function sortMoves(array $moves, Point $end): array
    {
        // prefer moves with closer overall distance to target, regardless of possible paths
        $sortedMoves = [];
        foreach ($moves as $move) {
            $distToEnd = $this->manhattanDist(
                ['x' => $move->x, 'y' => $move->y],
                ['x' => $end->x, 'y' => $end->y]
            );
            if (false === array_key_exists($distToEnd, $sortedMoves)) {
                $sortedMoves[$distToEnd] = [];
            }
            $sortedMoves[$distToEnd][] = $move;
        }
        ksort($sortedMoves);

        // prefer moving up
        foreach ($sortedMoves as $idx => $moves) {
            usort($moves, function(Point $a, Point $b) { return $b->z - $a->z; });
            $sortedMoves[$idx] = $moves;
        }

        return $sortedMoves;
    }

    private function manhattanDist(array $a, array $b): int
    {
        return (abs($a['x'] - $b['x']) + abs($a['y'] - $b['y']));
    }

    private function getPointKey(int $x, int $y): string
    {
        return $x . ':' . $y;
    }
}
