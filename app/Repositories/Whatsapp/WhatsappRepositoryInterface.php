<?php

namespace App\Repositories\Whatsapp;

use Illuminate\Http\Request;

interface WhatsappRepositoryInterface
{
    public function index(array $filters);

}
