<?php

namespace Omnibus\Offline\Action;

use Omnibus\Action\ActionInterface;
use Omnibus\Model\Tracking as TrackingModel;
use Omnibus\Model\TrackingStatus;
use Omnibus\Request\Request;
use Omnibus\Request\Tracking;

/** No carrier to ask: the status is unknown - the shop says it in person, or follows the tracking link. */
final class TrackingAction implements ActionInterface
{
    public function supports(Request $request): bool
    {
        return $request instanceof Tracking;
    }

    public function execute(Request $request): void
    {
        \assert($request instanceof Tracking);
        $request->setResult(new TrackingModel('offline', $request->trackingNumber, TrackingStatus::UNKNOWN));
    }
}
