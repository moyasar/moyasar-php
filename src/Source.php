<?php

namespace Moyasar;

abstract class Source extends Resource
{
    protected $skipProps = [
        'type'
    ];

    /**
     * Source Type
     *
     * @var string
     */
    protected $type;

    public function type()
    {
        return $this->type;
    }
}
