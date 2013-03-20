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
        for ($i = $this->currentPage - 3; $i < $this->currentPage + 3; $i++) {
            if ($i > 0 && $i <= $pageCount) {
                $pages[(string) $i] = $i;
            }
        }
        $pages['Next'] = $this->currentPage < $pageCount ? $this->currentPage + 1: $this->currentPage;
        $pages['Last'] = $pageCount;
        return $pages;
    }
}