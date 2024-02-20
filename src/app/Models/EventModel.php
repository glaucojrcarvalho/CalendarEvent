<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use YourAppRocks\EloquentUuid\Traits\HasUuid;

class EventModel extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'calendar_events';

    protected $fillable = [
        'uuid',
        'title',
        'description',
        'start_date',
        'end_date'
    ];
}
