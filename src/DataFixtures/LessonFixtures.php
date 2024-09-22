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
        for ($courseIndex = 1; $courseIndex <= 3; $courseIndex++) {
            $course = $this->getReference('course_' . $courseIndex); // Récupérer la référence de chaque cours

            for ($i = 1; $i <= 5; $i++) {
                $lesson = new Lesson();
                $lesson->setTitle('Leçon ' . $i . ' du cours ' . $courseIndex);
                $lesson->setContent('Contenu de la leçon ' . $i);

                $lesson->setCourse($course); // Associer la leçon au cours

                $manager->persist($lesson);
            }
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
