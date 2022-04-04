<?php

namespace percipiolondon\timeloop\models;

use Craft;
use craft\base\Model;

/**
 * @author    percipiolondon
 * @package   Timeloop
 * @since     1.0.0
 */

class TimeStringModel extends Model
{

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $ordinal;

    /**
     * @var string
     */
    public $day;

    // Public Methods
    // =========================================================================

    public function rules(): array
    {
        return [
            ['ordinal', 'string'],
            ['day', 'array'],
        ];
    }

}
