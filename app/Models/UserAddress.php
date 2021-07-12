<?php

namespace ModuleBigdata\Models;

class UserAddress extends AbstractModel
{
    protected $table = 'user_address';
    protected $guarded = ['id'];

    public function formatMark($data)
    {
        $fields = ['consignee', 'mobile', 'province_code', 'city_code', 'county_code'];
        $str = '';
        foreach ($fields as $field) {
            $str .= $data[$field] . '/';
        }
        return md5($str);
    }
}
