<?php

namespace AppBundle\Console;

use AppBundle\Service\EmailChangingSystem;
use AppBundle\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EmailChangeCommand.php
 * @package AppBundle\Console
 * @author Marcin Bonk <marvcin@gmail.com>
 */
class EmailChangeCommand extends ContainerAwareCommand
{
    /**
     * @var UserService
     */
    protected $userService;

    /**
     * @param UserService $emailChangingSystem
     */
    public function __construct(UserService $emailChangingSystem)
    {
        $this->userService = $emailChangingSystem;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('email:change')
            ->setDescription('Change user\'s email')
            ->addArgument(
                'current_email',
                InputArgument::REQUIRED,
                'Provide current email'
            )
            ->addArgument(
                'new_email',
                InputArgument::REQUIRED,
                'Provide new email'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $current = $input->getArgument('current_email');
        $new = $input->getArgument('new_email');
        $this->userService->changeEmail($current, $new);

        $output->writeln(sprintf(
            'Email for user changed from "%s" to "%s"',
            $current, $new
        ));
    }
}