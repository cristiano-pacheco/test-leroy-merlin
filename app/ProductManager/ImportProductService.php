<?php

namespace App\ProductManager;

use App\ProductManager\Models\Category;
use App\ProductManager\Models\File;
use App\ProductManager\Models\Product;
use App\ProductManager\Exceptions\InvalidProductException;
use Illuminate\Support\Facades\Storage;

class ImportProductService
{
    /**
     * Stores the data imported from the file.
     * @var array
     */
    private $data = [];

    /**
     * The relative path of the file to disk.
     * @var string
     */
    private $filePath = '';

    /**
     * @var File
     */
    private $fileModel;

    /**
     * @var Product
     */
    private $productModel;

    /**
     * @var Category
     */
    private $categoryModel;

    /**
     * @var StylesheetExtractService
     */
    private $stylesheetExtractService;

    public function __construct()
    {
        $this->fileModel = app()->make(File::class);
        $this->productModel = app()->make(Product::class);
        $this->categoryModel = app()->make(Category::class);
        $this->stylesheetExtractService = app()->make(StylesheetExtractService::class);
    }

    public function handle($filePath)
    {
        $this->filePath = $filePath;

        $this->extractDataFromStylesheet();

        $this->validateData();

        $this->saveCategory();

        $this->saveProducts();

        $this->deleteFileFromDisc();

        $this->changeStatusJobToProccessed();
    }

    /**
     * Extracts the data from the file
     * and stores it in the data attribute.
     */
    private function extractDataFromStylesheet()
    {
        $this->data = $this->stylesheetExtractService
            ->extractDataFromStylesheet($this->filePath);
    }

    /**
     * Validates the products extracted from the archive.
     *
     * @throws InvalidProductException
     */
    private function validateData()
    {
        $productValidator = new ProductValidator($this->data['products']);

        if ($productValidator->hasInvalidProduct()) {
            throw new InvalidProductException('Invalid product.');
        }
    }

    /**
     * Creates or updates the category extracted from the file.
     */
    private function saveCategory()
    {
        $this->categoryModel
            ->updateOrCreate(
                ['id' => $this->data['category']['id']],
                ['name' => $this->data['category']['name']]
            );
    }

    /**
     * Creates or updates the products extracted from the file.
     */
    private function saveProducts()
    {
        foreach ($this->data['products'] as $product) {
            $this->productModel
                ->updateOrCreate(
                    ['id' => $product['id']],
                    [
                        'category_id' => $product['category_id'],
                        'name' => $product['name'],
                        'free_shipping' => $product['free_shipping'],
                        'description' => $product['description'],
                        'price' => (float)$product['price'],
                    ]
                );
        }
    }

    /**
     * Delete the import file from disc,
     * after processing this file is not necessary.
     */
    private function deleteFileFromDisc()
    {
        if (Storage::exists($this->filePath)) {
            Storage::delete($this->filePath);
        }
    }

    /**
     * Changes the processing status of the file to processed,
     * so it can be consumed at the endpoint that checks this information.
     */
    private function changeStatusJobToProccessed()
    {
        $this->fileModel
            ->updateStatus($this->filePath, File::STATUS_PROCESSED);
    }
}
