<?php

declare(strict_types = 1);

namespace ModuleBigdata\Commands;

use Framework\Baseapp\Commands\AbstractCommand as AbstractCommandBase;

abstract class AbstractCommand extends AbstractCommandBase
{

    protected function getAppcode()
    {
        return 'bigdata';
    }
}
