<?php
namespace Acme\Bundle\DemoDataFlowBundle\Transform\Mapping;

use Oro\Bundle\DataFlowBundle\Transform\Mapping\ItemMapping;

/**
 * Customer mapping from Magento to Oro
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CustomerMapping extends ItemMapping
{

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->add('website', 'website');
        $this->add('password_hash', 'company', true);
    }
}
