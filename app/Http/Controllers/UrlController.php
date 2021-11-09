<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegisteredUrl;
use Illuminate\Http\RedirectResponse;


class UrlController extends Controller
{
    public function list()
    {
        return RegisteredUrl::all();
    }

    public function checkIfExists($short_url)
    {
        $result = RegisteredUrl::where('short_url', $short_url)->first();

        return $result == NULL ? 0 : 1;
    }

    public function access(Request $request, $short_url)
    {
        $result = RegisteredUrl::where('short_url', $short_url)->first();

        if ($result == NULL) {
            return "Url não encontrada.";
        } else {

            $this->registerAccess($short_url);

            $access_infos = $result->access_infos;

            $access_infos[] = [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            $result->access_infos = $access_infos;

            $result->save();

            return new RedirectResponse($result->original_url);
        }
    }

    public function registerAccess($short_url)
    {
        RegisteredUrl::where('short_url', $short_url)->increment('access_counter', 1);
    }

    public function store(Request $request)
    {
        $newItem = new RegisteredUrl;
        $newItem['original_url'] = $request["original_url"];
        $newItem['short_url'] = $request["short_url"];
        $newItem['access_infos'] = [];
        $newItem->save();

        return $newItem;
    }
}
