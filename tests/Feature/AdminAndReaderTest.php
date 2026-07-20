<?php

namespace Tests\Feature;

use App\Models\Manga;
use App\Models\Page;
use App\Models\ShareLink;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminAndReaderTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_from_admin(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_login_and_create_manga_with_pages_and_link(): void
    {
        Storage::fake('local');

        $admin = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => 'super-secure-password',
        ]);

        $this->post(route('admin.login.submit'), [
            'email' => 'admin@example.com',
            'password' => 'super-secure-password',
        ])->assertRedirect(route('admin.dashboard'));

        $this->actingAs($admin)
            ->post(route('admin.mangas.store'), [
                'title' => 'Secret Manga',
                'description' => 'Private book',
                'is_published' => '1',
            ])
            ->assertRedirect();

        $manga = Manga::first();
        $this->assertNotNull($manga);
        $this->assertTrue($manga->is_published);

        $image = UploadedFile::fake()->image('page1.jpg', 600, 900);

        $this->actingAs($admin)
            ->post(route('admin.mangas.pages.store', $manga), [
                'images' => [$image],
                'start_number' => 1,
            ])
            ->assertRedirect(route('admin.mangas.pages.index', $manga));

        $this->assertSame(1, $manga->pages()->count());

        $this->actingAs($admin)
            ->post(route('admin.mangas.links.store', $manga), [
                'label' => 'Telegram',
            ])
            ->assertRedirect();

        $link = ShareLink::first();
        $this->assertNotNull($link);

        $this->get(route('reader.show', $link->token))
            ->assertOk()
            ->assertSee('Secret Manga')
            ->assertSee('READER_DATA');

        $page = Page::first();
        $this->get(route('reader.page', [$link->token, $page->id]))
            ->assertOk();

        $this->assertSame(1, $link->fresh()->views_count);

        $this->actingAs($admin)
            ->getJson(route('admin.mangas.links.countries', [$manga, $link]))
            ->assertOk()
            ->assertJsonPath('views_count', 1);
    }

    public function test_inactive_or_unknown_link_returns_404(): void
    {
        $manga = Manga::create([
            'title' => 'Hidden',
            'is_published' => true,
        ]);

        $link = $manga->shareLinks()->create([
            'label' => 'Off',
            'is_active' => false,
        ]);

        $this->get(route('reader.show', $link->token))->assertNotFound();
        $this->get(route('reader.show', 'missing-token-value-1234567890'))->assertNotFound();
    }

    public function test_admin_create_command_makes_user(): void
    {
        $this->artisan('admin:create', [
            '--name' => 'Root',
            '--email' => 'root@example.com',
            '--password' => 'another-secure-pass',
        ])->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'email' => 'root@example.com',
            'name' => 'Root',
        ]);
    }
}
