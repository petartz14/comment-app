<?php

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Faker\faker;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(RefreshDatabase::class);

test('users can list comments', function () {
  Comment::factory()->count(3)->create();

  $response = getJson(route('comments.index'));

  $response->assertStatus(200);
});

test('users can create comment', function () {
    $response = postJson(route('comments.store'), [
      'comment_id' => Comment::factory()->create()->id,
      'comment' => faker()->paragraph,
      'name' => faker()->name,
    ]);

    $response->assertStatus(302);
});
