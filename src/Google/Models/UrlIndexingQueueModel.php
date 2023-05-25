<?php

namespace DescomMarket\Feeds\Google\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $action
 * @property string $url
 * @property int $priority
 */
class UrlIndexingQueueModel extends Model
{
    protected $table = 'google_url_indexing_queue';

    protected $fillable = [
        'url',
        'action',
        'priority',
    ];
}
