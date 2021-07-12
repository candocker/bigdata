<?php

namespace ModuleBigdata\Controllers;

class DataSyncController extends AbstractController
{
    public function sync()
    {
        $service = $this->getServiceObj('dataSync');
        $service->dealOrder('dsource');
    }
}
