<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomepageController extends AbstractController
{
   /**
    * homepage function
    * just to see the dashboard 
    * @return void
    */
    public function homepage()
    {
        return $this->render('homepage/homepage.html.twig');
    }
}
