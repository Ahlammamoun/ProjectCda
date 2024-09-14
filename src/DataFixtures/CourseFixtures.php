<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CourseFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création d'un utilisateur qui sera l'enseignant
        $teacher = new User();
        $teacher->setEmail('teacher@example.com');
        $teacher->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $teacher->setPassword($this->passwordHasher->hashPassword($teacher, 'password123'));

        $manager->persist($teacher);

        // Exemple de création de 3 cours
        for ($i = 1; $i <= 3; $i++) {
            $course = new Course();
            $course->setTitle('Cours ' . $i);
            $course->setDescription('Description du cours ' . $i);
            $course->setTeacher($teacher); // Assigner l'enseignant au cours

            $manager->persist($course);

            // Ajouter une référence pour l'utiliser dans LessonFixtures
            $this->addReference('course_' . $i, $course);
        }

        $manager->flush();
    }
}
