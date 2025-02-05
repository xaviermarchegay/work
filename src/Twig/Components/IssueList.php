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
use function Symfony\Component\String\s;

#[AsLiveComponent]
final class IssueList
{
    use DefaultActionTrait;

    #[LiveProp]
    public ?int $project_id = null;

    #[LiveProp(writable: true)]
    public string $query = '';

    public string $title = 'Issues';

    public function __construct(
        private readonly JiraInstanceRepository $repository,
        private readonly Jira                   $jira,
        private readonly RequestStack           $requestStack,
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

        if ($this->query !== '') {
            $issues = array_filter($issues, function (array $issue) {
                $key = $issue['key'];
                $title = $issue['fields']['summary'];
                $status = $issue['fields']['status']['name'];
                $prio = $issue['fields']['priority']['name'];
                $component = !empty($issue['fields']['components']) ? $issue['fields']['components'][0]['name'] : '';
                $fixVersion = !empty($issue['fields']['fixVersions']) ? $issue['fields']['fixVersions'][0]['name'] : '';

                $found = true;
                foreach (explode(' ', $this->query) as $str) {
                    $found = $found &&
                        (s($key)->ignoreCase()->startsWith($str) ||
                            s($title)->ignoreCase()->containsAny($str) ||
                            s($status)->ignoreCase()->containsAny($str) ||
                            s($prio)->ignoreCase()->containsAny($str) ||
                            s($component)->ignoreCase()->containsAny($str) ||
                            s($fixVersion)->ignoreCase()->containsAny($str)
                        );
                }
                return $found;
            });
        }

        return $issues;
    }
}
