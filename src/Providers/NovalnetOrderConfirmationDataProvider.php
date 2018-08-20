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
 * Script : NovalnetOrderConfirmationDataProvider.php
 *
 */

namespace Novalnetpayment\Providers;

use Plenty\Plugin\Templates\Twig;

use Novalnetpayment\Helper\PaymentHelper;
use Plenty\Modules\Comment\Contracts\CommentRepositoryContract;
use \Plenty\Modules\Authorization\Services\AuthHelper;
use Plenty\Modules\Order\Models\Order;
use Plenty\Modules\Payment\Models\Payment;
use Plenty\Modules\Payment\Contracts\PaymentRepositoryContract;

/**
 * Class NovalnetOrderConfirmationDataProvider
 *
 * @package Novalnetpayment\Providers
 */
class NovalnetOrderConfirmationDataProvider
{
    /**
     * Setup the Novalnet transaction comments for the requested order
     *
     * @param Twig $twig
     * @param PaymentRepositoryContract $PaymentRepositoryContract
     * @param Arguments $arg
     * @return string
     */
    public function call(Twig $twig,PaymentRepositoryContract $paymentRepositoryContract, $args)
    {
        $paymentHelper = pluginApp(PaymentHelper::class);
        $paymentMethodId = $paymentHelper->getPaymentMethod();
        $order = $args[0];
        $payments		=	$paymentRepositoryContract->getPaymentsByOrderId($order['id']);
       
        foreach($payments as $payment)
        {             
       
            if( $paymentMethodId == $payment->mopId)
            {
                $orderId = (int) $payment->order['orderId'];

                $authHelper = pluginApp(AuthHelper::class);
                $orderComments = $authHelper->processUnguarded(
                        function () use ($orderId) {
                            $commentsObj = pluginApp(CommentRepositoryContract::class);
                            $commentsObj->setFilters(['referenceType' => 'order', 'referenceValue' => $orderId]);
                            return $commentsObj->listComments();
                        }
                );
               
                $comment = '';
                foreach($orderComments as $data)
                {
                    $comment .= (string)$data->text;
                    $comment .= '</br>';
                }

                return $twig->render('Novalnetpayment::NovalnetOrderHistory', ['comments' => html_entity_decode($comment)]);
            }
        }
    }
}
