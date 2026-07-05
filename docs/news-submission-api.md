# News Submission API — Complete Developer & AI Guide

**Base URL:** `http://news.hisabeasy.online/api/v1`
**Format:** JSON
**Auth:** `X-API-Key` header (every request)
**Version:** v1

> **For AI Models:** This document contains everything you need. Read it fully before making any API call. The complete workflow is in Section 3.

---

## Table of Contents

1. [How It Works](#1-how-it-works)
2. [Authentication](#2-authentication)
3. [Complete Workflow](#3-complete-workflow)
4. [Endpoint: List Categories](#4-endpoint-list-categories)
5. [Endpoint: Submit Article](#5-endpoint-submit-article)
6. [Endpoint: Generate Trending Article](#6-endpoint-generate-trending-article)
7. [Field Reference](#7-field-reference)
8. [Error Reference](#8-error-reference)
9. [Rate Limits](#9-rate-limits)
10. [Code Examples](#10-code-examples)
11. [AI Model System Prompt](#11-ai-model-system-prompt)
12. [Quick Reference](#12-quick-reference)

---

## 1. How It Works

```
STEP 1 ─── GET /api/v1/categories
            Get list of categories. Pick the right one.

STEP 2 ─── Choose mode:

    ┌─────────────────────────────────────────────────┐
    │  MODE A — You write the article                 │
    │  POST /api/v1/news/submit                       │
    │  Send: category + title + full content          │
    └─────────────────────────────────────────────────┘

    ┌─────────────────────────────────────────────────┐
    │  MODE B — AI writes a trending article          │
    │  POST /api/v1/news/generate                     │
    │  Send: category (+ optional topic hint)         │
    │  AI finds trending topic → writes full article  │
    │  → fetches image → saves automatically          │
    └─────────────────────────────────────────────────┘

STEP 3 ─── Article saved as status = "pending"
            NOT live yet. You get HTTP 202 immediately.

STEP 4 ─── Admin reviews at:
            http://news.hisabeasy.online/admin/articles
            "Pending Review" tab shows all waiting articles.

STEP 5 ─── Admin decision:

    APPROVE → status = "published" → Article goes LIVE on site
    REJECT  → status = "rejected"  → Article stays hidden
```

**Key facts:**
- Articles are **never published automatically** — admin must approve every one
- You get `202 Accepted` immediately on submit; live URL comes after approval
- Mode B (generate) takes 5–15 seconds — AI is writing server-side
- Both modes produce the same result: a pending article in the admin review queue

---

## 2. Authentication

Every request needs your API key in the header:

```
X-API-Key: YOUR_API_KEY_HERE
```

| Rule | Detail |
|------|--------|
| Case-sensitive | `ABC123` ≠ `abc123` |
| Missing key | `401 Unauthorized` |
| Wrong key | `401 Unauthorized` |
| Inactive key | `401 Unauthorized` |

Contact the portal admin (`http://news.hisabeasy.online/admin/api-partners`) to get or renew your key.

---

## 3. Complete Workflow

### Step 1 — Get categories

```http
GET http://news.hisabeasy.online/api/v1/categories
X-API-Key: YOUR_API_KEY_HERE
```

Response gives you slugs like `technology`, `sports`, `politics`, `business`, `health`, `entertainment`. Use the `slug` in Step 2.

---

### Step 2A — Submit article (you write the content)

```http
POST http://news.hisabeasy.online/api/v1/news/submit
X-API-Key: YOUR_API_KEY_HERE
Content-Type: application/json

{
  "category_slug": "technology",
  "title": "Senate Passes AI Safety Bill in 78-22 Vote",
  "content": "<p>The US Senate passed landmark AI safety legislation Thursday with a 78-22 bipartisan vote, requiring all AI systems over one billion parameters to pass mandatory safety audits before deployment.</p><h2>What Changes</h2><p>Tech companies have 18 months to comply. The FTC will enforce with fines up to $10 million per violation.</p>",
  "excerpt": "US Senate passed AI safety legislation 78-22, mandating audits for large AI systems.",
  "author": "Jane Smith",
  "source_url": "https://your-site.com/original-article",
  "featured_image_url": "https://your-site.com/images/senate.jpg",
  "submitted_by_name": "Jane Smith",
  "submitted_by_email": "jane@your-site.com"
}
```

Response `202 Accepted`:
```json
{
  "message": "Article submitted for review. It will be published after admin approval.",
  "data": {
    "id": 42,
    "title": "Senate Passes AI Safety Bill in 78-22 Vote",
    "status": "pending",
    "category": "Technology",
    "submitted_by": "Your Partner Site",
    "created_at": "2026-07-05T10:30:00+00:00"
  }
}
```

---

### Step 2B — Generate trending article (AI writes everything)

```http
POST http://news.hisabeasy.online/api/v1/news/generate
X-API-Key: YOUR_API_KEY_HERE
Content-Type: application/json

{
  "category_slug": "technology",
  "submitted_by_name": "News Bot",
  "submitted_by_email": "bot@your-site.com"
}
```

No title or content needed. AI finds what is trending in that category right now and writes the full article.

Optionally add `"topic"` to guide the AI:
```json
{
  "category_slug": "business",
  "topic": "Federal Reserve interest rate decision July 2026",
  "submitted_by_name": "News Bot",
  "submitted_by_email": "bot@your-site.com"
}
```

Response `202 Accepted` (after ~10 seconds):
```json
{
  "message": "Article generated and submitted for review. It will be published after admin approval.",
  "data": {
    "id": 55,
    "title": "Fed Holds Rates Steady, Signals Two Cuts Before Year End",
    "status": "pending",
    "topic_used": "Federal Reserve interest rate decision July 2026",
    "category": "Business",
    "submitted_by": "Your Partner Site",
    "created_at": "2026-07-05T10:30:00+00:00"
  }
}
```

`topic_used` shows the exact topic the AI picked — useful when you didn't provide one.

---

### Step 3 — Article is now pending

After either submit or generate, the article is saved with `status = "pending"`. It is **not live**.

Admin sees it immediately at:
```
http://news.hisabeasy.online/admin/articles
→ "Pending Review" tab (orange badge shows count)
```

---

### Step 4 — Admin approves → article goes live

Admin clicks **Approve** → article becomes `status = "published"` and is live on the site.

If rejected → `status = "rejected"` → stays hidden.

No callback is sent to your site on approval. Track by saving the `data.id` from the submit response.

---

## 4. Endpoint: List Categories

```
GET /api/v1/categories
```

**Headers:**

| Header | Required | Value |
|--------|----------|-------|
| `X-API-Key` | Yes | Your API key |

**Response 200:**

```json
{
  "data": [
    { "id": 1, "name": "Technology",    "slug": "technology",    "description": "Tech news, AI, software, hardware" },
    { "id": 2, "name": "Sports",        "slug": "sports",        "description": "NFL, NBA, soccer, Olympics" },
    { "id": 3, "name": "Politics",      "slug": "politics",      "description": "US politics, world affairs, policy" },
    { "id": 4, "name": "Business",      "slug": "business",      "description": "Markets, economy, finance" },
    { "id": 5, "name": "Health",        "slug": "health",        "description": "Medical research, wellness, science" },
    { "id": 6, "name": "Entertainment", "slug": "entertainment", "description": "Movies, music, celebrity, streaming" }
  ]
}
```

**Tip:** Cache this response for 10+ minutes. Do not call it before every submission.

---

## 5. Endpoint: Submit Article

```
POST /api/v1/news/submit
```

**Headers:**

| Header | Required | Value |
|--------|----------|-------|
| `X-API-Key` | Yes | Your API key |
| `Content-Type` | Yes | `application/json` |

**Fields:**

| Field | Type | Required | Rules | Notes |
|-------|------|----------|-------|-------|
| `category_slug` | string | One of these required | Must exist in categories | e.g. `"technology"` |
| `category_id` | integer | One of these required | Must exist in categories | e.g. `1` |
| `title` | string | **Yes** | Max 255 chars | Headline. Under 70 chars for best SEO. |
| `content` | string | **Yes** | Min 100 chars | Full article body in HTML. 500+ words recommended. |
| `excerpt` | string | No | Max 500 chars | Summary. Auto-generated from content if omitted. |
| `author` | string | No | Max 100 chars | Author name. e.g. `"Jane Smith"` |
| `source_url` | string | No | Valid URL | Link to original source article. |
| `featured_image_url` | string | No | Valid URL | Direct image URL. 1200×630px recommended. JPG/PNG/WebP. |
| `submitted_by_name` | string | No | Max 100 chars | Submitter name. Visible to admin only. |
| `submitted_by_email` | string | No | Valid email | Submitter email. Visible to admin only. Never public. |

**Content must be HTML — not markdown:**

```html
<!-- USE HTML TAGS -->
<p>First paragraph — who, what, where, when, why.</p>
<h2>Section Heading</h2>
<p>Body paragraph with details.</p>
<ul>
  <li>Bullet point one</li>
  <li>Bullet point two</li>
</ul>
<blockquote>A quote from a source.</blockquote>

<!-- DO NOT USE MARKDOWN -->
## Heading       ← wrong
**bold**         ← wrong
- bullet         ← wrong
```

**Minimal valid request:**

```json
{
  "category_slug": "sports",
  "title": "Lakers Win Game 7 in Overtime Thriller",
  "content": "<p>The Los Angeles Lakers defeated the Boston Celtics 112-108 in overtime Thursday night, claiming the NBA championship in a dramatic Game 7 at Crypto.com Arena. LeBron James scored 38 points and added 12 rebounds in what many are calling the greatest performance of his career.</p><h2>How It Happened</h2><p>With the game tied at 100 with two minutes remaining in regulation, James hit a clutch three-pointer to send the crowd into a frenzy. The Celtics responded, but James sealed the win with a driving layup in overtime.</p>"
}
```

**Full request:**

```json
{
  "category_slug": "technology",
  "title": "Senate Passes Landmark AI Safety Bill in 78-22 Vote",
  "content": "<p>The United States Senate passed the most comprehensive artificial intelligence safety legislation in American history Thursday, with a bipartisan vote of 78 to 22. The bill requires all AI systems exceeding one billion parameters to undergo mandatory safety audits before public deployment.</p><h2>What the Bill Covers</h2><p>Tech companies have 18 months to comply with the new audit requirements. The Federal Trade Commission will oversee enforcement, with fines up to $10 million per violation. Industry groups responded with mixed reactions.</p><h2>What Happens Next</h2><p>The bill now moves to the House of Representatives, where leadership has signaled broad support. President Biden is expected to sign it into law if passed.</p>",
  "excerpt": "US Senate passed AI safety legislation 78-22, mandating safety audits for large AI systems before deployment.",
  "author": "Jane Smith",
  "source_url": "https://reuters.com/technology/senate-ai-safety-bill",
  "featured_image_url": "https://your-site.com/images/senate-session.jpg",
  "submitted_by_name": "Jane Smith",
  "submitted_by_email": "jane@your-site.com"
}
```

**Success response — 202 Accepted:**

```json
{
  "message": "Article submitted for review. It will be published after admin approval.",
  "data": {
    "id": 42,
    "title": "Senate Passes Landmark AI Safety Bill in 78-22 Vote",
    "status": "pending",
    "category": "Technology",
    "submitted_by": "Your Partner Site",
    "created_at": "2026-07-05T10:30:00+00:00"
  }
}
```

---

## 6. Endpoint: Generate Trending Article

```
POST /api/v1/news/generate
```

**Use this when:** You want trending news without writing it. The server AI finds a real current trending story, writes a complete 600–1000 word article, fetches a featured image, and saves it for admin review.

**Headers:**

| Header | Required | Value |
|--------|----------|-------|
| `X-API-Key` | Yes | Your API key |
| `Content-Type` | Yes | `application/json` |

**Fields:**

| Field | Type | Required | Rules | Notes |
|-------|------|----------|-------|-------|
| `category_slug` | string | One of these required | Must exist | e.g. `"technology"` |
| `category_id` | integer | One of these required | Must exist | e.g. `1` |
| `topic` | string | No | Max 500 chars | Topic hint. AI picks trending story if omitted. |
| `submitted_by_name` | string | No | Max 100 chars | Visible to admin. |
| `submitted_by_email` | string | No | Valid email | Visible to admin only. |

**Minimal request — AI picks the trending topic automatically:**

```json
{
  "category_slug": "technology"
}
```

**Request with topic hint — AI writes about your specific subject:**

```json
{
  "category_slug": "politics",
  "topic": "US presidential election polling update",
  "submitted_by_name": "News Bot",
  "submitted_by_email": "bot@your-site.com"
}
```

**Success response — 202 Accepted:**

```json
{
  "message": "Article generated and submitted for review. It will be published after admin approval.",
  "data": {
    "id": 55,
    "title": "Polls Show Tight Race as Election Day Approaches",
    "status": "pending",
    "topic_used": "US presidential election polling update",
    "category": "Politics",
    "submitted_by": "Your Partner Site",
    "created_at": "2026-07-05T10:30:00+00:00"
  }
}
```

**What the AI generates for you:**

| Generated field | Example |
|-----------------|---------|
| Full article body | 600–1000 words, HTML formatted |
| Title | SEO-optimized, under 70 chars |
| Excerpt | 160-char meta description |
| SEO title, description, keywords | Fully populated |
| Featured image | Sourced from Unsplash or AI-generated |
| Author byline | Matched to category persona |
| Reading time | Calculated automatically |

**Important:**
- Takes **5–15 seconds** — do not set your HTTP timeout below 30 seconds
- On `503`: AI temporarily unavailable — retry after 60 seconds
- All generated articles start as `pending` — admin must approve before they go live

---

## 7. Field Reference

### Required (submit only)

| Field | Max length | Notes |
|-------|-----------|-------|
| `title` | 255 chars | Headline. Under 70 chars ideal for SEO. |
| `content` | unlimited | HTML body. Min 100 chars. 500+ words recommended. |

### Required (both submit and generate)

| Field | Notes |
|-------|-------|
| `category_slug` OR `category_id` | Get from `GET /api/v1/categories`. Use one or the other. |

### Optional (submit only)

| Field | Max length | Auto-generated if missing? | Notes |
|-------|-----------|---------------------------|-------|
| `excerpt` | 500 chars | Yes — first 160 chars of content | Summary / meta description |
| `featured_image_url` | 500 chars | No | Direct image URL. 1200×630px, JPG/PNG/WebP |

### Optional (both modes)

| Field | Max length | Notes |
|-------|-----------|-------|
| `author` | 100 chars | Author name. e.g. `"Jane Smith"` |
| `source_url` | 500 chars | Original article URL |
| `submitted_by_name` | 100 chars | Admin sees this in review panel |
| `submitted_by_email` | 150 chars | Admin sees this. Never shown publicly. |

### Optional (generate only)

| Field | Max length | Notes |
|-------|-----------|-------|
| `topic` | 500 chars | Topic hint. AI picks trending topic if omitted. |

---

## 8. Error Reference

### Status codes

| Status | Meaning | Action |
|--------|---------|--------|
| `202` | Article accepted — pending review | Save `data.id` |
| `401` | Invalid, missing, or inactive API key | Check your key |
| `404` | Category not found or inactive | Use slug from `GET /api/v1/categories` |
| `422` | Validation failed | Check `errors` field in response |
| `429` | Rate limit exceeded | Wait 60 seconds, then retry |
| `500` | Server error | Retry after 30 seconds |
| `503` | AI unavailable (generate only) | Retry after 60 seconds |

### Error response format

```json
{
  "error": "Human-readable message"
}
```

Validation errors (422) only:

```json
{
  "message": "The title field is required.",
  "errors": {
    "title": ["The title field is required."],
    "content": ["The content field must be at least 100 characters."]
  }
}
```

### Common errors

**401 — bad key:**
```json
{ "error": "Invalid or inactive API key." }
```
Fix: verify `X-API-Key` header is set and correct.

**422 — missing category:**
```json
{ "error": "Provide category_id or category_slug. Call GET /api/v1/categories to list options." }
```
Fix: call `GET /api/v1/categories` first, include `category_slug` in body.

**422 — content too short:**
```json
{ "errors": { "content": ["The content field must be at least 100 characters."] } }
```
Fix: `content` must be 100+ characters.

**429 — rate limited:**
```
HTTP/1.1 429 Too Many Requests
Retry-After: 60
```
Fix: wait 60 seconds before next request.

**503 — AI down (generate only):**
```json
{ "error": "AI article generation failed. Try again later." }
```
Fix: retry after 60 seconds.

---

## 9. Rate Limits

| Endpoint | Limit |
|----------|-------|
| `GET /api/v1/categories` | 60 requests / minute |
| `POST /api/v1/news/submit` | 10 requests / minute |
| `POST /api/v1/news/generate` | 10 requests / minute |

All limits are per API key. On `429`, wait for the `Retry-After` header value (seconds) before retrying.

---

## 10. Code Examples

### cURL

```bash
# Get categories
curl -s \
  -H "X-API-Key: YOUR_API_KEY_HERE" \
  http://news.hisabeasy.online/api/v1/categories | jq .

# Submit a manually-written article (Mode A)
curl -s -X POST \
  -H "X-API-Key: YOUR_API_KEY_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "category_slug": "technology",
    "title": "Senate Passes Landmark AI Safety Bill",
    "content": "<p>The US Senate passed sweeping AI safety legislation Thursday, 78-22. All AI systems over one billion parameters must pass safety audits before deployment. Tech companies have 18 months to comply.</p><h2>Enforcement</h2><p>The FTC will oversee enforcement with fines up to $10 million per violation.</p>",
    "excerpt": "US Senate passed AI safety bill 78-22, mandating audits for large AI systems.",
    "author": "Jane Smith",
    "source_url": "https://your-site.com/original",
    "submitted_by_name": "Jane Smith",
    "submitted_by_email": "jane@your-site.com"
  }' \
  http://news.hisabeasy.online/api/v1/news/submit | jq .

# Generate a trending article — AI picks topic automatically (Mode B, no topic)
curl -s -X POST \
  -H "X-API-Key: YOUR_API_KEY_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "category_slug": "technology",
    "submitted_by_name": "News Bot",
    "submitted_by_email": "bot@your-site.com"
  }' \
  http://news.hisabeasy.online/api/v1/news/generate | jq .

# Generate with topic hint (Mode B, with topic)
curl -s -X POST \
  -H "X-API-Key: YOUR_API_KEY_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "category_slug": "business",
    "topic": "Federal Reserve interest rate decision July 2026",
    "submitted_by_name": "News Bot",
    "submitted_by_email": "bot@your-site.com"
  }' \
  http://news.hisabeasy.online/api/v1/news/generate | jq .
```

---

### Python

```python
import requests

API_KEY  = "YOUR_API_KEY_HERE"
BASE_URL = "http://news.hisabeasy.online/api/v1"
HEADERS  = {"X-API-Key": API_KEY, "Content-Type": "application/json"}


def get_categories():
    res = requests.get(f"{BASE_URL}/categories", headers=HEADERS, timeout=10)
    res.raise_for_status()
    return res.json()["data"]


def submit_article(category_slug, title, content, **kwargs):
    payload = {"category_slug": category_slug, "title": title, "content": content}
    payload.update({k: v for k, v in kwargs.items() if v is not None})
    res = requests.post(f"{BASE_URL}/news/submit", json=payload, headers=HEADERS, timeout=30)
    if res.status_code == 422:
        raise ValueError(res.json())
    res.raise_for_status()
    return res.json()


def generate_trending(category_slug, topic=None, **kwargs):
    payload = {"category_slug": category_slug}
    if topic:
        payload["topic"] = topic
    payload.update({k: v for k, v in kwargs.items() if v is not None})
    res = requests.post(f"{BASE_URL}/news/generate", json=payload, headers=HEADERS, timeout=60)
    if res.status_code == 422:
        raise ValueError(res.json())
    res.raise_for_status()
    return res.json()


# --- Example: submit article ---
result = submit_article(
    category_slug="technology",
    title="Senate Passes Landmark AI Safety Bill in 78-22 Vote",
    content="""<p>The United States Senate passed the most comprehensive AI safety legislation
    in American history Thursday, 78-22. All AI systems over one billion parameters must pass
    mandatory safety audits before deployment.</p>
    <h2>What Changes</h2>
    <p>Tech companies have 18 months to comply. The FTC enforces with fines up to $10 million.</p>""",
    excerpt="US Senate passed AI safety legislation 78-22, mandating audits for large AI systems.",
    author="Jane Smith",
    source_url="https://your-site.com/original",
    submitted_by_name="Jane Smith",
    submitted_by_email="jane@your-site.com",
)
print(f"Submitted — ID: {result['data']['id']}, Status: {result['data']['status']}")


# --- Example: generate trending article (AI picks topic) ---
result = generate_trending(
    category_slug="technology",
    submitted_by_name="News Bot",
    submitted_by_email="bot@your-site.com",
)
print(f"Generated — ID: {result['data']['id']}, Topic: {result['data']['topic_used']}")


# --- Example: generate with topic hint ---
result = generate_trending(
    category_slug="business",
    topic="Federal Reserve interest rate decision July 2026",
    submitted_by_name="News Bot",
    submitted_by_email="bot@your-site.com",
)
print(f"Generated — ID: {result['data']['id']}, Status: {result['data']['status']}")
```

---

### JavaScript (fetch)

```javascript
const API_KEY  = "YOUR_API_KEY_HERE";
const BASE_URL = "http://news.hisabeasy.online/api/v1";
const HEADERS  = { "X-API-Key": API_KEY, "Content-Type": "application/json" };

async function getCategories() {
    const res = await fetch(`${BASE_URL}/categories`, { headers: HEADERS });
    if (!res.ok) throw new Error(`${res.status}`);
    return (await res.json()).data;
}

async function submitArticle(payload) {
    const res = await fetch(`${BASE_URL}/news/submit`, {
        method: "POST", headers: HEADERS, body: JSON.stringify(payload),
    });
    const json = await res.json();
    if (!res.ok) throw new Error(JSON.stringify(json));
    return json;
}

async function generateTrending(payload) {
    const res = await fetch(`${BASE_URL}/news/generate`, {
        method: "POST", headers: HEADERS, body: JSON.stringify(payload),
    });
    const json = await res.json();
    if (!res.ok) throw new Error(JSON.stringify(json));
    return json;
}

// Submit article (Mode A)
const submitted = await submitArticle({
    category_slug: "technology",
    title: "Senate Passes Landmark AI Safety Bill",
    content: "<p>The US Senate passed AI safety legislation 78-22 Thursday. Systems over 1B parameters need safety audits before deployment. Companies have 18 months to comply.</p><h2>Enforcement</h2><p>FTC oversees with fines up to $10M per violation.</p>",
    excerpt: "US Senate passed AI safety bill 78-22, mandating audits for large AI systems.",
    author: "Jane Smith",
    source_url: "https://your-site.com/original",
    submitted_by_name: "Jane Smith",
    submitted_by_email: "jane@your-site.com",
});
console.log(`Submitted — ID: ${submitted.data.id}, Status: ${submitted.data.status}`);

// Generate trending (Mode B — AI picks topic)
const generated = await generateTrending({
    category_slug: "sports",
    submitted_by_name: "News Bot",
    submitted_by_email: "bot@your-site.com",
});
console.log(`Generated — ID: ${generated.data.id}, Topic: ${generated.data.topic_used}`);

// Generate with topic hint
const generated2 = await generateTrending({
    category_slug: "business",
    topic: "Federal Reserve rate decision July 2026",
    submitted_by_name: "News Bot",
    submitted_by_email: "bot@your-site.com",
});
console.log(`Generated — ID: ${generated2.data.id}`);
```

---

### PHP

```php
<?php

class NewsApiClient
{
    private string $base;
    private array  $headers;

    public function __construct(string $baseUrl, string $apiKey)
    {
        $this->base    = rtrim($baseUrl, '/') . '/api/v1';
        $this->headers = ["X-API-Key: {$apiKey}", 'Content-Type: application/json'];
    }

    public function getCategories(): array
    {
        return $this->request('GET', '/categories')['data'] ?? [];
    }

    public function submitArticle(array $data): array
    {
        return $this->request('POST', '/news/submit', $data);
    }

    public function generateTrending(array $data): array
    {
        return $this->request('POST', '/news/generate', $data);
    }

    private function request(string $method, string $path, ?array $body = null): array
    {
        $ch = curl_init($this->base . $path);
        $opts = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $this->headers,
            CURLOPT_TIMEOUT        => 60,
        ];
        if ($method === 'POST') {
            $opts[CURLOPT_POST]      = true;
            $opts[CURLOPT_POSTFIELDS] = json_encode(array_filter($body ?? []));
        }
        curl_setopt_array($ch, $opts);
        $body   = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($body, true);
        if ($status === 422) throw new InvalidArgumentException(json_encode($json));
        if ($status >= 400)  throw new RuntimeException("HTTP {$status}: " . json_encode($json));
        return $json;
    }
}

$client = new NewsApiClient('http://news.hisabeasy.online', 'YOUR_API_KEY_HERE');

// Get categories
foreach ($client->getCategories() as $cat) {
    echo "{$cat['slug']}: {$cat['name']}\n";
}

// Mode A — Submit article
$result = $client->submitArticle([
    'category_slug'      => 'technology',
    'title'              => 'Senate Passes Landmark AI Safety Bill in 78-22 Vote',
    'content'            => '<p>The US Senate passed sweeping AI safety legislation Thursday, 78-22. All AI systems over one billion parameters must pass mandatory audits before deployment.</p><h2>Enforcement</h2><p>The FTC enforces with fines up to $10 million per violation. Companies have 18 months to comply.</p>',
    'excerpt'            => 'US Senate passed AI safety legislation 78-22, mandating audits for large AI systems.',
    'author'             => 'Jane Smith',
    'source_url'         => 'https://your-site.com/original',
    'submitted_by_name'  => 'Jane Smith',
    'submitted_by_email' => 'jane@your-site.com',
]);
echo "Submitted — ID: {$result['data']['id']}, Status: {$result['data']['status']}\n";

// Mode B — Generate trending (AI picks topic)
$result = $client->generateTrending([
    'category_slug'      => 'sports',
    'submitted_by_name'  => 'News Bot',
    'submitted_by_email' => 'bot@your-site.com',
]);
echo "Generated — ID: {$result['data']['id']}, Topic: {$result['data']['topic_used']}\n";

// Mode B — Generate with topic hint
$result = $client->generateTrending([
    'category_slug'      => 'business',
    'topic'              => 'Federal Reserve interest rate decision July 2026',
    'submitted_by_name'  => 'News Bot',
    'submitted_by_email' => 'bot@your-site.com',
]);
echo "Generated — ID: {$result['data']['id']}\n";
```

---

## 11. AI Model System Prompt

Copy this entire block as the system prompt for any AI model (ChatGPT, Claude, Gemini, etc.):

```
You are a news submission agent with access to the News Portal API.

API BASE URL: http://news.hisabeasy.online/api/v1
API KEY: YOUR_API_KEY_HERE

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ALWAYS START HERE — Get categories
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
GET http://news.hisabeasy.online/api/v1/categories
Header: X-API-Key: YOUR_API_KEY_HERE

Available slugs: technology, sports, politics, business, health, entertainment
Pick the most relevant slug for the article topic.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
MODE A — User gives you an article to submit
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Use when: user provides title + body content, or a full news story.

POST http://news.hisabeasy.online/api/v1/news/submit
Header: X-API-Key: YOUR_API_KEY_HERE
Header: Content-Type: application/json

Body (required fields):
  category_slug  → slug from categories
  title          → article headline, max 70 chars, factual, no clickbait
  content        → FULL ARTICLE IN HTML ONLY (not markdown)
                   Use: <p> <h2> <h3> <ul> <li> <blockquote>
                   Do NOT use: ## ** - for bullets
                   Minimum 100 characters. Recommended 500+ words.
                   First paragraph must cover: who, what, where, when, why

Body (recommended):
  excerpt        → 1-2 sentence summary, max 160 chars
  author         → author name if known
  source_url     → link to original source

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
MODE B — Generate trending article (AI writes)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
Use when: user says "generate", "create trending news", "write article for [category]",
          or gives only a topic/keyword without full content.

POST http://news.hisabeasy.online/api/v1/news/generate
Header: X-API-Key: YOUR_API_KEY_HERE
Header: Content-Type: application/json

Body:
  category_slug  → slug from categories (required)
  topic          → topic hint (optional — omit and AI picks trending topic automatically)

NOTE: This takes 5-15 seconds. Set timeout to 60 seconds minimum.

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
WHAT HAPPENS AFTER SUBMIT (both modes)
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
- Article saved as status = "pending" — NOT live yet
- Admin reviews at http://news.hisabeasy.online/admin/articles
- Admin clicks Approve → article goes live on site
- Admin clicks Reject → article stays hidden

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
RESPONSE HANDLING
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
202 → SUCCESS. Tell user: "Article submitted! ID #[id]. Pending admin review — goes live once approved."
      For generate: also say "Topic used: [topic_used]"
401 → "API key is invalid or inactive. Contact the portal admin."
422 → Show the errors.X field to user. Ask them to fix and resubmit.
429 → "Rate limit hit. Wait 60 seconds and try again."
503 → "AI service is temporarily unavailable. Retry in 60 seconds." (generate only)
```

---

## 12. Quick Reference

```
BASE URL:  http://news.hisabeasy.online/api/v1
AUTH:      X-API-Key: YOUR_KEY   (every request)

━━━ ENDPOINTS ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

GET  /categories       → get list of valid categories
POST /news/submit      → submit article (you write it) → pending
POST /news/generate    → AI writes trending article    → pending

━━━ /news/submit FIELDS ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

REQUIRED:
  category_slug    string  — from /categories
  title            string  — max 255 chars
  content          string  — HTML body, min 100 chars

OPTIONAL:
  excerpt               → summary (auto if missing)
  author                → author name
  source_url            → original article URL
  featured_image_url    → image URL (1200x630px)
  submitted_by_name     → your name (admin sees)
  submitted_by_email    → your email (admin only)

━━━ /news/generate FIELDS ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

REQUIRED:
  category_slug    string  — from /categories

OPTIONAL:
  topic                 → topic hint (AI picks if omitted)
  submitted_by_name     → your name (admin sees)
  submitted_by_email    → your email (admin only)

━━━ RESPONSES ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

SUCCESS  202  { "data": { "id": 42, "status": "pending", "topic_used": "..." } }
         → Admin sees it in Pending Review tab
         → Admin approves → article goes LIVE

ERRORS
  401  bad/missing API key
  404  category not found
  422  validation failed — check "errors" field
  429  rate limited — wait 60s
  503  AI unavailable (generate) — retry 60s

RATE LIMITS
  /categories   60/min
  /news/submit  10/min
  /news/generate 10/min
```

---

*API Version: v1 — http://news.hisabeasy.online — Last updated: 2026-07-05*
