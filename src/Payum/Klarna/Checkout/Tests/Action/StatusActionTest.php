<?php
namespace Payum\Klarna\Checkout\Tests\Action;

use Payum\Core\Request\GetBinaryStatus;
use Payum\Core\Tests\GenericActionTest;
use Payum\Klarna\Checkout\Action\StatusAction;
use Payum\Klarna\Checkout\Constants;

class StatusActionTest extends GenericActionTest
{
    protected $actionClass = 'Payum\Klarna\Checkout\Action\StatusAction';

    protected $requestClass = 'Payum\Core\Request\GetHumanStatus';

    /**
     * @test
     */
    public function shouldMarkUnknownIfStatusNotSupported()
    {
        $action = new StatusAction();

        $status = new GetBinaryStatus(array(
            'status' => 'not-supported-status',
        ));

        //guard
        $status->markNew();

        $action->execute($status);

        $this->assertTrue($status->isUnknown());
    }

    /**
     * @test
     */
    public function shouldMarkNewIfDetailsEmpty()
    {
        $action = new StatusAction();

        $status = new GetBinaryStatus(array());

        //guard
        $status->markUnknown();

        $action->execute($status);

        $this->assertTrue($status->isNew());
    }

    /**
     * @test
     */
    public function shouldMarkNewIfOrderStatusNotSet()
    {
        $action = new StatusAction();

        $status = new GetBinaryStatus(array());

        //guard
        $status->markUnknown();

        $action->execute($status);

        $this->assertTrue($status->isNew());
    }

    /**
     * @test
     */
    public function shouldMarkNewIfStatusCheckoutIncomplete()
    {
        $action = new StatusAction();

        $status = new GetBinaryStatus(array(
            'status' => Constants::STATUS_CHECKOUT_INCOMPLETE,
        ));

        //guard
        $status->markUnknown();

        $action->execute($status);

        $this->assertTrue($status->isNew());
    }

    /**
     * @test
     */
    public function shouldMarkPendingIfStatusCheckoutComplete()
    {
        $action = new StatusAction();

        $status = new GetBinaryStatus(array(
            'status' => Constants::STATUS_CHECKOUT_COMPLETE,
        ));

        //guard
        $status->markUnknown();

        $action->execute($status);

        $this->assertTrue($status->isPending());
    }

    /**
     * @test
     */
    public function shouldMarkAuthorizedIfStatusCreated()
    {
        $action = new StatusAction();

        $status = new GetBinaryStatus(array(
            'status' => Constants::STATUS_CREATED,
        ));

        //guard
        $status->markUnknown();

        $action->execute($status);

        $this->assertTrue($status->isAuthorized());
    }

    /**
     * @test
     */
    public function shouldMarkCapturedIfInvoiceNumberSet()
    {
        $action = new StatusAction();

        $status = new GetBinaryStatus(array(
            'status' => Constants::STATUS_CREATED,
            'invoice_number' => 'aNum'
        ));

        //guard
        $status->markUnknown();

        $action->execute($status);

        $this->assertTrue($status->isCaptured());
    }
}