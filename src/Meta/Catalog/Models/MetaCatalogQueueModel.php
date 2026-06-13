<?php

namespace DescomMarket\Feeds\Meta\Catalog\Models;

use DescomMarket\Feeds\Meta\Catalog\Models\MetaCatalogBatchModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class MetaCatalogQueueModel extends Model
{
    use HasUuids;

    protected $table = 'meta_catalog_queue';

    protected $casts = [
        'product_id' => 'integer',
        'priority' => 'integer',
    ];

    protected $fillable = [
        'product_id',
        'action',
        'status',
        'error',
        'meta_catalog_batch_id',
        'priority',
    ];

    public function metaCatalogBatch()
    {
        return $this->belongsTo(MetaCatalogBatchModel::class, 'meta_catalog_batch_id');
    }
}
