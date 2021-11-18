<?php

declare(strict_types = 1);

namespace ModuleBigdata\Services;

use Swoolecan\Foundation\Services\TraitDatabaseService;

class DataSyncService extends AbstractService
{
    use TraitDatabaseService;

    public function recordDataSync($schema, $tables = null)
    {
        $connection = "l{$schema}";
        $tables = is_null($tables) ? $this->getTableDatas("bak_{$schema}", $connection, true) : $tables;
        $model = $this->getModelObj('bigdata-dataSync');
        foreach ($tables as $table => $data) {
            $where = ['code' => $table, 'source_type' => $schema];
            $exist = $model->where($where)->first();
            $result = $exist ? $this->dealUpdateRecord($exist, $table, $data) : $this->dealAddRecord($model, $schema, $table, $data);
        }
    }

    protected function dealAddRecord($model, $schema, $table, $data)
    {
        $info = [
            'code' => $table,
            'name' => $data['comment'],
            'source_type' => $schema,
            'table_row' => $data['table_row'],
            'increment' => intval($data['increment']),
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'] ?: $data['created_at'],
            'sync_id' => $data['currentIncrement'] ?? 0,
        ];
        //$model->create($info);
        $fieldStr = $valueStr = '';
        foreach ($info as $field => $value) {
            $fieldStr .= "`{$field}`,";
            $valueStr .= "'{$value}',";
        }
        $fieldStr = trim($fieldStr, ',');
        $valueStr = trim($valueStr, ',');
        $sql = "INSERT INTO `wp_data_sync`({$fieldStr}) VALUES($valueStr);\n";
        echo $sql;
        return true;
    }

    protected function dealUpdateRecord($exist, $table, $data)
    {
        $updateFields = ['table_row' => 'table_row', 'increment' => 'increment'];
        if (isset($data['currentIncrement'])) {
            $updateFields['sync_id'] = 'currentIncrement';
        }
        $setStr = '';
        foreach ($updateFields as $field => $sField) {
            $exist->$field = intval($data[$sField]);
            $setStr .= "`{$field}` = '{$data[$sField]}',";
        }
        $exist->save();
        $setStr = trim($setStr, ',');
        $updateSql = "UPDATE `wp_data_sync` SET {$setStr} WHERE `code` = '{$exist->code}' AND `source_type` = '{$exist->source_type}';\n";
        echo $updateSql;
        return true;
    }

    public function dumpSql($schema, $status, $type)
    {
        $infos = $this->getDataSyncInfos($schema, $status);
        $sql = $desc = '';
        foreach ($infos as $info) {
            if ($info->last_id - $info->sync_id == 0 || $info->sync_id == 0) {
                //continue;
            }
            $autoField = $info['auto_field'] ?: 'id';
            $desc .= $info['code'] . '--- ' . $info->sync_id . "\n";
            $where = $type == 'full' || empty($info->sync_id) ? '' : '--where="' . $autoField . ' > ' . $info->sync_id . '"';
            $extSetting = $schema == 'edu' ? '--lock-tables=false' : '';
            $sql .= $this->createSql($info['code'], "s{$schema}", $where, $extSetting);
        }
        $statusStr = implode('_', (array) $status);
        //file_put_contents("/data/log/dealdata/{$schema}{$statusStr}.sql", $sql);//, FILE_APPEND);
        echo $sql;exit();
        echo $desc;exit();
    }

    protected function getDataSyncInfos($schema, $status)
    {
        $query = $this->getModelObj('dataSync')->where(['source_type' => $schema]);
        if (!is_null($status)) {
            $status = (array) $status;//['', 'fixedness', 'expire'];
            $query = $query->whereIn('status', $status);
        }
        $infos = $query->get();
        return $infos;
    }

    public function changeTimestamp($datas, $type)
    {
        $sql = '';
        foreach ($datas as $table => $columns) {
            foreach ($columns as $column => $info) {
                //$sql .= "ALTER TABLE `{$info['database']}`.`{$table}` CHANGE `{$column}` `{$column}` TIMESTAMP NULL DEFAULT NULL COMMENT '{$info['comment']}'; \n";
                $sql .= "ALTER TABLE `{$info['database']}`.`{$table}` ADD COLUMN `{$column}_bakbak` TIMESTAMP NULL DEFAULT NULL COMMENT '{$info['comment']}'; \n";
                $sql .= "UPDATE `{$info['database']}`.`{$table}` SET `{$column}_bakbak` = FROM_UNIXTIME(`{$column}` + 1); \n";
                $sql .= "ALTER TABLE `{$info['database']}`.`{$table}` DROP COLUMN `{$column}`; \n";
                $sql .= "ALTER TABLE `{$info['database']}`.`{$table}` CHANGE `{$column}_bakbak` `{$column}` TIMESTAMP NULL DEFAULT NULL COMMENT '{$info['comment']}'; \n";
            }
        }
        echo $sql;
    }

    /**
     * discarded
     */
    public function checkRecord($connection, $status)
    {
        $model = $this->getModelObj('bigdata-dataSync');
        $datas = $model->query()->whereIn('status', $status)->get();
        $db = \DB::connection($connection);
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
