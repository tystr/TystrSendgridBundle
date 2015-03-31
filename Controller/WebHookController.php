<?php

namespace Tystr\Bundle\SendgridBundle\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Tystr\Bundle\SendgridBundle\Event\WebHookEvent;

/**
 * Class WebHookController.
 */
class WebHookController extends Controller
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function processHookAction(Request $request)
    {
        /** @var LoggerInterface $logger */
        $logger = $this->get('logger');

        $body = $request->getContent();
        $logger->info('Received sendgrid webhook '.$body);

        $events = json_decode($body, true);

        if ($events === false || $events === null) {
            $logger->error('Unable to decode sendgrid webhook. Body: '.$body);
            throw new BadRequestHttpException('Unabled to parse request body');
        }

        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = $this->get('event_dispatcher');

        foreach ($events as $eventData) {
            $type = 'sendgrid.'.$eventData['event'];
            $dispatcher->dispatch($type, new WebHookEvent($type, $eventData));
        }

        return new Response();
    }
}
