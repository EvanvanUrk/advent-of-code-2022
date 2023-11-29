<?php

namespace AoC\Day12;

class Point
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
        public readonly int $z
    ) { }
}
