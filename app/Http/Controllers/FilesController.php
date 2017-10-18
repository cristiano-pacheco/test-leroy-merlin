<?php

namespace App\Http\Controllers;

use App\ProductManager\Models\File;

class FilesController extends Controller
{
    /**
     * @var File
     */
    private $model;

    public function __construct()
    {
        $this->model = app()->make(File::class);
    }

    /**
     * Display the processing status of the file.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $file = $this->model->find($id);

        if (!$file) {
            return response()->json([
                'messages' => 'File not found.'
            ], 404);
        }

        return response()->json([
            'data' => [
                'processing_status' => $file->processing_status
            ]
        ]);
    }
}
