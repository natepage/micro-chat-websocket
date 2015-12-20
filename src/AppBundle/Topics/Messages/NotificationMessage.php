<?php

namespace AppBundle\Topics\Messages;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Templating\EngineInterface;

class NotificationMessage implements MessageInterface
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var \Symfony\Component\Security\Core\User\UserInterface;
     */
    private $user;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    /**
     * @var string
     */
    private $template = 'messages/notification.html.twig';

    /**
     * Sets the message templating.
     *
     * @param EngineInterface $templating
     * @return self
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;
        return $this;
    }

    /**
     * Sets the message content.
     *
     * @param string $content
     * @return self
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get the message content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the message date.
     *
     * @param \DateTime $date
     * @return self
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get the message date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets the message user.
     *
     * @param UserInterface $user
     * @return self
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get the message user.
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Return the message HTML representation.
     *
     * @return string
     */
    public function render()
    {
        return $this->templating->render($this->template, array(
            'content' => $this->content,
            'user' => $this->user
        ));
    }

    /**
     * Return the message type.
     *
     * @return string
     */
    public function getType()
    {
        return 'notification';
    }
}