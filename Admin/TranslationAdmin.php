<?php

namespace Ibrows\SonataTranslationBundle\Admin;
use Sonata\AdminBundle\Route\RouteCollection;

use Lexik\Bundle\TranslationBundle\Manager\TransUnitManagerInterface;

use Sonata\AdminBundle\Model\ModelManagerInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\Admin;

abstract class TranslationAdmin extends Admin
{
    /**
     * @var TransUnitManagerInterface
     */
    protected $transUnitManager;

    public function setTransUnitManager(TransUnitManagerInterface $translationManager)
    {
        $this->transUnitManager = $translationManager;
    }

    public function setManagedLocales($managedLocales)
    {
        $this->managedLocales = $managedLocales;
    }

    public function getFilterParameters()
    {
        $this->datagridValues = array_merge(array(
                'domain' => array(
                    'value' => 'messages',
                )
            ),
            $this->datagridValues

        );
        return parent::getFilterParameters();
    }
    
    /**
     * @param unknown $name
     * @return multitype:|NULL
     */
    public function getTemplate($name)
    {
        if ($name === 'layout') {
            return 'IbrowsSonataTranslationBundle::translation_layout.html.twig';
        }
        
        if ($name === 'list') {
            return 'IbrowsSonataTranslationBundle:CRUD:list.html.twig';
        }
        
        return parent::getTemplate($name);
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->add('clear_cache')
        ;
    }
    
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->add('id', 'integer')
            ->add('key', 'string')
            ->add('domain', 'string')
        ;
        
        foreach ($this->managedLocales as $locale) {
            $fieldDescription = $this->modelManager->getNewFieldDescriptionInstance($this->getClass(), $locale);
            $fieldDescription->setTemplate('IbrowsSonataTranslationBundle:CRUD:base_inline_translation_field.html.twig');
            $fieldDescription->setOption('locale', $locale);
            $fieldDescription->setType('text');
            $list->add($fieldDescription);
        }
    }
    
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('key', 'text')
            ->add('domain', 'text')
        ;
    }
}