<?php

namespace App\Controller\Admin;

use App\Entity\JiraInstance;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;

class JiraInstanceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JiraInstance::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Jira Instance')
            ->setEntityLabelInPlural('Jira Instances')
            ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name');
        yield UrlField::new('url')->setHelp('without the trailing slash');
        yield TextareaField::new('token');
    }
}
