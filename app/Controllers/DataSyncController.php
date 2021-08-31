<?php

namespace ModuleBigdata\Controllers;

class DataSyncController extends AbstractController
{
    public function sync()
    {
        $service = $this->getServiceObj('orderInfo');
        $service->dealOrder('dsource');
    }

    public function syncStatus()
    {
        $service = $this->getServiceObj('dataSync');
        $service->updateSources();
        //$service->updateSync();
    }

    public function syncDump()
    {
        $service = $this->getServiceObj('dataSync');
        //$this->checkRecord($service);
        //$tables = $service->getTableDatas('bak_order', 'dsource2');
        //$service->recordDataSync('serp', 'dsource2', $tables);

        //$service->dumpSql('serp');
        $service->dumpSqlOld('serp');
    }

    protected function checkRecord($service)
    {
        $model = $this->getModelObj('bigdata-dataSync');
        $datas = $model->query()->where(['status' => ''])->get();
        $db = \DB::connection('dsource2');
        foreach ($datas as $data) {
            $table = $data['code'];
            if (in_array($table, ['tmp_order', 'tmp_order_id', 'erp_stock_transfer_detail', 'erp_warehouse'])) {
                continue;
            }
            $info2 = $db->select("SELECT * FROM `data_order`.`{$table}` ORDER BY `id` DESC LIMIT 1;");
            if (!isset($info2[0])) {
                echo 'empty ' . $table . "\n";
                continue;
            }

            $attrs = get_object_vars($info2[0]);
            $info1 = $db->select("SELECT * FROM `bak_order`.`{$table}` WHERE `id` = {$info2[0]->id} LIMIT 1;");
            if (!isset($info1[0])) {
                print_r($info2);
                echo 'no  ' . $table . "\n";
                continue;
            }
            foreach (array_keys($attrs) as $attr) {
                //var_dump($info1[0]->$attr);var_dump($info2[0]->$attr);
                if ($info1[0]->$attr != $info2[0]->$attr) {
                    echo 'diff ' . $attr . '--' . $table . "\n";
                    continue;
                }
            }
            //echo 'same  ' . $table . "\n";
        }
    }
}
