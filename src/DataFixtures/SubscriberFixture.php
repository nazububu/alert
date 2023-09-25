<?php

namespace App\DataFixtures;

use App\DataFixtures\Utils\FixtureDataLoader;
use App\Entity\Subscriber;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubscriberFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = FixtureDataLoader::loadDataFromJson('subscribers.json');

        foreach ($data as $row) {
            $subscriber = new Subscriber();
            $subscriber->setName($row['name']);
            $subscriber->setIdentifier($row['identifier']);
            $subscriber->setRegions($row['regions']);
            $subscriber->setCreatedAt(new DateTimeImmutable());

            $manager->persist($subscriber);
        }

        $manager->flush();
    }
}
