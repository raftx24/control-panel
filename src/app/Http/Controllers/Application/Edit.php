<?php

namespace LaravelEnso\ControlPanel\app\Http\Controllers\Application;

use Illuminate\Routing\Controller;
use LaravelEnso\ControlPanel\app\Forms\Builders\ApplicationForm;
use LaravelEnso\ControlPanel\app\Models\Application;

class Edit extends Controller
{
    public function __invoke(Application $application, ApplicationForm $form)
    {
        return ['form' => $form->edit($application)];
    }
}
