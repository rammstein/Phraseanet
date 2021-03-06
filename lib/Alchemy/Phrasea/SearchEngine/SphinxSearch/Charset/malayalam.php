<?php

/*
 * SphinxSearch Unicode maps
 * Courtesy of http://speeple.com/unicode-maps.txt
 */

namespace Alchemy\Phrasea\SearchEngine\SphinxSearch\Charset;

use Alchemy\Phrasea\SearchEngine\SphinxSearch\AbstractCharset;

class malayalam extends AbstractCharset
{
    protected $name = 'Malayalam';
    protected $table = '
    #################################################
    # Malayalam
    U+0D05..U+0D0C, U+0D0E..U+0D10, U+0D12..U+0D28, U+0D2A..U+0D39, U+0D60,
    U+0D61, U+0D66..U+0D6F
';

}
