<?php

namespace ModuleBigdata\Resources;

class UserAddressCollection extends AbstractCollection
{

    protected function _frontInfoArray()
    {
        return $this->collection->toArray();
    }
}
