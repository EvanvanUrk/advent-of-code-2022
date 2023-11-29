<?php

namespace AoC\Day7;

class File extends FsNode
{
    public function __construct(
        string $name,
        Directory $parent,
        private int $size
    ) {
        parent::__construct($name, $parent);
    }

    public function getSize(): int
    {
        return $this->size;
    }
}
