<?php

namespace App\Tests;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Panther\PantherTestCase;

class ConferenceControllerTest extends WebTestCase
#class ConferenceControllerTest extends PantherTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        #$client = static::createPantherClient(['external_base_uri' => $_SERVER['SYMFONY_DEFAULT_ROUTE_URL']]);
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Give your feedback!');
    }
    
    public function testCommentSubmission()
    {
        $client = static::createClient();
        #$client = static::createPantherClient(['external_base_uri' => $_SERVER['SYMFONY_DEFAULT_ROUTE_URL']]);
        $client->request('GET', '/conference/terortest2022');
        $client->submitForm('Submit', [
            'comment_form[author]' => 'Luis Enrique',
            'comment_form[text]' => 'Test comment',
            'comment_form[email]' => $email = 'me@automat.ed',
            'comment_form[photo]' => dirname(__DIR__, 1).'/public/uploads/photos/testphoto.jpg',
        ]);
        $this->assertResponseRedirects();

        // simulate comment validation
        $comment = self::$container->get(CommentRepository::class)->findOneByEmail($email);
        $comment->setState('published');
        self::$container->get(EntityManagerInterface::class)->flush();

        $client->followRedirect();
        $this->assertSelectorExists('div', 'There are 2 comments.');
    }

    public function testConferencePage()
    {
        $client = static::createClient();
        #$client = static::createPantherClient(['external_base_uri' => $_SERVER['SYMFONY_DEFAULT_ROUTE_URL']]);
        $crawler = $client->request('GET', '/');

        $this->assertCount(2, $crawler->filter('h4'));

        $crawler = $client->clickLink('View');

        $this->assertPageTitleContains('Arucas Test');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h2', 'Arucas Test 2017');
        $this->assertSame('There are 1 comments.', $crawler->filter('div')->text());
    }
}
