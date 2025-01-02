<?php

namespace App\Controller;

use App\Entity\GitlabProject;
use Symfony\Bridge\Twig\Attribute\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class ComponentController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    #[Template('component.html.twig')]
    public function index(): array
    {
        $title = 'Homepage';
        $components = [['component_name' => 'ProjectList']];

        return compact('title', 'components');
    }

    #[Route('/gitlab/{id}', name: 'app_gitlab_project')]
    #[Template('component.html.twig')]
    public function gitlabProject(GitlabProject $project): array
    {
        $title = "Gitlab - {$project->getName()}";
        $components = [
            ['component_name' => 'MergeRequestList', 'component_extra' => ['project_id' => $project->getId()]],
            ['component_name' => 'PipelineList', 'component_extra' => ['project_id' => $project->getId()]],
            ['component_name' => 'BranchList', 'component_extra' => ['project_id' => $project->getId()]],
        ];

        return compact('title', 'components');
    }

    #[Route('/gitlab/{id}/commits?{branch}', name: 'app_gitlab_commits', requirements: ['branch' => '.+'])]
    #[Template('component.html.twig')]
    public function gitlabCommits(GitlabProject $project, string $branch): array
    {
        $title = "Gitlab - {$project->getName()}";
        $components = [['component_name' => 'CommitList', 'component_extra' => ['project_id' => $project->getId(), 'branch' => $branch]]];

        return compact('title', 'components');
    }
}
