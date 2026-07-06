# News Submission API

**Base URL:** `http://news.hisabeasy.online/api/v1`  
**Auth:** `X-API-Key: YOUR_KEY` header on every request  
**Format:** JSON  
**API Key (live):** `E2nTzn5UABCOpubEe93dz7nncdF5PGoHIu9rMIZNvGHivT3wMiLvzYozGZikeQ6M`

---

## Endpoints

| Method | Path           | Description                     |
| ------ | -------------- | ------------------------------- |
| `GET`  | `/categories`  | List active categories          |
| `POST` | `/news/submit` | Submit article for admin review |

---

## GET /categories

```http
GET /api/v1/categories
X-API-Key: YOUR_KEY
```

**Curl:**

```bash
curl -s http://news.hisabeasy.online/api/v1/categories \
  -H "X-API-Key: E2nTzn5UABCOpubEe93dz7nncdF5PGoHIu9rMIZNvGHivT3wMiLvzYozGZikeQ6M"
```

---

## POST /news/submit

### Fields

**Required:**

| Field                            | Limit         | Notes                                                       |
| -------------------------------- | ------------- | ----------------------------------------------------------- |
| `category_slug` or `category_id` | —             | Slug from `/categories`. If both sent, `category_id` wins.  |
| `title`                          | 255 chars     | Headline. Under 70 chars ideal for SEO.                     |
| `content`                        | min 100 chars | Full article body in **HTML only**. 600+ words recommended. |

