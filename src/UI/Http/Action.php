<?php
declare(strict_types=1);

namespace App\UI\Http;

use App\Core\Router\Route;
use Symfony\Component\HttpFoundation\Response;

interface Action
{

    public function __invoke(Route $route): Response;

}