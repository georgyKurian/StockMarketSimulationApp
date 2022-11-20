<?php

namespace App\Http\Controllers;

use App\Models\Ticker;

class TickerController extends Controller
{
    public function index()
    {
        $tickers = Ticker::query()
            ->orderBy('symbol')
            ->paginate(50);

        return view(
            'tickers.index',
            [
                'tickers' => $tickers
                    ->transform(function (Ticker $ticker) {
                        return [
                            'id' => $ticker->id,
                            'symbol' => $ticker->symbol,
                            'highest_profit' => number_format($ticker->simulations()->max('total_profit'), 2),
                            'number_of_simulations' => $ticker->simulations()->count(),
                            'date_range' => $ticker->getCandleDateRange(),
                        ];
                    }),
                'links' =>  $tickers->links(),
            ]
        );
    }

    public function show(Ticker $ticker)
    {
        $simulations = $ticker
            ->simulations()
            ->paginate(50);

        return view(
            'tickers.show',
            [
                'ticker' => [
                    'id' => $ticker->id,
                    'symbol' => $ticker->symbol,
                    'number_of_simulations' => $ticker->simulations()->count(),
                    'highest_profit' => number_format($ticker->simulations()->max('total_profit'), 2),
                ],
                'simulations' => $simulations
                    ->transform(fn ($simulation) => [
                        'id' => $simulation->id,
                        'threshold' => $simulation->threshold,
                        'long_profit' => number_format($simulation->long_profit, 2),
                        'short_profit' => number_format($simulation->short_profit, 2),
                        'total_profit' => number_format($simulation->total_profit, 2),
                        'created_at' => $simulation->created_at->format('M d, Y (h:i a)'),
                    ]),
                'links' =>  $simulations->links(),
            ]
        );
    }
}
