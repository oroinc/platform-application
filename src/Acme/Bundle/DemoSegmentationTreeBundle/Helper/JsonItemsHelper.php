<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\Helper;

/**
 * Helper for the items controller to format items data to 
 *
 * @author    Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
class JsonItemsHelper
{

    /**
     * Format simple product list 
     * @param ArrayCollection $simpleProducts
     *
     * @return array
     * @static
     */
    public static function simpleProductsResponse($simpleProducts)
    {
        $return = array();

        foreach ($simpleProducts as $simpleProduct) {
            $return[] = array(
                    'id' => $simpleProduct->getId(),
                    'name' => $simpleProduct->getName(),
                    'description' => $simpleProduct->getDescription()
            );
        }

        return $return;
    }

    /**
     * Format simple customer list 
     * @param ArrayCollection $simpleCustomers
     *
     * @return array
     * @static
     */
    public static function simpleCustomersResponse($simpleCustomers)
    {
        $return = array();

        foreach ($simpleCustomers as $simpleCustomer) {
            $return[] = array(
                    'id' => $simpleCustomer->getId(),
                    'name' => $simpleCustomer->getName(),
                    'phone' => $simpleCustomer->getPhone()
            );
        }

        return $return;
    }

}
