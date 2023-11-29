<?php

namespace AoC\Day16;

class Node
{
    public function __construct(
        public readonly string $name,
        public readonly int $value
    ) { }
}
