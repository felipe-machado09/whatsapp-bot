<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;


class WhatsappHelper
{

    private $whatsapp;

    public function __construct()
    {
        $url = env('Z_API_URL');
        $instance = env('Z_API_INSTANCE');
        $token = env('Z_API_TOKEN');
        $this->finalUrl = $url.$instance."/token/".$token;
        $this->logoImg = env('LOGO_LINK_CONFIRMA_NOTICIA');
    }

   public function sendMessage(string $phone, string $text){
        $response = Http::post($this->finalUrl.'/send-text', [
            "phone" => $phone,
            "message" => $text
        ]);
        return $response->object();
    }


   public function createGroup(string $groupName, array $phones){
        $response = Http::post($this->finalUrl.'/create-group', [
            "groupName" => $groupName,
            "phones" => $phones
        ]);
        return $response->object();
   }

   public function getGroupInfo(string $url){
        $response = Http::get($this->finalUrl.'/group-invitation-metadata?url='.$url);
        return $response->object();
   }

   public function sendLink(string $phone, string $message,string $thumbail ,string $linkUrl,string $title,string $linkDescription, int $delayMessage = 0){
        $response = Http::post($this->finalUrl.'/send-link', [
            "phone" => $phone,
            "message" => $message,
            "image" => $thumbail,
            "linkUrl" => $linkUrl,
            "title" => $title,
            "linkDescription" => $linkDescription,
            "delayMessage" => $delayMessage
        ]);
        return $response;
   }

   public function UpdateGroupSettings(string $phone, bool $adminOnlyMessage, bool $adminOnlySettings){
        $response = Http::post($this->finalUrl.'/update-group-settings', [
            "phone" => $phone,
            "adminOnlyMessage" => $adminOnlyMessage,
            "adminOnlySettings" => $adminOnlySettings,
        ]);
        return $response->object();
   }

   public static function getImgFromXml($xmlObj){
        $img = false;
        if(isset($xmlObj->children('media', true)->content)){
            $obj = $xmlObj->children('media', true)->content;
            $imgJson = json_encode($obj->attributes());
            $newArr = json_decode($imgJson, true);
            $img = $newArr['@attributes']['url'];
        }
        return $img;
    }

   public static function getResume($str, $limit=100, $strip = false) {
        $str = ($strip == true)?strip_tags($str):$str;
        if (strlen ($str) > $limit) {
            $str = substr ($str, 0, $limit - 3);
            return (substr ($str, 0, strrpos ($str, ' ')).'...');
        }
        return trim($str);
   }


}
