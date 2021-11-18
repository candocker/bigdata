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

    public function checkMulMobile()
    {
        $model = $this->getModelObj('bigdata-userHandwriting');
        $infos = $model->where('status', 90)->get();
        //echo count($infos);exit();
        foreach ($infos as $info) {
            $mobile = $info->mul_mobile;
            $datas = $model->where('mobile', $mobile)->get();
            if ($datas->count() > 0) {
                echo $mobile . '--';
                foreach ($datas as $data) {
                    echo $data['source_user_id'] . '===';
                }
                echo "\n";
                //exit();
                $info->status = 91;
                $info->save();
            } else {
                $info->status = 90;
                $info->save();
                echo 'ssss--' . $mobile . "\n";
            }
        }
    }

    public function checkEmail()
    {
        $model = $this->getModelObj('bigdata-userHandwriting');
        $infos = $model->where('status', 189)->where('mul_email', '<>', '')->limit(5000)->get();
        foreach ($infos as $info) {
            $email = $info['email'];
            $mulEmail = $info['mul_email'];
            if (!empty($info['nickname'])) {
                echo 'ffffffff';exit();
            }
            $pos = strpos($email, $mulEmail);
            $nickname = substr($email, 0, $pos);
            $info->nickname = $nickname;
            $info->email = $mulEmail;
            $info->mul_email = '';
            echo $info['id'] . '==' . $info['nickname'] . '--' . $pos . '--' . $nickname . '-' . $email . '==' . $mulEmail . "\n";
            $info->status = 1;
            $info->save();
            //print_r($info->toArray());exit();
        }
        exit();
    }

    public function checkEmailbak()
    {
        $model = $this->getModelObj('bigdata-userHandwriting');
        $infos = $model->where('status', 80)->limit(5000)->get();
        foreach ($infos as $info) {
            $check = $this->_checkEmail($info['email']);
            if (!$check) {
                $info->status = 180;
            } else {
                $info->status = 189;
            }
            $info->save();
            /*$mulEmail = $info->mul_email;
            if (!empty($mulEmail)) {
                $checkMul = $this->_checkEmail($mulEmail);
                if (!$checkMul) {
                    $info->account = $mulEmail;
                    $info->mul_email = '';
                    print_r($info->toArray());
                    exit();
                    //$info->save();
                }
            }*/
        }
        exit();
    }

    protected function _checkEmail($email)
    {
        $validator = \Validator::make(['email' =>$email], ['email' => 'required|email:dns']);
        if (!$validator->fails()) {
            return true;
        }
        return false;
    }
}
