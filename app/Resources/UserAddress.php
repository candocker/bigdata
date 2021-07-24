<?php

namespace ModuleBigdata\Resources;

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
