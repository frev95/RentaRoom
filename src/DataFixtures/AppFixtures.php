<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Room;
use App\Entity\Image;
use App\Entity\Feature;
use App\Entity\Category;
use App\Entity\Type;
use App\Entity\Reservation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Set admin
        $admin = new User();
        $admin->setEmail('admin@admin.fr')
            ->setPassword('$2y$13$wqXiXE8U6QhYtIRJFedLA.MkNVmDzn89jVz5CBYENUOwHfAlyYNG2')
            ->setName('Admin')
            ->setPhone('00 00 00 00 00')
            ->setRoles(['ROLE_ADMIN'])
            ->setRole('unused');
        $manager->persist($admin);

        // Set users
        $users = [];
        $search = array(' ','.','À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'à', 'á', 'â', 'ã', 'ä', 'å', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
        $replace = array('-','','A', 'A', 'A', 'A', 'A', 'A', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'a', 'a', 'a', 'a', 'a', 'a', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y');
        for ($i = 0; $i < 20; $i++) {
            $company = $faker->company();
            $user = new User();
            $user->setEmail(strtolower(str_replace($search, $replace, $company)) . '@' . $faker->freeEmailDomain())
                ->setPassword('$2y$13$wqXiXE8U6QhYtIRJFedLA.MkNVmDzn89jVz5CBYENUOwHfAlyYNG2')
                ->setName($company)
                ->setPhone($faker->phoneNumber())
                ->setRoles(['ROLE_USER'])
                ->setRole('unused');
            $manager->persist($user);
            array_push($users, $user);
        }

        // Set categories
        $categoryEnum = ['Salle de réunion', 'Salle de formation', 'Amphithéâtre', 'Salle de spectacle', 'Salle polyvalente', 'Gymnase', 'Sans catégorie'];
        $categories = [];
        for ($i = 0; $i < count($categoryEnum); $i++) {
            $category = new Category();
            $category->setName($categoryEnum[$i]);
            $manager->persist($category);
            array_push($categories, $category);
        }

        // Set types
        $typeEnum = ['Ergonomie', 'Matériel', 'Logiciel', 'Extras'];
        $types = [];
        for ($i = 0; $i < count($typeEnum); $i++) {
            $type = new Type();
            $type->setName($typeEnum[$i]);
            $manager->persist($type);
            array_push($types, $type);
        }

        // Set features
        $features = [];
        for ($i = 0; $i < 30; $i++) {
            $feature = new Feature();
            $feature->setName(str_replace('.', '', $faker->text($faker->numberBetween(10, 40))))
                    ->setType($faker->randomElement($types));
            $manager->persist($feature);
            array_push($features, $feature);
        }

        // Set rooms
        $rooms = [];
        for ($i = 0; $i < 30; $i++) {
            $room = new Room();
            $room->setDescription($faker->paragraphs($faker->numberBetween(1, 4), true));
                if ($i < 12) {
                    $room->setName($faker->randomElement(['Salle ', 'Salon ', 'Bureau ', 'Espace ']) . $faker->name())
                        ->setCapacity($faker->numberBetween(8, 20))
                        ->setCategory($faker->randomElement([$categories[0], $categories[1], $categories[6]]));
                } elseif ($i < 16) {
                    $room->setName($faker->randomElement(['Salle ', 'Amphi ', 'Ecole ', 'Lycée ']) . $faker->name())
                        ->setCapacity($faker->numberBetween(40, 80))
                        ->setCategory($categories[2]);
                } elseif ($i < 20) {
                    $room->setName($faker->randomElement(['Salle des sports ', 'Gymnase ']) . $faker->name())
                        ->setCapacity($faker->numberBetween(60, 120))
                        ->setCategory($categories[5]);
                } elseif ($i < 24) {
                    $room->setName($faker->randomElement(['Salle polyvalente de ', 'Salle des fêtes de ']) . $faker->city())
                        ->setCapacity($faker->numberBetween(80, 160))
                        ->setCategory($categories[4]);
                } else {
                    $room->setName($faker->randomElement(['Salle ', 'Espace ']) . $faker->name())
                        ->setCapacity($faker->numberBetween(120, 300))
                        ->setCategory($faker->randomElement([$categories[3], $categories[4], $categories[6]]));
                }
                $n = $faker->numberBetween(0, 8);
                for ($j = 0; $j < $n; $j++) {
                    $room->addFeature($faker->randomElement($features));
                }
            $manager->persist($room);
            array_push($rooms, $room);
        }

        // Set reservations
        $reservations = [];
        for ($i = 0; $i < 50; $i++) {
            $reservation = new Reservation();
            $startDate = $faker->dateTimeBetween('+1 day', '+6 months');
            $reservation->setStartDate($startDate)
                        ->setEndDate($faker->dateTimeInInterval($startDate, '+5 days'))
                        ->setUser($faker->randomElement($users))
                        ->setRoom($faker->randomElement($rooms))
                        ->setStatus('0');   // en attente par défaut
            $manager->persist($reservation);
            array_push($reservations, $reservation);
        }

        // Flush
        $manager->flush();
    }
}