<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Objects manage
 *
 * @author Jakub Westfalewski <jwest@jwest.pl>
 */
class LanboCMS_Objects {

    /**
     * Factory, get new object instance
     * @return LanboCMS_Objects
     */
    public static function factory()
    {
        return new self();
    }

    /**
     * Get object list
     * @todo get from config
     * @return array
     */
    public function objects()
    {
        return array
        (
            'pages' => NULL,
        );
    }

} // End LanboCMS_Objects
