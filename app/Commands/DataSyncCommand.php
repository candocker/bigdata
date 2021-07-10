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
        $type = $this->argument('type');
        $options = $this->option('options');
        file_put_contents('/tmp/text.txt', date('Y-m-d H:i:s') . '--'. $type. '==' . $options . 'ssssssssss', FILE_APPEND);
        echo 'sssssssssss';exit();
    }
}
