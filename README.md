# Newspaper — AI-Powered News Portal

Laravel 13 news website that auto-generates trending articles every 2 hours using OpenRouter AI, publishes them, and pushes URLs to Google for indexing. Designed for organic traffic growth through fresh, SEO-optimized content.

---

## What It Does

1. Fetches trending topics from **Google Trends RSS** (US, GB, CA)
2. Writes full SEO-optimized news articles via **OpenRouter TEXT_MODEL**
3. Attaches images: **Unsplash first**, then **AI-generated via IMAGE_MODEL**
4. Auto-publishes articles with SEO metadata + structured data (JSON-LD)
5. Regenerates **sitemap.xml** 5 minutes after each run
6. Submits new URLs to **Google Indexing API** 10 minutes after each run
7. Runs **12 times per day** (every 2 hours) = 12 fresh articles daily

---

## Architecture

```
Scheduler (cron)
    └─ news:generate (every 2h :00)
          ├─ GoogleTrendsService     → RSS: google.com/trending/rss (US/GB/CA)
          │     └─ fallback          → OpenRouterService::fetchTrendingTopics()
          │                            (SEARCH_MODEL = google/gemini-2.5-flash)
          ├─ OpenRouterService       → generateArticle()
          │     └─ TEXT_MODEL        = openai/gpt-4o-mini
          └─ ImageService
                ├─ Method 1          → Unsplash API (landscape, safe)
                └─ Method 2          → OpenRouterService::generateAIImage()
                                       (IMAGE_MODEL = bytedance/sdxl-lightning-4step)

    └─ news:update-sitemap (every 2h :05)
          └─ spatie/laravel-sitemap  → public/sitemap.xml

    └─ news:index-google (every 2h :10)
          └─ GoogleIndexingService   → Google Indexing API (push to crawl)
```

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 13 |
| Admin Panel | Filament v3 |
| AI Gateway | OpenRouter (OpenAI-compatible) |
| Trending Data | Google Trends RSS |
| Images | Unsplash API + AI generation |
| SEO | spatie/laravel-sitemap + JSON-LD structured data |
| Database | MySQL |
| Queue | Database |

---

## Installation

### 1. Clone and install dependencies

```bash
git clone <repo-url>
cd newspaper
composer install
npm install && npm run build
```

### 2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure `.env`

```env
APP_NAME="Your News Site Name"
APP_URL=https://yourdomain.com

DB_DATABASE=news_paper
DB_USERNAME=root
DB_PASSWORD=

# OpenRouter — get key at openrouter.ai/keys
OPENROUTER_API_KEY=sk-or-v1-your-key-here
OPENROUTER_BASE_URL=https://openrouter.ai/api/v1

# AI Model Selection
SEARCH_MODEL=google/gemini-2.5-flash       # trending topic discovery
TEXT_MODEL=openai/gpt-4o-mini              # article writing
IMAGE_MODEL=bytedance/sdxl-lightning-4step # AI image generation

# Unsplash — get key at unsplash.com/developers (free: 50 req/hr)
UNSPLASH_ACCESS_KEY=your-unsplash-key

# Google Indexing API (optional but recommended for SEO)
GOOGLE_INDEXING_API_KEY=
GOOGLE_INDEXING_CLIENT_EMAIL=

# Article automation
NEWS_MAX_ARTICLES_PER_RUN=1    # 1 per run × 12 runs/day = 12 articles/day
NEWS_DEFAULT_PUBLISH=true
```

### 4. Database setup

```bash
php artisan migrate
php artisan db:seed
```

### 5. Create admin user

```bash
php artisan make:filament-user
```

### 6. Link storage

```bash
php artisan storage:link
```

---

## Running the Application

### Development server

```bash
php artisan serve
```

### Start the scheduler (production)

Add this single cron entry to your server:

```
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

### Start the scheduler (development)

```bash
php artisan schedule:work
```

### Queue worker (required for background jobs)

```bash
php artisan queue:work --sleep=3 --tries=3
```

---

## Artisan Commands

| Command | Description |
|---------|-------------|
| `php artisan news:generate` | Generate articles from trending topics |
| `php artisan news:generate --topic="AI in healthcare"` | Generate article for specific topic |
| `php artisan news:generate --limit=3` | Generate 3 articles in one run |
| `php artisan news:generate --publish=false` | Generate as drafts (no auto-publish) |
| `php artisan news:update-sitemap` | Regenerate sitemap.xml |
| `php artisan news:index-google` | Submit articles to Google Indexing API |
| `php artisan news:index-google --limit=100` | Submit last 100 articles |

---

## Scheduled Jobs (Automatic)

```
0  */2 * * *   news:generate          — generate 1 article
5  */2 * * *   news:update-sitemap    — rebuild sitemap
10 */2 * * *   news:index-google      — push to Google crawl queue
```

Total: **12 articles per day**, sitemap and Google index updated after each.

---

## Admin Panel

URL: `https://yourdomain.com/admin`

