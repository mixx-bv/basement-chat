<?php

namespace BasementChat\Basement\Tests\Browser;

use BasementChat\Basement\Tests\Browser\Components\ChatBoxComponent;
use BasementChat\Basement\Tests\BrowserTestCase;
use BasementChat\Basement\Tests\Fixtures\User;
use BasementChat\Basement\Tests\WithPrivateMessages;
use BasementChat\Basement\Tests\WithUsers;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;

class ChatBoxContactTest extends BrowserTestCase
{
    use DatabaseMigrations;
    use WithPrivateMessages;
    use WithUsers;

    protected User $receiver;

    protected User $sender1;

    protected User $sender2;

    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpUsers();
        $this->setUpPrivateMessages();
        $this->addUsers(3);

        $this->receiver = $this->users[0];
        $this->sender1 = $this->users[1];
        $this->sender2 = $this->users[2];
    }

    /**
     * @test
     */
    public function itShouldBeAbleToSeeAllContactsWithTheirOnlineStatus(): void
    {
        $this->browse(function (Browser $browserReceiver, Browser $browserSender): void {
            $browserReceiver->loginAs($this->receiver, guard: 'web');
            $browserReceiver->visit('/dashboard');

            $browserSender->loginAs($this->sender1, guard: 'web');
            $browserSender->visit('/dashboard');

            $browserReceiver->within(
                selector: new ChatBoxComponent(),
                callback: fn (Browser $component) => $component
                    ->openChatBox()
                    ->waitUntilContactsVisible()
                    ->assertSeeContacts($this->receiver->name, $this->sender1->name, $this->sender2->name)
                    ->assertContactsIsOnline($this->receiver->name, $this->sender1->name)
                    ->assertContactsIsOffline($this->sender2->name),
            );
        });
    }
}
