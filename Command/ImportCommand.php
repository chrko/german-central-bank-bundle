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

        mb_internal_encoding('UTF-8');
        $encodingList = mb_detect_order();
        if (!in_array('ISO-8859-1', $encodingList)) {
            array_push($encodingList, 'ISO-8859-1');
            mb_detect_order($encodingList);
        }
        $fileEncoding = mb_detect_encoding($fileContents = file_get_contents($file));

        $output->writeln('<info>Opening file:</info> ' . $fileReal . '');
        $fileContentArray = explode("\n", $fileContents);
        $output->writeln(
            '<info>Found</info> ' . $lines = count($fileContentArray) . ' <info>lines in the file.</info>'
        );

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
        foreach ($fileContentArray as $row => $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            $bank = new Bank();

            if ($fileEncoding != mb_internal_encoding()) {
                $line = mb_convert_encoding($line, mb_internal_encoding(), $fileEncoding);
            }

            foreach ($map as $fieldName => $substr) {
                $method = 'set' . ucfirst($fieldName);
                $bank->$method(trim(mb_substr($line, $substr[0], $substr[1])));
            }

            $errors = $validator->validate($bank);

            if (count($errors) > 0) {
                $validationErrors[] = (string)$errors;
                $validationErrors[] = $row . $line;
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
        $entityManger->flush();
        $entityManger->clear();

        $output->writeln('');

        $output->write('<error>');

        dump($validationErrors);

        $output->writeln('</error>');

    }
}