TystrSendgridBundle
========================

[![Build Status](https://travis-ci.org/tystr/TystrSendgridBundle.svg?branch=master)](https://travis-ci.org/tystr/TystrSendgridBundle)

A simple bundle for integrating the sendgrid php library into the Symfony2 framework.

Installation
----------------------------------

### Via Composer

Simply add the following to your project's composer.json file:

    "tystr/sendgrid-bundle": "*"

Configuration
-------------

Add the following to your configuration file:

    tystr_sendgrid_bundle:
        username: YOUR_SENDGRID_USERNAME
        password: YOUR_SENDGRID_PASSWORD
        
Usage
------

Retrieve the service like so:

    $sendgrid = $this->get('tystr_sendgrid.sendgrid');
    
    
See the [Sendgrid Documentation](https://github.com/sendgrid/sendgrid-php/blob/master/README.md#usage)
for more information.


WebHooks
--------

The bundle supports sendgrid [webhooks](https://sendgrid.com/docs/API_Reference/Webhooks/event.html).

### Add the bundles routing to your application

```yml

# in app/config/routing.yml

sendgrid_hooks:
    resource: "@TystrSendgridBundle/Resources/config/routing.xml"
```

### Listen to any of the hook events


```xml

    <service id="acme_sendgrid_listener" class="Acme\Bundle\SendgridListener">
        <tag name="kernel.event_listener" event="sendgrid.bounce" method="onEmailBounce" />
    </service>

```

```php

namespace Acme\Bundle;

use Tystr\Bundle\SendgridBundle\Event\WebHookEvent;

class SendgridListener
{
    public function onEmailBounce(WebHookEvent $event)
    {
        $this->logger->info('Address bounced: ' . $event-getEmail()->getOrElse('unknownEmail'));
    }
}

```


### Register the webhook

In the sendgrid interface, register the URL `http://yourApp.com/__tystr/sendgrid` as the webhook.



