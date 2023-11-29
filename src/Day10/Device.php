<?php

namespace AoC\Day10;

class Device
{
    public function __construct(
        private readonly array $instructions,
        private int $cycle = 1,
        private int $instructionCounter = 0,
        private array $registers = [ 'X' => 1 ],
        private ?array $op = null,
    ) { }

    /**
     * Run program and return the state of the device *during* each cycle
     */
    public function runProgram(): array
    {
        $instructionCount = count($this->instructions);

        while ($this->getInstructionCounter() < $instructionCount) {
            $states[$this->getCycle()] = $this->registers;
            $this->doCycle();
        }

        return $states;
    }

    public function doCycle(): void
    {
        if ($this->op !== null) {
            $this->doCurrentOp();
            $this->op = null;
        } else {
            $this->loadCurrentOp();
            $this->instructionCounter += 1;
        }

        $this->cycle += 1;
    }

    private function doCurrentOp(): void
    {
        $this->op['fn'](...$this->op['args']);
    }

    private function loadCurrentOp(): void
    {
        $parts = explode(' ', $this->instructions[$this->instructionCounter]);
        $args = count($parts) > 1 ? [...array_slice($parts, 1)] : null;
        $this->op = $this->getOp($parts[0], $args);
    }

    private function getOp(string $instruction, ?array $args): ?array
    {
        $fn = match ($instruction) {
            'noop' => null,
            'addx' => function(int $val) {
                $this->registers['X'] += $val;
            },
        };

        return $fn === null ? null : [
            'fn' => $fn,
            'args' => $args,
        ];
    }

    public function getCycle(): int
    {
        return $this->cycle;
    }

    public function getInstructionCounter(): int
    {
        return $this->instructionCounter;
    }
}
