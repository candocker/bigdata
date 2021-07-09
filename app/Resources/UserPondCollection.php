<?php

namespace ModuleBigdata\Resources;

use Framework\Baseapp\Resources\AbstractCollection;

class UserPondCollection extends AbstractCollection
{

    protected function _frontInfoArray()
    {
        return $this->collection->toArray();
    }
}
