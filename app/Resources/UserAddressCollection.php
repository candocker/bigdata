<?php

namespace ModuleBigdata\Resources;

use Framework\Baseapp\Resources\AbstractCollection;

class UserAddressCollection extends AbstractCollection
{

    protected function _frontInfoArray()
    {
        return $this->collection->toArray();
    }
}
