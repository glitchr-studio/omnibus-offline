<?php

namespace Omnibus\Offline;

use Omnibus\Core\GatewayFactory;
use Omnibus\Offline\Action\ShippingAction;
use Omnibus\Offline\Action\TrackingAction;
use Omnibus\Offline\RatingAction;
use Omnibus\Offline\PickupAction;
use Omnibus\Offline\GetSlipAction;
use Omnibus\Offline\ParperlessAction;

class OfflineGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'omnibus.factory_name' => 'offline',
            'omnibus.factory_title' => 'Offline',
            'omnibus.action.shipping' => new ShippingAction(),
            'omnibus.action.tracking' => new TrackingAction(),
            'omnibus.action.rating' => new RatingAction(),
            'omnibus.action.pickup' => new PickupAction(),
            'omnibus.action.parperless' => new ParperlessAction(),
            'omnibus.action.get_slip' => new GetSlipAction(),
        ]);
    }
}