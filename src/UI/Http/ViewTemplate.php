<?php
declare(strict_types=1);

namespace App\UI\Http;

use App\Core\Template\Template;
use App\Core\UserConfig\UserConfig;
use App\UI\Http\Common\Authorization;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ViewTemplate
{
    /** @var Template */
    private $template;

    public function __construct(
        Template $template
    )
    {
        $this->template = $template;
    }

    public function __invoke(string $template): Response
    {
        return new StreamedResponse(function() use ($template) {
            $this->template->render($template);
        });
    }
}