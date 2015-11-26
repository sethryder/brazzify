<?php

namespace App\Http\Controllers;

use App\ImageModel;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;

class TestController extends controller
{
    public function getBrazzify(Request $request)
    {
        $valid_logo_locations = [
            'top-left',
            'top',
            'top-right',
            'left',
            'center',
            'right',
            'bottom-left',
            'bottom',
            'bottom-right',
        ];

        $url = $request->input('url');
        $logo_loc = $request->input('logo_loc', 'bottom');

        if (!$request->has('url'))
        {
            return response()->json(['error' => 'No url included.'])->setStatusCode(400);
        }

        try {
            $img = Image::make($url);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Image or URL is not valid.'])->setStatusCode(400);
        }

        if (!in_array($logo_loc, $valid_logo_locations)) {
            return response()->json(['error' => 'Not a valid logo location.'])->setStatusCode(400);
        }

        $img->insert(base_path() . '/resources/images/brazzers-logo.png', $logo_loc, 10, 10);
        $finished_image = $img->encode('png');

        $image_hash = hash('sha256', $finished_image . $logo_loc);
        $existing_image = ImageModel::where('hash', $image_hash)->first();

        if ($existing_image) {
            $imgur_id = $existing_image->image_host_id;
        } else {
            $client = new Client([
                'base_uri' => 'https://api.imgur.com',
                'timeout' => 10,
                'headers' => [
                    'Authorization' => 'Client-ID 4a5c9268ed3de93',
                ]
            ]);

            $response = $client->request('POST', '3/image', [
                'form_params' => [
                    'image' => base64_encode($finished_image),
                    'type' => 'file',
                    'description' => 'Image generated by http://brazzify.me',
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                return response()->json(['error' => 'Error uploading image to imgur'])->setStatusCode(400);
            }

            $imgur_response = json_decode($response->getBody()->getContents());
            $imgur_id = $imgur_response->data->id;

            $image_model = new ImageModel;
            $image_model->hash = $image_hash;
            $image_model->image_host = 1;
            $image_model->image_host_id = $imgur_id;

            $image_model->save();
        }



        return response()->json(['imgur_id' => $imgur_id]);
    }
}