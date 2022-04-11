<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SwordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function serviceDocument(Request $request) {
        $maxUploadBytes = $this->return_bytes(ini_get('upload_max_filesize'));
        return [
            '@context' => 'https://swordapp.github.io/swordv3/swordv3.jsonld',
            '@id' => $request->url(),
            '@type' => 'ServiceDocument',
            'dc:title' => config('app.name'),
            'dcterms:abstract' => env('APP_DESCRIPTION'),
            'root' => config('app.url'),
            'acceptDeposits' => true,
            'version' => 'http://purl.org/net/sword/3.0',
            'maxUploadSize' => $maxUploadBytes,
            'maxByReferenceSize' => (int) env('SWORD_MAX_BY_REFERENCE_SIZE'),
            'maxAssembledSize' => $maxUploadBytes,
            'maxSegments' => 1,
            'accept' => ['*/*'],
            'acceptArchiveFormat' => ['application/zip'],
            'acceptPackaging' => ['*'],
            'acceptMetadata' => ['http://purl.org/net/sword/3.0/types/Metadata'],
            'byReferenceDeposit' => false,
            'onBehalfOf' => false,
            'digest' => ['SHA-256'],
            'authentication' => ['APIKey'],
            'services' => [],
        ];
    }

    // See https://www.php.net/manual/en/function.ini-get.php (fixed for modern PHP)
    private function return_bytes($val) : int
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $exceptLast = substr($val, 0, -1);
        switch($last) {
            // The 'G' modifier is available
            case 'g':
                return $exceptLast * 1024 * 1024 * 1024;
            case 'm':
                return $exceptLast * 1024 * 1024;
            case 'k':
                return $exceptLast * 1024;
        }

        return $val;
    }
}
