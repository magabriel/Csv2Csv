<?php

namespace Csv2Csv\Converter;

/**
 * Base for converters
 * @author miguelangel
 *
 */
abstract class BaseConverter
{
    protected $inputFileName;
    protected $outputFileName;
    protected $results = array();

    /**
     * The input file name
     * @param string $inputFileName
     */
    public function setInputFileName($inputFileName)
    {
        $this->inputFileName = $inputFileName;
    }

    /**
     * The output file name
     * @param string $outputFileName
     */
    public function setOutputFileName($outputFileName)
    {
        $this->outputFileName = $outputFileName;
    }

    /**
     * Retrieve the conversion results
     * @return array
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Perform the conversion
     * @return boolean Success
     */
    public function convert()
    {
        // get definitions
        $inputDef = $this->getInputFileDefinition();
        $outputDef = $this->getOutputFileDefinition();

        // init input file
        $fInput = new \SplFileObject($this->inputFileName);
        $fInput->setFlags(\SplFileObject::READ_CSV);
        $fInput->setCsvControl($inputDef['delimiter'], $inputDef['enclosure']);

        // init output file
        @unlink($this->outputFileName);
        $fOutput = new \SplFileObject($this->outputFileName, 'w');
        $fOutput->setCsvControl($outputDef['delimiter'], $outputDef['enclosure']);

        // write headers if set
        if ($outputDef['has-headers']) {
            $outRecord = $outputDef['fields'];
            $fOutput->fputcsv($outRecord);
        }

        // process input file as records
        $count = 0;
        $headers = false;
        foreach ($fInput as $record) {

            // empty record == EOF
            if (!$record[0]) {
                break;
            }

            // skip headers line
            if ($inputDef['has-headers'] && !$headers) {
                $headers = true;
                continue;
            }

            // complete missing input fields with blanks
            if (count($inputDef['fields']) > count($record)) {
                $numFields = count($record);
                $numDefFields = count($inputDef['fields']);
                for ($i = $numFields; $i < $numDefFields; $i++) {
                    array_push($record, '');
                }
            }

            // call record converter and write results
            $inRecord = array_combine($inputDef['fields'], $record);
            $inRecord = $this->convertEncoding(
                $inRecord,
                $inputDef['encoding'],
                $outputDef['encoding']
            );
            $outRecord = $this->convertRecord($inRecord);

            // ensure the output file record has the fields in the right order
            $outRecord = $this->reorderRecordFields($outputDef['fields'], $outRecord);

            $fOutput->fputcsv($outRecord);

            $count++;
        }

        $this->results['records.count'] = $count;

        return true;
    }

    /**
     * Reorder record fields to match definition
     * @param array $recordFields
     * @param array $record
     */
    protected function reorderRecordFields(array $recordFields, array $record)
    {
        $recordReordered = array();

        foreach ($recordFields as $field) {
            if (isset($record[$field])) {
                $recordReordered[$field] = $record[$field];
            } else {
                $recordReordered[$field] = '';
            }
        }

        return $recordReordered;
    }

    /**
     * Convert the record to the right encoding
     * @param array $record
     * @param string $fromEncoding
     * @param string $toEncoding
     */
    protected function convertEncoding(array $record, $fromEncoding, $toEncoding)
    {
        array_walk(
            $record,
            function (&$field) use ($fromEncoding, $toEncoding) {
                $field = mb_convert_encoding($field, $toEncoding, $fromEncoding);
            }
        );

        return $record;
    }

    /**
     * Override to provide an appropiate conversion from input to output record
     *
     * @param array $record of (fieldName => fieldValue) pairs
     * @return array with converted (fieldName => fieldValue) pairs
     */
    protected function convertRecord(array $record)
    {
        return array();
    }

    /**
     * Override to provide the right definition
     *
     * @return array
     */
    protected function getInputFileDefinition()
    {
        $definition = array();

        $definition['fields'] = array(
        // 'field_name1', 'field_nameN'
        );

        $definition['enclosure'] = '"';
        $definition['delimiter'] = ',';

        $definition['encoding'] = 'UTF-8';
        $definition['has-headers'] = true;

        return $definition;
    }

    /**
     * Override to provide the right definition
     *
     * @return array
     */
    protected function getOutputFileDefinition()
    {
        $definition = array();

        $definition['fields'] = array(
        // 'field_name1', 'field_nameN'
        );

        $definition['enclosure'] = '"';
        $definition['delimiter'] = ',';

        $definition['encoding'] = 'UTF-8';
        $definition['has-headers'] = true;

        return $definition;
    }
}
