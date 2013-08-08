<?php
namespace Csv2Csv\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Convert new books datafeed to my book shop import format.
 *
 * This is just an example implementation - Use it as an example to make your own.
 */
class ConvertBooksCommand extends BaseConvertCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('convert:books')
             ->setDescription('Convert new books datafeed to books import format')
             ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $output->writeln('Convert the new books CSV file to books import format');
        $output->writeln('=====================================================');
        $output->writeln('');

        $converter = $this->getApplication()->getContainer()->get('books.converter');

        $converter->setInputFileName($input->getArgument('inputFile'));
        $converter->setOutputFileName($input->getArgument('outputFile'));
        $ret = $converter->convert();

        if (!$ret) {
            $output->writeln('Finished with errors.');
            return 1;
        }

        $results = $converter->getResults();
        $output->writeln(
            sprintf(
                'Finshed. %s records written (not counting headers)',
                $results['records.count']
            )
        );
    }
}
