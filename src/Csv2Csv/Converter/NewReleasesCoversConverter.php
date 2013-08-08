<?php

namespace Csv2Csv\Converter;

/**
 * Converter for books file to book cover import format
 * @author miguelangel
 *
 */
class NewReleasesCoversConverter extends BaseConverter
{
    protected function getInputFileDefinition()
    {
        $definition = array();

        $definition['fields'] = array(
                'ISBN',
                'Number of pages',
                'Price, current list (EUR)',
                'Full title',
                'Subtitle',
                'Edition number',
                'Series number/name(Desc.)',
                'EAN',
                'Book length (cm)',
                'Book thickness (cm)',
                'Book weight (oz) (kg.)',
                'Book width (cm)',
                'Subject 1',
                'Subject 1(Desc.)',
                'Author full name',
                'Author full name (#2)',
                'Author full name (#3)',
                'Date, pub or announced',
                'About the book',
                'Table of contents',
                'Jacket or cover copy'
        );

        $definition['enclosure'] = '"';
        $definition['delimiter'] = ',';

        $definition['encoding'] = 'ISO-8859-1';
        $definition['has-headers'] = true;

        return $definition;
    }

    protected function getOutputFileDefinition()
    {
        $definition = array();

        $definition['fields'] = array(
                'product_sku',
                'product_files_file_name'
        );

        $definition['enclosure'] = '~';
        $definition['delimiter'] = '^';

        $definition['encoding'] = 'UTF-8';
        $definition['has-headers'] = true;

        return $definition;
    }

    protected function convertRecord(array $record)
    {
        $out = array();

        $out['product_sku'] = $record['ISBN'];
        $out['product_files_file_name'] = $record['ISBN'] . '.jpg';

        return $out;
    }
}
