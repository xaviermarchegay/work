<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Entity\JiraInstance;
use App\HttpClient\Jira;
use App\Repository\JiraInstanceRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class IssueList
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?int $project_id = null;

    public string $title = 'Issues';

    public function __construct(
        private readonly JiraInstanceRepository $repository,
        private readonly Jira $jira,
        private readonly RequestStack $requestStack,
    )
    {
    }

    public function getProject(): JiraInstance
    {
        return $this->repository->find($this->project_id);
    }

    public function getIssues(): array
    {
        $issues = $this->jira->getMyIssues($this->getProject());

        usort($issues, static function ($a, $b) {
            return strcmp($b['fields']['updated'], $a['fields']['updated']);
        });

        return $issues;
    }
}
