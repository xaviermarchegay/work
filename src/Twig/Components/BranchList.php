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
final class BranchList
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?int $project_id = null;

    public string $title = 'Branches';

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

    public function getBranches(): array
    {
        $branches = $this->gitlab->getProjectBranches($this->getProject());

        usort($branches, static function ($a, $b) {
            return strcmp($b['commit']['created_at'], $a['commit']['created_at']);
        });

        return $branches;
    }
}
