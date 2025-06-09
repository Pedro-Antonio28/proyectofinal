<?php


// tests/Feature/Policies/PostPolicyTest.php

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Policies\PostPolicy;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertTrue;
use function Pest\Laravel\assertFalse;

uses(RefreshDatabase::class);

test('user can update own post', function () {
$user = User::factory()->create();
$post = Post::factory()->create(['user_id' => $user->id]);

$this->assertTrue($user->can('update', $post));
});

test('admin can update any post', function () {
$admin = User::factory()->create();
$admin->roles()->create(['name' => 'admin']); // Asegúrate de que la relación y modelo Role existen
$post = Post::factory()->create();

$this->assertTrue($admin->can('update', $post));
});

test('user cannot update other post', function () {
$user = User::factory()->create();
$post = Post::factory()->create();

$this->assertFalse($user->can('update', $post));
});

test('user can delete own post', function () {
$user = User::factory()->create();
$post = Post::factory()->create(['user_id' => $user->id]);

$this->assertTrue($user->can('delete', $post));
});

test('admin can delete any post', function () {
$admin = User::factory()->create();
$admin->roles()->create(['name' => 'admin']);
$post = Post::factory()->create();

$this->assertTrue($admin->can('delete', $post));
});

test('user cannot delete other post', function () {
$user = User::factory()->create();
$post = Post::factory()->create();

$this->assertFalse($user->can('delete', $post));
});




beforeEach(function () {
    $this->policy = new PostPolicy();
});



it('permite a un usuario autor actualizar su propio post', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $user->id]);

    $policy = new \App\Policies\PostPolicy();
    expect($policy->update($user, $post))->toBeTrue();
});

it('permite a un admin actualizar cualquier post', function () {
    $admin = new class extends \App\Models\User {
        public function hasRole($role)
        {
            return $role === 'admin';
        }
    };
    $admin->id = 2;
    $admin->exists = true;

    $author = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $author->id]);

    $policy = new \App\Policies\PostPolicy();
    expect($policy->update($admin, $post))->toBeTrue();
});

it('no permite a un usuario que no es autor ni admin actualizar el post', function () {
    $author = User::factory()->create();
    $other = User::factory()->create();
    $post = Post::factory()->create(['user_id' => $author->id]);

    $policy = new \App\Policies\PostPolicy();
    expect($policy->update($other, $post))->toBeFalse();
});


it('niega todos los demás permisos por defecto', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create();

    expect($this->policy->viewAny($user))->toBeFalse();
    expect($this->policy->view($user, $post))->toBeFalse();
    expect($this->policy->create($user))->toBeFalse();
    expect($this->policy->restore($user, $post))->toBeFalse();
    expect($this->policy->forceDelete($user, $post))->toBeFalse();
});
