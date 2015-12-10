<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\LoginType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction()
    {
        $form = $this->createForm('AppBundle\Form\LoginType');

        $helper = $this->get('security.authentication_utils');

        return $this->render('security/login.html.twig', array(
            'form' => $form->createView(),
            'error' => $helper->getLastAuthenticationError()
        ));
    }

    /**
     * @Route("/login_check", name="security_login_check")
     */
    public function loginCheckAction()
    {}

    /**
     * @Route("/user/create/{username}/{password}", name="security_user_create")
     */
    public function userCreateAction($username, $password)
    {
        $em = $this->getDoctrine()->getManager();
        $encoder = $this->get('security.password_encoder');
        $user = new User();

        $user->setUsername($username);
        $user->setPassword($encoder->encodePassword($user, $password));

        $em->persist($user);
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/user/get/{username}", name="security_user_get")
     */
    public function userGetAction($username)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(array('username' => $username));

        if(null === $user){
            throw $this->createNotFoundException(sprintf('User %s could not be found', $username));
        }

        return $this->render('default/index.html.twig', array('user' => $user));
    }
}
