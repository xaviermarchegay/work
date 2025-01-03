<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Repository\GitlabProjectRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ProjectList
{
    use DefaultActionTrait;

    public function __construct(private readonly GitlabProjectRepository $projectRepository)
    {
    }

    public function getProjects(): iterable
    {
        return $this->projectRepository->findAll();
    }
}
