<?php

namespace AoC;

use AoC\Day5\CrateStorage;
use AoC\Day5\Move;

class Day5 implements Solution
{
    public function part1(string $input): string
    {
        $parts = explode(PHP_EOL . PHP_EOL, $input);
        $state = $this->parseState($parts[0]);
        $moves = $this->parseMoves($parts[1]);

        foreach ($moves as $move) {
            $state->applyMove($move);
        }

        $answer = '';
        foreach ($state->getStacks() as $stack) {
            $answer .= end($stack);
        }

        return $answer;
    }

    public function part2(string $input): string
    {
        $parts = explode(PHP_EOL . PHP_EOL, $input);
        $state = $this->parseState($parts[0]);
        $moves = $this->parseMoves($parts[1]);

        foreach ($moves as $move) {
            $state->applyMoveSimultaneous($move);
        }

        $answer = '';
        foreach ($state->getStacks() as $stack) {
            $answer .= end($stack);
        }

        return $answer;
    }

    private function parseState(string $input): CrateStorage
    {
        $crateStorage = new CrateStorage();

        $crateLines = [];
        foreach (explode(PHP_EOL, $input) as $line) {
            if ($line === '') {
                continue;
            }

            $matches = [];
            if (0 === preg_match_all('/([0-9]+)/', $line, $matches)) {
                $crateLines[] = $line;
                continue;
            }

            foreach ($matches[0] as $match) {
                $crateStorage->createStackIfNotExists((int) $match);
            }
        }

        $keys = array_keys($crateStorage->getStacks());
        foreach ($crateLines as $crateLine) {
            preg_match_all('/( {4}|([A-Z]) ?)/', $crateLine, $matches);
            foreach ($matches[0] as $i => $match) {
                if ($match !== '    ') {
                    $crateStorage->addCrateToStack($keys[$i], $match, true);
                }
            }
        }

        return $crateStorage;
    }

    /**
     * @return Move[]
     */
    private function parseMoves(string $input): array
    {
        $moves = [];

        foreach (Util::splitByLines($input) as $line) {
            preg_match('/move ([0-9]+) from ([0-9]+) to ([0-9]+)/', $line, $matches);
            $moves[] = new Move($matches[1], $matches[2], $matches[3]);
        }

        return $moves;
    }
}
