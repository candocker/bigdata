<?php

namespace ModuleBigdata\Resources;

class UserPondCollection extends AbstractCollection
{

    protected function _frontInfoArray()
    {
        return $this->collection->toArray();
    }
}
