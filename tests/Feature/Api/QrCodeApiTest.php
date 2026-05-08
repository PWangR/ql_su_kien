<?php

namespace Tests\Feature\Api;

use Tests\TestCase;

class QrCodeApiTest extends TestCase
{
    public function test_generate_qr_defaults_to_svg()
    {
        $response = $this->get('/api/generate-qr?data=test');

        $response->assertOk();
        $this->assertStringContainsString('image/svg+xml', $response->headers->get('content-type'));
    }

    public function test_generate_qr_can_return_png_for_mobile()
    {
        $response = $this->get('/api/generate-qr?format=png&data=test');

        $response->assertOk();
        $this->assertStringContainsString('image/png', $response->headers->get('content-type'));
        $this->assertSame("\x89PNG", substr($response->getContent(), 0, 4));
    }
}
