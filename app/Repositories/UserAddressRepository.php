<?php
declare(strict_types = 1);

namespace ModuleBigdata\Repositories;

class UserAddressRepository extends AbstractRepository
{
    protected function _sceneFields()
    {
        return [
            'list' => ['id', 'user_id', 'consignee', 'mobile', 'province_code', 'city_code', 'county_code', 'address', 'zipcode', 'status', 'created_at'],
            'listSearch' => ['id', 'mobile', 'consignee'],
            'add' => ['name'],
            'update' => ['name'],
        ];
    }

    public function getShowFields()
    {
        return [
            'user_id' => ['valueType' => 'point', 'relate' => 'userPond'],
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
}
