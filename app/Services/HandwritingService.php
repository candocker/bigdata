<?php

declare(strict_types = 1);

namespace ModuleBigdata\Services;

use Carbon\Carbon;

class HandwritingService extends AbstractService
{
    public function dealUser()
    {
        $model = $this->getModelObj('bigdata-userHandwriting');
        foreach ($infos as $info) {
            $sourceUserId = $info['source_user_id'];
        }
    }
}
