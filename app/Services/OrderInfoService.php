<?php

declare(strict_types = 1);

namespace ModuleBigdata\Services;

use Carbon\Carbon;

class OrderInfoService extends AbstractService
{
    public function dealOrder($connection)
    {
        $where = ['deal_status' => 0];
        $datas = $this->getDbConnection($connection)->table('order')->where($where)->limit(20)->get();
        //var_dump($datas);exit();
        foreach ($datas as $data) {
            $data = get_object_vars($data);
            $user = $this->recordUserPond($data);
            $address = $this->recordUserAddress($data, $user);
            $order = $this->recordOrderInfo($data, $user, $address);
        }
        return true;
    }

    protected function recordUserPond($data)
    {
        $model = $this->getModelObj('userPond');
        $exist = $model->where(['customer_no' => $data['customer_no']])->first();
        $exist = empty($exist) ? $model->where(['mobile' => $data['receiver_mobile']])->first() : $exist;
        if (empty($exist)) {
            $new = [
                'spread_code' => 'shop',
            ];
            $new['spread_code'] = $this->getSpreadCode();
            $new = array_merge($new, $this->getRelateData($data, 'user'));
            $exist = $model->create($new);
        }
        return $exist;
    }

    protected function recordUserAddress($data, $user)
    {
        $model = $this->getModelObj('userAddress');
        $aData = $this->getRelateData($data, 'address');
        $mark = $model->formatMark($aData);
        $exist = $model->where(['mark' => $mark, 'user_id' => $user->id])->first();
        if (empty($exist)) {
            $aData['user_id'] = $user->id;
            $aData['mark'] = $mark;
            $exist = $model->create($aData);
        }
        return $exist;
    }

    protected function recordOrderInfo($data, $user, $address)
    {
        $model = $this->getModelObj('orderInfo');
        $exist = $model->where(['source_id' => $data['id'], 'spread_code' => $this->getSpreadCode()])->first();
        if ($exist) {
            return true;
        }
        $aData = $this->getRelateData($data, 'orderInfo');
        $aData['user_id'] = $user->id;
        $aData['address_id'] = $address->id;
        $aData['spread_code'] = $this->getSpreadCode();
        $orderInfo = $model->create($aData);
        $this->recordUserPaytrade($orderInfo, $user);
        return $exist;
    }

    protected function recordUserPaytrade($orderInfo, $user)
    {
        $model = $this->getModelObj('userPaytrade');
        $exist = $model->where(['user_id' => $user->id])->first();
        if (empty($exist)) {
            $data = [
                'user_id' => $user->id,
                'order_times' => 1,
                'money' => $orderInfo['receivable'],
                'first_order_at' => $orderInfo['created_at'],
                'last_order_at' => $orderInfo['created_at'],
            ];
            $exist = $model->create($data);
            return true;
        }

        $exist->order_times = $exist->order_times + 1;
        if (Carbon::parse($exist->first_order_at)->gt($orderInfo->created_at)) {
            $exist->first_order_at = $orderInfo->created_at;
        }
        if (Carbon::parse($exist->last_order_at)->lt($orderInfo->created_at)) {
            $exist->last_order_at = $orderInfo->created_at;
        }
        $exist->money = $exist->money + $orderInfo['receivable'];
        $exist->save();
        return true;
    }

    protected function getRelateData($data, $type)
    {
        $relates['user'] = [
            'mobile' => 'receiver_mobile',
            'source_id' => 'id',
            'name' => 'customer_name',
            'customer_no' => 'customer_no',
            'nickname' => 'buyer_nick',
            'created_at' => 'created',
        ];
        $relates['address'] = [
            'consignee' => 'receiver_name',
            'note' => 'receiver_area',
            'address' => 'receiver_address',
            'mobile' => 'receiver_mobile',
            //'' => 'receiver_telno',
            'zipcode' => 'receiver_zip',
            'province_code' => 'receiver_province',
            'city_code' => 'receiver_city',
            'county_code' => 'receiver_district',
            //'' => 'receiver_dtb',
            'created_at' => 'created',
        ];
        $relates['orderInfo'] = [
            'source_id' => 'id',
            'shop_no',
            'trade_status',
            'trade_type',
            'refund_status',
            'logistics_no',
            'logistics_id',
            'buyer_message',
            'cs_remark',
            'goods_type_count',
            'goods_count',
            'goods_amount',
            'receivable',
            'commission',
            'trade_time',
            'pay_account',
            'pay_time',
            'consign_time',
            'trade_from',
            'id_card_type',
            'id_card',
            'logistics_code',
            'created_at' => 'created',
        ];

        $result = [];
        foreach ($relates[$type] as $field => $attr) {
            $field = !is_string($field) ? $attr : $field;
            if (in_array($attr, ['trade_time', 'created'])) {
                $value = date('Y-m-d H:i:s', intval(substr($data[$attr], 0, 10)));
            } else {
                $value = $data[$attr];
            }
            $result[$field] = is_string($value) ? trim($value) : $value;
        }
        return $result;

    }

