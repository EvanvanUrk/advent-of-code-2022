<?php

namespace AoC;

use AoC\Day10\Device;

class Day10 implements Solution
{
    private array $states;

    public function part1(string $input): string
    {
        $cmds = Util::splitByLines($input);
        $cmds = array_map(fn(string $cmd) => trim($cmd), $cmds);

        $device = new Device($cmds);
        $this->states = $states = $device->runProgram();

        $ptr = 20;
        $step = 40;
        $signalStrengths = [];
        while ($ptr <= $device->getCycle()) {
            $signalStrengths[] = $states[$ptr]['X'] * $ptr;
            $ptr += $step;
        }

        return array_sum($signalStrengths);
    }

    public function part2(string $input): string
    {
        return array_reduce(
            array_chunk($this->states, 40),
            function (string $display, array $chunk) {
                return $display . $this->statesToLine($chunk);
            },
            ''
        );
    }

    private function statesToLine(array $states) {
        return array_reduce(
            $states,
            function(string $line, array $state) {
                $pos = strlen($line);
                $spritePos = $state['X'];
                return $line . (($pos >= $spritePos - 1 && $pos <= $spritePos + 1) ? '#' : '.');
            },
            '',
        ) . PHP_EOL;
    }
}
