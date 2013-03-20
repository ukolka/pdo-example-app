<?php
/**
 * User: mykola
 * Date: 3/20/13
 * Time: 10:22 AM
 */

namespace Utils;


class Paginator {

    private $numRecords, $currentPage, $recordsPerPage;

    public function __construct($numRecords, $currentPage, $recordsPerPage) {
        $this->numRecords = $numRecords;
        $this->currentPage = $currentPage;
        $this->recordsPerPage = $recordsPerPage;
    }

    public function paginate() {
        $pageCount = (int) ceil($this->numRecords / $this->recordsPerPage);
        $pages['First'] = 1;
        $pages['Previous'] = $this->currentPage > 1 ? $this->currentPage - 1 : $this->currentPage;
        $count = 0;
        $i = 0;
        while ($count < 7) {
            $pageNum = $this->currentPage - 3 + $i;
            if ($pageNum > 0 && $pageNum <= $pageCount ) {
                $pages[(string) $pageNum] = $pageNum;
                $count += 1;
            }
            $i += 1;
        }
        $pages['Next'] = $this->currentPage < $pageCount ? $this->currentPage + 1: $this->currentPage;
        $pages['Last'] = $pageCount;
        return $pages;
    }
}