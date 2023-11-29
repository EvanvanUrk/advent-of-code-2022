<?php

namespace AoC\Day7;

abstract class FsNode
{
    public function __construct(
        protected string $name,
        private ?Directory $parent = null,
    ) { }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParent(): ?Directory
    {
        return $this->parent;
    }

    abstract public function getSize(): int;

    public function getFullName(): string
    {
        $ancestorNames = [];
        $current = $this;
        while ($current->getParent() !== null) {
            $ancestorNames[] = $current->getName();
            $current = $current->getParent();
        }

        return '/' . implode('/', array_reverse($ancestorNames));
    }
}
