<?php

use App\Actions\ScrapeAndSaveMembersAction;
use App\Member;
use App\Term;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->action = $this->app->make(ScrapeAndSaveMembersAction::class);

    Http::fakeJsonFromFile('*/members?term=9', 'members.json');
    Term::factory(['number' => 9])->create();
});

it('creates new member records', function () {
    $this->action->execute(9);

    Http::assertSentCount(1);
    expect(Member::count())->toEqual(1);
    expect(Member::first()->web_id)->toEqual(12345);

    $termNumbers = Member::first()->terms()->pluck('number')->toArray();
    expect($termNumbers)->toEqualCanonicalizing([9]);
});

it('merges terms with existing member records', function () {
    $member = Member::factory(['web_id' => 12345])
        ->has(Term::factory(['number' => 8]))
        ->create();

    $this->action->execute(9);

    expect(Member::count())->toEqual(1);
    $termNumbers = Member::first()->terms()->pluck('number')->toArray();
    expect($termNumbers)->toEqualCanonicalizing([8, 9]);
});
