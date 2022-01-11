<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesOrderGridInvoice\Ui\Component\Listing\Column;

use Magento\Framework\App\ResourceConnection;

/**
 * Retrieve invoice ids column sub select
 */
class InvoiceIds
{
    /**
     * Invoice table name
     */
    private const TABLE_NAME = 'sales_invoice';

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * Initialize resource
     *
     * @param ResourceConnection $resource
     */
    public function __construct(
        ResourceConnection $resource
    ) {
        $this->resource = $resource;
    }

    /**
     * Retrieve sub select string
     *
     * @return string
     */
    public function __toString()
    {
        return '(' . $this->getSelect() . ')';
    }

    /**
     * Retrieve invoices column sub select
     *
     * @return string
     */
    private function getSelect()
    {
        return (string)$this->resource->getConnection()->select()->from(
            [$this->resource->getTableName(self::TABLE_NAME)],
            ['GROUP_CONCAT(increment_id)']
        )->where('order_id = sales_order.entity_id');
    }
}
