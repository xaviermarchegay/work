<?php

declare(strict_types=1);

namespace App\HttpClient;

use App\Entity\GitlabProject;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Gitlab
{
    public function __construct(private HttpClientInterface $client)
    {
    }

    public function getProject(GitlabProject $project): array
    {
        $url = "projects/{$project->getProjectId()}";

        return $this->get($project, $url);
    }

    public function getProjectBranches(GitlabProject $project): array
    {
        $url = "projects/{$project->getProjectId()}/repository/branches?per_page=100";

        return $this->get($project, $url);
    }

    public function getProjectMergeRequests(GitlabProject $project): array
    {
        $url = "projects/{$project->getProjectId()}/merge_requests?state=opened&per_page=100";

        return $this->get($project, $url);
    }

    public function getProjectPipelines(GitlabProject $project): array
    {
        $url = "projects/{$project->getProjectId()}/pipelines?state=opened&per_page=5";

        return $this->get($project, $url);
    }

    public function getProjectPipeline(GitlabProject $project, int $pipeline): array
    {
        $url = "projects/{$project->getProjectId()}/pipelines/$pipeline";

        return $this->get($project, $url);
    }

    public function getProjectCommits(GitlabProject $project, string $branch): array
    {
        $url = "projects/{$project->getProjectId()}/repository/commits?per_page=10&ref_name=$branch";

        return $this->get($project, $url);
    }

    private function get(GitlabProject $project, string $url): array
    {
        try {
            return $this->client->withOptions([
                'base_uri' => $project->getInstance()?->getUrl() . '/api/v4/',
                'headers' => ['PRIVATE-TOKEN' => $project->getInstance()?->getToken()],
            ])->request('GET', $url)->toArray();
        } catch (\Throwable) {
            return [];
        }
    }
}