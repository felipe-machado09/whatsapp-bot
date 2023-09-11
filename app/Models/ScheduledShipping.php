<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduledShipping extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'scheduled_shippings';

    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'scheduled_message_id',
        'status',
        'date',
        'time',
        'send',
        'recurring',
        'shipping_quantity',
        'sending_limit',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d');
    }

    public function scheduled_message()
    {
        return $this->belongsTo(ScheduledMessage::class, 'scheduled_message_id');
    }

}
