<?php

namespace Moyasar;

class PaginationResult extends Resource
{
    public $currentPage;
    public $nextPage;
    public $previousPage;
    public $totalPages;
    public $totalCount;

    /**
     * Current page items
     *
     * @var Payment[]|Invoice[]
     */
    public $result;

    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }
}
