<?php

namespace Omnibus\Offline;

use Omnibus\Core\GatewayFactory;
use Omnibus\Offline\Action\ShippingAction;
use Omnibus\Offline\Action\TrackingAction;

class OfflineGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'omnibus.factory_name' => 'offline',
            'omnibus.factory_title' => 'Offline',
            'omnibus.action.shipping' => new ShippingAction(),
            'omnibus.action.tracking' => new TrackingAction(),
        ]);
    }
}