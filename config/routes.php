<?php
declare(strict_types=1);

use App\UI\Http\ViewTemplate;

return [
    /** 1.1 Public views */
    'homepage:index' => ['GET', '/', [ViewTemplate::class, 'page/homepage']],
];
