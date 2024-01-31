<?php

namespace Mlopez\Supergiros;

error_reporting(E_ERROR | E_PARSE);

use DOMDocument;
use DOMXPath;

class Supergiros
{

    public function call($date)
    {
        $html = $this->getResponse($date);
        $data = $this->tarnsform($html);
        return $data;
    }

    private function getResponse($date)
    {
        $url = 'https://supergirosatlantico.com.co/tabla-resultados/';

        $paramenters = [
            'nombre' => '',
            'fecha' => $date,
            'enviar' => 'consultar'
        ];

        $defaults = [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $paramenters,
            CURLOPT_RETURNTRANSFER => true,
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_PROXY, 'http://77d5838c880c44e8e90480f1e40f5b801c64ccb8:@proxy.zenrows.com:8001');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, $defaults);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    private function getResponseOld($date)
    {
        $paramenters = [
            'nombre' => '',
            'fecha' => $date,
            'enviar' => 'consultar'
        ];

        $defaults = [
            CURLOPT_URL => 'https://supergirosatlantico.com.co/tabla-resultados/',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $paramenters,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'CF-Connecting-IP: ' . $_SERVER['REMOTE_ADDR']
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $response = curl_exec($ch);

        curl_close($ch);
        return $response;
    }

    private function tarnsform($htmlContent)
    {
        $domdocument = new DOMDocument();
        $domdocument->loadHTML($htmlContent);
        $dom = new DOMXPath($domdocument);
        $spaner = $dom->query("//*[contains(@class, 'colum')]");

        $result = [];

        for ($i = 0; $i < $spaner->length - 1; $i++) {
            $result[] = $spaner->item($i)->textContent;
        }

        $format = array_chunk($result, 4);
        $response = [];

        foreach ($format as $row) {
            $response[] = [
                'loteria' => trim($row[0]),
                'fecha' => trim($row[1]),
                'resultados' => trim($row[2]),
                'serie' => trim($row[3]),
            ];
        }
        return $response;
    }

}