| Section | Features |
|---------|----------|
| Articles | Create, edit, publish/unpublish, SEO fields |
| Categories | Manage news categories, ordering |
| Ad Placements | Manage ad slots for monetization |
| Settings | Global app settings (auto-publish, limits) |
| News Generation Logs | View AI generation history and status |

---

## Frontend Routes

| Route | Description |
|-------|-------------|
| `/` | Homepage: featured, latest, trending articles |
| `/article/{slug}` | Article detail with JSON-LD structured data |
| `/category/{slug}` | Category listing page |
| `/search?q=term` | Full-text search |
| `/search/suggest?q=term` | Search autocomplete (JSON) |

---

## SEO Features

- **JSON-LD structured data** on every article (`NewsArticle` schema)
- **Open Graph** + **Twitter Card** meta tags
- **sitemap.xml** auto-updated every 2 hours with image tags
- **robots.txt** included
- **Canonical URLs** on all pages
- Articles optimized with: `seo_title`, `seo_description`, `seo_keywords`, `reading_time_minutes`
- Google Indexing API push = faster crawl turnaround

---

## AI Model Guide

| Task | Model | Why |
|------|-------|-----|
| Trending topics | `google/gemini-2.5-flash` | Real-time web access, identifies what's being searched right now |
| Article writing | `openai/gpt-4o-mini` | Best balance of quality vs cost (~$0.15/1M input tokens) |
| Image generation | `bytedance/sdxl-lightning-4step` | Fast (4-step inference), low cost, good quality |

**Alternative models** (set in `.env`):
- TEXT_MODEL: `meta-llama/llama-3.1-8b-instruct` (cheaper, slightly lower quality)
- IMAGE_MODEL: `stabilityai/stable-diffusion-xl-base-1.0` (higher quality, slower)

---

## Image Pipeline

```
getFeaturedImage($topic, $title, $excerpt)
    │
    ├─ searchUnsplash($topic)
    │       ├─ UNSPLASH_ACCESS_KEY set?  → YES → search landscape photos → return
    │       └─ NO or no results         → continue
    │
    └─ generateWithAI($title, $excerpt)
            ├─ generateImagePrompt()    → TEXT_MODEL writes detailed prompt
            ├─ generateAIImage(prompt)  → IMAGE_MODEL renders at 1024×1024
            └─ fallback                 → /images/placeholder-news.jpg
```

---

## Database Schema (key tables)

**articles**
```
id, category_id, title, slug (unique), content (HTML),
excerpt, featured_image, image_credit, image_source,
author, trending_topic, is_published, published_at,
seo_title, seo_description, seo_keywords,
reading_time_minutes, view_count, is_trending,
created_at, updated_at
```

**categories** — name, slug, description, is_active, order

**settings** — key/value store for runtime configuration

**news_generation_logs** — topic, status, articles_count per run

**ad_placements** — ad slot management for monetization

---

## Directory Structure

```
app/
├── Console/Commands/
│   ├── GenerateNews.php          # main automation command
│   ├── UpdateSitemap.php         # sitemap regeneration
│   └── SubmitToGoogleIndex.php   # Google Indexing API push
├── Services/
│   ├── OpenRouterService.php     # AI: articles, image prompts, AI images, trending
│   ├── GoogleTrendsService.php   # Google Trends RSS + AI fallback
│   ├── ImageService.php          # Unsplash + AI image pipeline
│   ├── GoogleIndexingService.php # Google Indexing API client
│   └── SEOService.php            # JSON-LD, OG tags, Twitter cards
├── Models/
│   ├── Article.php
│   ├── Category.php
│   ├── Setting.php
│   ├── AdPlacement.php
│   └── NewsGenerationLog.php
├── Http/Controllers/
│   ├── HomeController.php
│   ├── ArticleController.php
│   ├── CategoryController.php
│   └── SearchController.php
└── Filament/Resources/           # admin panel resources
routes/
├── web.php                       # public frontend routes
└── console.php                   # scheduler definitions
```

---

## Troubleshooting

**No articles generating:**
```bash
php artisan news:generate --topic="test topic"
# Check logs:
tail -f storage/logs/laravel.log
```

**Scheduler not running:**
```bash
php artisan schedule:list   # verify jobs are registered
php artisan schedule:run    # run manually once
```

**Config not updating after `.env` change:**
```bash
php artisan config:clear
```

**Sitemap not updating:**
```bash
php artisan news:update-sitemap
# Verify: public/sitemap.xml
```

---

## Cost Estimate (12 articles/day)

| Service | Usage | Est. Cost/month |
|---------|-------|----------------|
| OpenRouter TEXT_MODEL (gpt-4o-mini) | ~360 articles × ~2K tokens | ~$0.50 |
| OpenRouter IMAGE_MODEL (SDXL) | ~360 images (Unsplash misses only) | ~$1–3 |
| OpenRouter SEARCH_MODEL (gemini-2.5-flash) | fallback only, minimal | ~$0.10 |
| Unsplash API | free tier (50 req/hr) | $0 |
| Google Indexing API | free (200 req/day) | $0 |

**Total: ~$2–5/month** for fully automated 360 articles/month.

---

## License

MIT
