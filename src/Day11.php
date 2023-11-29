<?php

namespace AoC;

use AoC\Day11\Monkey;

class Day11 implements Solution
{
    public function part1(string $input): string
    {
        $monkeyNotes = explode(PHP_EOL . PHP_EOL, trim($input));
        $monkeyNotes = array_map(fn(string $monkey) => trim($monkey), $monkeyNotes);

        $monkeys = array_map(fn(string $notes) => $this->parseNotes($notes), $monkeyNotes);
        $monkeyBusiness = $this->doRounds($monkeys, 20, true);

        sort($monkeyBusiness);
        return array_product(array_slice($monkeyBusiness, -2));
    }

    public function part2(string $input): string
    {
        $monkeyNotes = explode(PHP_EOL . PHP_EOL, trim($input));
        $monkeyNotes = array_map(fn(string $monkey) => trim($monkey), $monkeyNotes);

        $monkeys = array_map(fn(string $notes) => $this->parseNotes($notes), $monkeyNotes);
        $monkeyBusiness = $this->doRounds($monkeys, 10000, false);

        sort($monkeyBusiness);
        return array_product(array_slice($monkeyBusiness, -2));
    }

    private function doRounds(array $monkeys, int $rounds, bool $divide): array
    {
        $monkeyBusiness = [];
        $lcm = array_product(
            array_map(fn(Monkey $monkey) => $monkey->getTest(), $monkeys)
        );
        foreach (range(1, $rounds) as $round) {
            foreach ($monkeys as $num => $monkey) {
                if (false === array_key_exists($num, $monkeyBusiness)) {
                    $monkeyBusiness[$num] = 0;
                }

                while (count($monkey->getItems()) > 0) {
                    $item = $monkey->getNextItem();

                    $newValue = $monkey->doOperation($item);
                    if (true === $divide) {
                        $newValue = (int) floor((float) $newValue / 3.0);
                    }
                    $newValue = $newValue % $lcm;
                    $target = (int) $monkey->getTargets()[$newValue % $monkey->getTest() === 0];

                    $targetMonkey = $monkeys[$target];
                    $targetMonkey->addItem($newValue);

//                    echo sprintf("Monkey %d: Throwing %d to monkey %d\n", $num, $newValue, $target);

                    $monkeyBusiness[$num] += 1;
                }
            }
//            echo PHP_EOL;
        }

        return $monkeyBusiness;
    }

    private function parseNotes(string $note): Monkey
    {
        $lines = Util::splitByLines($note);

        preg_match('/Monkey ([0-9]+):/', $lines[0], $matches);
        $num = $matches[1];

        $items = explode(': ', $lines[1])[1];
        $items = explode(', ', $items);
        $items = array_map(fn(string $item) => (int) $item, $items);

        $operation = explode(' = ', explode(': ', $lines[2])[1])[1];
        preg_match('/(old|[0-9]+) ([*+]) (old|[0-9]+)/', $operation, $matches);
        $operation = [
            'args' => [$matches[1], $matches[3]],
            'op' => $matches[2],
        ];

        $test = explode(' ', $lines[3])[5];

        $targets = [
            true => explode(' ', $lines[4])[9],
            false => explode(' ', $lines[5])[9],
        ];

        return new Monkey($num, $items, $operation, $test, $targets);
    }
}