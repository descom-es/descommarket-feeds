<?php

namespace DescomMarket\Feeds\Google\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $action
 * @property string $url
 * @property int $priority
 */
class Indexer extends Model
{
    protected $table = 'google_indexing';

    protected $fillable = [
        'url',
        'action',
        'priority',
    ];
}
