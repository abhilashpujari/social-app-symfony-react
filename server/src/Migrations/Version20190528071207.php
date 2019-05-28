<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190528071207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, user_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, INDEX IDX_9474526C727ACA70 (parent_id), INDEX IDX_9474526CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8D6E9059DF');
        $this->addSql('DROP INDEX IDX_5A8A6C8D6E9059DF ON post');
        $this->addSql('ALTER TABLE post CHANGE id_user user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DA76ED395 ON post (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C727ACA70');
        $this->addSql('DROP TABLE comment');
        $this->addSql('ALTER TABLE post DROP FOREIGN KEY FK_5A8A6C8DA76ED395');
        $this->addSql('DROP INDEX IDX_5A8A6C8DA76ED395 ON post');
        $this->addSql('ALTER TABLE post CHANGE user_id ID_User INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8D6E9059DF FOREIGN KEY (ID_User) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_5A8A6C8D6E9059DF ON post (ID_User)');
    }
}
