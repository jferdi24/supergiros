<?php

namespace Mlopez\Supergiros;

error_reporting(E_ERROR | E_PARSE);

use DOMDocument;
use DOMXPath;
use GuzzleHttp\Client;

class Supergiros
{
    public function call(string $date): array
    {
        $html = $this->getResponse($date);
        $data = $this->transform($html);

        return $data;
    }

    private function getResponse($date): string
    {
        $paramenters = [
            'nombre' => '',
            'fecha' => $date,
            'enviar' => 'consultar'
        ];

        $client = new Client([
            'base_uri' => 'https://supergirosatlantico.com.co/tabla-resultados',
            'proxy' => 'http://77d5838c880c44e8e90480f1e40f5b801c64ccb8:@proxy.zenrows.com:8001',
            'verify' => false,
        ]);

        return $client->post('', $paramenters)->getBody()->getContents();
    }

    private function transform($htmlContent): array
    {
        $domDocument = new DOMDocument();
        $domDocument->loadHTML($htmlContent);
        $dom = new DOMXPath($domDocument);
        $nodes = $dom->query("//*[contains(@class, 'colum')]");

        $result = [];

        for ($i = 0; $i < $nodes->length - 1; $i++) {
            $result[] = $nodes->item($i)->textContent;
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
