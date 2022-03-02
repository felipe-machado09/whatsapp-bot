<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class XmlMirror extends Model
{
    use HasFactory;
    protected $table = 'xml_mirrors';
    protected $fillable = [
        'list',
    ];
}
