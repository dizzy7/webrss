<?php

namespace Dizzy\RssReaderBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/",name="index")
     * @Template()
     * @Security("has_role('ROLE_USER')")
     */
    public function indexAction()
    {

        return [];
    }
}
