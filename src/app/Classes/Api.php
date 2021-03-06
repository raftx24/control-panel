<?php

namespace LaravelEnso\ControlPanel\app\Classes;

use Carbon\Carbon;
use GuzzleHttp\Client;
use LaravelEnso\ControlPanel\app\Enums\ApplicationTypes;
use LaravelEnso\ControlPanel\app\Enums\DataTypes;
use LaravelEnso\ControlPanel\app\Exceptions\ApiRequest as Exception;
use LaravelEnso\ControlPanel\app\Models\Application;
use LaravelEnso\Helpers\app\Classes\Obj;

class Api
{
    private const EnsoStatistics = '/token/statistics';
    private const LegacyStatistics = '/api/statistics';
    private const Maintenance = '/token/maintenance';
    private const ClearLog = '/token/clearLog';

    private $params;
    private $application;
    private $client;

    public function __construct(Application $application, array $params = [])
    {
        $this->params = new Obj($params);
        $this->application = $application;
        $this->client = new Client();
    }

    public function statistics()
    {
        return $this->params->get('type') === ApplicationTypes::Legacy
            ? $this->legacy()
            : $this->enso();
    }

    public function maintenance()
    {
        if ($this->application->type === 2) {
            return $this->request('POST', self::Maintenance);
        }

        throw Exception::unsupportedOperation();
    }

    public function clearLog()
    {
        if ($this->application->type === 2) {
            return $this->request('POST', self::ClearLog);
        }

        throw Exception::unsupportedOperation();
    }

    private function enso()
    {
        return $this->request('GET', self::EnsoStatistics);
    }

    private function legacy()
    {
        return $this->request('GET', self::LegacyStatistics);
    }

    private function request($method, $path)
    {
        return $this->client->request($method, $this->application->url.$path, [
            'headers' => [
                'Api-Token' => $this->application->token,
            ],
            'query' => $this->query(),
        ]);
    }

    private function query()
    {
        $format = config('enso.config.dateTimeFormat');

        return $this->params->isNotEmpty()
            ? [
                'startDate' => $this->formattedDate($format, $this->params->get('startDate')),
                'endDate' => $this->formattedDate($format, $this->params->get('endDate')),
                'dataTypes' => json_encode(DataTypes::keys()),
            ] : [];
    }

    private function formattedDate($format, $date)
    {
        return Carbon::createFromFormat($format, $date)
            ->toDateTimeString();
    }
}
