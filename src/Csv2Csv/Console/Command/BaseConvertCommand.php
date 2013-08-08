<?php
namespace Csv2Csv\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Base for conversion commands
 */
abstract class BaseConvertCommand extends Command
{
    protected function configure()
    {
        // add common arguments and options
        $this->addArgument('inputFile', InputArgument::REQUIRED, 'The input CSV file');
        $this->addArgument('outputFile', InputArgument::REQUIRED, 'The output CSV file');
        $this->addOption('force', 'f', InputOption::VALUE_NONE, 'Force execution (don\'t ask)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // check input and output files

        $inputFile = $input->getArgument('inputFile');
        $outputFile = $input->getArgument('outputFile');

        if (!file_exists($inputFile)) {
            $output->writeln(sprintf('<error>Error: Input file "%s" not found</error>', $inputFile));
            return 1;
        }

        if (file_exists($outputFile) && !$input->getOption('force')) {
            $dialog = $this->getHelperSet()->get('dialog');
            if (!$dialog
                ->askConfirmation(
                    $output,
                    sprintf(
                        '<question>Output file "%s" already exists. Overwrite? [y/N] </question>',
                        $outputFile
                    ),
                    false
                )
                    ) {
                $output->writeln('Halted');
                return 1;
            }
        }
    }
}
