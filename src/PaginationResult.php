<?php

namespace Moyasar;

class PaginationResult
{
    public $currentPage;
    public $nextPage;
    public $previousPage;
    public $totalPages;
    public $totalCount;

    /**
     * @var \Moyasar\Payment[]|\Moyasar\Invoice[]
     */
    public $result;

    public static function fromArray($meta, $result)
    {
        $pr = new self();

        $pr->result = $result;

        $pr->currentPage    = self::extract($meta, 'current_page');
        $pr->nextPage       = self::extract($meta, 'next_page');
        $pr->previousPage   = self::extract($meta, 'prev_page');
        $pr->totalPages     = self::extract($meta, 'total_pages');
        $pr->totalCount     = self::extract($meta, 'total_count');

        return $pr;
    }

    private static function extract($data, $key, $default = null)
    {
        return isset($data[$key]) ? $data[$key] : $default;
    }
}
