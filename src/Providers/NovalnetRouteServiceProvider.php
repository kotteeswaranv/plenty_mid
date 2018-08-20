<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License
 *
 * @author Novalnet <technic@novalnet.de>
 * @copyright Novalnet
 * @license GNU General Public License
 *
 * Script : NovalnetRouteServiceProvider.php
 *
 */

namespace Novalnetpayment\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

/**
 * Class NovalnetRouteServiceProvider
 *
 * @package Novalnetpayment\Providers
 */
class NovalnetRouteServiceProvider extends RouteServiceProvider
{
    /**
     * Set route for success, failure and callback process
     *
     * @param Router $router
     */
    public function map(Router $router)
    {
        // Get the Novalnet success, cancellation and callback URLs
        $router->post('payment/novalnet/callback', 'Novalnetpayment\Controllers\CallbackController@processCallback');
        $router->post('payment/novalnet/paymentResponse' , 'Novalnetpayment\Controllers\PaymentController@paymentResponse' );
    }
}
