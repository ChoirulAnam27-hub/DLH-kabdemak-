<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test validation rules for waste_type.
     */
    public function test_report_validation_with_invalid_waste_type(): void
    {
        $response = $this->post('/lapor', [
            'category_id' => 999, // invalid category
            'waste_type' => 'invalid_type', // should only be organik or anorganik
        ]);

        $response->assertSessionHasErrors(['category_id', 'waste_type']);
    }
}
