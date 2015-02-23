<?php
namespace SimplexIS\VoicedataPhp;

use Illuminate\Config\Repository;
use SimpleXMLElement;
use Log;

class Voicedata
{

    protected $config;

    /**
     * 
     * @param array $config
     */
    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function call($extension, $destination)
    {
        if (! is_null($extension) && ! is_null($destination)) {
            $xml = $this->generateXml($extension, $destination);
            
            return $this->doRequest($xml, new \GuzzleHttp\Client());
        } else {
            return false;
        }
    }

    /**
     * Generate XML for given config and params
     * 
     * @param integer $extension
     * @param string $destination
     * @return mixed xml string
     */
    public function generateXml($extension, $destination)
    {
        $destination = str_replace('-', '', $destination);
        
        $xml = new SimpleXMLElement('<message/>');
        $header = $xml->addChild('messageheader');
        $header->addChild('debug', 'false');
        $header->addChild('errorcode', 0);
        $header->addChild('errorcodedescription', 'ok');
        $header->addChild('msgdatetime', date('c'));
        $header->addChild('msgidentifier', uniqid());
        $header->addChild('msgtype', 'originate');
        $header->addChild('msgversion', '1.0');
        $body = $xml->addChild('messagebody');
        $body->addChild('accountid', $this->config->get('voicedata-php::accountid'));
        $body->addChild('xmlpass', $this->config->get('voicedata-php::xmlpass'));
        $body->addChild('extension', $extension);
        $body->addChild('destination', $destination);
        
        return $xml->asXML();
    }

    public function doRequest($xml, $client)
    {
        try {
            $response = $client->post($this->config->get('voicedata-php::post_url'), [
                'body' => $xml
            ])
                ->xml();
            return
                $response &&
                $response->messageheader &&
                $response->messageheader->msgtype &&
                $response->messageheader->msgtype == 'ack';
        } catch (GuzzleHttp\Exception\ClientException $e) {
            return false;
        } catch (GuzzleHttp\Exception\RequestException $e) {
            return true;
        }
    }
}
