<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @category   Phoenix
 * @package    Phoenix_Creditpass
 * @copyright  Copyright (c) 2009 Phoenix Medien GmbH & Co. KG (http://www.phoenix-medien.de)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Phoenix_Creditpass_Helper_Payment extends Mage_Payment_Helper_Data
{
	/**
	 * Retrieve available payment methods for store, taking conigured customer group payment methods into account.
	 *
	 * @param   mixed $store
	 * @param   boolean $quote
	 * @return  array
	 * @see     Mage_Payment_Helper_Data::getStoreMethods()
	 */
	public function getStoreMethods($store=null, $quote=null)
	{
		$methods = parent::getStoreMethods($store, $quote);

		$helper = Mage::helper('creditpass');
		if ($helper->moduleActive() && !$helper->inAdmin() && $helper->filterActive())
		{
			Mage::log('creditPass: Payment filtering active');

			/**
			 * Only allow enabled methods
			 */
			$res = array();
			$allowed_methods = $helper->getAllowedPaymentMethods();
			if (is_array($allowed_methods) && $allowed_methods) {
				foreach ($methods as $method) {
					if (in_array($method->getCode(), $allowed_methods))
						$res[] = $method;
				}
			}
			$methods = $res;
		} else {
			Mage::log('creditPass: Payment filtering NOT active. moduleActive:'.var_export($helper->moduleActive(),true).';inAdmin:'.var_export($helper->inAdmin(),true).';filterActive:'.var_export($helper->filterActive(),true));
		}

		// @TODO: add error message if no payment methods are available

		return $methods;
	}
}
?>