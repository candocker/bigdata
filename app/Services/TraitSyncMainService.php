<?php

declare(strict_types = 1);

namespace ModuleBigdata\Services;

trait TraitSyncMainService
{
    public function getMarkedInfos($model, $number = 200, $where = ['status' => 0])
    {
        $mark = rand(100, 10000);
        $r = $model->where($where)->limit($number)->update(['status' => $mark]);
        $infos = $model->where(['status' => $mark])->get();
        return $infos;
    }

    public function getRemoteInfo($connection, $table, $value, $field = 'id')
    {
        $remoteConnect = $this->getDbConnection($connection);
        $data = $field != 'id' ? $remoteConnect->table($table)->where($field, $value)->first() : $remoteConnect->table($table)->find($value);
        $data = empty($data) ? [] : get_object_vars($data);
        return $data;
    }

    protected function getDbConnection($connection = null)
    {
        if (is_null($connection)) {
            return \DB::connection();
        }
        return \DB::connection($connection);
    }

    protected function formatTimestamp($timestamp)
    {
        return empty($timestamp) ? null : date('Y-m-d H:i:s', $timestamp);
    }
}
