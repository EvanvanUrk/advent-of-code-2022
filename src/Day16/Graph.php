<?php

namespace AoC\Day16;

class Graph
{
    private array $adj;
    private array $nodes;

    public function __construct()
    {
        $this->adj = [];
        $this->nodes = [];
    }

    public function addNode(Node $node): void
    {
        if (false === array_key_exists($node->name, $this->nodes)) {
            $this->nodes[$node->name] = $node;
            $this->adj[$node->name] = [];
        }
    }

    public function getNode(string $name): ?Node
    {
        if (true === array_key_exists($name, $this->nodes)) {
            return $this->nodes[$name];
        }
        return null;
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function addEdge(Node $from, Node $to): void
    {
        if (true === array_key_exists($from->name, $this->adj)) {
            $this->adj[$from->name][] = $to->name;
        }
    }

    public function __toString(): string
    {
        $str = '';
        foreach ($this->nodes as $name => $node) {
            $str .= sprintf(
                "Valve %s has flow rate=%d; tunnels lead to valves %s\n",
                $name,
                $node->value,
                array_key_exists($name, $this->adj)
                    ? implode(', ', $this->adj[$name])
                    : '[]'
            );
        }
        return $str . PHP_EOL;
    }
}
