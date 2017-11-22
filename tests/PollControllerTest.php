<?php

namespace Tests;

use AbstractEverything\Poll\Models\Option;
use AbstractEverything\Poll\Models\Poll;
use AbstractEverything\Poll\PollManager;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Request;
use Tests\TestCase;

class PollControllerTest extends TestCase
{
    public function test_it_shows_poll_list()
    {
        $response = $this->get('/polls');

        $response->assertStatus(200);
    }

    public function test_it_shows_poll_creation_form()
    {
        $response = $this->get('/polls/create?options=5');

        $response->assertStatus(200);
    }

    public function test_it_shows_a_single_poll()
    {
        $pm = resolve(PollManager::class);
        $poll = $pm->create($this->getSamplePollData());
        $response = $this->get('/polls/1');

        $response->assertStatus(200);
    }

    public function test_it_creates_a_poll()
    {
        $response = $this->post('/polls', $this->getSamplePollData());

        $response->assertRedirect('/polls');
        $this->assertEquals(1, Poll::count());
        $this->assertEquals(2, Option::count());
        $this->assertEquals('cats or dogs', Poll::first()->title);
        $this->assertEquals('cats', Option::first()->label);
    }
}