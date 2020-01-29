<?php

namespace LaravelEnso\ControlPanelApi\App\Services\Statistics;

use Carbon\Carbon;

class ServerTime extends BaseSensor
{
    public function value()
    {
        return Carbon::now()->format('H:i');
    }

    public function tooltip(): string
    {
        return 'server time';
    }

    public function icon()
    {
        return ['fad', 'clock'];
    }
}
