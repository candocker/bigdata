<?php

namespace ModuleBigdata\Resources;

use Framework\Baseapp\Resources\AbstractResource;

class UserAddress extends AbstractResource
{
    protected function _frontInfoArray()
    {
        return [              
            'id' => $this->id,
            'name' => $this->name,
        ];
    }
}
