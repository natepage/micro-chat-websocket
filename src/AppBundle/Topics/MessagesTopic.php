<?php

namespace AppBundle\Topics;

use AppBundle\Topics\Messages\ConversationMessage;
use AppBundle\Topics\Messages\NotificationMessage;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Symfony\Component\Templating\EngineInterface;

class MessagesTopic implements TopicInterface
{
    /**
     * @var \Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface
     */
    protected $clientManipulator;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $templating;

    public function __construct(ClientManipulatorInterface $clientManipulator, EngineInterface $templating)
    {
        $this->clientManipulator = $clientManipulator;
        $this->templating = $templating;
    }

    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param  WampRequest $request
     */
    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $content = 'has joined conversation.';
        $user = $this->clientManipulator->getClient($connection);
        $notification = new NotificationMessage($this->templating, $user, $content);

        $topic->broadcast(['msg' => $notification->render()], [$connection->WAMP->sessionId]);
    }

    /**
     * This will receive any UnSubscription requests for this topic.
     *
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param  WampRequest $request
     */
    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $content = 'has left conversation.';
        $user = $this->clientManipulator->getClient($connection);
        $notification = new NotificationMessage($this->templating, $user, $content);

        $topic->broadcast(['msg' => $notification->render()], [$connection->WAMP->sessionId]);
    }

    /**
     * This will receive any Publish requests for this topic.
     *
     * @param  ConnectionInterface $connection
     * @param  Topic $topic
     * @param  WampRequest $request
     * @param  $event
     * @param  array $exclude
     * @param  array $eligible
     */
    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        $user = $this->clientManipulator->getClient($connection);
        $message = new ConversationMessage($this->templating, $user, $event);

        $topic->broadcast(['msg' => $message->render()]);
    }

    /**
     * Like RPC is will use to prefix the channel
     *
     * @return string
     */
    public function getName()
    {
        return 'messages.topic';
    }
}