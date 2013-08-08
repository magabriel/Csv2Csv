<?php

namespace Csv2Csv\Test;

use \Csv2Csv\Converter\NewReleasesDatafeedConverter;

class NewReleasesDatafeedConverterTest extends \PHPUnit_Framework_TestCase
{
    public function testSimpleConversion()
    {
        $input = __DIR__.'/fixtures/input/books.csv';
        $output = tempnam('/tmp', 'Csv2Csv');
        $expected = __DIR__.'/fixtures/expected/books-output.csv';

        $converter = new NewReleasesDatafeedConverter();
        $converter->setInputFileName($input);
        $converter->setOutputFileName($output);
        $converter->setDiscount(.5);
        $converter->setMarkUp(.1);
        $ret = $converter->convert();

        $this->assertTrue($ret, 'Converter does not return error');

        $results = $converter->getResults();

        $this->assertEquals(1, $results['records.count'], 'Number of converted records');

        $this->assertFileEquals($expected, $output, 'Converted file');

        unlink($output);
    }
}
