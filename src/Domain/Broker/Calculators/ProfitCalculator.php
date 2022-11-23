<?php

namespace Domain\Broker\Calculators;

class ProfitCalculator
{
    private const COMMISSION_ON_ONE_TRADE = 0.01;
    private const MINIMUM_COMMISSION = 4.95;
    private const MAXIMUM_COMMISSION = 9.95;

    private function __construct(private  int $daysEntered)
    {
    }

    private function getTotalMinimumCommission()
    {
        return $this->numberOfTransactions * self::MINIMUM_COMMISSION;
    }
}
