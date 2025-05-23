<?php

namespace App\EasyBundle\Command;

use App\EasyBundle\Entity\BaseEntity;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(
    name: 'easy:create-admin-object',
    description: 'Creates a new admin object.',
    aliases: [],
    hidden: false
)]
class CreateNewAdminObjectCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setHelp(<<<'HELP'
This command allows you to create a new admin object by creating
the entity, form, controller, service, and repository.
HELP
            )
            ->addArgument('name', InputArgument::REQUIRED, 'Object Name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Starting...</info>');
        $output->writeln([
            'Creating entity, service, form, repository and controller',
            '============',
            '',
        ]);

        $objectName = ucfirst($input->getArgument('name'));

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

        // Run doctrine:schema:update --force command (better use full name)
        $command = $this->getApplication()->find('doctrine:schema:update');
        $updateInput = new ArrayInput([
            'command' => 'doctrine:schema:update',
            '--force'  => true,
        ]);
        $command->run($updateInput, $output);

        $output->writeln('<info>This is the end...</info>');

        return Command::SUCCESS;
    }

    protected function createEntity(string $objectName): void
    {
        $entity = sprintf('<?php

namespace App\Entity;

use App\EasyBundle\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\\%1$sRepository")]
#[ORM\Table(name: "%2$s")]
class %1$s extends BaseEntity
{
}
',
            $objectName,
            strtolower($objectName)
        );

        file_put_contents(sprintf('src/Entity/%s.php', $objectName), $entity);
    }


    protected function createRepository(string $objectName): void
    {
        $repository = sprintf('<?php

namespace App\Repository;

use App\Entity\%s;
use App\EasyBundle\Library\AbstractRepository;

class %sRepository extends AbstractRepository
{
    public function getEntityClass(): string
    {
        return %s::class;
    }

    public function getFilterFields(): array
    {
        return [\'title\'];
    }
}
',
            $objectName,
            $objectName,
            $objectName
        );

        file_put_contents(sprintf('src/Repository/%sRepository.php', $objectName), $repository);
    }

    protected function createService(string $objectName): void
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
',
            $objectName,
            $objectName,
            $objectName,
            $objectName,
            $objectName
        );

        file_put_contents(sprintf('src/Service/%sService.php', $objectName), $service);
    }

    protected function createForm(string $objectName): void
    {
        $form = sprintf('<?php

namespace App\Form\Admin;

use App\Entity\%s;
use App\EasyBundle\Form\Admin\BaseForm;
use Symfony\Component\OptionsResolver\OptionsResolver;

class %sForm extends BaseForm
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            \'data_class\' => %s::class,
            \'attr\' => [
                \'novalidate\' => \'novalidate\',
                \'autocomplete\' => \'off\',
                \'accept-charset\' => \'UTF-8\'
            ],
        ]);
    }
}
',
            $objectName,
            $objectName,
            $objectName
        );

        file_put_contents(sprintf('src/Form/Admin/%sForm.php', $objectName), $form);
    }

    protected function createController(string $objectName): void
    {
        $controller = sprintf('<?php

namespace App\Controller\Admin;

use App\EasyBundle\Library\AbstractAdminController;
use App\EasyBundle\Library\AbstractService;
use App\Service\%1$sService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route(path: "/admin/%2$s", name: "admin_%2$s_")]
class %1$sController extends AbstractAdminController
{
    public function __construct(
        protected %1$sService $service,
        TranslatorInterface $translator,
    ) {
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
        return \'admin_%2$s\';
    }
}
',
            $objectName,
            strtolower($objectName)
        );

        file_put_contents(sprintf('src/Controller/Admin/%sController.php', $objectName), $controller);
    }
}
