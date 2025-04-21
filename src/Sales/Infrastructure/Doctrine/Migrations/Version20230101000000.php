<?php

declare(strict_types=1);

namespace Sales\Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230101000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Sales domain tables';
    }

    public function up(Schema $schema): void
    {
        // Create sales_orders table
        $this->addSql('CREATE TABLE sales_orders (
            id UUID NOT NULL,
            status VARCHAR(20) NOT NULL,
            customer_email VARCHAR(255) NOT NULL,
            customer_name VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            placed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX idx_sales_orders_status ON sales_orders (status)');

        // Create sales_order_items table
        $this->addSql('CREATE TABLE sales_order_items (
            id UUID NOT NULL,
            order_id UUID NOT NULL,
            product_name VARCHAR(255) NOT NULL,
            unit_price JSON NOT NULL,
            quantity INT NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX idx_sales_order_items_order_id ON sales_order_items (order_id)');
        $this->addSql('ALTER TABLE sales_order_items ADD CONSTRAINT fk_sales_order_items_order_id FOREIGN KEY (order_id) REFERENCES sales_orders (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sales_order_items DROP CONSTRAINT fk_sales_order_items_order_id');
        $this->addSql('DROP TABLE sales_order_items');
        $this->addSql('DROP TABLE sales_orders');
    }
}
