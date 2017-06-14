<?php

namespace LaravelEnso\StatisticsManager\app\Http\Controllers;

use App\Http\Controllers\Controller;
use LaravelEnso\ActionLogger\app\Models\ActionLog;
use LaravelEnso\Core\app\Models\Login;
use LaravelEnso\Helpers\Classes\Object;

class StatisticsController extends Controller
{
    /** Valid calls are:
     * (api token) http://enso.dev/api/statistics?api_token=abc&startDate=2017-01-01&endDate=2017-12-01
     * (passport) http://enso.dev/api/statistics?startDate=2017-01-01&endDate=2017-12-01
     * For passport you need to pass the bearer in the header.
     *
     * @return array
     */
    public function getStatistics()
    {
        $startDate = \Date::parse(request('startDate'))->format('Y-m-d');
        $endDate = \Date::parse(request('endDate'))->format('Y-m-d');
        $response = [];

        $tmp = new Object();
        $tmp->key = 'logins';
        $tmp->value = Login::where('created_at', '>', $startDate)->where('created_at', '<', $endDate)->count();

        $response[] = $tmp;

        $tmp = new Object();
        $tmp->key = 'actions';
        $tmp->value = ActionLog::where('created_at', '>', $startDate)->where('created_at', '<', $endDate)->count();

        $response[] = $tmp;

        return json_encode($response);
    }
}
