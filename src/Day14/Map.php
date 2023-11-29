<?php

namespace AoC\Day14;

class Map
{
    public function __construct(
        private int $xMin,
        private int $xMax,
        private int $yMin,
        private int $yMax,
        private array $map = [],
    ) {
        $this->map = [];
        foreach (range($yMin, $yMax) as $y) {
            $this->map[$y] = [];
            foreach (range($xMin, $xMax) as $x) {
                $this->map[$y][$x] = State::Open;
            }
        }
    }

    public function get(int $x, int $y): State
    {
        if (array_key_exists($y, $this->map) && array_key_exists($x, $this->map[$y])) {
            return $this->map[$y][$x];
        }

        return State::Open;
    }

    public function set(int $x, int $y, State $state): void
    {
        if ($x < $this->xMin || $x > $this->xMax || $y < $this->yMin || $y > $this->yMax) {
            return;
        }

        if (false === array_key_exists($y, $this->map)) {
            $this->map[$y] = [];
        }

        $this->map[$y][$x] = $state;
    }

    public function getStateCount(State $state): int
    {
        return array_reduce(
            $this->map,
            fn(int $count, array $row) => $count + count(
                array_filter(
                    $row,
                    fn(State $col) => $col === $state
                )
            ),
            0
        );
    }

    public function setPath(array $path, State $state): void
    {
        foreach (range(0, count($path) - 2) as $i) {
            [$x, $y] = $path[$i];
            $end = $path[$i + 1];

            $xStep = $x !== $end[0] ? ($x < $end[0] ? 1 : -1) : 0;
            $yStep = $y !== $end[1] ? ($y < $end[1] ? 1 : -1) : 0;

            while (($xStep > 0 && $x <= $end[0]) || ($xStep < 0 && $x >= $end[0])
                || ($yStep > 0 && $y <= $end[1]) || ($yStep < 0 && $y >= $end[1])) {
                if (false === array_key_exists($y, $this->map)) {
                    $this->map[$y] = [];
                    if ($y < $this->yMin) { $this->yMin = $y; }
                    if ($y > $this->yMax) { $this->yMax = $y; }
                }

                if ($x < $this->xMin) { $this->xMin = $x; }
                if ($x > $this->xMax) { $this->xMax = $x; }

                $this->map[$y][$x] = $state;
                $x += $xStep;
                $y += $yStep;
            }
        }
    }

    public function getXMin(): int
    {
        return $this->xMin;
    }

    public function getXMax(): int
    {
        return $this->xMax;
    }

    public function getYMin(): int
    {
        return $this->yMin;
    }

    public function getYMax(): int
    {
        return $this->yMax;
    }

    public function __toString(): string
    {
        $str = '';

        foreach (range($this->yMin, $this->yMax) as $y) {
            foreach (range($this->xMin, $this->xMax) as $x) {
                $str .= $this->get($x, $y)->value;
            }
            $str .= PHP_EOL;
        }

        return $str . PHP_EOL;
    }
}
