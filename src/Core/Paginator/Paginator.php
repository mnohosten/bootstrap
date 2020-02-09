<?php
declare(strict_types=1);

namespace Paginator;


class Paginator implements \Iterator
{

    /** @var int */
    private $actual;
    /** @var array */
    private $pages = [];
    private $needle;
    /** @var int */
    private $totalCount;

    public function __construct(
        int $actual,
        int $totalCount,
        int $pageSize,
        int $surroundSize
    )
    {
        $this->needle = 0;
        $this->actual = $actual;
        $this->pages = $this->buildPages($actual, $totalCount, $pageSize, $surroundSize);
        $this->totalCount = $totalCount;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function left()
    {
        return $this->pages[$this->needle - 1] ?? null;
    }

    public function right()
    {
        return $this->pages[$this->needle + 1] ?? null;
    }

    public function current()
    {
        return $this->pages[$this->needle];
    }

    public function next()
    {
        $this->needle += 1;
    }

    public function key()
    {
        return $this->needle;
    }

    public function valid()
    {
        return isset($this->pages[$this->needle]);
    }

    public function rewind()
    {
        $this->needle = 0;
    }

    public function size()
    {
        return count($this->pages);
    }

    public function isGap()
    {
        if(is_null($this->left())) return false;
        if(($this->current() - $this->left()) > 1) {
            return true;
        }
        return false;
    }

    public function isActual(int $page)
    {
        return $this->actual == $page;
    }

    /**
     * @param int $actual
     * @param int $totalCount
     * @param int $pageSize
     * @param int $surroundSize
     * @return array
     */
    private function buildPages(int $actual, int $totalCount, int $pageSize, int $surroundSize): array
    {
        $max = (int)ceil($totalCount / $pageSize);
        $pages = [1];
        for ($i = $actual - $surroundSize; $i < $actual && $i > 0; $i++) {
            $pages[] = $i;
        }
        for ($i = $actual; $i <= $max && $i <= $actual + $surroundSize; $i++) {
            $pages[] = $i;
        }
        $pages[] = $max;
        return array_values(
            array_unique($pages, SORT_NUMERIC)
        );
    }

}