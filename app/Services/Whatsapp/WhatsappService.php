<?php

namespace App\Services\Whatsapp;

use Exception;
use Throwable;
use App\Models\User;
use App\Models\GroupLinks;
use App\Models\NewsMirror;
use Illuminate\Http\Request;
use App\Services\BaseService;
use App\Helpers\WhatsappHelper;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Validators\Whatsapp\WhatsappValidator;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Whatsapp\WhatsappRepositoryInterface;

class WhatsappService extends BaseService
{
    private $whatsappRepository;

    public function __construct(WhatsappRepositoryInterface $whatsappRepository)
    {
        $this->whatsappRepository = $whatsappRepository;
    }

    public function index(Request $request)
    {
        try {
            $urlXML = env('RSS_LINK_CONFIRMA_NOTICIA');
            $xmlFile = simplexml_load_file($urlXML,'SimpleXMLElement', LIBXML_NOCDATA);
            $news = [];
            $grupoUrl = 'https://chat.whatsapp.com/BiNLqOnG0ua2Kisr9ZW9Z0';
            $groupsTosend = [];
            $groups = GroupLinks::all();
            foreach($groups as $g){
                $response = (new WhatsappHelper)->getGroupInfo(trim($g->link));
                if(isset($response->phone)){
                    $idGrupo = $response->phone;
                    array_push($groupsTosend, $idGrupo);
                }

            }

            foreach ($xmlFile->channel->item as $item) {
                $img = WhatsappHelper::getImgFromXml($item);
                $item->addChild('thumbnail', $img);
                $json = json_encode($item, true);
                $xmlArray = json_decode($json, true);
                array_push($news,$xmlArray );
            }
            if(isset($news)){
                $newsToSend = [];
                foreach($news as $new){
                    $hasNew = NewsMirror::where('guid', $new['guid'])->first();
                    if(!isset($hasNew)){
                        if($new['author'] == "Jornalismo"){
                            //$phone = "5511964585695";
                             foreach($groupsTosend as $gt){
                                $phone = $gt;
                                $linkUrl = $new['link'];
                                $thumbnail = $new['thumbnail'];
                                $message = (new WhatsappHelper)->getResume($new['description'], 130);

                                $title = $new['title'];
                                $linkDescription = $new['category'];
                                $response = (new WhatsappHelper)->sendLink($phone, $message, $thumbnail, $linkUrl, $title, $linkDescription);

                            }


                        }
                        array_push($newsToSend, $new );

                        $this->whatsappRepository->index($new);

                    }
                }

                if(!empty($newsToSend)){
                    return $this->sendResponse([], "Robo enviando as noticias", Response::HTTP_OK);
                }else{
                    return $this->sendError([], 'Sem notícias novas.', Response::HTTP_NOT_FOUND);
                }
            }else{
                return $this->sendError([], 'News not found.', Response::HTTP_NOT_FOUND);
            }




        } catch (Exception $e) {
            dd($e);
            return $this->sendError([], $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $t) {
            dd($t);
            return $this->sendError([], $t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
