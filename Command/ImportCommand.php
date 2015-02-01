<?php

namespace ChrKo\Bundle\GermanCentralBankBundle\Command;

use ChrKo\Bundle\GermanCentralBankBundle\Entity\Bank;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ImportCommand
    extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('chrko:gcbb:import')
            ->setDescription('Import bank number txt')
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'The file you want to import'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        $fileReal = realpath($file);

        $output->write('<info>Checking if file</info> ' . $file . ' <info>exits...</info> ');
        if (!$this->getContainer()->get('filesystem')->exists($fileReal)) {
            $output->writeln('<error> File not found! </error>');

            return;
        }
        $output->writeln('<info>File found!</info>');

        $output->writeln('<info>Opening file:</info> ' . $fileReal . '');
        $fileContents = file($file);
        $output->writeln('<info>Found</info> ' . $lines = count($fileContents) . ' <info>lines in the file.</info>');


        $questionHelper = $this->getHelper('question');
        $output->writeln('');
        if (
        !$questionHelper->ask(
            $input,
            $output,
            new ConfirmationQuestion(
                '<question>Do you confirm deleting all entries? (Y/n)</question> ',
                true
            )
        )
        ) {
            $output->writeln('');
            $output->writeln('<error>                                                     </error>');
            $output->writeln('<error>  Aborting, because merging is not yet implemented!  </error>');
            $output->writeln('<error>                                                     </error>');

            return;
        }

        $output->writeln('');

        $doctrine = $this->getContainer()->get('doctrine');
        $entityManger = $doctrine->getManager();

        $validator = $this->getContainer()->get('validator');

        $deletedRows = $entityManger->createQuery('DELETE FROM ChrKo\Bundle\GermanCentralBankBundle\Entity\Bank b')
                                    ->execute();
        $output->writeln('<info>Deleted </info>' . $deletedRows . '<info> Rows!</info>');

        $output->writeln('');
        if (
        !$questionHelper->ask(
            $input,
            $output,
            new ConfirmationQuestion('<question>Do you want to proceed and import? (Y/n)</question> ')
        )
        ) {
            $output->writeln('');
            $output->writeln('<error>             </error>');
            $output->writeln('<error>  Aborting.  </error>');
            $output->writeln('<error>             </error>');
        }

        $output->writeln('');


        $map = [
            'bankNumber'                      => [0, 8],
            'attribute'                       => [8, 1],
            'name'                            => [9, 58],
            'zipCode'                         => [67, 5],
            'city'                            => [72, 35],
            'shortname'                       => [107, 27],
            'pan'                             => [134, 5],
            'bic'                             => [139, 11],
            'checkDigitPlanCalculationMethod' => [150, 2],
            'recordNumber'                    => [152, 6],
            'modificationIdentifier'          => [158, 1],
            'bankNumberDeletion'              => [159, 1],
            'successorBankNumber'             => [160, 8]
        ];

        $progressBar = new ProgressBar($output);
        $progressBar->setRedrawFrequency($redrawFrequency = 100);
        $progressBar->start($lines);
        $validationErrors = [];
        foreach ($fileContents as $line) {
            $bank = new Bank();

            foreach ($map as $fieldName => $substr) {
                $method = 'set' . ucfirst($fieldName);
                $bank->$method(trim(mb_substr($line, $substr[0], $substr[1])));
            }

            $errors = $validator->validate($bank);

            if (count($errors) > 0) {
                $validationErrors[] = (string)$errors;
            } else {
                $entityManger->persist($bank);
            }

            if ($progressBar->getProgress() % $redrawFrequency == 0) {
                $entityManger->flush();
                $entityManger->clear();
            }

            $progressBar->advance();
        }
        $progressBar->finish();

        $output->writeln('');

        $output->write('<error>');

        dump($validationErrors);

        $output->writeln('</error>');

    }
}