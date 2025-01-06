<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Repository\ExternalLinkRepository;
use App\Repository\GitlabProjectRepository;
use App\Repository\JiraInstanceRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Menu
{
    use DefaultActionTrait;

    public function __construct(
        private readonly JiraInstanceRepository $jiraInstanceRepository,
        private readonly GitlabProjectRepository $gitlabProjectRepository,
        private readonly ExternalLinkRepository $externalLinkRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function getJiraInstances(): iterable
    {
        return $this->jiraInstanceRepository->findAll();
    }

    public function getGitlabProjects(): iterable
    {
        return $this->gitlabProjectRepository->findAll();
    }

    public function getExternalLinks(): iterable
    {
        return $this->externalLinkRepository->findAll();
    }

    public function getHelmMainCommitUrl(): string
    {
        $helmProject = $this->gitlabProjectRepository->findOneBy(['path' => 'whiskyfr/helm']);

        return $this->urlGenerator->generate('app_gitlab_commits', [
            'id' => $helmProject?->getId(),
            'branch' => 'main',
        ]);
    }
}
