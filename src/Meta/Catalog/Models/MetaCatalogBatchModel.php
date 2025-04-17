<?php

namespace DescomMarket\Feeds\Meta\Catalog\Models;

use DescomMarket\Feeds\Meta\Catalog\Models\MetaCatalogQueueModel;
use Illuminate\Database\Eloquent\Model;

class MetaCatalogBatchModel extends Model
{
    protected $table = 'meta_catalog_batch';

    protected $fillable = [
        'handle',
        'status',
        'data',
    ];

    public function metaCatalogQueues()
    {
        return $this->hasMany(MetaCatalogQueueModel::class, 'meta_catalog_batch_id');
    }
}
