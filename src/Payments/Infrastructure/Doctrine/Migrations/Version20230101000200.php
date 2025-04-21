<?php

declare(strict_types=1);

namespace Payments\Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230101000200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create Payments domain tables';
    }

    public function up(Schema $schema): void
    {
        // Create payments_payments table
        $this->addSql('CREATE TABLE payments_payments (
            id UUID NOT NULL,
            invoice_id UUID NOT NULL,
            transaction_id VARCHAR(100) NOT NULL,
            amount JSON NOT NULL,
            status VARCHAR(20) NOT NULL,
            method VARCHAR(50) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            completed_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX idx_payments_payments_transaction_id ON payments_payments (transaction_id)');
        $this->addSql('CREATE INDEX idx_payments_payments_invoice_id ON payments_payments (invoice_id)');
        $this->addSql('CREATE INDEX idx_payments_payments_status ON payments_payments (status)');

        // Create messenger_messages table for async events
        $this->addSql('CREATE TABLE messenger_messages (
            id BIGSERIAL NOT NULL,
            body TEXT NOT NULL,
            headers TEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX idx_messenger_messages_queue_name ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX idx_messenger_messages_available_at ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX idx_messenger_messages_delivered_at ON messenger_messages (delivered_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE payments_payments');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
