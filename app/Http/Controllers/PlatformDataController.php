<?php

namespace App\Http\Controllers;

use App\PlatformData;
use Illuminate\Http\Request;

class PlatformDataController extends Controller
{
    public function store(Request $request){

        $validatedData = $request->validate([
            'platform' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $platform = new PlatformData();
        $platform->platform = $validatedData['platform'];
        $platform->username = $validatedData['username'];
        $platform->password = encrypt($validatedData['username']);
        $platform->save();

        $request->user()->platforms()->associate($platform);

    }
}
