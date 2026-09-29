<?php

namespace Omnibus\Offline\Action;

use Omnibus\Core\Action\ActionInterface;
use Omnibus\Core\Model\Label;
use Omnibus\Core\Request\Request;
use Omnibus\Core\Request\Shipping;

/**
 * Nothing to book: the tracking number is the one the shop typed (option
 * "tracking_number", from the post office's receipt), else the shipment's
 * reference. No document - the shop writes the address itself.
 */
final class ShippingAction implements ActionInterface
{
    public function __construct(private readonly ?string $trackingUrl = null)
    {
    }

    public function supports(Request $request): bool
    {
        return $request instanceof Shipping;
    }

    public function execute(Request $request): void
    {
        \assert($request instanceof Shipping);
        $shipment = $request->shipment;
        $number = (string) ($shipment->option('tracking_number') ?? $shipment->reference ?? strtoupper(bin2hex(random_bytes(6))));

        $request->setResult(new Label(
            'offline',
            $number,
            trackingUrl: $this->trackingUrl ? str_replace('{number}', rawurlencode($number), $this->trackingUrl) : null,
        ));
    }
}
