<?php
declare(strict_types=1);

namespace App\Core\Bus;

use Exception;
use League\Event\Emitter;

class InputBus
{

    /** @var HandlerFactory */
    private HandlerFactory $factory;
    /** @var Emitter */
    private Emitter $emitter;

    public function __construct(
        HandlerFactory $factory,
        Emitter $emitter
    )
    {
        $this->factory = $factory;
        $this->emitter = $emitter;
    }

    public function handle(Input $input)
    {
        return $this->execute(
            $this->factory->create($input),
            $input
        );
    }

    private function execute(Handler $handler, Input $input)
    {
        $result = null;
        $inputName = InputHandlerName::from($input);
        $this->emitter->emit(EventName::received('input'));
        $this->emitter->emit(EventName::received($inputName));
        try {
            $result = $handler->handle($input);
        } catch (Exception $exception) {
            $this->emitter->emit(EventName::failed('input'));
            $this->emitter->emit(EventName::failed($inputName));
            throw $exception;
        }
        $this->emitter->emit(EventName::handled('input'));
        $this->emitter->emit(EventName::handled($inputName));
        return $result;
    }

}