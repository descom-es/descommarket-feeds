<?php

namespace DescomMarket\Feeds\Google\Models;

use Illuminate\Database\Eloquent\Model;

class Indexer extends Model
{
    protected $table = 'google_indexing';

    protected $fillable = [
        'url',
        'action',
        'priority',
    ];
}
