<?php

namespace App\Http\Controllers\Api\Whatsapp;

use App\Http\Controllers\Controller;
use App\Services\Whatsapp\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsappController extends Controller
{
    private $whatsappService;

    public function __construct(WhatsappService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
        $this->loggedUser = Auth::guard('sanctum')->user();

        // $this->middleware('permission:publication-list', ['only' => ['index','show']]);
        // $this->middleware('permission:publication-create', ['only' => ['store']]);
        // $this->middleware('permission:publication-edit', ['only' => ['update']]);
        // $this->middleware('permission:publication-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
      $this->whatsappService->index($request);
      $this->whatsappService->ScheduledShipping();
    }


}
