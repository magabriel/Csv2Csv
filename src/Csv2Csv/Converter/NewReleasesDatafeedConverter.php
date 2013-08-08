<?php

namespace Csv2Csv\Converter;

/**
 * Converter for books file to books import format
 * @author miguelangel
 *
 */
class NewReleasesDatafeedConverter extends BaseConverter
{
    protected $discount = 0;
    protected $markUp = 0;

    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    public function setMarkUp($markUp)
    {
        $this->markUp = $markUp;
    }

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
                'product_name',
                'category_path',
                'manufacturer_name',
                'product_s_desc',
                'product_desc',
                'product_price',
                'product_full_image',
                'product_thumb_image',
                'product_in_stock',
                'product_availability',
                'product_available_date',
                'product_currency',
                'product_discount',
                'product_discount_date_start',
                'product_discount_date_end',
                'product_packaging',
                'product_publish',
                'product_special',
                'product_tax',
                'product_url',
                'product_type_1_name',
                'product_type_1_field01',
                'product_type_1_field02',
                'product_type_1_field03',
                'product_type_1_field04',
                'product_type_1_field05',
                'product_type_1_field06'
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

        $subtitle = '';
        $out['product_name'] = $record['Full title'] . $subtitle;

        $out['category_path'] = $record['Subject 1(Desc.)'];
        $out['manufacturer_name'] = 'The Publisher Inc.';
        $out['product_s_desc'] = $record['Jacket or cover copy'];

        $toc = $record['Table of contents']
                ? '<br/><h4>Table of contents:</h4>' . $record['Table of contents']
                : '';
        $out['product_desc'] = $record['About the book'] . $toc;

        $out['product_price'] = $this->getNewPrice($record['Price, current list (EUR)']);
        $out['product_full_image'] = $record['ISBN'] . '.jpg';
        $out['product_thumb_image'] = 'resized/'.$record['ISBN'] . '_100x100.jpg';
        $out['product_in_stock'] = 9999;
        $out['product_availability'] = '7d.gif';

        $date = new \DateTime($record['Date, pub or announced']);
        $out['product_available_date'] = $date->format('Y-m-d');
        $out['product_currency'] = 'EUR';
        $out['product_discount'] = 0;
        $out['product_discount_date_start'] = null;
        $out['product_discount_date_end'] = null;
        $out['product_packaging'] = 0;
        $out['product_publish'] = 'Y';
        $out['product_special'] = 'N';
        $out['product_tax'] = '4.00';
        $out['product_url'] = null;
        $out['product_type_1_name'] = 'Libro';
        $out['product_type_1_field01'] = $record['Edition number'];
        $out['product_type_1_field02'] = $record['Author full name'];
        $out['product_type_1_field03'] = $record['Number of pages'];
        $out['product_type_1_field04'] = $date->format('Y');
        $out['product_type_1_field05'] = null;
        $out['product_type_1_field06'] = $record['ISBN'];

        return $out;
    }

    protected function getNewPrice($price)
    {
        return $price * (1 - $this->discount) * (1 + $this->markUp);
    }
}
