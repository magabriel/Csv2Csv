<?php
namespace Csv2Csv\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Convert new books datafeed to my book shop import format for book covers.
 *
 * This is just an example implementation - Use it as an example to make your own.
 */
class ConvertCoversCommand extends BaseConvertCommand
{
    protected function configure()
    {
        parent::configure();

        $this->setName('convert:covers')
             ->setDescription('Convert new books datafeed to books covers import format')
              ->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $output->writeln('Convert the new books CSV file to books covers import format');
        $output->writeln('============================================================');
        $output->writeln('');

        $converter = $this->getApplication()->getContainer()->get('covers.converter');

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
