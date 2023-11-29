<?php

namespace AoC;

use Exception;

class Day3 implements Solution
{
    public function part1(string $input): string
    {
        $sum = 0;
        $rucksacks = Util::splitByLines($input);
        foreach ($rucksacks as $rucksack) {
            $compartments = $this->splitRucksack($rucksack);
            $commonItems = $this->getCommonItems($compartments);
            $sum += array_sum(array_map(fn(string $item) => $this->getItemPriority($item), $commonItems));
        }

        return $sum;
    }

    public function part2(string $input): string
    {
        $sum = 0;
        $rucksacks = Util::splitByLines($input);
        foreach (array_chunk($rucksacks, 3) as $group) {
            $commonItems = $this->getCommonItems([$group[0], $group[1]]);
            $commonItems = $this->getCommonItems([implode($commonItems), $group[2]]);
            $sum += $this->getItemPriority($commonItems[0]);
        }

        return $sum;
    }

    private function getCommonItems(array $compartments): array
    {
        $common = [];
        foreach (str_split($compartments[0]) as $item) {
            if (str_contains($compartments[1], $item) && false === in_array($item, $common)) {
                $common[] = $item;
            }
        }
        return $common;
    }

    private function splitRucksack(string $items): array
    {
        return str_split($items, strlen($items) / 2);
    }

    private function getItemPriority(string $item): int
    {
        if (strlen($item) !== 1) {
            throw new Exception('An item should be exactly 1 character');
        }

        $code = ord($item);

        if ($code >= 65 && $code < 91) {
            return $code - 38;
        } elseif ($code >= 97 && $code < 123) {
            return $code - 96;
        }

        throw new Exception('Invalid item value ' . $item);
    }

    private function testItemPriorities(): void
    {
        $abc = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        foreach (str_split($abc) as $idx => $item) {
            $priority = $this->getItemPriority($item);
            if ($idx + 1 !== $priority) {
                throw new Exception(sprintf('Invalid item priority %s for item %d', $priority, $item));
            }
        }
    }
}
