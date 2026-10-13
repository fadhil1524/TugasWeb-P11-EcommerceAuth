<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite(); // tidak perlu `npm run build` saat testing
    }

    private function makeUser(string $role): User
    {
        return User::factory()->create(['role' => $role]);
    }

    public function test_guest_diarahkan_ke_login(): void
    {
        $this->get('/admin')->assertRedirect('/login');
        $this->get('/posts')->assertRedirect('/login');
    }

    public function test_user_biasa_ditolak_dari_admin_dan_manage(): void
    {
        $user = $this->makeUser('user');

        $this->actingAs($user)->get('/admin')->assertForbidden();
        $this->actingAs($user)->get('/manage/products')->assertForbidden();
    }

    public function test_editor_boleh_manage_tapi_bukan_admin(): void
    {
        $editor = $this->makeUser('editor');

        $this->actingAs($editor)->get('/manage/products')->assertOk();
        $this->actingAs($editor)->get('/admin')->assertForbidden();
    }

    public function test_admin_boleh_semua(): void
    {
        $admin = $this->makeUser('admin');

        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->actingAs($admin)->get('/manage/products')->assertOk();
    }

    public function test_role_tidak_bisa_diubah_lewat_mass_assignment(): void
    {
        $user = User::create([
            'name' => 'X', 'email' => 'x@example.com', 'password' => 'secret123', 'role' => 'admin',
        ]);

        $this->assertSame('user', $user->fresh()->role);
    }

    public function test_user_hanya_boleh_edit_post_miliknya(): void
    {
        $owner = $this->makeUser('user');
        $other = $this->makeUser('user');
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($owner)->get("/posts/{$post->id}/edit")->assertOk();
        $this->actingAs($other)->get("/posts/{$post->id}/edit")->assertForbidden();
    }

    public function test_editor_boleh_edit_tapi_tidak_boleh_hapus_post_orang_lain(): void
    {
        $owner  = $this->makeUser('user');
        $editor = $this->makeUser('editor');
        $post   = Post::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($editor)->get("/posts/{$post->id}/edit")->assertOk();
        $this->actingAs($editor)->delete("/posts/{$post->id}")->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_admin_boleh_hapus_post_siapa_saja(): void
    {
        $owner = $this->makeUser('user');
        $admin = $this->makeUser('admin');
        $post  = Post::factory()->create(['user_id' => $owner->id]);

        $this->actingAs($admin)->delete("/posts/{$post->id}")->assertRedirect('/posts');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
}
