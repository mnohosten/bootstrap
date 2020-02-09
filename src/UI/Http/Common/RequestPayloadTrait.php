<?php
declare(strict_types=1);

namespace App\UI\Http\Common;

use Symfony\Component\HttpFoundation\Request;

trait RequestPayloadTrait
{

    public function getPayload(Request $request)
    {
        return
            (array)json_decode($request->getContent(), true)
            + $request->request->all();
    }

}