<?php

namespace App\DataFixtures;

use App\Entity\Lesson;
use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LessonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        // Ajouter 5 leçons pour chaque cours
        for ($i = 1; $i <= 5; $i++) {
            $lesson = new Lesson();
            $lesson->setTitle('Leçon ' . $i);
            $lesson->setContent('Contenu de la leçon ' . $i);

            // Associer à un cours existant
            /** @var Course $course */
            $course = $this->getReference('course_1'); // Référence d'un cours créé dans CourseFixtures
            $lesson->setCourse($course);

            $manager->persist($lesson);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CourseFixtures::class, // S'assurer que les cours sont déjà chargés
        ];
    }
}
