<?php

namespace Moyasar;

class Search
{
    /**
     * Payment or Invoice Id
     *
     * @var string
     */
    private $id;

    /**
     * Payment source
     *
     * @var string
     */
    private $source;

    /**
     * Payment or Invoice status
     *
     * @var
     */
    private $status;

    /**
     * Page
     *
     * @var int
     */
    private $page;

    /**
     * Get results created after some point in time
     *
     * @var string
     */
    private $createdAfter;

    /**
     * Get results created before some point in time
     *
     * @var string
     */
    private $createdBefore;

    private function __construct()
    {
    }

    public static function query()
    {
        return new self();
    }

    public function id($id)
    {
        $this->id = $id;
        return $this;
    }

    public function source($source)
    {
        $this->source = $source;
        return $this;
    }

    public function status($status)
    {
        $this->status = $status;
        return $this;
    }

    public function page($page)
    {
        $this->page = $page;
        return $this;
    }

    public function createdAfter($date)
    {
        $this->createdAfter = $date;
        return $this;
    }

    public function createdBefore($date)
    {
        $this->createdBefore = $date;
        return $this;
    }

    public function toArray()
    {
        $result = [];

        if ($this->id !== null) $result['id'] = $this->id;
        if ($this->source !== null) $result['source'] = $this->source;
        if ($this->status !== null) $result['status'] = $this->status;
        if ($this->page !== null) $result['page'] = $this->page;
        if ($this->createdAfter !== null) $result['created[gt]'] = $this->createdAfter;
        if ($this->createdBefore !== null) $result['created[lt]'] = $this->createdBefore;

        return $result;
    }
}
