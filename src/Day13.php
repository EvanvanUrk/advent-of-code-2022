<?php

namespace AoC;

class Day13 implements Solution
{
    public function part1(string $input): string
    {
        $indices = [];
        foreach (explode(PHP_EOL . PHP_EOL, trim($input)) as $i => $pair) {
            $packets = explode(PHP_EOL, trim($pair));
            $packets = array_map(fn(string $packet) => $this->parse($packet), $packets);

            if ($this->compare($packets[0], $packets[1])) {
                $indices[] = $i + 1;
            }
        }

        return array_sum($indices);
    }

    public function part2(string $input): string
    {
        $lines = Util::splitByLines($input);
        $lines = array_map(fn(string $line) => trim($line), $lines);
        $lines = array_filter($lines, fn(string $line) => strlen($line) > 0);
        $lines[] = '[[2]]';
        $lines[] = '[[6]]';
        $packets = array_map(fn(string $packet) => $this->parse($packet), $lines);

        usort(
            $packets,
            fn(array $a, array $b) => match ($this->compare($a, $b)) {
                false =>  1,
                null  =>  0,
                true  => -1,
            }
        );

        $dividerIdxs = [];
        foreach ($packets as $i => $packet) {
            if ($packet === [[2]] || $packet === [[6]]) {
                $dividerIdxs[] = $i + 1;
            }
        }

        return array_product($dividerIdxs);
    }

    /**
     * @param array|int $lft
     * @param array|int $rgt
     * @return null|bool
     */
    private function compare(mixed $lft, mixed $rgt): ?bool
    {
        if (is_int($lft) && is_int($rgt)) {
//            echo sprintf("cmp: %d <> %d\n", $lft, $rgt);
            return $lft === $rgt ? null : $lft < $rgt;
        }

        if (is_int($lft)) {
            $lft = [$lft];
        }

        if (is_int($rgt)) {
            $rgt = [$rgt];
        }

        foreach ($rgt as $i => $rgtVal) {
            if (false === array_key_exists($i, $lft)) {
//                echo 'cmp: Left shorter than Right' . PHP_EOL;
                return true;
            }

            $cmp = $this->compare($lft[$i], $rgtVal);
            if (null !== $cmp) {
                return $cmp;
            }
        }

//        echo 'cmp: Equal length or Left longer than Right' . PHP_EOL;
        return count($lft) === count($rgt) ? null : false;
    }

    private function parse(string $input): array
    {
        $input = trim($input);
//        echo 'Input: ' . $input . PHP_EOL;

        if (str_starts_with($input, '[')
            && $this->findClosingBracket($input) === strlen($input) - 1) {
            $input = substr($input, 1, -1);
//            echo 'Trimmed input: ' . $input . PHP_EOL;
        }

        $packet = [];
        $val = '';
        $pos = 0;
        $len = strlen($input);
        while ($pos < $len) {
            $c = $input[$pos];
//            echo '$input[' . $pos . ']: ' . $c . PHP_EOL;

            if ($c === '[') {
                $substr = substr($input, $pos);
                $subPacket = substr($substr, 0, $this->findClosingBracket($substr) + 1);
                $packet[] = $this->parse($subPacket);
                $pos += strlen($subPacket);
                if ($pos < $len && $input[$pos + 1] === ',') { $pos += 1; }
                continue;
            } elseif ($c === ']') {
                if (is_numeric($val)) { $packet[] = (int) $val; }
                return $packet;
            } elseif ($c === ',') {
                if (is_numeric($val)) { $packet[] = (int) $val; }
                $val = '';
            } elseif (is_numeric($c)) {
                $val .= $c;
            }

            $pos += 1;
        }

        if (is_numeric($val)) { $packet[] = (int) $val; }
        return $packet;
    }

    private function findClosingBracket(string $input, int $pos = 0): int|null|false
    {
//        echo 'Finding closing bracket for: ' . $input . PHP_EOL;
        $chars = str_split(substr($input, $pos));
        if ($chars[0] !== '[') {
            return false;
        }

        $stack = 0;
        $current = 0;
        do {
            $char = array_shift($chars);
            if ($char === '[') {
                $stack += 1;
            } elseif ($char === ']') {
                $stack -= 1;
            }

            if ($stack === 0) {
                return $current + $pos;
            }

            $current += 1;
        } while ($stack > 0 && count($chars) > 0);

        return null;
    }
}
