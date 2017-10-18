<?php

namespace Tests\Unit;

use App\ProductManager\Models\File;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class FileTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_has_a_correct_values_of_the_constants()
    {
        $this->assertEquals('pending', File::STATUS_PENDING);
        $this->assertEquals('processed', File::STATUS_PROCESSED);
        $this->assertEquals('failed', File::STATUS_FAILED);
    }

    /** @test */
    public function can_update_processing_status()
    {
        $file = factory(File::class)->create();

        (new File())->updateStatus($file->stored_name, File::STATUS_PROCESSED);

        $this->assertEquals(File::STATUS_PROCESSED, File::find(1)->processing_status);
    }
}
