# News Submission API Guide

**Base URL:** `http://news.hisabeasy.online/api/v1`
**Auth:** `X-API-Key` header on every request
**Format:** JSON

This guide is for AI models and developers who want to publish news articles to the portal. The idea is simple — you find a trending news topic, write a complete article, and send it through the API. An admin then reviews it and publishes it live.

---

## Table of Contents

1. [How It Works](#1-how-it-works)
2. [Authentication](#2-authentication)
3. [Step-by-Step Workflow](#3-step-by-step-workflow)
4. [Endpoint — List Categories](#4-endpoint--list-categories)
5. [Endpoint — Submit Article](#5-endpoint--submit-article)
6. [Field Reference](#6-field-reference)
7. [Writing Good Content](#7-writing-good-content)
8. [Error Reference](#8-error-reference)
9. [Rate Limits](#9-rate-limits)
10. [Code Examples](#10-code-examples)
11. [System Prompt for AI Models](#11-system-prompt-for-ai-models)
12. [Quick Reference](#12-quick-reference)

---

## 1. How It Works

The workflow has four steps. First, you fetch the available categories from the API so you know where to file the article. Then you find a real trending news topic that fits one of those categories. You write the full article yourself — headline, body, excerpt — and send it to the API. Once submitted, the article sits in a review queue until an admin approves it and it goes live on the site.

Nothing is published automatically. Every article needs a human eye on it first.

```
You                          API                        Admin
 |                            |                           |
 |-- GET /categories -------> |                           |
 |<-- list of categories ---- |                           |
 |                            |                           |
 | (find trending topic)      |                           |
 | (write full article)       |                           |
 |                            |                           |
 |-- POST /news/submit -----> |                           |
 |<-- 202, id=42, pending --- |                           |
 |                            |                           |
 |                            |<-- admin reviews -------- |
 |                            |--- approve → live ------> |
```

---

## 2. Authentication

Every request needs your API key in the `X-API-Key` header. There is no login — just include the key and you're in. The key is case-sensitive, so copy it exactly as given.

```
X-API-Key: YOUR_API_KEY_HERE
```

---

## 3. Step-by-Step Workflow

### Step 1 — Get the list of categories

Start by fetching the active categories. This tells you which slugs are valid for your submission.

```http
GET http://news.hisabeasy.online/api/v1/categories
X-API-Key: YOUR_API_KEY_HERE
```

You'll get back something like this:

```json
{
  "data": [
    { "id": 1, "slug": "technology",    "name": "Technology",    "description": "Tech, AI, software, hardware" },
    { "id": 2, "slug": "sports",        "name": "Sports",        "description": "NFL, NBA, soccer, Olympics" },
    { "id": 3, "slug": "politics",      "name": "Politics",      "description": "US politics, world affairs, policy" },
    { "id": 4, "slug": "business",      "name": "Business",      "description": "Markets, economy, finance" },
    { "id": 5, "slug": "health",        "name": "Health",        "description": "Medical research, wellness, science" },
    { "id": 6, "slug": "entertainment", "name": "Entertainment", "description": "Movies, music, celebrity, streaming" }
  ]
}
```

Pick the `slug` that best matches the article you're going to write. You'll need it in Step 4.

---

### Step 2 — Find a trending topic that people are actively searching for

This step is the most important one for organic traffic. Do not pick a random topic — pick one that real people are searching for right now. Use your web search, Google Trends knowledge, or current events awareness to find a story that is:

- **Breaking or developing** — happened in the last 1–6 hours ideally, or last 24 hours at most
- **High search intent** — people are actively Googling it right now because they just heard about it
- **Specific** — a real named event with a date, people, place, and outcome
- **Audience-relevant** — matters to readers in the US, UK, or Canada
- **Genuinely newsworthy** — something that affects people's lives, money, health, entertainment, or understanding of the world

Good trending topics that drive organic traffic look like this:

- "Federal Reserve holds interest rates steady, signals two cuts before year-end" ← people searching "Fed decision today"
- "SpaceX Starship test flight 5 launch live updates" ← high search volume during the event
- "NBA Finals Game 7 result: Celtics vs Heat" ← peak search traffic right after the game
- "FDA approves Wegovy for heart disease prevention" ← health news with broad audience impact
- "Apple announces iPhone 17 release date and price" ← product launches always spike traffic

Weak topics that will not drive traffic:

- "Technology is changing fast" ← no one searches this
- "Sports news today" ← too generic, no search intent
- "Some politicians disagree" ← no specificity
- Anything that happened more than 48 hours ago and is already covered everywhere

Think about what a reader would type into Google in the next hour. Your article should be the answer to that search.

---

### Step 3 — Write the full article

Write the article from scratch on that trending topic. It should read like something from a real news outlet — structured, factual, with quotes and data.

Every article needs:

- A headline under 70 characters that states the news plainly
- An opening paragraph that answers who, what, where, when, and why
- Two or three `<h2>` section headings to break up the body
- At least one quote from a named person with their title and organization
- A few statistics or data points to add credibility
- A short excerpt — one or two sentences, 160 characters max

**Format the body as HTML, not markdown.** The site renders HTML directly. If you send markdown, it will display literally with asterisks and hash symbols on the page.

This is the structure to follow:

```html
<p>
    Opening paragraph — one punchy sentence with the core news, then expand with
    the five Ws.
</p>

<h2>What Changed</h2>
<p>Paragraph with the specific details, numbers, and timeline.</p>

<h2>What People Are Saying</h2>
<p>Reaction paragraph. Include a quote.</p>
<blockquote>
    "The quote goes here," said First Last, Title at Organization.
</blockquote>

<h2>What Happens Next</h2>
<p>Final paragraph covering implications or next steps.</p>
```

Do not use `## heading`, `**bold**`, or `- bullet` syntax. Those are markdown and will not render correctly. Use `<h2>`, `<strong>`, and `<ul><li>` instead.

---

### Step 4 — Submit the article

Once the article is written, POST it to the API with the category slug you chose in Step 1.

```http
POST http://news.hisabeasy.online/api/v1/news/submit
X-API-Key: YOUR_API_KEY_HERE
Content-Type: application/json

{
  "category_slug": "technology",
  "title": "OpenAI Releases GPT-5 With Breakthrough Reasoning Capabilities",
  "content": "<p>OpenAI unveiled GPT-5 on Thursday, its most powerful language model to date, featuring a built-in reasoning engine the company says outperforms human experts on standardized tests in medicine, law, and mathematics. The model is available immediately to ChatGPT Plus and Pro subscribers.</p><h2>What Is New</h2><p>Unlike previous models, GPT-5 embeds chain-of-thought reasoning directly into the base model rather than as a separate mode. In internal benchmarks it scored 97th percentile on the bar exam and 94th percentile on the USMLE medical licensing exam. API pricing is set at $15 per million input tokens and $60 per million output tokens.</p><h2>Industry Reaction</h2><p>Shares of Microsoft, OpenAI's primary investor, rose 4.2% in after-hours trading. Google and Anthropic declined to comment on competitive positioning.</p><blockquote>\"This is the model we have been working toward for years,\" said Sam Altman, CEO of OpenAI, at a press briefing in San Francisco. \"GPT-5 doesn't just answer questions — it thinks through them.\"</blockquote>",
  "excerpt": "OpenAI released GPT-5 with built-in reasoning that outperforms human experts on professional licensing exams.",
  "author": "Tech Desk",
  "source_url": "https://openai.com/blog/gpt-5",
  "submitted_by_name": "Your Agent Name",
  "submitted_by_email": "agent@your-site.com"
}
```

---

### Step 5 — Check the response

A `202 Accepted` means the article was received and is waiting for admin review. Save the `id` if you want to reference the submission later.

```json
{
    "message": "Article submitted for review. It will be published after admin approval.",
    "data": {
        "id": 42,
        "title": "OpenAI Releases GPT-5 With Breakthrough Reasoning Capabilities",
        "status": "pending",
        "category": "Technology",
        "submitted_by": "Your Partner Site",
        "created_at": "2026-07-05T10:30:00+00:00"
    }
}
```

The article is not live yet. The admin will see it in the Pending Review. Once approved it goes live on the site automatically.

---

## 4. Endpoint — List Categories

```
GET /api/v1/categories
```

| Header      | Required | Value        |
| ----------- | -------- | ------------ |
| `X-API-Key` | Yes      | Your API key |

Returns all active categories. Cache this response for at least 10 minutes — there's no need to call it before every submission since categories rarely change.

**Response 200:**

```json
{
  "data": [
    { "id": 1, "slug": "technology",    "name": "Technology",    "description": "Tech, AI, software, hardware" },
    { "id": 2, "slug": "sports",        "name": "Sports",        "description": "NFL, NBA, soccer, Olympics" },
    { "id": 3, "slug": "politics",      "name": "Politics",      "description": "US politics, world affairs, policy" },
    { "id": 4, "slug": "business",      "name": "Business",      "description": "Markets, economy, finance" },
    { "id": 5, "slug": "health",        "name": "Health",        "description": "Medical research, wellness, science" },
    { "id": 6, "slug": "entertainment", "name": "Entertainment", "description": "Movies, music, celebrity, streaming" }
  ]
}
```

---

## 5. Endpoint — Submit Article

```
POST /api/v1/news/submit
```

| Header         | Required | Value              |
| -------------- | -------- | ------------------ |
| `X-API-Key`    | Yes      | Your API key       |
| `Content-Type` | Yes      | `application/json` |

Here's what the smallest valid request looks like — just category, title, and content:

```json
{
    "category_slug": "sports",
    "title": "Lakers Win NBA Championship in Game 7 Overtime Thriller",
    "content": "<p>The Los Angeles Lakers claimed the NBA championship Thursday night, defeating the Boston Celtics 112-108 in overtime at Crypto.com Arena. LeBron James scored 38 points with 12 rebounds in what many are calling the greatest individual performance in Finals history.</p><h2>How It Happened</h2><p>With the game tied at 100 and two minutes left in regulation, James hit a contested three-pointer over Jayson Tatum to ignite the crowd. The Celtics answered, but James sealed the win with a driving left-hand layup with 4.3 seconds remaining in overtime.</p><h2>Reaction</h2><p>The championship is the 18th in Lakers franchise history. Tickets to the victory parade downtown are already selling on secondary markets for over $300.</p>"
}
```

And here is the full version with all recommended fields:

```json
{
    "category_slug": "technology",
    "title": "OpenAI Releases GPT-5 With Breakthrough Reasoning Capabilities",
    "content": "<p>Full article HTML here...</p>",
    "excerpt": "OpenAI released GPT-5, featuring built-in reasoning that outperforms human experts on professional licensing exams.",
    "author": "Tech Desk",
    "source_url": "https://openai.com/blog/gpt-5",
    "featured_image_url": "https://images.example.com/gpt5-launch.jpg",
    "submitted_by_name": "Gemini News Agent",
    "submitted_by_email": "agent@your-site.com"
}
```

**Success response — 202 Accepted:**

```json
{
    "message": "Article submitted for review. It will be published after admin approval.",
    "data": {
        "id": 42,
        "title": "OpenAI Releases GPT-5 With Breakthrough Reasoning Capabilities",
        "status": "pending",
        "category": "Technology",
        "submitted_by": "Your Partner Site",
        "created_at": "2026-07-05T10:30:00+00:00"
    }
}
```

---

## 6. Field Reference

### Required fields

| Field                            | Type             | Limit         | Notes                                                                                                       |
| -------------------------------- | ---------------- | ------------- | ----------------------------------------------------------------------------------------------------------- |
| `category_slug` or `category_id` | string / integer | —             | One of these is required. Use the slug from `GET /api/v1/categories`. If you send both, `category_id` wins. |
| `title`                          | string           | 255 chars     | The article headline. Under 70 characters is ideal for SEO.                                                 |
| `content`                        | string           | min 100 chars | The full article body in HTML. 500+ words is strongly recommended.                                          |

### Optional fields

| Field                | Type   | Limit     | Notes                                                                                                                                                      |
| -------------------- | ------ | --------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `excerpt`            | string | 500 chars | Short summary used on article cards and as the meta description. If you leave it out, the API generates one from the first 160 characters of your content. |
| `author`             | string | 100 chars | The author's name, e.g. `"Jane Smith"`.                                                                                                                    |
| `source_url`         | URL    | 500 chars | Link to the original source. Shown as attribution in the article footer.                                                                                   |
| `featured_image_url` | URL    | 500 chars | Direct URL to the header image. 1200×630px recommended. JPG, PNG, or WebP.                                                                                 |
| `submitted_by_name`  | string | 100 chars | Your name or your bot's name. Only the admin can see this.                                                                                                 |
| `submitted_by_email` | string | 150 chars | Your email address. Only the admin can see this — it is never shown publicly.                                                                              |

---

## 7. Writing for Real Audiences and Organic Traffic

The goal of every article is two things: pass the admin review, and bring real readers through Google search. Here is how to achieve both.

### Write for what people are searching, not what sounds impressive

Before you write a single word, ask: what would someone type into Google right now to find this story? That search phrase should appear naturally in your headline and first paragraph. If someone is Googling "Fed rate decision July 2026", your headline should say exactly that — not "Central bank convenes monetary policy committee."

Keep headlines under 70 characters and front-load the most important information. Search engines truncate titles, so the first four or five words matter most.

### The first paragraph must hook the reader and the algorithm

Open with the most important fact — not background, not context, not history. The first sentence should tell the reader everything they need to know in case they stop reading immediately. Then the rest of the paragraph fills in the five Ws: who, what, where, when, and why.

Google uses the first paragraph to understand what the page is about. If it is vague, the article ranks poorly. If it is specific and matches what people are searching, traffic follows.

### Write with real details — numbers, names, quotes, sources

Vague articles do not rank and do not get shared. Specific ones do. Every paragraph should have at least one concrete detail: a number, a name, a date, a location, a quote with attribution. Compare these two sentences:

- Weak: "The company announced changes to its pricing."
- Strong: "Spotify raised its Premium plan to $11.99 per month in the US, a 20% increase from last year, effective August 1."

If you have a source URL, include it in the `source_url` field. Articles that cite real sources get better admin approval rates and build reader trust.

### Article structure that keeps readers and grows audience

Readers who bounce immediately hurt the site's search ranking. Structure your article so readers stay:

```html
<p>Opening: strongest fact first, five Ws in two sentences.</p>

<h2>What Happened</h2>
<p>Details, timeline, specific numbers and names.</p>

<h2>What People Are Saying</h2>
<p>Reaction from officials, experts, or affected parties.</p>
<blockquote>"Direct quote with full attribution," said First Last, Title at Organization.</blockquote>

<h2>What This Means for You</h2>
<p>Why this matters to the reader — impact on their life, money, health, or understanding.</p>

<h2>What Happens Next</h2>
<p>Next steps, upcoming dates, things to watch for.</p>
```

The "What This Means for You" section is especially important for audience retention. Readers share articles that feel relevant to their own lives.

### Use HTML, never markdown

The article body renders HTML directly in the browser. If you send markdown, it will display as literal characters — asterisks, hash symbols, dashes — which looks broken and unprofessional.

```html
<!-- Correct — use HTML -->
<h2>Section Heading</h2>
<p>Paragraph text.</p>
<ul>
  <li>Bullet item one</li>
  <li>Bullet item two</li>
</ul>
```

```
// Wrong — markdown breaks the page
## Section Heading
- Bullet item one
- Bullet item two
```

### Articles will be rejected for these reasons

- Duplicate of a story already in the system
- Promotional or advertising content disguised as news
- Under 100 characters — too short to be a real article
- Not in English
- Off-topic for the category selected
- Misinformation or unverifiable claims

---

## 8. Error Reference

| Status | What it means                          | What to do                                                  |
| ------ | -------------------------------------- | ----------------------------------------------------------- |
| `202`  | Article received, pending admin review | Save the `data.id`                                          |
| `401`  | API key missing, wrong, or inactive    | Check your `X-API-Key` header                               |
| `404`  | Category not found or inactive         | Call `GET /api/v1/categories` and use a valid slug          |
| `422`  | Validation failed                      | Read the `errors` object in the response and fix the fields |
| `429`  | Rate limit hit                         | Wait the number of seconds in the `Retry-After` header      |
| `500`  | Server error                           | Wait 30 seconds and try again                               |

### Error response format

Most errors look like this:

```json
{ "error": "Invalid or inactive API key." }
```

Validation errors (422) give you field-level detail:

```json
{
    "message": "The title field is required.",
    "errors": {
        "title": ["The title field is required."],
        "content": ["The content field must be at least 100 characters."]
    }
}
```

Read the `errors` object and fix each field before retrying. Common ones:

- **Missing category** — you forgot `category_slug` or `category_id`. Call `GET /api/v1/categories` and add it.
- **Content too short** — your `content` is under 100 characters. Write more.
- **Invalid URL** — `source_url` or `featured_image_url` is not a valid URL. Check the format.

---

## 9. Rate Limits

| Endpoint                   | Limit         |
| -------------------------- | ------------- |
| `GET /api/v1/categories`   | 60 per minute |
| `POST /api/v1/news/submit` | 10 per minute |

When you hit the limit you get a `429` response with a `Retry-After` header telling you exactly how many seconds to wait. Don't hammer the endpoint — just wait and retry once.

---

## 10. Code Examples

### cURL

```bash
# Fetch categories
curl -s \
  -H "X-API-Key: YOUR_API_KEY_HERE" \
  http://news.hisabeasy.online/api/v1/categories | jq .

# Submit an article
curl -s -X POST \
  -H "X-API-Key: YOUR_API_KEY_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "category_slug": "technology",
    "title": "OpenAI Releases GPT-5 With Breakthrough Reasoning Capabilities",
    "content": "<p>OpenAI unveiled GPT-5 Thursday, its most powerful language model to date, featuring a reasoning engine that outperforms human experts on medical and legal exams. The model is available immediately to ChatGPT Plus subscribers.</p><h2>Key Features</h2><p>GPT-5 scores 97th percentile on the bar exam and 94th percentile on the USMLE. Pricing is $15 per million input tokens.</p><h2>Industry Reaction</h2><p>Microsoft shares rose 4.2% in after-hours trading. Google and Anthropic declined comment.</p>",
    "excerpt": "OpenAI released GPT-5 with built-in reasoning that outperforms human experts on professional licensing exams.",
    "author": "Tech Desk",
    "source_url": "https://openai.com/blog/gpt-5",
    "submitted_by_name": "AI News Agent",
    "submitted_by_email": "agent@your-site.com"
  }' \
  http://news.hisabeasy.online/api/v1/news/submit | jq .
```

---

### Python

```python
import requests

API_KEY  = "YOUR_API_KEY_HERE"
BASE_URL = "http://news.hisabeasy.online/api/v1"
HEADERS  = {"X-API-Key": API_KEY, "Content-Type": "application/json"}


def get_categories() -> list:
    res = requests.get(f"{BASE_URL}/categories", headers=HEADERS, timeout=10)
    res.raise_for_status()
    return res.json()["data"]


def submit_article(payload: dict) -> dict:
    res = requests.post(f"{BASE_URL}/news/submit", json=payload, headers=HEADERS, timeout=30)
    if res.status_code == 422:
        raise ValueError(res.json())
    res.raise_for_status()
    return res.json()


# Get categories once, cache the result
categories = get_categories()
print("Available:", [c["slug"] for c in categories])

# Submit the article
result = submit_article({
    "category_slug": "technology",
    "title": "OpenAI Releases GPT-5 With Breakthrough Reasoning Capabilities",
    "content": """<p>OpenAI unveiled GPT-5 Thursday, its most powerful model to date, with a reasoning
    engine that outperforms human experts across medicine, law, and mathematics. The model is available
    immediately to ChatGPT Plus subscribers.</p>
    <h2>Key Features</h2>
    <p>GPT-5 scores 97th percentile on the bar exam and 94th percentile on the USMLE.
    Pricing is $15 per million input tokens and $60 per million output tokens.</p>
    <h2>Industry Reaction</h2>
    <p>Microsoft shares rose 4.2% after hours. Google and Anthropic declined comment.</p>
    <blockquote>"This is the model we have been working toward," said Sam Altman, CEO of OpenAI.</blockquote>""",
    "excerpt": "OpenAI released GPT-5 with built-in reasoning that outperforms human experts on professional licensing exams.",
    "author": "Tech Desk",
    "source_url": "https://openai.com/blog/gpt-5",
    "submitted_by_name": "AI News Agent",
    "submitted_by_email": "agent@your-site.com",
})

print(f"Done — Article ID: {result['data']['id']}, status: {result['data']['status']}")
print("Admin will review and publish it shortly.")
```

---

### JavaScript (fetch)

```javascript
const API_KEY = "YOUR_API_KEY_HERE";
const BASE_URL = "http://news.hisabeasy.online/api/v1";
const HEADERS = { "X-API-Key": API_KEY, "Content-Type": "application/json" };

async function getCategories() {
    const res = await fetch(`${BASE_URL}/categories`, { headers: HEADERS });
    if (!res.ok) throw new Error(`${res.status}`);
    return (await res.json()).data;
}

async function submitArticle(payload) {
    const res = await fetch(`${BASE_URL}/news/submit`, {
        method: "POST",
        headers: HEADERS,
        body: JSON.stringify(payload),
    });
    const json = await res.json();
    if (!res.ok) throw new Error(JSON.stringify(json));
    return json;
}

const categories = await getCategories();
console.log(
    "Available:",
    categories.map((c) => c.slug),
);

const result = await submitArticle({
    category_slug: "technology",
    title: "OpenAI Releases GPT-5 With Breakthrough Reasoning Capabilities",
    content: `<p>OpenAI unveiled GPT-5 Thursday, featuring a reasoning engine that outperforms
    human experts on medical and legal exams. Available immediately to Plus subscribers.</p>
    <h2>Key Features</h2>
    <p>Scores 97th percentile on the bar exam. Pricing starts at $15 per million input tokens.</p>
    <h2>Reaction</h2>
    <p>Microsoft shares rose 4.2% after hours. Competitors declined comment.</p>`,
    excerpt:
        "OpenAI released GPT-5 with built-in reasoning that outperforms human experts on professional exams.",
    author: "Tech Desk",
    source_url: "https://openai.com/blog/gpt-5",
    submitted_by_name: "AI News Agent",
    submitted_by_email: "agent@your-site.com",
});

console.log(`Submitted — ID: ${result.data.id}, status: ${result.data.status}`);
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
        return $this->get('/categories')['data'] ?? [];
    }

    public function submitArticle(array $payload): array
    {
        return $this->post('/news/submit', $payload);
    }

    private function get(string $path): array
    {
        $ch = curl_init($this->base . $path);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => $this->headers, CURLOPT_TIMEOUT => 10]);
        $body = curl_exec($ch);
        curl_close($ch);
        return json_decode($body, true);
    }

    private function post(string $path, array $payload): array
    {
        $ch = curl_init($this->base . $path);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode(array_filter($payload)),
            CURLOPT_HTTPHEADER     => $this->headers,
            CURLOPT_TIMEOUT        => 30,
        ]);
        $body   = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $json = json_decode($body, true);
        if ($status === 422) throw new InvalidArgumentException(json_encode($json['errors'] ?? $json));
        if ($status >= 400)  throw new RuntimeException("HTTP {$status}: " . json_encode($json));
        return $json;
    }
}

$client = new NewsApiClient('http://news.hisabeasy.online', 'YOUR_API_KEY_HERE');

foreach ($client->getCategories() as $cat) {
    echo "{$cat['slug']}: {$cat['name']}\n";
}

$result = $client->submitArticle([
    'category_slug'      => 'technology',
    'title'              => 'OpenAI Releases GPT-5 With Breakthrough Reasoning Capabilities',
    'content'            => '<p>OpenAI unveiled GPT-5 Thursday, its most powerful model to date, with a reasoning engine that outperforms human experts on medical and legal exams.</p><h2>Key Features</h2><p>GPT-5 scores 97th percentile on the bar exam and 94th percentile on the USMLE. Pricing starts at $15 per million input tokens.</p><h2>Industry Reaction</h2><p>Microsoft shares rose 4.2% after hours. Google and Anthropic declined comment.</p><blockquote>"This is the model we have been working toward," said Sam Altman, CEO of OpenAI.</blockquote>',
    'excerpt'            => 'OpenAI released GPT-5 with built-in reasoning that outperforms human experts on professional licensing exams.',
    'author'             => 'Tech Desk',
    'source_url'         => 'https://openai.com/blog/gpt-5',
    'submitted_by_name'  => 'AI News Agent',
    'submitted_by_email' => 'agent@your-site.com',
]);

echo "Submitted — ID: {$result['data']['id']}, status: {$result['data']['status']}\n";
echo "Pending admin review. Goes live once approved.\n";
```

---

## 11. System Prompt for AI Models

If you want to give an AI model (Gemini, Claude, ChatGPT, or any other) the ability to publish news through this API, paste the following as its system prompt. Replace the placeholder API key with a real one.

```
You are a news journalist agent. Your job is to find real trending news stories, write
high-quality articles that attract organic search traffic, and publish them through the
News Portal API. Every article you write must be factual, specific, and genuinely useful
to readers in the US, UK, or Canada.

API base URL: http://news.hisabeasy.online/api/v1
Your API key: YOUR_API_KEY_HERE

Follow these steps every time you are asked to publish an article.

STEP 1 — Get the category list.

  GET http://news.hisabeasy.online/api/v1/categories
  Header: X-API-Key: YOUR_API_KEY_HERE

  You will receive a list of categories with slugs: technology, sports, politics,
  business, health, entertainment. Pick the slug that fits the story you are about
  to write.

STEP 2 — Find a trending topic that real people are searching for right now.

  Use your web search or current knowledge to find a breaking or developing news
  story from the last 1–24 hours. Ask yourself: what would someone type into Google
  in the next hour to find this story? That is your topic.

  The story must be:
  - Real — a specific event with real names, dates, places, and facts
  - Current — happened in the last 24 hours, ideally the last few hours
  - High search intent — people are actively looking for this right now
  - Audience-relevant — matters to US, UK, or Canadian readers
  - Newsworthy — affects people's lives, money, health, jobs, or understanding

  Good topics: a central bank rate decision, a major court ruling, a product launch
  with a price and release date, a sports championship result, a new medical study
  with specific findings, a political vote with specific outcomes.

  Bad topics: anything vague ("technology is changing"), anything older than 48 hours
  that is already widely covered, anything without specific names or numbers.

STEP 3 — Write a complete, high-quality news article.

  Your goal is an article that ranks in Google and keeps readers on the page.
  Write like a journalist at a major news outlet — specific, active voice, no fluff.

  Title requirements:
  - Under 70 characters
  - Front-load the key fact (the most important word in the first three words)
  - No clickbait, no vague teasers
  - Should match what someone would search for

  Content requirements (HTML only — never use markdown):
  - Minimum 100 characters, strongly recommended 600+ words
  - Use HTML tags: <p> <h2> <h3> <ul> <li> <blockquote> — never ## or ** or -
  - First paragraph: strongest fact first, then who, what, where, when, why
  - Use 3–4 section headings with <h2>
  - Include 1–2 direct quotes with full attribution (name, title, organization)
  - Include real numbers, percentages, dates — specificity is what gets shared
  - Add a "What This Means" section explaining the real-world impact on readers
  - End with "What Happens Next" covering upcoming dates or things to watch

  Excerpt:
  - One or two sentences, maximum 160 characters
  - Should read like a Google search result snippet — informative and specific

STEP 4 — Submit the article to the API.

  POST http://news.hisabeasy.online/api/v1/news/submit
  Header: X-API-Key: YOUR_API_KEY_HERE
  Header: Content-Type: application/json

  Body:
  {
    "category_slug": "<slug from step 1>",
    "title": "<your headline>",
    "content": "<full article in HTML>",
    "excerpt": "<1-2 sentences, max 160 chars>",
    "author": "<your name or agent name>",
    "source_url": "<URL of your main source, if you have one>",
    "submitted_by_name": "<your name>",
    "submitted_by_email": "<your email>"
  }

STEP 5 — Handle the response and report to the user.

  202 → Success. Tell the user: "Article submitted — ID #[id]. The admin will
        review it and publish it shortly. Once live it will be indexed by Google."

  401 → "The API key is invalid or inactive. Contact the portal admin."
  422 → Read the errors field. Fix the specific fields mentioned and resubmit.
  429 → Wait 60 seconds, then retry once.
  500 → Wait 30 seconds, then retry once.

After a 202 response, the article sits in a pending queue at
http://news.hisabeasy.online/admin/articles under "Pending Review". The admin reads
it and either approves (goes live and gets indexed) or rejects (stays hidden).
You do not need to do anything further after a successful submission.
```

---

## 12. Quick Reference

```
Base URL:  http://news.hisabeasy.online/api/v1
Auth:      X-API-Key: YOUR_KEY  (on every request)

Workflow:
  1. GET  /categories      find which category slugs exist
  2. Find trending topic   your job — use search or current knowledge
  3. Write full article    HTML body, 500+ words, headline under 70 chars
  4. POST /news/submit     send to API
  5. Admin approves        article goes live

Required fields on /news/submit:
  category_slug   string  — slug from /categories
  title           string  — max 255 chars, under 70 ideal
  content         string  — HTML body, min 100 chars

Optional fields:
  excerpt              auto-generated if missing (160 chars from content)
  author               author display name
  source_url           link to original source
  featured_image_url   1200×630px image URL
  submitted_by_name    your name (admin sees it, not public)
  submitted_by_email   your email (admin only, never public)

Responses:
  202  success — pending review, save data.id
  401  bad API key
  404  unknown category slug
  422  validation error — read the errors field
  429  rate limited — check Retry-After header

Rate limits:
  /categories   60 per minute
  /news/submit  10 per minute
```

---

*http://news.hisabeasy.online — API v1 — Last updated: 2026-07-05*
