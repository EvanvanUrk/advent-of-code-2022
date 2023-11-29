<?php

namespace AoC\Day12;

use AoC\Util;

class Map
{
    private int $w;
    private int $h;

    private array $map = [];
    private ?array $start = null;
    private ?array $end = null;

    /** @var array From char to numerical */
    private array $zMap;
    /** @var array From numerical to char */
    private array $zMapR;

    public function __construct(string $input)
    {
        $this->zMapR = str_split(implode(range('a', 'z')));
        $this->zMap = array_flip($this->zMapR);
        $this->zMap['S'] = 0;
        $this->zMap['E'] = 25;

        $lines = Util::splitByLines(trim($input));
        $lines = array_map(fn(string $line) => trim($line), $lines);

        foreach ($lines as $y => $line) {
            $this->map[$y] = [];
            foreach (str_split($line) as $x => $z) {
                if ($z === 'S') { $this->start = ['x' => $x, 'y' => $y]; }
                if ($z === 'E') { $this->end = ['x' => $x, 'y' => $y]; }
                $this->map[$y][$x] = new Point($x, $y, $this->zMap[$z]);
            }
        }

        $this->h = count($this->map);
        $this->w = count($this->map[0]);
    }

    public function get(int $x, int $y): ?Point
    {
        return $x < 0 || $x >= $this->w
            || $y < 0 || $y >= $this->h
            ? null : $this->map[$y][$x];
    }

    public function getStart(): ?array
    {
        return $this->start;
    }

    public function getEnd(): ?array
    {
        return $this->end;
    }

    public function getW(): int
    {
        return $this->w;
    }

    public function getH(): int
    {
        return $this->h;
    }

    public function getZMap(): array
    {
        return $this->zMap;
    }

    public function getZMapR(): array
    {
        return $this->zMapR;
    }

    /** @return Point[] */
    public function getPossibleMoves(Point $point, bool $reverse = false): array
    {
        $directions = [
            ['x' =>  1, 'y' =>  0],
            ['x' => -1, 'y' =>  0],
            ['x' =>  0, 'y' =>  1],
            ['x' =>  0, 'y' => -1],
        ];

        $moves = [];
        foreach ($directions as $direction) {
            $target = $this->get($point->x + $direction['x'], $point->y + $direction['y']);
            if (null === $target) { continue; }

            if ((false === $reverse && $target->z <= $point->z + 1)
                || (true === $reverse && $target->z >= $point->z - 1)) {
                $moves[] = $target;
            }
        }

        return $moves;
    }

    public function isStart(Point $point): bool
    {
        if (null === $this->start) { return false; }
        return $point->x === $this->start['x'] && $point->y === $this->start['y'];
    }

    public function isEnd(Point $point): bool
    {
        if (null === $this->end) { return false; }
        return $point->x === $this->end['x'] && $point->y === $this->end['y'];
    }

    public function __toString(): string
    {
        return array_reduce(
            $this->map,
            function(string $str, array $points) {
                return $str . array_reduce(
                    $points,
                    function(string $str, Point $point) {
                        if ($this->isStart($point)) { return $str . 'S'; }
                        if ($this->isEnd($point)) { return $str . 'E'; }
                        return $str . $this->zMapR[$point->z];
                    },
                    ''
                ) . PHP_EOL;
            },
            ''
        );
    }
}
