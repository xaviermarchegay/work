<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\GitlabInstance;
use App\Entity\GitlabProject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

        yield MenuItem::section('Gitlab');
        yield MenuItem::linkToCrud('Instances', null, GitlabInstance::class);
        yield MenuItem::linkToCrud('Projects', null, GitlabProject::class);
    }
}
