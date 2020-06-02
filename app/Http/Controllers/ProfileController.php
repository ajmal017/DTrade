<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    private $pageLinks;

    public function __construct()
    {
        $this->middleware('auth');

        // Compute an array of all of the links to other pages in the profile group.
        $allRoutes = collect(Route::getroutes()->get());
        $allRoutes->filter(function (\Illuminate\Routing\Route $route) {
            return strpos($route->uri, 'profile') === 0;
        })->each(function (\Illuminate\Routing\Route $route) {
            if (array_key_exists('as', $route->action)) {
                $page = $route->action['as'];
                $this->pageLinks[$page] = "/$route->uri";
            }
        });
    }

    public function index()
    {
        return view('pages.profile.index', [
            'pageLinks' => json_encode($this->pageLinks),
        ]);
    }

    public function robinhood()
    {
        $account = Auth::user()->platforms()->where('platform', 'robinhood')->first();

        return view('pages.profile.robinhood', [
            'username'  => $account ? $account->username : '',
            'pageLinks' => json_encode($this->pageLinks),
        ]);
    }

    protected function robinhoodValidator(array $data)
    {
        return Validator::make($data, [
            'username'     => ['required', 'string', 'max:255'],
            'password'     => ['required', 'string', 'confirmed'],
        ]);
    }

    public function saveRobinhood(Request $request)
    {
        $this->robinhoodValidator($request->all())->validate();

        $platforms = Auth::user()->platforms();
        $username = $request->input('username');
        $password = Hash::make($request->input('password'));

        if ($platform = $platforms->where('platform', 'robinhood')->first()) {
            $platform->username = $username;
            $platform->password = $password;
            $platform->save();
        } else {
            $platform = $platforms->create([
                'platform' => 'robinhood',
                'username' => $username,
                'password' => $password,
            ]);
        }

        return response($platform, 200);
    }

    public function alphaVantage()
    {
        $dataSource = Auth::user()->dataSource;

        return view('pages.profile.alphavantage', [
            'api_key'   => $dataSource ? $dataSource->api_key : '',
            'pageLinks' => json_encode($this->pageLinks),
        ]);
    }

    protected function alphaVantageValidator(array $data)
    {
        return Validator::make($data, [
            'api-key'     => ['required', 'string'],
        ]);
    }

    public function saveAlphaVantage(Request $request)
    {
        $this->alphaVantageValidator($request->all())->validate();

        $alphaVantageApis = Auth::user()->dataSource();
        $apiKey = $request->input('api-key');

        if ($alphaVantageApi = $alphaVantageApis->first()) {
            $alphaVantageApi->api_key = $apiKey;
            $alphaVantageApi->save();
        } else {
            $alphaVantageApi = $alphaVantageApis->create([
                'api_key' => $apiKey,
            ]);
        }

        return response($alphaVantageApi, 200);
    }
}
