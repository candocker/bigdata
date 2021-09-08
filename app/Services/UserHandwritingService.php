<?php

declare(strict_types = 1);

namespace ModuleBigdata\Services;

use Carbon\Carbon;

class UserHandwritingService extends AbstractService
{
    use TraitSyncMainService;

    public function dealUser()
    {
        $model = $this->getModelObj('bigdata-userHandwriting');
        $infos = $this->getMarkedInfos($model, 4000);
        foreach ($infos as $info) {
            $sourceUserId = $info['source_user_id'];
            $remoteData = $this->getRemoteInfo('ledu', 'el_user', $sourceUserId, 'uid');
            $account = $this->formatAccount($remoteData);
            $updateData = $this->formatBaseInfo($remoteData);
            $updateData = array_merge($updateData, $account);
            foreach ($updateData as $key => $value) {
                $info->$key = $value;
            }
            $info->status = 1;
            $info->save();
            //$this->createUserPond($remoteData);
        }
    }

    protected function createUserPond($data)
    {
        $mobile = $data['phone'];
        $data['phone'] = '';
        $validatorInfo = \Validator::make($data, [
            'email' => 'email',
            'phone' => 'required|mobile'
        ]);
        $errorFields = [];
        $newData = [
            'mobile' => !empty($data['phone']) && !in_array('phone', $errorFields) ? $data['phone'] : '',
            'email' => !empty($data['email']) && !in_array('email', $errorFields) ? $data['email'] : '',
        ];
    }

    protected function formatAccount($data)
    {
        /*$data = [
            'login' => 'test',
            'uname' => 'sss', 
            'phone' => 'a@1683.com',
            'login2' => '18111111111',
            'email' => '13811974106',
        ];
        print_r($data);*/
        $fields = ['login', 'uname', 'phone', 'login2', 'email'];
        $return = $mobiles = $emails = [];
        foreach ($fields as $field) {
            $value = trim(strval($data[$field]));
            if (empty($value) || in_array($value, $mobiles) || in_array($value, $emails)) {
                continue;
            }
            $checkMobile = ['mobile' => $value];
            $validator = \Validator::make($checkMobile, ['mobile' => 'required|mobile']);
            if (!$validator->fails()) {
                $mobiles[$field] = $value;
                continue;
            }
            $checkEmail = ['email' => $value];
            $validator = \Validator::make($checkEmail, ['email' => 'required|email']);
            if (!$validator->fails()) {
                $emails[$field] = $value;
                continue;
            }
            $return[$field] = $value;
        }
        /*if ($validatorInfo->fails()) {
            $errors = $validatorInfo->errors();
            $errorFields = $errors->keys();
        }*/

        return [
            'mobile' => strval(array_shift($mobiles)),
            'mul_mobile' => empty($mobiles) ? '' : implode(',', $mobiles),
            'email' => strval(array_shift($emails)),
            'mul_email' => empty($emails) ? '' : implode(',', $emails),
            'name' => strval(array_shift($return)),
            'nickname' => empty($return) ? '' : array_shift($return),
            'account' => empty($return) ? '' : array_shift($return),
            'mul_name' => empty($return) ? '' : implode(',', $return),
        ];
    }

    protected function formatBaseinfo($data)
    {
        $fields = [
            'signin_num' => 'login_num',
            'signin_first' => 'ctime',
            'created_at' => 'ctime',
            'register_ip' => 'reg_ip',
            'last_at' => 'last_login_time',
        ];
        $result = [];
        foreach ($fields as $field => $attr) {
            $result[$field] = in_array($field, ['signin_first', 'created_at', 'last_at']) ? $this->formatTimestamp($data[$attr]) : $data[$attr];
        }
        return $result;
    }
}
