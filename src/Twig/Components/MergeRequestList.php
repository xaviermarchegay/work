<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\GitlabProject;
use App\HttpClient\Gitlab;
use App\Repository\GitlabProjectRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class MergeRequestList
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?int $project_id = null;

    public string $title = 'Merge Requests';

    public function __construct(
        private readonly GitlabProjectRepository $projectRepository,
        private readonly Gitlab $gitlab,
        private readonly RequestStack $requestStack,
    )
    {
    }

    public function getProject(): GitlabProject
    {
        return $this->projectRepository->find($this->project_id);
    }

    public function getMergeRequests(): array
    {
        return $this->gitlab->getProjectMergeRequests($this->getProject());
    }
}
