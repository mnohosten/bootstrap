<?php
declare(strict_types=1);

namespace App\UI\Http\Middleware;

use Symfony\Component\HttpFoundation\Response;

interface Middleware
{

    public function execute(): ?Response;

}