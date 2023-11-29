<?php

namespace AoC\Day7;

class Directory extends FsNode
{
    public function __construct(
        string $name,
        ?Directory $parent = null,
        private array $children = []
    ) {
        parent::__construct($name, $parent);
    }

    public function addChild(FsNode $child): void
    {
        if ($child->getParent() === null) {
            return;
        }

        foreach ($this->children as $sibling) {
            if ($child->getName() === $sibling->getName()) {
                return;
            }
        }

        $this->children[$child->getName()] = $child;
    }

    public function hasChild(string $name): bool
    {
        foreach ($this->children as $child) {
            if ($child->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Directory[]|File[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function getChild(string $name): ?FsNode
    {
        foreach ($this->children as $child) {
            if ($child->getName() === $name) {
                return $child;
            }
        }

        return null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSize(): int
    {
        return array_sum(
            array_map(
                fn(FsNode $node) => $node->getSize(),
                $this->children
            )
        );
    }
}
