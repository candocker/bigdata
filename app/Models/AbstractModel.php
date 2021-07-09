<?php

declare(strict_types = 1);

namespace ModuleBigdata\Models;

use Framework\Baseapp\Models\AbstractModel as AbstractModelBase;

class AbstractModel extends AbstractModelBase
{
    protected $connection = 'bigdata';
}
