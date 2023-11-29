<?php

namespace AoC\Day5;

class Move
{
    public function __construct(
        private int $amount,
        private int $from,
        private int $to,
    ) { }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getFrom(): int
    {
        return $this->from;
    }

    public function getTo(): int
    {
        return $this->to;
    }
}
