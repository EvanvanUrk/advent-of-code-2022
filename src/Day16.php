<?php

namespace AoC;

use AoC\Day16\Graph;
use AoC\Day16\Node;

class Day16 implements Solution
{
    public function part1(string $input): string
    {
        $lines = Util::splitByLines($input);
        $lines = array_map(fn(string $line) => trim($line), $lines);
        $graph = new Graph();
        $adjacencies = [];
        foreach ($lines as $line) {
            $parts = explode(' ', $line);
            $name = $parts[1];
            $val = (int)substr($parts[4], 5, -1);
            $adjacencies[$name] = array_map(
                fn(string $adj) => substr($adj, 0, 2),
                array_slice($parts, 9)
            );
            $graph->addNode(new Node($name, $val));
        }

        foreach ($adjacencies as $name => $adjacent) {
            $from = $graph->getNode($name);
            if (null === $from) { continue; }

            foreach ($adjacent as $to) {
                $to = $graph->getNode($to);
                if (null === $to) { continue; }
                $graph->addEdge($from, $to);
            }
        }

        echo $graph;

        return '';
    }

    public function part2(string $input): string
    {
        return '';
    }
}
