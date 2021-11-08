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

    public function access($short_url)
    {
        $result = RegisteredUrl::where('short_url', $short_url)->first();

        if ($result == NULL) {
            return "Url não encontrada.";
        } else {
            RegisteredUrl::where('short_url', $short_url)->increment('access_counter', 1);

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
        $newItem->save();

        return $newItem;
    }
}
