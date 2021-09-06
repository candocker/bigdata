<?php

declare(strict_types = 1);

namespace ModuleBigdata\Services;

trait TraitSyncMainService
{
    public function getMarkedInfos($model, $number = 200, $where = ['status' => 0])
    {
        $mark = rand(100, 10000);
        $model->update(['status' => $mark])->where($where)->limit(200);
        $infos = $model->where(['status' => $mark])->get();
        return $infos;
    }

    public function getRemoteInfo($connection, $table, $value)
    {
        $remoteConnect = $this->getDbConnection($connection);
        return $remoteConnect->table('order')->find($value);
    }
}
