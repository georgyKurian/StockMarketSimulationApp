<?php

namespace App\Http\Controllers;

use App\Models\Ticker;

class TickerController extends Controller
{
    public function index()
    {
        $tickers = Ticker::query()
            ->orderBy('symbol')
            ->with(['bestPerformingSimulation'])
            ->paginate(50);

        return view(
            'tickers.index',
            [
                'tickers' => $tickers
                    ->transform(function (Ticker $ticker) {
                        return [
                            'id' => $ticker->id,
                            'symbol' => $ticker->symbol,
                            'highest_profit' => number_format($ticker->bestPerformingSimulation?->total_net_profit, 2),
                            'highest_profit_percentage' => number_format($ticker->bestPerformingSimulation?->profit_percentage, 2).' %',
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
            ->orderByDesc('total_net_profit')
            ->paginate(50);

        return view(
            'tickers.show',
            [
                'ticker' => [
                    'id' => $ticker->id,
                    'symbol' => $ticker->symbol,
                    'number_of_simulations' => $ticker->simulations()->count(),
                    'highest_profit' => number_format($ticker->bestPerformingSimulation?->total_profit, 2),
                    'highest_profit_percentage' => number_format($ticker->bestPerformingSimulation?->profit_percentage, 2).' %',
                ],
                'simulations' => $simulations
                    ->transform(fn ($simulation) => [
                        'id' => $simulation->id,
                        'threshold' => $simulation->threshold,
                        'long_profit' => number_format($simulation->long_profit, 2),
                        'short_profit' => number_format($simulation->short_profit, 2),
                        'total_profit' => number_format($simulation->total_profit, 2),

                        'short_entered_days' => $simulation->short_entered_days,
                        'long_entered_days' => $simulation->long_entered_days,

                        'long_net_profit' => number_format($simulation->long_net_profit, 2),
                        'short_net_profit' => number_format($simulation->short_net_profit, 2),
                        'total_net_profit' => number_format($simulation->total_net_profit, 2),

                        'profit_percentage' => number_format($simulation->profit_percentage, 2).' %',

                        'created_at' => $simulation->created_at->format('M d, Y (h:i a)'),
                        'date_range' => $simulation->dateRange(),
                    ]),
                'links' =>  $simulations->links(),
            ]
        );
    }
}
