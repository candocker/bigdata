<?php
declare(strict_types = 1);

namespace ModuleBigdata\Repositories;

class UserPondRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'name', 'mobile', 'nickname', 'customer_no', 'gender', 'birthday', 'spread_code', 'source_id', 'created_at', 'status'],
            'listSearch' => ['id', 'name'],
            'add' => [],
            'update' => [],
        ];
    }

    public function getShowFields()
    {
        return [
            'gender' => ['valueType' => 'key'],
            'status' => ['valueType' => 'key'],
            'spread_code' => ['valueType' => 'key'],
        ];
    }

    public function getFormFields()
    {
        return [
        ];
    }

    public function getSearchFields()
    {
        return [
        ];
    }

    public function getSearchDealFields()
    {
        return [
        ];
    }

    public function _getFieldOptions()
    {
        return [
        ];
    }

    protected function _statusKeyDatas()
    {
        return [
        ];
    }

    protected function _spreadCodeKeyDatas()
    {
        return [
            'tmp' => '旧数据',
            'history' => '历史数据',
            'order' => '订单数据',
        ];
    }

    protected function _genderKeyDatas()
    {
        return [
            0 => '未知',
            1 => '男',
            2 => '女',
        ];
    }
}
