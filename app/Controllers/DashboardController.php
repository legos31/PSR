<?php

namespace App\Controllers;

use Framework\Controller\AbstractController;
use Framework\Http\Response;

class DashboardController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('dashboard.html.twig');
    }
}
