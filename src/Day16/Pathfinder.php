<?php

namespace AoC\Day16;

class Pathfinder
{
    private array $highestScorePerNode;
    private int $numValvesToOpen;
    private array $routes;

    public function __construct(
        private readonly Graph $graph,
        private readonly int $maxSteps
    ) {
        $this->highestValuePerNode = [];
        $this->routes = [];

        $this->numValvesToOpen = count(
            array_filter($graph->getNodes(), fn(Node $node) => $node->value > 0)
        );
    }

    private function traverseFromPoint(
        Node $node,
        array $path = [],
        array $valvesOpened = [],
        int $totalScore = 0,
        int $currentSteps = 0
    ): void {
        if ($totalScore > $this->highestValuePerNode[$node->name]) {
            $this->highestValuePerNode[$node->name] = $totalScore;
        }

        // if node has value (valve pressure relief > 0)
        //   and was not opened before
        //   and currentSteps < $this->maxSteps
        // - open valve (add to opened valves)
        // - add value to score
        // - currentSteps += 1

        if ($currentSteps === $this->maxSteps
            || count($valvesOpened) === $this->numValvesToOpen) {
            $this->routes[] = [
                'score' => $totalScore,
                'steps' => $currentSteps,
                'path' => $path,
            ];
            return;
        }

        // foreach edge of current node as nextNode
        // if nextNode is not in highestScorePerNode
        //   or totalScore + nextNode->value > highestScorePerNode[nextNode]
        // - recurse
        //   - path = array_merge($path, $node)
        //   - currentSteps = currentSteps + 1

        return;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
