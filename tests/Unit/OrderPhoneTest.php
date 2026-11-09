<?php

namespace Tests\Unit;

use App\Models\Order;
use PHPUnit\Framework\TestCase;

class OrderPhoneTest extends TestCase
{
    public function test_moroccan_numbers_are_normalised_for_whatsapp(): void
    {
        foreach (['0612345678', '+212612345678', '00212612345678', '06 12-34 56 78'] as $phone) {
            $this->assertSame('212612345678', (new Order(['phone' => $phone]))->wa_phone, $phone);
        }
    }
}
