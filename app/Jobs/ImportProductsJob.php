<?php

namespace App\Jobs;

use App\ProductManager\Models\File;
use App\ProductManager\ImportProductService;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The relative path of the file to disk.
     * @var string
     */
    private $filePath;

    /**
     * ImportProductsJob constructor.
     * @param string $filePath
     */
    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Processes the importation of products
     *
     * @param ImportProductService $service
     */
    public function handle(ImportProductService $service)
    {
        $service->handle($this->filePath);
    }

    /**
     * Changes the processing status to failed.
     *
     * @param \Exception $exception
     */
    public function failed(\Exception $exception)
    {
        (new File())->updateStatus($this->filePath, File::STATUS_FAILED);
    }
}
