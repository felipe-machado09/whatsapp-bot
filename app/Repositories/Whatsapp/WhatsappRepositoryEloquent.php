<?php

namespace App\Repositories\Whatsapp;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Whatsapp;
use App\Models\XmlMirror;
use App\Models\NewsMirror;
use Illuminate\Http\Request;
use App\Helpers\WhatsappHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Http;


class WhatsappRepositoryEloquent implements WhatsappRepositoryInterface
{
    private $whatsapp;

    public function __construct()
    {
        $this->whatsapp = '$whatsapp';
    }



    public function index($new)
    {

        NewsMirror::create([
            'title' => $new['title'],
            'link' => $new['link'],
            'guid' => $new['guid'],
            'author' => 'Confirma Notícia',
            'description' => $new['description'],
            'category' => $new['category'],
            'pubDate' => $new['pubDate']
        ]);



    }


}
