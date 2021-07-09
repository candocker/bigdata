<?php

namespace ModuleBigdata\Resources;

use Framework\Baseapp\Resources\AbstractResource;

class UserPond extends AbstractResource
{
    protected function _frontInfoArray()
    {
        return [              
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
