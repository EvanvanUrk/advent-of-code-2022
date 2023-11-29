<?php

namespace AoC\Day11;

class Monkey
{
    public function __construct(
        private int $number,
        private array $items,
        private array $operation,
        private string $test,
        private array $targets
    )
    { }

    public function getNextItem(): int
    {
        return array_shift($this->items);
    }

    public function addItem(int $item): void
    {
        $this->items[] = $item;
    }

    public function doOperation(int $old): int
    {
        $args = $this->operation['args'];
        $args = array_map(fn(string $arg) => $arg === 'old' ? $old : (int) $arg, $args);
        return match ($this->operation['op']) {
            '+' => $args[0] + $args[1],
            '*' => $args[0] * $args[1],
        };
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getTargets(): array
    {
        return $this->targets;
    }

    public function getTest(): string
    {
        return $this->test;
    }
}
