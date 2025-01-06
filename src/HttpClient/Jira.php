<?php

declare(strict_types=1);

namespace App\HttpClient;

use App\Entity\JiraInstance;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Jira
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function getProject(JiraInstance $project): array
    {
        $url = "projects";

        return $this->get($project, $url);
    }

    public function getMyIssues(JiraInstance $project): array
    {
        $url = 'search?jql=assignee=currentUser()';

        return $this->get($project, $url)['issues'];
    }

    private function get(JiraInstance $project, string $url): array
    {
        try {
            return $this->client->withOptions([
                'base_uri' => $project->getUrl() . '/rest/api/3/',
                'auth_basic' => [$project->getUsername(), $project->getToken()],
            ])->request('GET', $url)->toArray();
        } catch (\Throwable) {
            return [];
        }
    }
}