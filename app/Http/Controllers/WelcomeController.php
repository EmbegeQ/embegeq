<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use EmbegeQ\Nutrisi\Contracts\View\FactoryInterface;
use EmbegeQ\Nutrisi\Contracts\View\ViewInterface;
use EmbegeQ\Nutrisi\Http\Controllers\Controller as BaseController;

class WelcomeController extends BaseController
{
    public function __construct(FactoryInterface $views)
    {
        parent::__construct($views);
    }

    public function index(): ViewInterface
    {
        return $this->view('welcome', [
            'title' => 'Welcome to EmbegeQ',
        ]);
    }
}
