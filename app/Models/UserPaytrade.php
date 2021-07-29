<?php

namespace ModuleBigdata\Models;

class UserPaytrade extends AbstractModel
{
    protected $table = 'user_paytrade';
    protected $guarded = ['id'];

    public function userPond()
    {
        return $this->hasOne(UserPond::class, 'id', 'user_id');
    }
}
