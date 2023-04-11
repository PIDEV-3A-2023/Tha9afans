<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230410134109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE billet (id INT AUTO_INCREMENT NOT NULL, id_evenement INT DEFAULT NULL, code VARCHAR(150) NOT NULL, date_validite DATE NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_1F034AF68B13D439 (id_evenement), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_evenement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_produit INT DEFAULT NULL, datecommande DATETIME NOT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_6EEAA67D6B3CA4B (id_user), INDEX IDX_6EEAA67DF7384557 (id_produit), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandeproduit (id INT AUTO_INCREMENT NOT NULL, id_commende INT DEFAULT NULL, quantite INT NOT NULL, id_produit INT NOT NULL, INDEX IDX_D2C2F071B153DDBF (id_commende), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, id_event_id INT DEFAULT NULL, commentaire VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_67F068BC79F37AE5 (id_user_id), INDEX IDX_67F068BC212C041E (id_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, createur_id INT DEFAULT NULL, categorie_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(10000) NOT NULL, date DATE NOT NULL, localisation VARCHAR(255) NOT NULL, nb_participants INT NOT NULL, nb_aime INT NOT NULL, prix INT NOT NULL, INDEX IDX_B26681E73A201E5 (createur_id), INDEX IDX_B26681EBCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, id_commende INT DEFAULT NULL, datefacture DATETIME NOT NULL, tva DOUBLE PRECISION NOT NULL, refrancefacture VARCHAR(255) NOT NULL, INDEX IDX_FE866410B153DDBF (id_commende), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE galerie (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, photo LONGBLOB NOT NULL, INDEX IDX_9E7D159071F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE jaime (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_3CB7780571F7E88B (event_id), INDEX IDX_3CB77805A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, total DOUBLE PRECISION NOT NULL, INDEX IDX_24CC0DF2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panierproduit (id INT AUTO_INCREMENT NOT NULL, id_panier INT DEFAULT NULL, id_produit INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_656FE9BA2FBB81F (id_panier), INDEX IDX_656FE9BAF7384557 (id_produit), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE personnes (id INT AUTO_INCREMENT NOT NULL, cin VARCHAR(30) NOT NULL, nom VARCHAR(30) NOT NULL, prenom VARCHAR(30) NOT NULL, email VARCHAR(40) NOT NULL, password VARCHAR(30) NOT NULL, role VARCHAR(30) DEFAULT NULL, telephone VARCHAR(30) NOT NULL, adresse VARCHAR(30) NOT NULL, photo LONGBLOB DEFAULT NULL, datenaissance DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, vendeur_id INT NOT NULL, categorie_id INT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, libelle INT NOT NULL, prix DOUBLE PRECISION NOT NULL, image LONGBLOB DEFAULT NULL, remise DOUBLE PRECISION NOT NULL, rating DOUBLE PRECISION NOT NULL, prixapresremise DOUBLE PRECISION NOT NULL, qt INT NOT NULL, INDEX IDX_29A5EC27858C065E (vendeur_id), INDEX IDX_29A5EC27BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (question_id INT AUTO_INCREMENT NOT NULL, question VARCHAR(200) NOT NULL, answer VARCHAR(200) NOT NULL, timer INT NOT NULL, first_possible_answer VARCHAR(50) NOT NULL, second_possible_answer VARCHAR(50) NOT NULL, third_possible_answer VARCHAR(50) NOT NULL, image LONGBLOB DEFAULT NULL, PRIMARY KEY(question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz (quiz_id INT AUTO_INCREMENT NOT NULL, quiz_name VARCHAR(50) NOT NULL, nbr_questions INT NOT NULL, quiz_cover LONGBLOB DEFAULT NULL, quiz_description VARCHAR(200) NOT NULL, PRIMARY KEY(quiz_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quiz_question (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, question_id INT DEFAULT NULL, INDEX IDX_6033B00B853CD175 (quiz_id), INDEX IDX_6033B00B1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, id_billet INT DEFAULT NULL, id_user INT DEFAULT NULL, date_reservation DATE NOT NULL, isPaid TINYINT(1) NOT NULL, payment_info VARCHAR(255) NOT NULL, INDEX IDX_42C849553934FF1B (id_billet), INDEX IDX_42C849556B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE score (id INT AUTO_INCREMENT NOT NULL, quiz_id INT DEFAULT NULL, id_user INT DEFAULT NULL, score INT NOT NULL, times_played INT NOT NULL, INDEX IDX_32993751853CD175 (quiz_id), INDEX IDX_329937516B3CA4B (id_user), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, parlant VARCHAR(255) NOT NULL, debit TIME NOT NULL, fin TIME NOT NULL, INDEX IDX_D044D5D4FD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, cin VARCHAR(30) DEFAULT NULL, nom VARCHAR(30) DEFAULT NULL, prenom VARCHAR(30) DEFAULT NULL, telephone VARCHAR(30) DEFAULT NULL, adresse VARCHAR(30) DEFAULT NULL, photo LONGBLOB DEFAULT NULL, datenaissance DATE DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT FK_1F034AF68B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE commandeproduit ADD CONSTRAINT FK_D2C2F071B153DDBF FOREIGN KEY (id_commende) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC79F37AE5 FOREIGN KEY (id_user_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC212C041E FOREIGN KEY (id_event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E73A201E5 FOREIGN KEY (createur_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_evenement (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410B153DDBF FOREIGN KEY (id_commende) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE galerie ADD CONSTRAINT FK_9E7D159071F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB7780571F7E88B FOREIGN KEY (event_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE jaime ADD CONSTRAINT FK_3CB77805A76ED395 FOREIGN KEY (user_id) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE panierproduit ADD CONSTRAINT FK_656FE9BA2FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE panierproduit ADD CONSTRAINT FK_656FE9BAF7384557 FOREIGN KEY (id_produit) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27858C065E FOREIGN KEY (vendeur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (quiz_id)');
        $this->addSql('ALTER TABLE quiz_question ADD CONSTRAINT FK_6033B00B1E27F6BF FOREIGN KEY (question_id) REFERENCES question (question_id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849553934FF1B FOREIGN KEY (id_billet) REFERENCES billet (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556B3CA4B FOREIGN KEY (id_user) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_32993751853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (quiz_id)');
        $this->addSql('ALTER TABLE score ADD CONSTRAINT FK_329937516B3CA4B FOREIGN KEY (id_user) REFERENCES personnes (id)');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY FK_1F034AF68B13D439');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D6B3CA4B');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DF7384557');
        $this->addSql('ALTER TABLE commandeproduit DROP FOREIGN KEY FK_D2C2F071B153DDBF');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC79F37AE5');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC212C041E');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E73A201E5');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EBCF5E72D');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410B153DDBF');
        $this->addSql('ALTER TABLE galerie DROP FOREIGN KEY FK_9E7D159071F7E88B');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB7780571F7E88B');
        $this->addSql('ALTER TABLE jaime DROP FOREIGN KEY FK_3CB77805A76ED395');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2A76ED395');
        $this->addSql('ALTER TABLE panierproduit DROP FOREIGN KEY FK_656FE9BA2FBB81F');
        $this->addSql('ALTER TABLE panierproduit DROP FOREIGN KEY FK_656FE9BAF7384557');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27858C065E');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B853CD175');
        $this->addSql('ALTER TABLE quiz_question DROP FOREIGN KEY FK_6033B00B1E27F6BF');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849553934FF1B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556B3CA4B');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_32993751853CD175');
        $this->addSql('ALTER TABLE score DROP FOREIGN KEY FK_329937516B3CA4B');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4FD02F13');
        $this->addSql('DROP TABLE billet');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE categorie_evenement');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commandeproduit');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE galerie');
        $this->addSql('DROP TABLE jaime');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE panierproduit');
        $this->addSql('DROP TABLE personnes');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE quiz');
        $this->addSql('DROP TABLE quiz_question');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE score');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
