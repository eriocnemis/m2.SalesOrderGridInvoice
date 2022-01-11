<?php
/**
 * Copyright © Eriocnemis, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Eriocnemis\SalesOrderGridInvoice\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchVersionInterface;
use Eriocnemis\SalesOrderGridInvoice\Ui\Component\Listing\Column\InvoiceIds;

/**
 * Upgrade DB patch
 */
class UpdateOrderGrid implements DataPatchInterface, PatchVersionInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var InvoiceIds
     */
    private $columnInvoiceIds;

    /**
     * Initialize patch
     *
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param InvoiceIds $columnInvoiceIds
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        InvoiceIds $columnInvoiceIds
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->columnInvoiceIds = $columnInvoiceIds;
    }

    /**
     * Run code inside patch
     *
     * @return $this
     */
    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $setup->startSetup();
        $connection = $setup->getConnection();

        $columns = [
            'entity_id' => 'entity_id',
            'invoice_ids' => (string)$this->columnInvoiceIds
        ];

        $select = $connection->select()
            ->from(
                ['sales_order' => $setup->getTable('sales_order')],
                $columns
            );

        $connection->insertOnDuplicate(
            $setup->getTable('sales_order_grid'),
            $connection->fetchAll($select),
            array_keys($columns)
        );

        $setup->endSetup();
        return $this;
    }

    /**
     * Retrieve array of patches that have to be executed prior to this
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Retrieve version associate patch with Magento setup version
     *
     * @return string
     */
    public static function getVersion()
    {
        return '2.0.0';
    }

    /**
     * Retrieve aliases for the patch
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }
}
