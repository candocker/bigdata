<?php

namespace ModuleBigdata\Commands;

use Swoolecan\Foundation\Commands\TraitDataSyncCommand;

class DataSyncCommand extends AbstractCommand
{
    use TraitDataSyncCommand;

    /**
     * The name of command.
     *
     * @var string
     */
    //protected $name = 'data:sync {type} {--options=}';

    protected $signature = 'data:sync {type} {--options=}';

    /**
     * The description of command.
     *
     * @var string
     */
    protected $description = 'Create a new RESTful controller.';

    public function handle()
    {
        $type = $this->getPointArgument('type');

        $method = "_{$type}Deal";
        $this->$method();
    }

    protected function _orderDeal()
    {
        $service = $this->getServiceObj('orderInfo');
        $service->dealOrder('dsource');

        echo 'sssssssssss';exit();
    }
}
