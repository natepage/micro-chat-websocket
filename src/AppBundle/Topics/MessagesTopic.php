<?php

namespace AppBundle\Topics;

use AppBundle\Topics\Messages\MessagesManager;
use Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

class MessagesTopic implements TopicInterface
{
    /**
     * @var \Gos\Bundle\WebSocketBundle\Client\ClientManipulatorInterface
     */
    protected $clientManipulator;

    /**
     * @var \AppBundle\Topics\Messages\MessagesManager
     */
    protected $messagesManager;

    public function __construct(ClientManipulatorInterface $clientManipulator, MessagesManager $messagesManager)
    {
        $this->clientManipulator = $clientManipulator;
        $this->messagesManager = $messagesManager;
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
        $notification = $this->messagesManager->getMessage('notification', $user, $content);

        $topic->broadcast(['msg' => $notification->render()], [$connection->WAMP->sessionId]);

        $oldMessages = $this->messagesManager->getMessages();
        foreach($oldMessages as $message){
            $topic->broadcast(['msg' => $message->render()], [], [$connection->WAMP->sessionId]);
        }
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
        $notification = $this->messagesManager->getMessage('notification', $user, $content);

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
        $message = $this->messagesManager->getMessage('conversation', $user, $event);

        $topic->broadcast(['msg' => $message->render()]);

        $this->messagesManager->save($message);
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