<?php

declare(strict_types = 1);

namespace ModuleBigdata\Services;

use Swoolecan\Foundation\Services\TraitDatabaseService;

class DataSyncService extends AbstractService
{
    use TraitDatabaseService;

    public function updateSync()
    {
        $current1 = $this->getTableDatas('data_order', 'dsource');
        $current2 = $this->getTableDatas('test_tmp', 'bigdata');
        $results = $this->getModelObj('dataSync')->get();
        $fields = ['sync_num' => 'last_num', 'sync_id' => 'last_id', 'updated_at' => 'updated_at'];
        foreach ($results as $result) {
            $code = $result['code'];
            if (!isset($current1[$code]) && !isset($current2[$code])) {
                continue;
            }
            $cData = in_array($code, array_keys($current2)) ? $current2[$code] : $current1[$code];
            foreach ($fields as $field => $attr) {
                $value = $cData[$attr];
                if (empty($value) && $field == 'updated_at') {
                    $value = date('Y-m-d H:i:s');
                }
                $result->$field = $value;
            }

            $r = $result->save();
            var_dump($r);
        }
    }

    public function updateSources()
    {
        $datas = require(base_path() . '/storage/framework/sql.php');
        $this->recordDataSync('serp', '', $datas);
        print_R($datas);
    }
}
