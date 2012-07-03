<?php
/**
 * File containing the Content Search handler class
 *
 * @copyright Copyright (C) 1999-2012 eZ Systems AS. All rights reserved.
 * @license http://www.gnu.org/licenses/gpl-2.0.txt GNU General Public License v2
 * @version //autogentag//
 */

namespace eZ\Publish\Core\Persistence\Solr\Content\Search\FieldValueMapper;

use eZ\Publish\Core\Persistence\Solr\Content\Search\FieldValueMapper,
    eZ\Publish\SPI\Persistence\Content\Search\DocumentField;

/**
 * Maps raw document field values to something Solr can index.
 */
class DateMapper extends FieldValueMapper
{
    /**
     * Check if field can be mapped
     *
     * @param DocumentField $field
     * @return void
     */
    public function canMap( DocumentField $field )
    {
        return $field instanceof DocumentField\DateField;
    }

    /**
     * Map field value to a proper Solr representation
     *
     * @param DocumentField $field
     * @return void
     */
    public function map( DocumentField $field )
    {
        if ( is_numeric( $field->value ) )
        {
            $date = new \DateTime( "@{$field->value}" );
        }
        else
        {
            try
            {
                $date = new \DateTime( $field->value );
            }
            catch ( Exception $e )
            {
                throw new \InvalidArgumentException( "Invalid date provided: " . $field->value );
            }
        }

        return $date->format( "Y-m-d\\TH:i:s\\Z" );
    }
}
