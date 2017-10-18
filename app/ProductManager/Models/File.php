<?php

namespace App\ProductManager\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSED = 'processed';
    const STATUS_FAILED = 'failed';

    protected $guarded = [];

    public function updateStatus($fileName, $status)
    {
        $jobStatus = $this->where('stored_name', $fileName)
            ->first();

        $jobStatus->processing_status = $status;
        $jobStatus->save();
    }
}
