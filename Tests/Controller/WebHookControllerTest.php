<?php

namespace Tystr\Bundle\SendgridBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Tystr\Bundle\SendgridBundle\Event\EventType;
use Tystr\Bundle\SendgridBundle\Event\WebHookEvent;

/**
 * Class WebHoookControllerTest.
 */
class WebHookControllerTest  extends WebTestCase
{
    public function testWebHook()
    {
        $client = self::createClient();

        $raw = <<< BODY
[
  {
    "email": "john.doe@sendgrid.com",
    "timestamp": 1337197600,
    "smtp-id": "<4FB4041F.6080505@sendgrid.com>",
    "event": "processed"
  },
  {
    "email": "john.doe@sendgrid.com",
    "timestamp": 1337966815,
    "category": "newuser",
    "event": "click",
    "url": "https://sendgrid.com"
  },
  {
    "email": "john.doe@sendgrid.com",
    "timestamp": 1337969592,
    "smtp-id": "<20120525181309.C1A9B40405B3@Example-Mac.local>",
    "event": "unsubscribe",
    "asm_group_id": 42
  }
]
BODY;

        $dispatcher = $client->getKernel()->getContainer()->get('event_dispatcher');
        $triggeredHooks = array();

        $dispatcher->addListener(EventType::Click, function (WebHookEvent $event) use (&$triggeredHooks) {
            $triggeredHooks[$event->getType()] = true;
        });

        $dispatcher->addListener(EventType::Processed, function (WebHookEvent $event) use (&$triggeredHooks) {
            $triggeredHooks[$event->getType()] = true;
        });

        $dispatcher->addListener(EventType::Unsubscribe, function (WebHookEvent $event) use (&$triggeredHooks) {
            $triggeredHooks[$event->getType()] = true;
        });

        $client->request(
            'POST',
            '/__tystr/sendgrid',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $raw
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($triggeredHooks['sendgrid.click']);
        $this->assertTrue($triggeredHooks['sendgrid.processed']);
        $this->assertTrue($triggeredHooks['sendgrid.unsubscribe']);
    }
}
