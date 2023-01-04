<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Blog;
use App\Entity\Comment;

class DataFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fake = \Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 10; $i++) {
            $blog = new Blog();
            $image = "";
            $imgNum = rand(1, 3);
            switch ($imgNum) {
                case 1:
                    $image = "https://media.gettyimages.com/id/112047194/fr/photo/a-stack-of-stones.jpg?s=612x612&w=0&k=20&c=gRUZq6XFCJ6JHvHJE4dmQPULDGk_ymb5Zg7Ur-p3eVo=";
                    break;
                case 2:
                    $image = "https://media.gettyimages.com/id/907763240/fr/photo/deer-on-snow-covered-landscape-against-mountain.jpg?s=612x612&w=0&k=20&c=xpRWDAMAovwNl1Db3fANfDntm5Gz_SV25wWaWb7yokw=";
                    break;
                case 3:
                    $image = "https://media.gettyimages.com/id/110873140/fr/vectoriel/funky-baladeur.jpg?s=612x612&w=0&k=20&c=tCSO2AJi3LcK4bQeN859JNwgbvMJtV90kbYdWkQRgDQ=";
                    break;
                default:
                    $image = "https://media.gettyimages.com/id/185759581/fr/vectoriel/foot-and-mouth-disease-virus.jpg?s=612x612&w=0&k=20&c=Vw0fPKzuXG1ZZLo8FPTzo1zPzClciWh0MDxS6HYBKxM=";
            }
            $text = "";
            $txtNum = rand(1, 4);
            switch ($txtNum) {
                case 1:
                    $text = "Le Lorem Ipsum est simplement du faux texte employé dans la composition et la mise en page avant impression. Le Lorem Ipsum est le faux texte standard de l'imprimerie depuis les années 1500, quand un imprimeur anonyme assembla ensemble des morceaux de texte pour réaliser un livre spécimen de polices de texte.";
                    break;
                case 2:
                    $text = "Contrairement à une opinion répandue, le Lorem Ipsum n'est pas simplement du texte aléatoire. Il trouve ses racines dans une oeuvre de la littérature latine classique datant de 45 av. J.-C., le rendant vieux de 2000 ans. Un professeur du Hampden-Sydney College, en Virginie, s'est intéressé à un des mots latins les plus obscurs, consectetur, extrait d'un passage du Lorem Ipsum, et en étudiant tous les usages de ce mot dans la littérature classique, découvrit la source incontestable du Lorem Ipsum.";
                    break;
                case 3:
                    $text = "Plusieurs variations de Lorem Ipsum peuvent être trouvées ici ou là, mais la majeure partie d'entre elles a été altérée par l'addition d'humour ou de mots aléatoires qui ne ressemblent pas une seconde à du texte standard. Si vous voulez utiliser un passage du Lorem Ipsum, vous devez être sûr qu'il n'y a rien d'embarrassant caché dans le texte.";
                    break;
                case 4:
                    $text = "Tous les générateurs de Lorem Ipsum sur Internet tendent à reproduire le même extrait sans fin, ce qui fait de lipsum.com le seul vrai générateur de Lorem Ipsum. Iil utilise un dictionnaire de plus de 200 mots latins, en combinaison de plusieurs structures de phrases, pour générer un Lorem Ipsum irréprochable. Le Lorem Ipsum ainsi obtenu ne contient aucune répétition, ni ne contient des mots farfelus, ou des touches d'humour.";
                    break;
                default:
                    $text = "De nombreuses suites logicielles de mise en page ou éditeurs de sites Web ont fait du Lorem Ipsum leur faux texte par défaut, et une recherche pour 'Lorem Ipsum' vous conduira vers de nombreux sites qui n'en sont encore qu'à leur phase de construction. Plusieurs versions sont apparues avec le temps, parfois par accident, souvent intentionnellement (histoire d'y rajouter de petits clins d'oeil, voire des phrases embarassantes).";
            }
            $blog->setTitre("Article numéro {$i}")
                ->setContenu($text)
                // ->setContenu($fake->paragraph())
                ->setImage($image)
                ->setDateCreation($fake->dateTime);

            $manager->persist($blog);

            for ($k = 0; $k < rand(1, 6); $k++) {

                $user = new User();
                $user->setUsername($fake->name)
                    ->setEmail($fake->email)
                    ->setPassword($fake->password);

                $manager->persist($user);

                for ($j = 0; $j < rand(3, 5); $j++) {
                    $comment = new Comment();

                    $txtCment = "";
                    $comNum = rand(1, 4);
                    switch ($comNum) {
                        case 1:
                            $txtCment = "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.";
                            break;
                        case 2:
                            $txtCment = "Nor again is there anyone who loves or pursues or desires to obtain pain of itself, because it is pain, but because occasionally circumstances occur in which toil and pain can procure him some great pleasure.";
                            break;
                        case 3:
                            $txtCment = "Et harum quidem rerum facilis est et expedita distinctio.";
                            break;
                        case 4:
                            $txtCment = "Tous les générateurs de Lorem Ipsum sur Internet tendent à reproduire le même extrait sans fin, ce qui fait de lipsum.com le seul vrai générateur de Lorem Ipsum. Iil utilise un dictionnaire de plus de 200 mots latins, en combinaison de plusieurs structures de phrases, pour générer un Lorem Ipsum irréprochable. Le Lorem Ipsum ainsi obtenu ne contient aucune répétition, ni ne contient des mots farfelus, ou des touches d'humour.";
                            break;
                        default:
                            $txtCment = "The wise man therefore always holds in these matters to this principle of selection: he rejects pleasures to secure other greater pleasures, or else he endures pains to avoid worse pains.";
                    }


                    $comment->setProprietaire($user)
                        ->setBlog($blog)
                        ->setContenu($txtCment)
                        ->setDateCreation($fake->dateTime);

                    $manager->persist($comment);
                }
            }
        }

        $manager->flush();
    }
}
