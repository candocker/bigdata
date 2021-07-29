<?php

declare(strict_types = 1);

namespace ModuleBigdata\Repositories;

class UserPaytradeRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'user_id', 'money', 'order_times', 'first_order_at', 'last_order_at', 'goods_list', 'remark_list', 'created_at'],
            'listSearch' => ['id'],
            'add' => [],
            'update' => [],
        ];
    }

    public function getShowFields()
    {
        return [
            'user_id' => ['valueType' => 'point', 'relate' => 'userPond'],
        ];
    }

    public function getSearchFields()
    {
        return [
            //'type' => ['type' => 'select', 'infos' => $this->getKeyValues('type')],
        ];
    }

    public function getFormFields()
    {
        return [
            //'type' => ['type' => 'select', 'infos' => $this->getKeyValues('type')],
        ];
    }

    protected function _statusKeyDatas()
    {
        return [
            0 => '未激活',
            1 => '使用中',
            99 => '锁定',
        ];
    }
}
