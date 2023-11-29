<?php

namespace AoC;

use AoC\Day7\Directory;
use AoC\Day7\File;

class Day7 implements Solution
{
    private Directory $fs;
    private array $sizeList;

    public function part1(string $input): string
    {
        $this->fs = $fs = $this->parseFs($input);
        $maxSize = 100000;
        $this->sizeList = $sizeList = $this->listAllDirectorySizes($fs);
        return array_sum(array_filter($sizeList, fn (int $size) => $size <= $maxSize));
    }

    public function part2(string $input): string
    {
        $diskSize = 70000000;
        $spaceRequired = 30000000;
        $used = $this->fs->getSize();
        $available = $diskSize - $used;
        $minDeleteSize = $spaceRequired - $available;
        $this->sizeList = array_filter($this->sizeList, fn (int $size) => $size >= $minDeleteSize);
        sort($this->sizeList);
        return reset($this->sizeList);
    }

    private function listAllDirectorySizes(Directory $dir): array
    {
        $list = [];
        $list[$dir->getFullName()] = $dir->getSize();
        foreach ($dir->getChildren() as $child) {
            if ($child instanceof Directory) {
                $childList = $this->listAllDirectorySizes($child);
                $list = array_merge($list, $childList);
            }
        }
        return $list;
    }

    private function parseFs(string $input): Directory
    {
        $fs = new Directory('');
        $currentDir = $fs;
        $lines = Util::splitByLines($input);
        array_shift($lines);
        foreach ($lines as $line) {
            $parts = explode(' ', trim($line));
            if (str_starts_with($line, '$')) {
                if ($parts[1] === 'cd') {
                    if ($parts[2] === "..") {
                        if ($currentDir->getParent() !== null) {
                            $currentDir = $currentDir->getParent();
                        }
                    } else {
                        if (false === $currentDir->hasChild($parts[2])) {
                            $newNode = new Directory($parts[2], $currentDir);
                            $currentDir->addChild($newNode);
                            $currentDir = $newNode;
                        } else {
                            $target = $currentDir->getChild($parts[2]);
                            if ($target instanceof Directory) {
                                $currentDir = $target;
                            }
                        }
                    }
                }
            } else {
                $fileParts = explode(' ', $line);
                if (is_numeric($fileParts[0])) {
                    $file = new File($fileParts[1], $currentDir, $fileParts[0]);
                    $currentDir->addChild($file);
                }
            }
        }

        return $fs;
    }
}