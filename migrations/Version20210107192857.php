<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210107192857 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resources_permissions (resources_id INT NOT NULL, permissions_id INT NOT NULL, PRIMARY KEY(resources_id, permissions_id))');
        $this->addSql('CREATE INDEX IDX_DFF50779ACFC5BFF ON resources_permissions (resources_id)');
        $this->addSql('CREATE INDEX IDX_DFF507799C3E4F87 ON resources_permissions (permissions_id)');
        $this->addSql('ALTER TABLE resources_permissions ADD CONSTRAINT FK_DFF50779ACFC5BFF FOREIGN KEY (resources_id) REFERENCES resources (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resources_permissions ADD CONSTRAINT FK_DFF507799C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE resources_permissions');
    }
}
