<?php

namespace App\Http\Controllers;

use App\Charts\Stocks\StockIndicators;
use App\Charts\Stocks\StockPriceChart;
use App\Charts\Stocks\StockProjections;
use App\Stock;

class StocksController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('stocks.list', [
            'stocks' => Stock::all(),
        ]);
    }

    public function get($stock)
    {
        $stock = Stock::fetch($stock);
        $price = new StockPriceChart($stock);
        $projections = new StockProjections($stock);
        $indicators = new StockIndicators($stock);

        return view('stocks.stock', [
            'stock'     => $stock,
            'charts'    => [
                'price'         => $price,
                'projections'   => $projections,
                'indicators'    => $indicators,
            ],
        ]);
    }
}