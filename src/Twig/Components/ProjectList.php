<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\HttpClient\Gitlab;
use App\Repository\GitlabProjectRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProjectList
{
    use DefaultActionTrait;

    public function __construct(private readonly GitlabProjectRepository $projectRepository, private readonly Gitlab $gitlab)
    {
    }

    public function getProjects(): iterable
    {
        foreach ($this->projectRepository->findAll() as $project) {
            yield [
                'project' => $project,
                'gitlab' => $this->gitlab->getProject($project),
            ];
        }
    }
}