    protected function getDbConnection($connection = null)
    {
        if (is_null($connection)) {
            return \DB::connection();
        }
        return \DB::connection($connection);
    }

    protected function getSpreadCode()
    {
        return 'tmp';
    }
}
/*(
    [id] => 1
    [buyer_nick] => 家有儿女552177244
    [customer_name] => 王岩
    [customer_no] => KH201905298491
    [receiver_mobile] => 18204687007
    [created] => 1559125449000

    [receiver_name] => 王岩
    [receiver_area] => 黑龙江省 鹤岗市 萝北县
    [receiver_address] => 宝泉岭管理局局直宝泉岭管理局山水家园9号楼1单元202室
    [receiver_mobile] => 18204687007
    [receiver_telno] => 
    [receiver_zip] => 154200
    [receiver_province] => 230000
    [receiver_city] => 230400
    [receiver_district] => 230421
    [receiver_dtb] => 鹤岗市 萝北县

    -[id] => 1
    [trade_no] => JY2019052910093
    [platform_id] => 1
    [shop_no] => 002
    [shop_name] => 六品办公专营店
    [shop_remark] => 
    [src_tids] => 463193922738114330,462875265940114330
    [trade_status] => 5
    [trade_type] => 1
    -[buyer_nick] => 家有儿女552177244
    -[customer_name] => 王岩
    -[customer_no] => KH201905298491
    -[receiver_name] => 王岩
    -[receiver_area] => 黑龙江省 鹤岗市 萝北县
    -[receiver_address] => 宝泉岭管理局局直宝泉岭管理局山水家园9号楼1单元202室
    --[receiver_mobile] => 18204687007
    -[receiver_telno] => 
    -[receiver_zip] => 154200
    -[receiver_province] => 230000
    -[receiver_city] => 230400
    -[receiver_district] => 230421
    -[receiver_dtb] => 鹤岗市 萝北县
    [delivery_term] => 1
    [refund_status] => 3
    [warehouse_no] => 014
    [logistics_no] => 
    [logistics_id] => 
    [buyer_message] => 
    [cs_remark] => 
    [goods_type_count] => 3.0000
    [goods_count] => 3.0000
    [goods_amount] => 39.6000
    [post_amount] => 0.0000
    [other_amount] => 0.0000
    [discount] => 20.0000
    [receivable] => 19.6000
    [cod_amount] => 0.0000
    [ext_cod_fee] => 0.0000
    [commission] => 0.0000
    [trade_time] => 1559124731000
    [pay_time] => 2019-05-29 18:12:24
    [consign_time] => 
    [to_deliver_time] => 
    [trade_from] => 1
    [single_spec_no] => 
    [raw_goods_count] => 2.0000
    [raw_goods_type_count] => 2
    [freeze_reason] => 0
    [currency] => 
    [id_card_type] => 0
    [id_card] => 
    [stockout_no] => 
    [modified] => 1559204221000
    --[created] => 1559125449000
    [invoice_id] => 0
    [invoice_type] => 0
    [invoice_title] => 
    [invoice_content] => 
    [logistics_name] => 上溪韵达凌云
    [logistics_code] => 068
    [logistics_type] => 
    [warehouse_type] => 1
    [pay_account] => 187****8883
    [fenxiao_type] => 0
    [fenxiao_nick] => 
    [receiver_ring] => 
    [bad_reason] => 0
    [remark_flag] => 0
    [print_remark] => 
    [goods_cost] => 3.3300
    [post_cost] => 2.5000
    [weight] => 0.0100
    [profit] => 13.7700
    [tax] => 0.0000
    [tax_rate] => 0.0000
    [salesman_name] => 系统
    [checker_name] => 系统
    [fchecker_name] => 系统
    [checkouter_name] => 系统
    [flag_name] => 无
    [version_id] => 1
    [deal_status] => 0
)*/
