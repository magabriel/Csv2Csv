<?php

namespace Csv2Csv\Test;

use \Csv2Csv\Converter\NewReleasesCoversConverter;

class NewReleasesCoversConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleConversion()
    {
        $input = __DIR__.'/fixtures/input/books.csv';
        $output = tempnam('/tmp', 'Csv2Csv');
        $expected = __DIR__.'/fixtures/expected/books-output-covers.csv';

        $converter = new NewReleasesCoversConverter();
        $converter->setInputFileName($input);
        $converter->setOutputFileName($output);
        $ret = $converter->convert();

        $this->assertTrue($ret, 'Converter does not return error');

        $results = $converter->getResults();

        $this->assertEquals(1, $results['records.count'], 'Number of converted records');

        $this->assertFileEquals($expected, $output, 'Converted file');

        unlink($output);
    }
}
