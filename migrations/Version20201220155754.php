<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201220155754 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_roles (user_id INT NOT NULL, roles_id INT NOT NULL, PRIMARY KEY(user_id, roles_id))');
        $this->addSql('CREATE INDEX IDX_54FCD59FA76ED395 ON user_roles (user_id)');
        $this->addSql('CREATE INDEX IDX_54FCD59F38C751C4 ON user_roles (roles_id)');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59F38C751C4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE roles_user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE roles_user (roles_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(roles_id, user_id))');
        $this->addSql('CREATE INDEX idx_57048b30a76ed395 ON roles_user (user_id)');
        $this->addSql('CREATE INDEX idx_57048b3038c751c4 ON roles_user (roles_id)');
        $this->addSql('ALTER TABLE roles_user ADD CONSTRAINT fk_57048b3038c751c4 FOREIGN KEY (roles_id) REFERENCES roles (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE roles_user ADD CONSTRAINT fk_57048b30a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE user_roles');
    }
}
