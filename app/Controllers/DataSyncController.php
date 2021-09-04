<?php

namespace ModuleBigdata\Controllers;

class DataSyncController extends AbstractController
{
    public function dealOrder()
    {
        $service = $this->getServiceObj('orderInfo');
        $service->dealOrder('dsource');
    }

    public function updateSync()
    {
        $service = $this->getServiceObj('dataSync');
        //$service->checkRecord(); // 整理数据时的功能，已不再使用

        $type = $this->request->input('type');
        $schema = $this->request->input('schema');
        if ($type == 'check') {
            $datas = require(base_path() . '/storage/framework/' . $schema . '.php');
            //$datas = $service->getTableDatas('bak_erp', 'lerp');
            $this->recordDataSync($schema, $datas);
            return $this->success();
        }
        $service->recordDataSync($schema);
        exit();
    }

    public function syncDump()
    {
        $schema = $this->request->input('schema');
        $status = $this->request->input('status');
        $status = !is_null($status) ? (array) $status : null;
        $type = $this->request->input('type', '');
        $service = $this->getServiceObj('dataSync');

        $service->dumpSql($schema, $status, $type);
    }

    public function tableStructure()
    {
        $service = $this->getServiceObj('dataSync');
        $databases = ['mysql', 'infocms', 'shop', 'paytrade', 'third', 'bench', 'bigdata', 'culture'];
        $config = $this->config->get('database.connections');
        foreach ($databases as $database) {
            if (!isset($config[$database])) {
                echo 'no-' . $database;
                exit();
            }
            $type = 'int';// 'datetime';
            $columns = $service->getColumnDatas('mysql', $config[$database]['database'], null, ['DATA_TYPE' => 'int']);
            $service->changeTimestamp($columns, $type);
        }
        exit();
    }
}
