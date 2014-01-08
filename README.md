Csv2Csv
=======

Converter between CSV formats created as a proof-of-concept implementation of the [symfony-cli-skeleton](http://github.com/magabriel/symfony-cli-skeleton).

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/36ddadfb-07a7-48a4-9d09-bf2991a03c3d/mini.png)](https://insight.sensiolabs.com/projects/36ddadfb-07a7-48a4-9d09-bf2991a03c3d)

License
-------

Copyright (c) 2013 Miguel Angel Gabriel (magabriel@gmail.com)

This extension is licensed under the MIT license. See `LICENSE.md`.

Description
-----------

Everything is explained in this [blog post](http://dev4theweb.blogspot.com.es/2013/07/a-csv-to-csv-converter-using-symfony.html).

Just as a quick recap, this provides an easily customizable. maintainable and testable solution to a common problem: converting between different CSV file formats. Just read the blog post, clone the project and start customizing the conversion. 

The example
-----------

Let's imagine that we receive a CSV file with new book releases and are tasked with converting it to two separate files:

- A file with data for each ebook in the input file, but with a different set of fields and some conversions.
- A file with only cover images data.

The assumption is that each output format corresponds to the format needed to import the books data into a given ecommerce platform.  

Testing
-------

Unit tests are included and are very easy to adapt. 

To run them: `phpunit -c app/` from the project root.
 
Sidenote
--------

This project was never intended to be released. Instead, it was created just to provide a walkthrough of a real case implementation of the [symfony-cli-skeleton](http://github.com/magabriel/symfony-cli-skeleton). But, after some interaction with a very persistent guy (who talked me into helping him to make it work, which in turn led to finding a bug in the code), I reconsidered my position and decided to upload it to GitHub. I really hope someone find it useful.


