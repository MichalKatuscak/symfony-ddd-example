<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230101000100 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Billing domain tables';
    }

    public function up(Schema $schema): void
    {
        // Create billing_invoices table
        $this->addSql('CREATE TABLE billing_invoices (
            id UUID NOT NULL,
            order_id UUID NOT NULL,
            invoice_number VARCHAR(50) NOT NULL,
            status VARCHAR(20) NOT NULL,
            customer_email VARCHAR(255) NOT NULL,
            customer_name VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            issued_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            paid_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX idx_billing_invoices_invoice_number ON billing_invoices (invoice_number)');
        $this->addSql('CREATE INDEX idx_billing_invoices_order_id ON billing_invoices (order_id)');
        $this->addSql('CREATE INDEX idx_billing_invoices_status ON billing_invoices (status)');

        // Create billing_invoice_items table
        $this->addSql('CREATE TABLE billing_invoice_items (
            id UUID NOT NULL,
            invoice_id UUID NOT NULL,
            description VARCHAR(255) NOT NULL,
            unit_price JSON NOT NULL,
            quantity INT NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX idx_billing_invoice_items_invoice_id ON billing_invoice_items (invoice_id)');
        $this->addSql('ALTER TABLE billing_invoice_items ADD CONSTRAINT fk_billing_invoice_items_invoice_id FOREIGN KEY (invoice_id) REFERENCES billing_invoices (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE billing_invoice_items DROP CONSTRAINT fk_billing_invoice_items_invoice_id');
        $this->addSql('DROP TABLE billing_invoice_items');
        $this->addSql('DROP TABLE billing_invoices');
    }
}
