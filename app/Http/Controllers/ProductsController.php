<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Jobs\ImportProductsJob;
use App\ProductManager\Models\File;
use App\ProductManager\Models\Product;
use App\ProductManager\ImportProductService;
use Illuminate\Http\UploadedFile;

class ProductsController extends Controller
{
    /**
     * @var Product
     */
    private $model;

    /**
     * @var File;
     */
    private $fileModel;

    /**
     * @var ImportProductService;
     */
    private $importProductService;

    public function __construct()
    {
        $this->model = app()->make(Product::class);
        $this->fileModel = app()->make(File::class);
        $this->importProductService = app()->make(ImportProductService::class);
    }

    /**
     * Display a listing of products.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $products = $this->model->with('category')->paginate();

        return response()->json($products);
    }

    /**
     * Display the specified product.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = $this->model->with('category')->find($id);

        if (!$product) {
            return response()->json([
                'messages' => 'Product not found.'
            ], 404);
        }

        return response()->json([
            'data' => $product
        ], 200);
    }

    /**
     * Store a newly created products in storage.
     *
     * @param StoreProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreProductRequest $request)
    {
        $fileStored = $this->storeAFile($request->file('spreadsheet'));

        $this->dispatch(new ImportProductsJob($fileStored->stored_name));

        return response()->json([
            'data' => [
                'url_processing_status' => route('processing.status', $fileStored->id)
            ]
        ], 201);
    }

    /**
     * Update the specified product in storage.
     *
     * @param UpdateProductRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->model->find($id);

        if (!$product) {
            return response()->json([
                'messages' => 'Product not found.'
            ], 404);
        }

        $product->update($request->all());

        return response()->json([
            'data' => $product
        ], 200);
    }

    /**
     * Remove the specified product from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $product = $this->model->find($id);

        if (!$product) {
            return response()->json([
                'messages' => 'Product not found.'
            ], 404);
        }

        $product->delete();

        return response()->json([], 204);
    }

    /**
     * Store a file to disk and generates record in the database
     * with pending processing status.
     *
     * @param UploadedFile $file
     * @return \App\ProductManager\Models\File
     */
    private function storeAFile(UploadedFile $file)
    {
        $storedFileName = $file->store('files');

        $fileStored = $this->fileModel->create([
            'original_name' => $file->getClientOriginalName(),
            'stored_name' => $storedFileName,
            'processing_status' => File::STATUS_PENDING
        ]);

        return $fileStored;
    }
}
