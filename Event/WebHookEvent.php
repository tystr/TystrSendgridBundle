<?php
namespace Tystr\Bundle\SendgridBundle\Event;

use PhpOption\None;
use PhpOption\Option;
use PhpOption\Some;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class WebHookEvent
 * @package Tystr\Bundle\Event
 */
class WebHookEvent extends Event
{
    /**
     * @var array
     */
    private $data;

    /**
     * @var
     */
    private $type;

    /**
     * @param array $data
     */
    public function __construct($type, array $data)
    {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * @return Option
     */
    public function getEmail()
    {
        if (!isset($this->data['email'])) {
            return None::create();
        }

        return new Some($this->data['email']);
    }

    /**
     * @return Option
     */
    public function getSmtpID()
    {
        if (!isset($this->data['smtp-id'])) {
            return None::create();
        }

        return new Some($this->data['smtp-id']);
    }

    /**
     * @return Option
     */
    public function getTimestamp()
    {
        if (!isset($this->data['timestamp'])) {
            return None::create();
        }

        return new Some(\DateTime::createFromFormat('U', $this->data['timestamp']));
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getEventData()
    {
        return $this->data;
    }
}
