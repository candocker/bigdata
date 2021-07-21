<?php

namespace ModuleBigdata\Controllers;

class DataSyncController extends AbstractController
{
    public function sync()
    {
        $service = $this->getServiceObj('orderInfo');
        $service->dealOrder('dsource');
    }

    public function syncStatus()
    {
        $service = $this->getServiceObj('dataSync');
        $service->updateSync();
    }

    public function syncDump()
    {
        $service = $this->getServiceObj('dataSync');
        $service->dumpSql('serp');
    }
}
