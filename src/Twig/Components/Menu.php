<?php

declare(strict_types=1);

namespace App\Twig\Components;

use App\Repository\GitlabProjectRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class Menu
{
    use DefaultActionTrait;

    public function __construct(
        private readonly GitlabProjectRepository $gitlabProjectRepository,
        private readonly UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    public function getGitlabProjects(): iterable
    {
        return $this->gitlabProjectRepository->findAll();
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
