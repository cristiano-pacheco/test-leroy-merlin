<?php

namespace Tests\Feature;

use App\ProductManager\Models\File;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShowFileProcessingStatusTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function can_view_a_file_processing_status()
    {
        $filePending = factory(File::class)->states('pending_status')->create();
        $fileProcessed = factory(File::class)->states('processed_status')->create();
        $fileFailed = factory(File::class)->states('failed_status')->create();


        $this->json('GET', '/api/processing_status/'.$filePending->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'processing_status' => File::STATUS_PENDING
                ]
            ]);

        $this->json('GET', '/api/processing_status/'.$fileProcessed->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'processing_status' => File::STATUS_PROCESSED
                ]
            ]);

        $this->json('GET', '/api/processing_status/'.$fileFailed->id)
            ->assertStatus(200)
            ->assertJson([
                'data' => [
                    'processing_status' => File::STATUS_FAILED
                ]
            ]);
    }

    /** @test */
    public function file_not_found()
    {
        $this->json('GET', '/api/processing_status/11111')
            ->assertStatus(404)
            ->assertJson([
                'messages' => 'File not found.'
            ]);
    }
}
