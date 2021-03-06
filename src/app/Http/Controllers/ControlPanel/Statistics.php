<?php

namespace LaravelEnso\ControlPanel\app\Http\Controllers\ControlPanel;

use App\Http\Controllers\Controller;
use LaravelEnso\ControlPanel\app\Http\Responses\StatisticsResponse;
use LaravelEnso\ControlPanel\app\Models\Application;

class Statistics extends Controller
{
    public function __invoke(Application $application)
    {
        return new StatisticsResponse($application);
    }
}
