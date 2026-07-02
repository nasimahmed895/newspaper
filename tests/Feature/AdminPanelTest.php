<?php

namespace Tests\Feature;

use App\Filament\Resources\AdPlacements\Pages\CreateAdPlacement;
use App\Filament\Resources\AdPlacements\Pages\EditAdPlacement;
use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Categories\Pages\CreateCategory;
use App\Filament\Resources\Categories\Pages\EditCategory;
use App\Filament\Resources\Settings\Pages\CreateSetting;
use App\Filament\Resources\Settings\Pages\EditSetting;
use App\Models\AdPlacement;
use App\Models\Article;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);

        $this->category = Category::create([
            'name' => 'Technology',
            'slug' => 'technology',
            'is_active' => true,
        ]);

        Setting::create(['key' => 'test.setting', 'value' => 'test', 'group' => 'general']);
        AdPlacement::create(['name' => 'Test Ad', 'location' => 'sidebar-top']);
    }

    // === PAGE LOADS ===

    public function test_admin_login_page_loads(): void
    {
        auth()->logout();
        $this->get('/admin/login')->assertStatus(200);
    }

    public function test_admin_dashboard_requires_auth(): void
    {
        auth()->logout();
        $this->get('/admin')->assertStatus(302);
    }

    public function test_admin_dashboard_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin')->assertStatus(200);
    }

    public function test_articles_list_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/articles')->assertStatus(200);
    }

    public function test_articles_create_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/articles/create')->assertStatus(200);
    }

    public function test_categories_list_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/categories')->assertStatus(200);
    }

    public function test_categories_create_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/categories/create')->assertStatus(200);
    }

    public function test_settings_list_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/settings')->assertStatus(200);
    }

    public function test_settings_create_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/settings/create')->assertStatus(200);
    }

    public function test_ad_placements_list_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/ad-placements')->assertStatus(200);
    }

    public function test_ad_placements_create_page_loads(): void
    {
        $this->actingAs($this->admin)->get('/admin/ad-placements/create')->assertStatus(200);
    }

    // === ARTICLE CRUD via Livewire ===

    public function test_article_can_be_created(): void
    {
        $this->actingAs($this->admin);

        Livewire::actingAs($this->admin)
            ->test(CreateArticle::class)
            ->set('data.category_id', (string) $this->category->id)
            ->set('data.title', 'Test Article Title')
            ->set('data.slug', 'test-article-title')
            ->set('data.content', '<p>Test content for article</p>')
            ->set('data.author', 'News Desk')
            ->set('data.is_published', true)
            ->set('data.reading_time_minutes', 5)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('articles', ['title' => 'Test Article Title']);
    }

    public function test_article_can_be_edited(): void
    {
        $this->actingAs($this->admin);
        $article = Article::create([
            'category_id' => $this->category->id,
            'title' => 'Original Title',
            'slug' => 'original-title',
            'content' => '<p>Original content</p>',
            'is_published' => true,
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditArticle::class, ['record' => $article->id])
            ->set('data.title', 'Updated Title')
            ->set('data.slug', 'updated-title')
            ->set('data.content', '<p>Updated content</p>')
            ->set('data.category_id', (string) $this->category->id)
            ->set('data.is_published', true)
            ->set('data.reading_time_minutes', 3)
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('articles', ['title' => 'Updated Title']);
    }

    public function test_article_can_be_deleted(): void
    {
        $this->actingAs($this->admin);
        $article = Article::create([
            'category_id' => $this->category->id,
            'title' => 'Delete Me',
            'slug' => 'delete-me',
            'content' => '<p>To be deleted</p>',
        ]);

        Livewire::actingAs($this->admin)
            ->test(EditArticle::class, ['record' => $article->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('articles', ['id' => $article->id]);
    }

    // === CATEGORY CRUD ===

    public function test_category_can_be_created(): void
    {
        $this->actingAs($this->admin);

        Livewire::actingAs($this->admin)
            ->test(CreateCategory::class)
            ->set('data.name', 'New Category')
            ->set('data.slug', 'new-category')
            ->set('data.description', 'A test category')
            ->set('data.is_active', true)
            ->set('data.order', 1)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    }

    public function test_category_can_be_deleted(): void
    {
        $this->actingAs($this->admin);
        $category = Category::create(['name' => 'Temp Cat', 'slug' => 'temp-cat']);

        Livewire::actingAs($this->admin)
            ->test(EditCategory::class, ['record' => $category->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    // === SETTINGS CRUD ===

    public function test_setting_can_be_created(): void
    {
        $this->actingAs($this->admin);

        Livewire::actingAs($this->admin)
            ->test(CreateSetting::class)
            ->set('data.key', 'custom.setting')
            ->set('data.value', 'custom value')
            ->set('data.group', 'general')
            ->set('data.label', 'Custom Setting')
            ->set('data.type', 'text')
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('settings', ['key' => 'custom.setting']);
    }

    public function test_setting_can_be_deleted(): void
    {
        $this->actingAs($this->admin);
        $setting = Setting::create(['key' => 'temp.key', 'value' => 'temp', 'group' => 'general']);

        Livewire::actingAs($this->admin)
            ->test(EditSetting::class, ['record' => $setting->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('settings', ['id' => $setting->id]);
    }

    // === AD PLACEMENT CRUD ===

    public function test_ad_placement_can_be_created(): void
    {
        $this->actingAs($this->admin);

        Livewire::actingAs($this->admin)
            ->test(CreateAdPlacement::class)
            ->set('data.name', 'New Ad')
            ->set('data.location', 'header')
            ->set('data.code', '<script>ad code</script>')
            ->set('data.is_active', true)
            ->set('data.order', 1)
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('ad_placements', ['name' => 'New Ad']);
    }

    public function test_ad_placement_can_be_deleted(): void
    {
        $this->actingAs($this->admin);
        $ad = AdPlacement::create(['name' => 'Temp', 'location' => 'sidebar-bottom']);

        Livewire::actingAs($this->admin)
            ->test(EditAdPlacement::class, ['record' => $ad->id])
            ->callAction('delete');

        $this->assertDatabaseMissing('ad_placements', ['id' => $ad->id]);
    }
}
