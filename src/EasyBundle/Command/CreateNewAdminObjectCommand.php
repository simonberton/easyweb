<?php

namespace App\EasyBundle\Command;

use App\EasyBundle\Entity\BaseEntity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateNewAdminObjectCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'easy:create-admin-object';

    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new admin object.')

            ->setHelp('This command allows you to create a a new admin object, 
            by creating the entity, form, controller, service and repository.')

            ->addArgument('name', InputArgument::REQUIRED, 'Object Name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting...</info>');
        $output->writeln([
            'Creating entity, service, form, repository and controller',
            '============',
            '',
        ]);

        $objectName = $input->getArgument('name');

        $this->createEntity($objectName);
        $output->writeln('Entity Created');
        $this->createRepository($objectName);
        $output->writeln('Repository Created');
        $this->createService($objectName);
        $output->writeln('Service Created');
        $this->createForm($objectName);
        $output->writeln('Form Created');
        $this->createController($objectName);
        $output->writeln('Controller Created');

        $command = $this->getApplication()->find('d:s:u');
        $updateInput = new ArrayInput([
            'command' => 'd:s:u',
            '--force'  => true,
        ]);
        $command->run($updateInput, $output);

        $output->writeln('This is the end...');

        return 0;
    }

    protected function createEntity($objectName)
    {
        $entity = sprintf('<?php


namespace App\Entity;
        
use App\EasyBundle\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
        
/**
 * @ORM\Entity(repositoryClass="App\Repository\%sRepository")
 * @ORM\Table(name="%s")
 */
class %s extends BaseEntity
{

}
    ', ucfirst($objectName), $objectName, ucfirst($objectName));

        file_put_contents(sprintf('src/Entity/%s.php', ucfirst($objectName)), $entity);
    }

    protected function createRepository($objectName)
    {
        $repository = sprintf('<?php


namespace App\Repository;

use App\Entity\%s;
use App\EasyBundle\Library\AbstractRepository;

class %sRepository extends AbstractRepository
{

    public function getEntityClass()
    {
        return %s::class;
    }

    public function getFilterFields()
    {
        return [\'title\'];
    }
}
    ', ucfirst($objectName), ucfirst($objectName), ucfirst($objectName));

        file_put_contents(sprintf('src/Repository/%sRepository.php', ucfirst($objectName)), $repository);
    }

    protected function createService($objectName)
    {
        $service = sprintf('<?php


namespace App\Service;

use App\Entity\%s;
use App\Form\Admin\%sForm;
use App\EasyBundle\Library\AbstractService;

class %sService extends AbstractService
{

    public function getEntityClass(): string
    {
        return %s::class;
    }

    public function getFormClass(): string
    {
        return %sForm::class;
    }

    public function getSortFields(): array
    {
        return [\'title\', \'publishStatus\'];
    }

    public function getListFields(): array
    {
        return [
            [\'name\' => \'title\'],
            [\'name\' => \'slug\'],
            [\'name\' => \'publishStatus\'],
        ];
    }
}

    ', ucfirst($objectName), ucfirst($objectName), ucfirst($objectName), ucfirst($objectName), ucfirst($objectName));

        file_put_contents(sprintf('src/Service/%sService.php', ucfirst($objectName)), $service);
    }

    protected function createForm($objectName)
    {
        $form = sprintf('<?php


namespace App\Form\Admin;

use App\Entity\%s;
use App\EasyBundle\Form\Admin\BaseForm;
use Symfony\Component\OptionsResolver\OptionsResolver;

class %sForm extends BaseForm
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            \'data_class\' => %s::class,
            \'attr\' => [
                \'novalidate\' => \'novalidate\',
                \'autocomplete\' => \'off\',
                \'accept-charset\'=> \'UTF-8\'
            ]
        ]);
    }
}

    ', ucfirst($objectName), ucfirst($objectName), ucfirst($objectName));

        file_put_contents(sprintf('src/Form/Admin/%sForm.php', ucfirst($objectName)), $form);
    }

    protected function createController($objectName)
    {
        $controller = sprintf('<?php


namespace App\Controller\Admin;

use App\EasyBundle\Library\AbstractAdminController;
use App\EasyBundle\Library\AbstractService;
use App\Service\%sService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/admin/%s", name="admin_%s_")
 */
class %sController extends AbstractAdminController
{
    protected $service;

    public function __construct(%sService $service, TranslatorInterface $translator)
    {
        $this->service = $service;

       parent::__construct($translator);
    }

    protected function getService(): AbstractService
    {
        return $this->service;
    }

    protected function getListFields(): array
    {
        return $this->getService()->getListFields();
    }

    protected function getRoutePrefix(): string
    {
        return \'admin_%s\';
    }
}


    ', ucfirst($objectName), $objectName, $objectName, ucfirst($objectName), ucfirst($objectName), $objectName);

        file_put_contents(sprintf('src/Controller/Admin/%sController.php', ucfirst($objectName)), $controller);
    }
}
