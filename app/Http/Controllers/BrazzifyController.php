<?php

namespace App\Http\Controllers;

use App\ImageModel;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;
use App\Http\Controllers\Controller;

class BrazzifyController extends controller
{
    public function postBrazzify(Request $request)
    {
        $url = $request->input('url');
        $logo_loc = $request->input('logo-location');

        if (!$request->has('url') || !$request->has('logo-location')) {
            return redirect('/');
        }

        $image = $this->getBrazzifiedImage($url, $logo_loc);

        if (array_key_exists('error', $image)) {
            return view('home', ['error' => $image['error']]);
        }

        if (array_key_exists('imgur_id', $image)) {
            return view('brazzify', ['imgur_id' => $image['imgur_id']]);
        }

        return view('home', ['error' => 'Unknown error.']);
    }

    private function getBrazzifiedImage($url, $logo_loc)
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

        try {
            $img = Image::make($url);
        } catch (\Exception $e) {
            return ['error' => 'Image or URL is not valid.'];
        }

        if (!in_array($logo_loc, $valid_logo_locations)) {
            return ['error' => 'Not a valid logo location.'];
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
                    'Authorization' => 'Client-ID ' . env('IMGUR_CLIENT_ID'),
                ]
            ]);

            $response = $client->request('POST', '3/image', [
                'form_params' => [
                    'image' => base64_encode($finished_image),
                    'type' => 'base64',
                    'description' => 'Image generated by http://brazzify.me',
                ]
            ]);

            if ($response->getStatusCode() != 200) {
                return ['error' => 'Error uploading image to imgur.'];
            }

            $imgur_response = json_decode($response->getBody()->getContents());
            $imgur_id = $imgur_response->data->id;

            $image_model = new ImageModel;
            $image_model->hash = $image_hash;
            $image_model->image_host = 1;
            $image_model->image_host_id = $imgur_id;

            $image_model->save();
        }

        return ['imgur_id' => $imgur_id];
    }
}