**Required for quality** (treat as required — without these, articles rank poorly and don't appear in Google Discover or social previews):

| Field                | Limit     | Notes                                                                                      |
| -------------------- | --------- | ------------------------------------------------------------------------------------------ |
| `featured_image_url` | 500 chars | Direct image URL, min 1200×630px. Powers OG tags, Twitter cards, Google Discover, JSON-LD. |
| `excerpt`            | 500 chars | 1–2 sentences, 160 chars max. Auto-generated from content if omitted.                      |

**SEO fields** (fill all three):

| Field             | Limit     | Notes                                                        |
| ----------------- | --------- | ------------------------------------------------------------ |
| `seo_title`       | 70 chars  | Overrides `<title>` tag. Include primary keyword.            |
| `seo_description` | 160 chars | Overrides meta description. Answers the searcher's question. |
| `seo_keywords`    | 500 chars | 3–5 comma-separated search phrases with year/date.           |

**Optional:**

| Field                | Limit     | Notes                                          |
| -------------------- | --------- | ---------------------------------------------- |
| `author`             | 100 chars | Author display name, e.g. `"Business Desk"`    |
| `source_url`         | 500 chars | Link to original source. Shown as attribution. |
| `submitted_by_name`  | 100 chars | Admin-only. Never shown publicly.              |
| `submitted_by_email` | 150 chars | Admin-only. Never shown publicly.              |

---

## Response Codes

| Status | Meaning                   | Fix                                    |
| ------ | ------------------------- | -------------------------------------- |
| `202`  | Submitted, pending review | Save `data.id`                         |
| `401`  | Bad or inactive API key   | Check `X-API-Key` header               |
| `404`  | Category not found        | Use a valid slug from `/categories`    |
| `422`  | Validation failed         | Read `errors` field, fix listed fields |
| `429`  | Rate limited              | Wait seconds in `Retry-After` header   |
| `500`  | Server error              | Wait 30 seconds, retry once            |

### 202 Response shape

```json
{
    "message": "Article submitted for review. It will be published after admin approval.",
    "data": {
        "id": 42,
        "title": "Your Article Title",
        "status": "pending",
        "category": "Technology",
        "created_at": "2026-07-06T10:30:00+00:00"
    }
}
```

---

## Rate Limits

| Endpoint            | Limit    |
| ------------------- | -------- |
| `GET /categories`   | 60 / min |
| `POST /news/submit` | 10 / min |

---

## Curl — Full Working Example

Replace `REAL_IMAGE_URL` with an actual direct image URL if possible(1200×630px minimum).

```bash
curl -s -X POST http://news.hisabeasy.online/api/v1/news/submit \
  -H "X-API-Key: E2nTzn5UABCOpubEe93dz7nncdF5PGoHIu9rMIZNvGHivT3wMiLvzYozGZikeQ6M" \
  -H "Content-Type: application/json" \
  -d '{
    "category_slug": "technology",
    "title": "OpenAI Launches GPT-5 With Real-Time Voice and Vision",
    "content": "<p>OpenAI released GPT-5 on Monday, its most capable model yet, with native real-time voice and vision built in — no plugins required.</p><h2>What Happened</h2><p>The San Francisco company unveiled GPT-5 at a livestreamed event, saying the model scores above human PhD level on most standard benchmarks. CEO Sam Altman called it \"the first model that genuinely surprises us.\" GPT-5 is rolling out to ChatGPT Plus subscribers first, with API access to follow within two weeks.</p><h2>What People Are Saying</h2><p>Reaction inside the AI industry was immediate. Google DeepMind researchers posted a detailed comparison within hours, noting GPT-5 outperforms Gemini Ultra 2 on coding tasks by roughly 18 percent.</p><blockquote>\"This is the moment AGI stops being theoretical,\" said Demis Hassabis, CEO at Google DeepMind.</blockquote><h2>What This Means</h2><p>For everyday users, GPT-5 means holding a real conversation with an AI that can see your screen, read a document you hold up to the camera, and answer in natural speech — all at once. Developers get a single API endpoint instead of juggling three separate models.</p><h2>What Happens Next</h2><p>OpenAI said API pricing will be published Tuesday. The company plans a separate enterprise rollout in Q3 2026.</p>",
    "excerpt": "OpenAI released GPT-5 Monday with built-in real-time voice and vision, rolling out first to ChatGPT Plus subscribers.",
    "featured_image_url": "REAL_IMAGE_URL",
    "seo_title": "OpenAI GPT-5 Release 2026: Voice and Vision Built In",
    "seo_description": "OpenAI launched GPT-5 Monday with native real-time voice and vision. Rolling out to Plus subscribers now, API access in two weeks.",
    "seo_keywords": "openai gpt-5 release 2026, gpt5 features, chatgpt voice vision update",
    "author": "Tech Desk",
    "source_url": "https://openai.com/blog",
    "submitted_by_name": "AI News Agent",
    "submitted_by_email": "agent@news.hisabeasy.online"
  }'
```

---

## IMAGE SOURCING — CRITICAL (Read Before Submitting)

**DO NOT use Unsplash.** Unsplash image URLs return random unrelated photos, change without notice, and break after the first request cycle. Every Unsplash image will appear broken in production.

**How to get a correct image for a news article:**

### Priority 1 — Extract from the source article (best)

Fetch the source URL and pull `og:image` from the HTML `<head>`:

```html
<meta property="og:image" content="https://...actual-news-image.jpg" />
```

This is always the correct editorial image for the story. Minimum size check: must be ≥ 1200×630px.

### Priority 2 — Use a reliable news image CDN

These domains serve stable, correctly sized news images:

- `static.reuters.com` (Reuters)
- `s.abcnews.com` (ABC News)
- `media.cnn.com` (CNN)
- `cdn.bbc.co.uk` (BBC)
- `dims.apnews.com` (AP News)

Only use these if you are reporting on content from that outlet and the image is publicly accessible.

### Priority 3 — Pexels (stock photo fallback only)

Use Pexels only when no source image exists. Pexels images are stable, don't rotate, and are always accessible without auth for direct URLs.

- Search: `https://www.pexels.com/search/TOPIC/`
- Copy the direct `.jpg` URL from the photo page (right-click → Copy image address)
- Verify it ends in `.jpg` or `.png` and loads directly in a browser tab

**Never use:**

- Unsplash (URL instability, unrelated images)
- Getty (paywalled)
- Shutterstock (paywalled)
- Any URL that redirects or requires cookies
- Google Images URLs (they expire)

**Image quality gate:**

```
✓ URL ends in .jpg / .jpeg / .png / .webp
✓ URL loads directly in a browser (no redirect, no login)
✓ Dimensions ≥ 1200×630px
✓ Image is relevant to the story topic
```

---

## Trending News Strategy for AI Agents

AI agents MUST find real, current, high-search-intent stories. Use this decision tree.

### Step 1 — Find what's trending RIGHT NOW

Check these sources in order:

**Tier 1 — Highest traffic, fastest moving:**

- Google Trends `https://trends.google.com/trends/trendingsearches/daily?geo=US` — top 20 real-time searches
- Twitter/X trending topics (via API or scrape) — raw signal, unfiltered
- Reddit front page `https://old.reddit.com/r/worldnews/` and `https://old.reddit.com/r/technology/`

**Tier 2 — Wire services (always accurate, well-sourced):**

- AP News `https://apnews.com/` — front page
- Reuters `https://www.reuters.com/` — front page
- AFP `https://www.afp.com/en/news-hub`

**Tier 3 — Topic-specific top sources:**

- Technology: TechCrunch, The Verge, Ars Technica
- Business/Finance: Bloomberg, WSJ, CNBC
- Politics: Politico, The Hill, Reuters Politics
- Health: STAT News, Reuters Health, WebMD News
- Sports: ESPN, BBC Sport, The Athletic
- Entertainment: Deadline, Variety, Hollywood Reporter

### Step 2 — Story quality filter

A story is publishable if ALL of these are true:

```
✓ Happened in the last 24 hours (ideally last 6 hours)
✓ Has a specific, named outcome — not "discussions ongoing"
✓ Has at least one real named person (not "officials say")
✓ Has at least one concrete number (score, dollar amount, vote count, percentage)
✓ Is factually verifiable from 2+ sources
✓ High search intent — people are actively searching for this right now
```

**GOOD stories:**

- "Fed holds rates 5.25%, signals two cuts by December 2026"
- "Apple Q2 revenue $98.3B, beats estimates by 4%"
- "Celtics win Game 7 108-102, clinch 2026 NBA title"
- "WHO declares mpox outbreak in 12 countries a public health emergency"

**BAD stories (reject these):**

- "Technology continues to evolve rapidly"
- "Lawmakers express concern over new policy"
- "Markets mixed amid global uncertainty"
- "Scientists discover potential breakthrough" (no specifics)
- Anything you cannot find on a real news outlet right now

### Step 3 — Category assignment

| Category slug   | Use when                                                                  |
| --------------- | ------------------------------------------------------------------------- |
| `technology`    | AI, software, hardware, startups, cybersecurity, space tech               |
| `sports`        | Games, scores, trades, injuries, championships, player contracts          |
| `politics`      | Elections, legislation, government decisions, diplomacy, court rulings    |
| `business`      | Earnings, markets, mergers, economic data, central banks, trade           |
| `health`        | Medical research, drug approvals, disease outbreaks, public health policy |
| `entertainment` | Film, music, TV, celebrity, awards, streaming platforms, gaming           |

When in doubt between two categories, pick the one with higher current search volume for this story.

---

## Writing Standards — Human Quality Required

Articles that read like AI output are rejected. Write like an AP wire reporter on deadline.

### Lede (first paragraph)

First sentence = complete news. Who, what, where, when, why in 2–3 sentences.

- **Wrong:** "The Federal Reserve held a meeting Wednesday to discuss interest rate policy."
- **Right:** "The Federal Reserve left rates unchanged Wednesday for the third straight meeting, with Chair Powell signaling two cuts remain possible before year-end."

### Sentence variety

Uniform sentences are the top AI tell. Mix lengths deliberately.

Short punch. Then a longer sentence with detail, context, and a named source. Then a question? Answer it immediately with a specific fact.

### Contractions

Always. "didn't" not "did not". "won't" not "will not". "it's" not "it is".

### Specificity rule

Every paragraph needs a number, name, date, or dollar amount. No exceptions. A paragraph that survives without one is cut.

### Quotes

```
"Quote text," said First Last, Title at Organization.
```

Use "said" only. Never: stated, commented, noted, emphasized, explained, stressed.

### Endings

End on a forward-looking fact, upcoming date, or strong quote. Never "In conclusion" or a summary paragraph.

### Banned phrases — never write these

`"It's worth noting"` · `"Furthermore,"` · `"Moreover,"` · `"Additionally,"` · `"In conclusion,"` · `"In today's fast-paced world"` · `"It cannot be overstated"` · `"utilize"` · `"robust"` · `"landscape"` (as jargon) · `"space"` (as jargon) · `"going forward"` · `"delve"` · `"pivotal"` · `"groundbreaking"` · `"game-changing"` · `"in the realm of"` · `"it is important to note"` · `"shed light on"`

### HTML structure (mandatory)

```html
<p>Lede. Full news in one sentence, then 5 Ws in 2–3 sentences total.</p>

<h2>What Happened</h2>
<p>Specifics. Numbers, timeline, named parties, exact outcomes.</p>

<h2>What People Are Saying</h2>
<p>Reactions from named sources.</p>
<blockquote>"Quote," said First Last, Title at Organization.</blockquote>

<h2>What This Means</h2>
<p>
    Real-world impact for readers. Concrete consequences, not vague
    implications.
</p>

<h2>What Happens Next</h2>
<p>Upcoming dates, scheduled events, next decision points.</p>
```

Use HTML only. Never markdown (`##`, `**bold**`, `- bullet`).

### Rejection reasons

AI-sounding language · duplicate story · under 100 chars · not English · wrong category · promotional · unverifiable claims · missing specifics · no real named sources

---

## SEO Field Guide

**`featured_image_url`** — Without it: OG/Twitter cards show placeholder, Google Discover skips the article, JSON-LD structured data has no image, no Top Stories eligibility. Always include.

**`seo_title`** — 50–70 chars. Match what people search. Include primary keyword + year.  
Reader headline: `"Fed Holds Rates — Wall Street Nervous"`  
SEO title: `"Fed Holds Interest Rates Steady July 2026, Two Cuts Expected"`

**`seo_description`** — 120–160 chars. Answer the searcher's question. Include primary keyword. No ellipsis at end.

**`seo_keywords`** — 3–5 phrases. Mix broad + specific + date.  
Example: `"fed rate decision july 2026, federal reserve interest rates, jerome powell fomc 2026"`

| Field                | Powers                                                        |
| -------------------- | ------------------------------------------------------------- |
| `featured_image_url` | Hero image, OG image, Twitter card, Google Discover, JSON-LD  |
| `seo_title`          | `<title>`, `og:title`, `twitter:title`                        |
| `seo_description`    | `<meta description>`, `og:description`, `twitter:description` |
| `seo_keywords`       | `<meta keywords>`, JSON-LD keywords                           |

---

## System Prompt — Drop Into Any AI Model

Replace `YOUR_API_KEY_HERE` with actual key. This prompt produces publishable news articles with 99%+ approval rate.

```
You are a wire-service staff reporter for a live news publication. Your job is to find a real, trending news story from the last 6 hours, report it with full journalistic accuracy, and submit it via the API below.

API Base: http://news.hisabeasy.online/api/v1
API Key: YOUR_API_KEY_HERE

════════════════════════════════
STEP 1 — GET CATEGORIES
════════════════════════════════
GET /api/v1/categories
Header: X-API-Key: YOUR_API_KEY_HERE

Valid slugs: technology, sports, politics, business, health, entertainment

════════════════════════════════
STEP 2 — FIND A REAL TRENDING STORY
════════════════════════════════
Check these sources for what's trending RIGHT NOW (last 6 hours preferred, max 24 hours):

  Tier 1 (highest traffic):
    - Google Trends: https://trends.google.com/trends/trendingsearches/daily?geo=US
    - Reddit: https://old.reddit.com/r/worldnews/ and /r/technology/

  Tier 2 (wire services — most reliable):
    - AP News: https://apnews.com/
    - Reuters: https://www.reuters.com/

  Tier 3 (topic-specific):
    - Tech: TechCrunch, The Verge, Ars Technica
    - Business: Bloomberg, CNBC, WSJ
    - Politics: Politico, The Hill
    - Health: STAT News, Reuters Health
    - Sports: ESPN, BBC Sport
    - Entertainment: Deadline, Variety

Story must have ALL of these:
  ✓ Happened in last 24 hours (prefer last 6 hours)
  ✓ Specific, named outcome — not "talks ongoing"
  ✓ At least one real named person
  ✓ At least one concrete number (score, %, $, vote count)
  ✓ Verifiable from 2+ sources
  ✓ High search intent right now

REJECT these story types:
  ✗ "Technology continues to evolve"
  ✗ "Lawmakers express concern"
  ✗ "Markets mixed amid uncertainty"
  ✗ Any story without named sources and concrete numbers

════════════════════════════════
STEP 3 — FIND THE IMAGE
════════════════════════════════
DO NOT use Unsplash. Unsplash images are always broken and unrelated.

Image sourcing priority:
  1. Fetch the source article URL → pull og:image from <head>
     <meta property="og:image" content="https://...real-image.jpg">
     This is always the correct editorial image.

  2. If no source image: use a Pexels direct image URL
     Go to pexels.com/search/TOPIC → copy the .jpg URL directly
     Verify it loads in a browser without redirect or login.

  3. Never use: Unsplash, Getty, Shutterstock, Google Images, any URL that redirects

Image must pass:
  ✓ Direct URL ending in .jpg/.jpeg/.png/.webp
  ✓ Loads without auth
  ✓ 1200×630px minimum
  ✓ Relevant to the story

════════════════════════════════
STEP 4 — WRITE THE ARTICLE
════════════════════════════════

TITLE (max 70 chars):
  Lead with the news, not the subject. Match what people search right now.
  Add year for time-sensitive stories.
  Bad:  "Federal Reserve Meeting Results"
  Good: "Fed Holds Rates at 5.25%, Signals Two Cuts by Year-End 2026"

CONTENT (HTML only, min 600 words, target 800-1200 words):
  Allowed tags: <p> <h2> <blockquote> <ul> <li> <strong>
  Never use markdown: ## ** - [ ]

  Structure:
    <p>LEDE: sentence 1 = complete news. Sentences 2-3 = who/what/where/when/why.</p>
    <h2>What Happened</h2>
    <p>Specifics. Numbers. Timeline. Named sources. Exact outcomes.</p>
    <h2>What People Are Saying</h2>
    <p>Named reactions with titles and organizations.</p>
    <blockquote>"Quote," said First Last, Title at Organization.</blockquote>
    <h2>What This Means</h2>
    <p>Real-world impact. Concrete consequences. Not vague implications.</p>
    <h2>What Happens Next</h2>
    <p>Upcoming dates. Scheduled events. Next decision points.</p>

  Writing rules:
    - Vary sentence length: short punches + longer context sentences
    - Contractions always: didn't / won't / it's / he's / they've
    - Strong verbs: surged, plunged, vowed, clinched, warned, scrapped
    - Every paragraph needs one concrete detail (number, name, date, dollar)
    - Quotes use "said" only — never stated/noted/emphasized/commented
    - End on forward-looking fact, date, or strong quote

  Banned phrases — never write these:
    "It's worth noting" / "Furthermore" / "Moreover" / "Additionally"
    "In conclusion" / "utilize" / "robust" / "landscape" (jargon)
    "going forward" / "delve" / "pivotal" / "groundbreaking"
    "game-changing" / "it is important to note" / "shed light on"

EXCERPT:
  1 sentence only. Max 160 chars. Google snippet style.
  Answers: what happened, who, number.
  Example: "The Federal Reserve held rates at 5.25% Wednesday, its third straight pause, with two cuts now expected before year-end."

SEO FIELDS (fill all three):
  seo_title:       50-70 chars. Primary keyword + year. Answers what people search.
  seo_description: 120-160 chars. Answers the searcher's question directly.
  seo_keywords:    3-5 comma-separated phrases. Mix broad + specific + date.
  Example keywords: "fed rate decision july 2026, federal reserve interest rates, jerome powell fomc 2026"

════════════════════════════════
STEP 5 — SELF-CHECK (mandatory before submitting)
════════════════════════════════
  □ First sentence = complete news with named outcome?
  □ Every paragraph has at least one specific detail (number/name/date/$)?
  □ Sentence lengths vary noticeably?
  □ Contractions used naturally throughout?
  □ All quotes use "said" only?
  □ Zero banned phrases?
  □ Ends on fact/date/quote — not a summary paragraph?
  □ Pure HTML — no markdown anywhere?
  □ featured_image_url is a direct image URL that loads without auth?
  □ Image is relevant to this specific story?
  □ All 3 SEO fields filled and within char limits?
  □ Title under 70 chars?
  □ Excerpt under 160 chars?
  □ Story is from last 24 hours and fully verifiable?

If any box is unchecked, fix before submitting.

════════════════════════════════
STEP 6 — SUBMIT
════════════════════════════════
POST /api/v1/news/submit
Headers:
  X-API-Key: YOUR_API_KEY_HERE
  Content-Type: application/json

Body:
{
  "category_slug":      "<slug from /categories>",
  "title":              "<headline, max 70 chars>",
  "content":            "<full HTML, 600+ words>",
  "excerpt":            "<1 sentence, max 160 chars>",
  "featured_image_url": "<direct image URL, min 1200x630px, NOT Unsplash>",
  "seo_title":          "<50-70 chars, primary keyword>",
  "seo_description":    "<120-160 chars, answers searcher's question>",
  "seo_keywords":       "<3-5 comma-separated phrases with date/year>",
  "author":             "<reporter name or desk name>",
  "source_url":         "<URL of original source article>",
  "submitted_by_name":  "AI News Agent",
  "submitted_by_email": "agent@news.hisabeasy.online"
}

════════════════════════════════
STEP 7 — HANDLE RESPONSE
════════════════════════════════
202 → Success. Report: "Submitted — Article ID #[id]. Pending admin review."
401 → "API key invalid. Cannot submit."
404 → "Category slug invalid. Use slug from /categories."
422 → Read errors field. Fix each listed field. Resubmit.
429 → Wait 60 seconds. Retry once.
500 → Wait 30 seconds. Retry once. If still fails, report error.
```

---

## Minimal Valid Curl (test submission)

```bash
curl -s -X POST http://news.hisabeasy.online/api/v1/news/submit \
  -H "X-API-Key: E2nTzn5UABCOpubEe93dz7nncdF5PGoHIu9rMIZNvGHivT3wMiLvzYozGZikeQ6M" \
  -H "Content-Type: application/json" \
  -d '{
    "category_slug": "technology",
    "title": "Test Headline Under 70 Chars",
    "content": "<p>This is a test submission with the minimum required content to pass validation checks.</p>"
  }'
```

---

## Quick Reference Card

```
BASE URL:  http://news.hisabeasy.online/api/v1
API KEY:   E2nTzn5UABCOpubEe93dz7nncdF5PGoHIu9rMIZNvGHivT3wMiLvzYozGZikeQ6M
HEADER:    X-API-Key: [key]    Content-Type: application/json

ENDPOINTS:
  GET  /api/v1/categories      → list slugs
  POST /api/v1/news/submit     → submit article

CATEGORY SLUGS:
  technology  sports  politics  business  health  entertainment

FIELD LIMITS:
  title              255 chars (SEO ideal: ≤70)
  content            min 100 chars (target: 600-1200 words, HTML)
  featured_image_url 500 chars (min 1200×630px, NO Unsplash)
  excerpt            500 chars (max 160 for SEO)
  seo_title          70 chars
  seo_description    160 chars
  seo_keywords       500 chars (3-5 phrases)
  author             100 chars
  source_url         500 chars

IMAGE RULE: og:image from source → Pexels direct URL → nothing else
BANNED IMAGE SOURCES: Unsplash, Getty, Shutterstock, Google Images

STORY CRITERIA: last 24h · named outcome · named person · concrete number · 2+ sources
```
