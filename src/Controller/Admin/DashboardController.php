<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\ExternalLink;
use App\Entity\GitlabInstance;
use App\Entity\GitlabProject;
use App\Entity\JiraInstance;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(GitlabInstanceCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Work')
            ->renderContentMaximized();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToUrl('Homepage', 'fas fa-home', '/')->setLinkRel('app_homepage');

        yield MenuItem::section('Jira');
        yield MenuItem::linkToCrud('Instances', null, JiraInstance::class);

        yield MenuItem::section('Gitlab');
        yield MenuItem::linkToCrud('Instances', null, GitlabInstance::class);
        yield MenuItem::linkToCrud('Projects', null, GitlabProject::class);

        yield MenuItem::section('Misc');
        yield MenuItem::linkToCrud('External Links', null, ExternalLink::class);
    }
}
