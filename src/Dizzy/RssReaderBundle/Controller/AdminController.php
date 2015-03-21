<?php

namespace Dizzy\RssReaderBundle\Controller;

use Dizzy\RssReaderBundle\Entity\PostRepository;
use Dizzy\RssReaderBundle\Entity\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Ob\HighchartsBundle\Highcharts\Highchart;

class AdminController extends Controller
{
    /**
     * @Route("/admin/stat",name="admin_stat")
     * @Template()
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function statAction()
    {
        /** @var UserRepository $userRepo */
        $userRepo = $this->getDoctrine()->getManager()->getRepository('DizzyRssReaderBundle:User');
        $data = $userRepo->getUsersStat();
        $series1 = array(
            array("name" => "Количество пользователей",  "data" => $data['values'])
        );

        $usersChart = new Highchart();
        $usersChart->chart->renderTo('users');  // The #id of the div where to render the chart
        $usersChart->title->text('Количество зарегистрированных пользователей');
        $usersChart->xAxis->title(array('text'  => "Дата"));
        $usersChart->xAxis->categories($data['labels']);
        $usersChart->yAxis->title(array('text'  => "Количество"));
        $usersChart->yAxis->min(0);
        $usersChart->series($series1);

        /** @var PostRepository $postRepo */
        $postRepo = $this->getDoctrine()->getManager()->getRepository('DizzyRssReaderBundle:Post');
        $data = $postRepo->getPostsStat();
        $series2 = array(
            array("name" => "Новых постов за день",  "data" => $data['values'])
        );

        $postsChart = new Highchart();
        $postsChart->chart->renderTo('posts');  // The #id of the div where to render the chart
        $postsChart->title->text('Постов за день');
        $postsChart->xAxis->title(array('text'  => "Дата"));
        $postsChart->xAxis->categories($data['labels']);
        $postsChart->yAxis->title(array('text'  => "Количество"));
        $postsChart->yAxis->min(0);
        $postsChart->series($series2);

        return ['users' => $usersChart,'posts'=>$postsChart];
    }
}
