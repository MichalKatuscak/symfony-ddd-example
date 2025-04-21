<?php

declare(strict_types=1);

namespace Infrastructure\Doctrine\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230101000300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create event store table';
    }

    public function up(Schema $schema): void
    {
        // Create event_store table
        $this->addSql('CREATE TABLE event_store (
            id BIGSERIAL NOT NULL,
            aggregate_id UUID NOT NULL,
            aggregate_type VARCHAR(100) NOT NULL,
            event_type VARCHAR(100) NOT NULL,
            event_data JSON NOT NULL,
            occurred_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX idx_event_store_aggregate_id ON event_store (aggregate_id)');
        $this->addSql('CREATE INDEX idx_event_store_aggregate_type ON event_store (aggregate_type)');
        $this->addSql('CREATE INDEX idx_event_store_event_type ON event_store (event_type)');
        $this->addSql('CREATE INDEX idx_event_store_occurred_at ON event_store (occurred_at)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE event_store');
    }
}
