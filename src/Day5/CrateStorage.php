<?php

namespace AoC\Day5;

class CrateStorage
{
    public function __construct(
        private array $stacks = [],
    ) { }

    public function getStacks(): array
    {
        return $this->stacks;
    }

    public function createStackIfNotExists(int $stack): void
    {
        if (false === array_key_exists($stack, $this->stacks)) {
            $this->stacks[$stack] = [];
        }
    }

    public function addCrateToStack(int $stack, string $crate, bool $unshift = false): void
    {
        $this->createStackIfNotExists($stack);
        if ($unshift) {
            array_unshift($this->stacks[$stack], $crate);
        } else {
            $this->stacks[$stack][] = $crate;
        }
    }

    public function applyMove(Move $move): void
    {
        for ($i = 0; $i < $move->getAmount(); $i += 1) {
            $crate = array_pop($this->stacks[$move->getFrom()]);
            if ($crate !== null) {
                $this->addCrateToStack($move->getTo(), $crate);
            }
        }
    }

    public function applyMoveSimultaneous(Move $move): void
    {
        $crates = [];
        for ($i = 0; $i < $move->getAmount(); $i += 1) {
            $popped = array_pop($this->stacks[$move->getFrom()]);
            if ($popped !== null) {
                $crates[] = $popped;
            }
        }

        $crates = array_reverse($crates);
        foreach ($crates as $crate) {
            $this->addCrateToStack($move->getTo(), $crate);
        }
    }
}
