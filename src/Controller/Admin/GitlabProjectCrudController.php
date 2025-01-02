<?php

namespace App\Controller\Admin;

use App\Entity\GitlabProject;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class GitlabProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GitlabProject::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Gitlab Project')
            ->setEntityLabelInPlural('Gitlab Projects')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('project_id');
        yield TextField::new('name');
        yield AssociationField::new('instance');
    }
}
