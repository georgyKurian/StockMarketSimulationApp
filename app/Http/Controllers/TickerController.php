<?php

namespace App\Http\Controllers;

use App\Models\Ticker;

class TickerController extends Controller
{
    public function index()
    {
        $tickers = Ticker::query()
            ->with(['bestPerformingSimulation'])
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
                            'highest_profit' => convert_cents_to_dollar_string($ticker->bestPerformingSimulation?->total_net_profit),
                            'highest_profit_percentage' => convert_to_percentage_string($ticker->bestPerformingSimulation?->profit_percentage),
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
                    'highest_profit' => convert_cents_to_dollar_string($ticker->bestPerformingSimulation?->total_profit),
                    'highest_profit_percentage' => convert_to_percentage_string($ticker->bestPerformingSimulation?->profit_percentage),
                ],
                'simulations' => $simulations
                    ->transform(fn ($simulation) => [
                        'id' => $simulation->id,
                        'threshold' => convert_cents_to_dollar_string($simulation->threshold),
                        'long_profit' => convert_cents_to_dollar_string($simulation->long_profit),
                        'short_profit' => convert_cents_to_dollar_string($simulation->short_profit),
                        'total_profit' => convert_cents_to_dollar_string($simulation->total_profit),

                        'short_entered_days' => $simulation->short_entered_days,
                        'long_entered_days' => $simulation->long_entered_days,

                        'long_net_profit' => convert_cents_to_dollar_string($simulation->long_net_profit),
                        'short_net_profit' => convert_cents_to_dollar_string($simulation->short_net_profit),
                        'total_net_profit' => convert_cents_to_dollar_string($simulation->total_net_profit),

                        'profit_percentage' => convert_to_percentage_string($simulation->profit_percentage),

                        'created_at' => $simulation->created_at->format('M d, Y (h:i a)'),
                        'date_range' => $simulation->dateRange(),
                    ]),
                'links' =>  $simulations->links(),
            ]
        );
    }
}
