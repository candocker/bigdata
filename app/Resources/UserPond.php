<?php

namespace ModuleBigdata\Resources;

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
