<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $articles = [
            1 => [
                'title' => 'Titre 1',
                'slug' => 'titre_1',
                'content' => 'Suspendisse condimentum tempus dignissim. Cras at erat blandit enim faucibus cursus. Vivamus vitae suscipit odio. Mauris congue at purus quis suscipit. Ut suscipit leo ac lacus facilisis accumsan non vel quam. Nullam ac mauris auctor, cursus elit sed, lobortis justo. Nam non viverra felis. Sed et augue vitae quam sollicitudin fringilla. Cras volutpat condimentum luctus. Nam ac orci elit. Donec vitae cursus ipsum, quis venenatis sapien. Duis vehicula mi urna, non pharetra risus posuere a. Aliquam erat volutpat. In vitae blandit ligula.',
            ],
            2 => [
                'title' => 'Titre 2',
                'slug' => 'titre_2',
                'content' => 'Nulla eu ipsum mi. Vestibulum dignissim, sapien hendrerit dapibus elementum, ipsum est feugiat lectus, sit amet lobortis massa quam eget est. Maecenas sodales quam sed diam bibendum, sit amet pulvinar magna lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam in quam interdum hendrerit ut quis leo. Suspendisse semper sapien consectetur arcu bibendum suscipit. Mauris eleifend dui id ultrices venenatis. Aliquam porta tincidunt mauris, eu ornare tellus egestas eget. Morbi sapien felis, consectetur eget lacinia sit amet, dapibus non enim. Duis aliquam arcu quis leo scelerisque, ac fringilla odio rutrum.',
            ],
            3 => [
                'title' => 'Titre 3',
                'slug' => 'titre_3',
                'content' => 'Maecenas tempus turpis a nulla aliquam varius. Maecenas gravida fermentum varius. Ut vehicula ultricies nisi ut ultrices. Donec ut tristique leo. Aenean eget dolor arcu. Morbi tincidunt est auctor tellus viverra aliquam. Aliquam tempus pretium enim, in congue est interdum sit amet. Integer risus velit, condimentum sit amet tincidunt at, pharetra sed felis. Nulla lobortis, metus eget volutpat consequat, lectus tortor lobortis lorem, ut tempus purus neque ac lectus. Nullam ut ante sed ipsum feugiat rutrum. Sed dictum id turpis sit amet lacinia. Praesent laoreet a ipsum a iaculis. Integer venenatis urna a egestas ultrices. Morbi porttitor pharetra commodo.',
            ],
            4 => [
                'title' => 'Titre 4',
                'slug' => 'titre_4',
                'content' => 'Donec placerat est tortor, eu sagittis risus ultricies ut. Aenean ac efficitur ipsum. Mauris luctus metus eleifend lorem tempor vulputate. Proin iaculis sem vitae ex luctus, a lacinia turpis semper. Integer rutrum efficitur ipsum. Nulla tortor massa, venenatis at leo sit amet, tempus sagittis leo. Suspendisse pellentesque dui metus, vitae sollicitudin dolor finibus vitae. Proin convallis sodales tortor. Curabitur euismod vulputate sodales. Aenean elementum ex sed dui imperdiet pharetra. Curabitur elit lacus, lobortis a mattis sit amet, sollicitudin sed tortor. Integer eleifend sed quam in ornare. Donec molestie purus lectus, vitae commodo mauris efficitur in.',
            ],
            5 => [
                'title' => 'Titre 5',
                'slug' => 'titre_5',
                'content' => 'Suspendisse condimentum tempus dignissim. Cras at erat blandit enim faucibus cursus. Vivamus vitae suscipit odio. Mauris congue at purus quis suscipit. Ut suscipit leo ac lacus facilisis accumsan non vel quam. Nullam ac mauris auctor, cursus elit sed, lobortis justo. Nam non viverra felis. Sed et augue vitae quam sollicitudin fringilla. Cras volutpat condimentum luctus. Nam ac orci elit. Donec vitae cursus ipsum, quis venenatis sapien. Duis vehicula mi urna, non pharetra risus posuere a. Aliquam erat volutpat. In vitae blandit ligula.',
            ],
            6 => [
                'title' => 'Titre 6',
                'slug' => 'titre_6',
                'content' => 'Nulla eu ipsum mi. Vestibulum dignissim, sapien hendrerit dapibus elementum, ipsum est feugiat lectus, sit amet lobortis massa quam eget est. Maecenas sodales quam sed diam bibendum, sit amet pulvinar magna lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam in quam interdum hendrerit ut quis leo. Suspendisse semper sapien consectetur arcu bibendum suscipit. Mauris eleifend dui id ultrices venenatis. Aliquam porta tincidunt mauris, eu ornare tellus egestas eget. Morbi sapien felis, consectetur eget lacinia sit amet, dapibus non enim. Duis aliquam arcu quis leo scelerisque, ac fringilla odio rutrum.',
            ],
            7 => [
                'title' => 'Titre 7',
                'slug' => 'titre_7',
                'content' => 'Maecenas tempus turpis a nulla aliquam varius. Maecenas gravida fermentum varius. Ut vehicula ultricies nisi ut ultrices. Donec ut tristique leo. Aenean eget dolor arcu. Morbi tincidunt est auctor tellus viverra aliquam. Aliquam tempus pretium enim, in congue est interdum sit amet. Integer risus velit, condimentum sit amet tincidunt at, pharetra sed felis. Nulla lobortis, metus eget volutpat consequat, lectus tortor lobortis lorem, ut tempus purus neque ac lectus. Nullam ut ante sed ipsum feugiat rutrum. Sed dictum id turpis sit amet lacinia. Praesent laoreet a ipsum a iaculis. Integer venenatis urna a egestas ultrices. Morbi porttitor pharetra commodo.',
            ],
            8 => [
                'title' => 'Titre 8',
                'slug' => 'titre_8',
                'content' => 'Donec placerat est tortor, eu sagittis risus ultricies ut. Aenean ac efficitur ipsum. Mauris luctus metus eleifend lorem tempor vulputate. Proin iaculis sem vitae ex luctus, a lacinia turpis semper. Integer rutrum efficitur ipsum. Nulla tortor massa, venenatis at leo sit amet, tempus sagittis leo. Suspendisse pellentesque dui metus, vitae sollicitudin dolor finibus vitae. Proin convallis sodales tortor. Curabitur euismod vulputate sodales. Aenean elementum ex sed dui imperdiet pharetra. Curabitur elit lacus, lobortis a mattis sit amet, sollicitudin sed tortor. Integer eleifend sed quam in ornare. Donec molestie purus lectus, vitae commodo mauris efficitur in.',
            ],
            9 => [
                'title' => 'Titre 9',
                'slug' => 'titre_9',
                'content' => 'Suspendisse condimentum tempus dignissim. Cras at erat blandit enim faucibus cursus. Vivamus vitae suscipit odio. Mauris congue at purus quis suscipit. Ut suscipit leo ac lacus facilisis accumsan non vel quam. Nullam ac mauris auctor, cursus elit sed, lobortis justo. Nam non viverra felis. Sed et augue vitae quam sollicitudin fringilla. Cras volutpat condimentum luctus. Nam ac orci elit. Donec vitae cursus ipsum, quis venenatis sapien. Duis vehicula mi urna, non pharetra risus posuere a. Aliquam erat volutpat. In vitae blandit ligula.',
            ],
            10 => [
                'title' => 'Titre 10',
                'slug' => 'titre_10',
                'content' => 'Nulla eu ipsum mi. Vestibulum dignissim, sapien hendrerit dapibus elementum, ipsum est feugiat lectus, sit amet lobortis massa quam eget est. Maecenas sodales quam sed diam bibendum, sit amet pulvinar magna lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam in quam interdum hendrerit ut quis leo. Suspendisse semper sapien consectetur arcu bibendum suscipit. Mauris eleifend dui id ultrices venenatis. Aliquam porta tincidunt mauris, eu ornare tellus egestas eget. Morbi sapien felis, consectetur eget lacinia sit amet, dapibus non enim. Duis aliquam arcu quis leo scelerisque, ac fringilla odio rutrum.',
            ],
            11 => [
                'title' => 'Titre 11',
                'slug' => 'titre_11',
                'content' => 'Maecenas tempus turpis a nulla aliquam varius. Maecenas gravida fermentum varius. Ut vehicula ultricies nisi ut ultrices. Donec ut tristique leo. Aenean eget dolor arcu. Morbi tincidunt est auctor tellus viverra aliquam. Aliquam tempus pretium enim, in congue est interdum sit amet. Integer risus velit, condimentum sit amet tincidunt at, pharetra sed felis. Nulla lobortis, metus eget volutpat consequat, lectus tortor lobortis lorem, ut tempus purus neque ac lectus. Nullam ut ante sed ipsum feugiat rutrum. Sed dictum id turpis sit amet lacinia. Praesent laoreet a ipsum a iaculis. Integer venenatis urna a egestas ultrices. Morbi porttitor pharetra commodo.',
            ],
            12 => [
                'title' => 'Titre 12',
                'slug' => 'titre_12',
                'content' => 'Donec placerat est tortor, eu sagittis risus ultricies ut. Aenean ac efficitur ipsum. Mauris luctus metus eleifend lorem tempor vulputate. Proin iaculis sem vitae ex luctus, a lacinia turpis semper. Integer rutrum efficitur ipsum. Nulla tortor massa, venenatis at leo sit amet, tempus sagittis leo. Suspendisse pellentesque dui metus, vitae sollicitudin dolor finibus vitae. Proin convallis sodales tortor. Curabitur euismod vulputate sodales. Aenean elementum ex sed dui imperdiet pharetra. Curabitur elit lacus, lobortis a mattis sit amet, sollicitudin sed tortor. Integer eleifend sed quam in ornare. Donec molestie purus lectus, vitae commodo mauris efficitur in.',
            ],
            13 => [
                'title' => 'Titre 13',
                'slug' => 'titre_13',
                'content' => 'Suspendisse condimentum tempus dignissim. Cras at erat blandit enim faucibus cursus. Vivamus vitae suscipit odio. Mauris congue at purus quis suscipit. Ut suscipit leo ac lacus facilisis accumsan non vel quam. Nullam ac mauris auctor, cursus elit sed, lobortis justo. Nam non viverra felis. Sed et augue vitae quam sollicitudin fringilla. Cras volutpat condimentum luctus. Nam ac orci elit. Donec vitae cursus ipsum, quis venenatis sapien. Duis vehicula mi urna, non pharetra risus posuere a. Aliquam erat volutpat. In vitae blandit ligula.',
            ],
            14 => [
                'title' => 'Titre 14',
                'slug' => 'titre_14',
                'content' => 'Nulla eu ipsum mi. Vestibulum dignissim, sapien hendrerit dapibus elementum, ipsum est feugiat lectus, sit amet lobortis massa quam eget est. Maecenas sodales quam sed diam bibendum, sit amet pulvinar magna lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam in quam interdum hendrerit ut quis leo. Suspendisse semper sapien consectetur arcu bibendum suscipit. Mauris eleifend dui id ultrices venenatis. Aliquam porta tincidunt mauris, eu ornare tellus egestas eget. Morbi sapien felis, consectetur eget lacinia sit amet, dapibus non enim. Duis aliquam arcu quis leo scelerisque, ac fringilla odio rutrum.',
            ],
            15 => [
                'title' => 'Titre 15',
                'slug' => 'titre_15',
                'content' => 'Maecenas tempus turpis a nulla aliquam varius. Maecenas gravida fermentum varius. Ut vehicula ultricies nisi ut ultrices. Donec ut tristique leo. Aenean eget dolor arcu. Morbi tincidunt est auctor tellus viverra aliquam. Aliquam tempus pretium enim, in congue est interdum sit amet. Integer risus velit, condimentum sit amet tincidunt at, pharetra sed felis. Nulla lobortis, metus eget volutpat consequat, lectus tortor lobortis lorem, ut tempus purus neque ac lectus. Nullam ut ante sed ipsum feugiat rutrum. Sed dictum id turpis sit amet lacinia. Praesent laoreet a ipsum a iaculis. Integer venenatis urna a egestas ultrices. Morbi porttitor pharetra commodo.',
            ],
            16 => [
                'title' => 'Titre 16',
                'slug' => 'titre_16',
                'content' => 'Donec placerat est tortor, eu sagittis risus ultricies ut. Aenean ac efficitur ipsum. Mauris luctus metus eleifend lorem tempor vulputate. Proin iaculis sem vitae ex luctus, a lacinia turpis semper. Integer rutrum efficitur ipsum. Nulla tortor massa, venenatis at leo sit amet, tempus sagittis leo. Suspendisse pellentesque dui metus, vitae sollicitudin dolor finibus vitae. Proin convallis sodales tortor. Curabitur euismod vulputate sodales. Aenean elementum ex sed dui imperdiet pharetra. Curabitur elit lacus, lobortis a mattis sit amet, sollicitudin sed tortor. Integer eleifend sed quam in ornare. Donec molestie purus lectus, vitae commodo mauris efficitur in.',
            ],
            17 => [
                'title' => 'Titre 17',
                'slug' => 'titre_17',
                'content' => 'Suspendisse condimentum tempus dignissim. Cras at erat blandit enim faucibus cursus. Vivamus vitae suscipit odio. Mauris congue at purus quis suscipit. Ut suscipit leo ac lacus facilisis accumsan non vel quam. Nullam ac mauris auctor, cursus elit sed, lobortis justo. Nam non viverra felis. Sed et augue vitae quam sollicitudin fringilla. Cras volutpat condimentum luctus. Nam ac orci elit. Donec vitae cursus ipsum, quis venenatis sapien. Duis vehicula mi urna, non pharetra risus posuere a. Aliquam erat volutpat. In vitae blandit ligula.',
            ],
            18 => [
                'title' => 'Titre 18',
                'slug' => 'titre_18',
                'content' => 'Nulla eu ipsum mi. Vestibulum dignissim, sapien hendrerit dapibus elementum, ipsum est feugiat lectus, sit amet lobortis massa quam eget est. Maecenas sodales quam sed diam bibendum, sit amet pulvinar magna lobortis. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam at diam in quam interdum hendrerit ut quis leo. Suspendisse semper sapien consectetur arcu bibendum suscipit. Mauris eleifend dui id ultrices venenatis. Aliquam porta tincidunt mauris, eu ornare tellus egestas eget. Morbi sapien felis, consectetur eget lacinia sit amet, dapibus non enim. Duis aliquam arcu quis leo scelerisque, ac fringilla odio rutrum.',
            ],
            19 => [
                'title' => 'Titre 19',
                'slug' => 'titre_19',
                'content' => 'Maecenas tempus turpis a nulla aliquam varius. Maecenas gravida fermentum varius. Ut vehicula ultricies nisi ut ultrices. Donec ut tristique leo. Aenean eget dolor arcu. Morbi tincidunt est auctor tellus viverra aliquam. Aliquam tempus pretium enim, in congue est interdum sit amet. Integer risus velit, condimentum sit amet tincidunt at, pharetra sed felis. Nulla lobortis, metus eget volutpat consequat, lectus tortor lobortis lorem, ut tempus purus neque ac lectus. Nullam ut ante sed ipsum feugiat rutrum. Sed dictum id turpis sit amet lacinia. Praesent laoreet a ipsum a iaculis. Integer venenatis urna a egestas ultrices. Morbi porttitor pharetra commodo.',
            ],
            20 => [
                'title' => 'Titre 20',
                'slug' => 'titre_20',
                'content' => 'Donec placerat est tortor, eu sagittis risus ultricies ut. Aenean ac efficitur ipsum. Mauris luctus metus eleifend lorem tempor vulputate. Proin iaculis sem vitae ex luctus, a lacinia turpis semper. Integer rutrum efficitur ipsum. Nulla tortor massa, venenatis at leo sit amet, tempus sagittis leo. Suspendisse pellentesque dui metus, vitae sollicitudin dolor finibus vitae. Proin convallis sodales tortor. Curabitur euismod vulputate sodales. Aenean elementum ex sed dui imperdiet pharetra. Curabitur elit lacus, lobortis a mattis sit amet, sollicitudin sed tortor. Integer eleifend sed quam in ornare. Donec molestie purus lectus, vitae commodo mauris efficitur in.',
            ],
        ];

        foreach ($articles as $key => $value) {
            $articles = new Article();
            $articles->setTitle($value['title']);
            $articles->setSlug($value['slug']);
            $articles->setContent($value['content']);

            $manager->persist($articles);
        }

        $manager->flush();
    }
}
