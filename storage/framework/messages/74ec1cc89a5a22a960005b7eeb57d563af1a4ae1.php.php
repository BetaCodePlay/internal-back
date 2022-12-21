<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImagenController extends Controller
{
    public function imagenUpload(Request $request)
    {
        require_once(__ROOT__."../../../vendor/kraken-io/lib/Kraken.php");

        $kraken = new Kraken(env('KRAKEN_API_KEY'), env('KRAKEN_API_SECRET') );

        $params = array(
            "file" => $request->imagen,
            "wait" => true
        );

        $data = $kraken->upload($params);

        if ($data["success"]) {
            echo "Success. Optimized image URL: " . $data["kraked_url"];
        } else {
            echo "Fail. Error message: " . $data["message"];
        }
    }
}
