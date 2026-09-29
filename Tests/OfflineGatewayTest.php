<?php

namespace Omnibus\Offline\Tests;

use Omnibus\Core\Model\Address;
use Omnibus\Core\Model\Parcel;
use Omnibus\Core\Model\Shipment;
use Omnibus\Core\Model\TrackingStatus;
use Omnibus\Core\Request\GetSlip;
use Omnibus\Offline\OfflineGatewayFactory;
use PHPUnit\Framework\TestCase;

final class OfflineGatewayTest extends TestCase
{
    public function testTheTrackingNumberIsTheOneTypedWithItsLink(): void
    {
        $gateway = (new OfflineGatewayFactory())->create(['tracking_url' => 'https://www.laposte.fr/outils/suivre-vos-envois?code={number}']);
        $shop = new Address('Boutique', ['1 rue A'], '75001', 'Paris', 'FR');

        $label = $gateway->ship(new Shipment($shop, $shop, [new Parcel(500)], options: ['tracking_number' => '6A123 456']));

        self::assertSame('6A123 456', $label->trackingNumber);
        self::assertSame('https://www.laposte.fr/outils/suivre-vos-envois?code=6A123%20456', $label->trackingUrl);
        self::assertNull($label->content);
        self::assertSame(TrackingStatus::UNKNOWN, $gateway->track('6A123 456')->status);
        self::assertFalse($gateway->supports(GetSlip::class));
    }

    public function testThePickupPointsAreTheShopsCounters(): void
    {
        $gateway = (new OfflineGatewayFactory())->create(['pickup_points' => [
            ['id' => 'shop', 'name' => 'La boutique', 'street' => ['1 rue A'], 'postcode' => '75001', 'city' => 'Paris', 'country' => 'FR', 'opening_hours' => [2 => [['10:00', '19:00']]]],
        ]]);

        $points = $gateway->pickupPoints(new Address('Client', [], '69001', 'Lyon', 'FR'));

        self::assertCount(1, $points);
        self::assertSame('shop', $points[0]->id);
        self::assertSame([2 => [['10:00', '19:00']]], $points[0]->openingHours);
    }
}
