<?php

namespace AoC;

const ROCK = 'ROCK';
const PAPER = 'PAPER';
const SCISSORS = 'SCISSORS';

class Day2 implements Solution
{
    private const MAP = [
        'A' => ROCK,
        'X' => ROCK,
        'B' => PAPER,
        'Y' => PAPER,
        'C' => SCISSORS,
        'Z' => SCISSORS,
    ];

    private const WINS_AGAINST = [
        ROCK => SCISSORS,
        PAPER => ROCK,
        SCISSORS => PAPER,
    ];

    private const SCORES = [
        ROCK => 1,
        PAPER => 2,
        SCISSORS => 3,
    ];

    private const NECESSARY_MOVE = [
        'A X' => SCISSORS,
        'A Y' => ROCK,
        'A Z' => PAPER,
        'B X' => ROCK,
        'B Y' => PAPER,
        'B Z' => SCISSORS,
        'C X' => PAPER,
        'C Y' => SCISSORS,
        'C Z' => ROCK,
    ];

    private const WIN_SCORE = 6;
    private const DRAW_SCORE = 3;

    public function part1(string $input): string
    {
        $matches = Util::splitByLines($input);
        $score = 0;
        foreach ($matches as $match) {
            $parts = explode(' ', $match);
            $opponent = self::MAP[$parts[0]];
            $me = self::MAP[$parts[1]];

            $score += $this->getScoreForMatch($me, $opponent);
        }

        return $score;
    }

    public function part2(string $input): string
    {
        $matches = Util::splitByLines($input);
        $score = 0;
        foreach ($matches as $match) {
            $parts = explode(' ', $match);
            $opponent = self::MAP[$parts[0]];

            $score += $this->getScoreForMatch(self::NECESSARY_MOVE[$match], $opponent);
        }

        return $score;
    }

    private function getScoreForMatch(string $move, string $opponent): int
    {
        $score = 0;

        $score += self::SCORES[$move];

        if ($opponent === $move) {
            $score += self::DRAW_SCORE;
        } elseif (self::WINS_AGAINST[$move] === $opponent) {
            $score += self::WIN_SCORE;
        }

        return $score;
    }
}
