<?php

namespace Omnibus\Offline\Action;

use Omnibus\Action\ActionInterface;
use Omnibus\Model\Address;
use Omnibus\Model\PickupPoint;
use Omnibus\Request\Pickup;
use Omnibus\Request\Request;

/** The shop's own counters (click & collect), as configured - wherever the customer is. */
final class PickupAction implements ActionInterface
{
    /** @param list<array<string, mixed>> $points */
    public function __construct(private readonly array $points)
    {
    }

    public function supports(Request $request): bool
    {
        return $request instanceof Pickup;
    }

    public function execute(Request $request): void
    {
        \assert($request instanceof Pickup);
        $points = array_map(static fn (array $p) => new PickupPoint(
            'offline',
            (string) $p['id'],
            (string) $p['name'],
            new Address((string) $p['name'], (array) ($p['street'] ?? []), (string) ($p['postcode'] ?? ''), (string) ($p['city'] ?? ''), (string) ($p['country'] ?? 'FR'), phone: $p['phone'] ?? null),
            isset($p['latitude']) ? (float) $p['latitude'] : null,
            isset($p['longitude']) ? (float) $p['longitude'] : null,
            array_map(static fn (array $slots) => array_map(static fn (array $s) => [(string) $s[0], (string) $s[1]], $slots), (array) ($p['opening_hours'] ?? [])),
        ), $this->points);

        $request->setResult(\array_slice($points, 0, $request->limit));
    }
}
