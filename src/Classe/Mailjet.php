<?php


namespace App\Classe;


use Mailjet\Client;
use Mailjet\Resources;

class Mailjet
{
    private $apiKey_pub = '727f59e5c763e469e68750e42092dcc8';
    private $apiKey_secret = '9f500c6b79c7863b173d6a7344f6b041';
    public function send($to_email, $to_name, $subject,$content){
            $mj = new Client($this->apiKey_pub, $this->apiKey_secret, true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "ismalsacko@gmail.com",
                        'Name' => "ismaeldev"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name,
                        ]
                    ],
                    'TemplateID' => 2392836,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'content' => $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success() ;
        }
}