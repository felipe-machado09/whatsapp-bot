<?php

namespace App\Services\Whatsapp;

use App\Helpers\WhatsappHelper;
use App\Models\GroupLinks;
use App\Models\NewsMirror;
use App\Repositories\Whatsapp\WhatsappRepositoryInterface;
use App\Services\BaseService;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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
            $urlXMLVIDEO = env('RSS_LINK_CONFIRMA_NOTICIA_VIDEO');

            // dd($urlXMLVIDEO);
            $xmlFile = simplexml_load_file($urlXML, 'SimpleXMLElement', LIBXML_NOCDATA);
            $xmlFileVideo = simplexml_load_file($urlXMLVIDEO, 'SimpleXMLElement', LIBXML_NOCDATA);
            $news = [];
            $grupoUrl = 'https://chat.whatsapp.com/BiNLqOnG0ua2Kisr9ZW9Z0';
            $groupsTosend = [];
            $groups = GroupLinks::all();
            foreach ($groups as $g) {

                $response = (new WhatsappHelper())->getGroupInfo(trim($g->link));
        if (isset($response->phone)) {
                    $idGrupo = $response->phone;
                    array_push($groupsTosend, $idGrupo);
                }
            }

            foreach ($xmlFile->channel->item as $item) {
                $img = WhatsappHelper::getImgFromXml($item);
                $item->addChild('thumbnail', $img);
                $json = json_encode($item, true);
                $xmlArray = json_decode($json, true);
                array_push($news, $xmlArray);
            }

            foreach ($xmlFileVideo->channel->item as $item) {
                $img = WhatsappHelper::getImgFromXml($item);
                $item->addChild('thumbnail', $img);
                $json = json_encode($item, true);
                $xmlArray = json_decode($json, true);
                array_push($news, $xmlArray);
            }

            if (isset($news)) {
                $newsToSend = [];
                foreach ($news as $new) {
                    $hasNew = NewsMirror::where('guid', $new['guid'])->first();
                    if (!isset($hasNew)) {
                        // dd($new);
                        if(is_array($new['description'])){
                            $messageResume = count($new['description']) > 0 ? (new WhatsappHelper())->getResume($new['description'][0], 130) : 'Confira a notícia completa no link abaixo';
                        }else{
                            $messageResume = strlen($new['description']) > 0 ? (new WhatsappHelper())->getResume($new['description'], 130) : 'Confira a notícia completa no link abaixo';
                        }
                        if(is_array($new['category'])){
                            $new['category'] = count($new['category']) > 0 ? $new['category'][0] : ['Sem categoria'];
                        }else{
                            $new['category'] = strlen($new['category']) > 0 ? $new['category'] : ['Sem categoria'];
                        }


                            foreach ($groupsTosend as $gt) {

                                $phone = '5511964585695';
                                //$phone = $gt;
 
                                $linkUrl = $new['link'];
                                $thumbnail = $new['thumbnail'] ?? $new['enclosure']['@attributes']['url'];
                                $message = $messageResume;

                                $title = $new['title'];

                                $linkDescription = $new['category'];

                                 $response = (new WhatsappHelper())->sendLink($phone, $message, $thumbnail, $linkUrl, $title, $linkDescription);
                            }

                        $new['description'] = $messageResume;

                        array_push($newsToSend, $new);

                        $this->whatsappRepository->index($new);

                    }
                }

                if (!empty($newsToSend)) {
                    return $this->sendResponse([], 'Robo enviando as noticias', Response::HTTP_OK);
                } else {
                    return $this->sendError([], 'Sem notícias novas.', Response::HTTP_NOT_FOUND);
                }
            } else {
                return $this->sendError([], 'News not found.', Response::HTTP_NOT_FOUND);
            }
        } catch (Exception $e) {
    return $this->sendError([], $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (Throwable $t) {
           return $this->sendError([], $t->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
