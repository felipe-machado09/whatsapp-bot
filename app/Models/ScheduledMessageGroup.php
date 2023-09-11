<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduledMessageGroup extends Model
{
    use SoftDeletes, HasFactory;

    public $table = 'scheduled_message_groups';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'scheduled_message_id',
        'group_link_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function scheduled_message()
    {
        return $this->belongsTo(ScheduledMessage::class, 'scheduled_message_id');
    }

    public function group_link()
    {
        return $this->belongsTo(GroupLink::class, 'group_link_id');
    }
}
