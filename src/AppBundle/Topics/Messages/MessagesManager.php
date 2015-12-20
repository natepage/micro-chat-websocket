<?php

namespace AppBundle\Topics\Messages;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Templating\EngineInterface;
use AppBundle\Entity\Message;

class MessagesManager
{
    /**
     * @var \Doctrine\ORM\EntityManager;
     */
    private $em;

    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    protected $templating;

    /**
     * @var string
     */
    private $repoMessage = 'AppBundle:Message';

    /**
     * @var string
     */
    private $repoUser = 'AppBundle:User';

    /**
     * @var array
     */
    private $classes = array(
        'conversation' => 'AppBundle\Topics\Messages\ConversationMessage',
        'notification' => 'AppBundle\Topics\Messages\NotificationMessage'
    );

    public function __construct(EntityManager $em, EngineInterface $templating)
    {
        $this->em = $em;
        $this->templating = $templating;
    }

    /**
     * Get messages by type.
     *
     * @param string $type
     * @return mixed
     */
    public function getMessages($type = 'conversation')
    {
        if(!array_key_exists($type, $this->classes)){
            throw new InvalidArgumentException(sprintf('Invalid "%s" message type given.', $type));
        }

        $entities = $this->em->getRepository($this->repoMessage)->findMessagesByTypeWithUser($type);
        $messages = array();

        foreach($entities as $entity){
            $class = $this->classes[$type];

            $message = new $class();
            $message->setTemplating($this->templating);
            $message->setContent($entity->getContent());
            $message->setDate($entity->getDate());
            $message->setUser($entity->getUser());

            $messages[] = $message;
        }

        return $messages;
    }

    /**
     * Get MessageInterface from type, user and content.
     *
     * @param $type
     * @param UserInterface $user
     * @param $content
     * @return MessageInterface $message
     */
    public function getMessage($type, UserInterface $user, $content)
    {
        if(!array_key_exists($type, $this->classes)){
            throw new InvalidArgumentException(sprintf('Invalid "%s" message type given.', $type));
        }

        $class = $this->classes[$type];

        $message = new $class();
        $message->setTemplating($this->templating);
        $message->setContent($content);
        $message->setDate(new \DateTime());
        $message->setUser($user);

        return $message;
    }

    /**
     * Save in database the Message entity representation of a MessageInterface instance.
     *
     * @param MessageInterface $message
     */
    public function save(MessageInterface $message)
    {
        $entity = new Message();
        $entity->setContent($message->getContent());
        $entity->setDate($message->getDate());
        $entity->setUser($this->getUserEntity($message->getUser()));
        $entity->setType($message->getType());

        try {
            $this->em->persist($entity);
            $this->em->flush();
        } catch(\Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     * Get the good User Entity.
     *
     * @param UserInterface $user
     * @return UserInterface
     */
    private function getUserEntity(UserInterface $user)
    {
        return $this->em->getRepository($this->repoUser)->findOneBy(array('username' => $user->getUsername()));
    }
}