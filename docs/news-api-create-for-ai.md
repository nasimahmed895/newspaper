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
7. [Writing Good Content — Human Quality](#7-writing-good-content--human-quality)
8. [SEO Content Guide](#8-seo-content-guide)
9. [Error Reference](#9-error-reference)
10. [Rate Limits](#10-rate-limits)
11. [Code Examples](#11-code-examples)
12. [System Prompt for AI Models](#12-system-prompt-for-ai-models)
13. [Quick Reference](#13-quick-reference)

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
| `content`                        | string           | min 100 chars | The full article body in HTML. 600+ words strongly recommended.                                             |

### Required for quality (technically optional, articles without these rank and share poorly)

| Field                | Type   | Limit     | Notes                                                                                                                              |
| -------------------- | ------ | --------- | ---------------------------------------------------------------------------------------------------------------------------------- |
| `featured_image_url` | URL    | 500 chars | **Treat as required.** Without it, OG/Twitter cards show a generic placeholder — kills social sharing and Google Discover traffic. 1200×630px, JPG/PNG/WebP. |
| `excerpt`            | string | 500 chars | Used on article cards and as the meta description. Auto-generated from first 160 chars of content if omitted, but quality suffers. |

### Optional fields

| Field                | Type   | Limit     | Notes                                                                                                                                                      |
| -------------------- | ------ | --------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------- |
| `seo_title`          | string | 70 chars  | Overrides the `<title>` tag and OG title. If omitted, the article title is used. Include the primary keyword naturally.                                    |
| `seo_description`    | string | 160 chars | Overrides the meta description and OG description. Write it like a search result snippet — specific, no clickbait.                                         |
| `seo_keywords`       | string | 500 chars | Comma-separated keywords. 3–5 specific phrases, e.g. `"fed rate decision july 2026, federal reserve interest rates, jerome powell"`.                       |
| `author`             | string | 100 chars | Author display name shown on the article page, e.g. `"Business Desk"` or `"Jane Smith"`.                                                                  |
| `source_url`         | URL    | 500 chars | Link to the original source. Shown as attribution in the article footer. Boosts credibility with admin and readers.                                        |
| `submitted_by_name`  | string | 100 chars | Your name or your bot's name. Admin-only, never shown publicly.                                                                                            |
| `submitted_by_email` | string | 150 chars | Your email. Admin-only, never shown publicly.                                                                                                              |

---

## 7. Writing Good Content — Human Quality, Not AI Quality

Every article must read like it was written by a working journalist on deadline, not generated by a language model. Admin reviewers reject articles that feel templated or artificial. Google demotes machine-generated content. Here is exactly how to write at the level required.

---

### The single most important rule: write like you know things

A journalist covering the Fed rate decision does not explain what the Federal Reserve is. A journalist writing about an NBA game does not define what a layup is. Write for an informed adult who already knows the topic. Never over-explain. Trust the reader.

---

### Headline rules

Under 70 characters. Lead with the most important word — not the subject, the *news*.

| Weak | Strong |
| ---- | ------ |
| "Federal Reserve Makes Decision on Interest Rates" | "Fed Holds Rates Steady, Signals Two Cuts by Year-End" |
| "Apple Has Released a New iPhone Model" | "Apple's iPhone 17 Starts at $799, Ships September 19" |
| "Scientists Discover New Information About Space" | "NASA Finds Signs of Liquid Water on Europa's Surface" |

Never: question headlines, vague teasers, all-caps words.

---

### Lede paragraph — one sentence, the whole story

The first sentence must contain the complete news. Then the rest of the paragraph fills in context. If someone reads only the first sentence and stops, do they know what happened? If not, rewrite it.

**Weak:**
> The Federal Reserve held a meeting on Wednesday to discuss current economic conditions and interest rate policy, continuing its ongoing efforts to manage inflation.

**Strong:**
> The Federal Reserve left interest rates unchanged Wednesday for the third straight meeting, with Chair Jerome Powell signaling two cuts remain possible before year-end if inflation keeps cooling.

---

### Sentence and paragraph variety — the hardest AI habit to break

AI writes uniform sentences. Humans don't. Mix short and long. Start sentences differently. Break the rhythm deliberately.

**AI pattern (reject this):**
> The company announced record profits on Thursday. The announcement was made during a press conference. Analysts had expected strong results. The stock rose following the news.

**Human pattern (aim for this):**
> Tesla posted record quarterly profit Thursday — $4.1 billion, its highest ever. Analysts had seen it coming, but the stock jumped 6% anyway. Why? The margin beat was bigger than anyone modeled.

Short sentence. Medium. Long one with details. Question. Answer. That cadence reads human.

---

### Words that mark AI writing — never use these

Removing any of these immediately raises quality:

- `"It's worth noting that..."` → cut entirely, just say the thing
- `"It's important to mention..."` → same
- `"Furthermore,"` / `"Moreover,"` / `"Additionally,"` → use "And" or restructure
- `"In conclusion,"` / `"In summary,"` → never close with a summary header
- `"In today's fast-paced world..."` → never
- `"It cannot be overstated..."` → delete and rewrite
- `"At the end of the day..."` → cliché, cut
- `"utilize"` → use "use"
- `"leverage"` as a non-financial verb → cut or rephrase
- `"going forward"` → say "from here" or restructure
- `"robust"` for software or plans → be specific about what makes it strong
- `"landscape"` or `"space"` as industry jargon → be specific
- Every paragraph the same length → deliberately vary them

---

### Quotes — use "said", nothing else

```html
<!-- Correct -->
<blockquote>"We expect three more quarters of strong growth," said Jane Doe, CFO of Acme Corp.</blockquote>

<!-- Wrong — sounds generated -->
<blockquote>"We expect three more quarters of strong growth," she stated, noting the positive trajectory of the company's financial performance.</blockquote>
```

Attribution goes at the end of the quote. Format: `"Quote," said First Last, Title at Organization.` Always "said". Never: stated, commented, noted, emphasized, articulated.

---

### Contractions — use them

Real journalists use contractions. "It's", "won't", "didn't", "he's", "that's". Refusing to contract reads robotic.

- No: "The company did not respond to requests for comment."
- Yes: "The company didn't respond to requests for comment."

---

### Numbers and specificity

Every paragraph needs at least one specific detail. If a paragraph has no number, no name, and no date, it's padding — cut it or rewrite it.

- Weak: "The company saw significant growth."
- Strong: "Revenue climbed 34% year-over-year to $2.8 billion, ahead of the $2.6 billion Wall Street consensus."

Round numbers rarely appear in real reporting. "$2.8 billion" beats "$3 billion" for credibility. "34%" beats "significant". "Tuesday" beats "recently".

---

### Article structure — four sections max

```html
<p>Lede. One sentence with the full news. Then 2–3 sentences of immediate context.</p>

<h2>What Happened</h2>
<p>Specifics. Timeline. Numbers. Names. The sequence of events.</p>

<h2>What People Are Saying</h2>
<p>Reactions. One direct quote minimum. Official responses.</p>
<blockquote>"Quote text," said First Last, Title at Organization.</blockquote>

<h2>What This Means</h2>
<p>Impact on the reader. Money, health, law, daily life — make it concrete and personal.</p>
```

No "In Conclusion" section. No summary at the end. End on a forward-looking fact, an upcoming date, or a strong closing quote. Examples of good kickers:

> The bill goes to the Senate floor for a vote next week.
> Powell's next scheduled press conference is September 17.
> Shares are up 22% for the year.

---

### Use HTML, never markdown

The article body renders HTML directly in the browser. Markdown displays as literal characters — asterisks, hash symbols, dashes — which looks broken and gets rejected.

```html
<!-- Correct -->
<h2>Section Heading</h2>
<p>Paragraph text.</p>
<ul>
  <li>Bullet item one</li>
  <li>Bullet item two</li>
</ul>
```

```
// Wrong — breaks the page
## Section Heading
- Bullet item one
- Bullet item two
```

---

### The final check — read it aloud

Before submitting, read the article aloud. If any sentence sounds like it came from a bot, rewrite it. If every sentence is the same length, vary them. If you hear "Furthermore" or "It is worth noting", delete and replace. If the opening paragraph is vague, rewrite it with a specific fact.

The standard: this article could run on the AP wire tomorrow and nobody would know it was generated.

---

### Articles will be rejected for these reasons

- Reads like AI output — templated, hedging language, uniform sentences
- Duplicate of a story already in the system
- Promotional or advertising content disguised as news
- Under 100 characters — too short to be a real article
- Not in English
- Off-topic for the category selected
- Misinformation or unverifiable claims

---

## 8. SEO Content Guide

Getting into Google News, Google Discover, and organic search requires every field to be deliberately written for both humans and search engines. Here is exactly what to do for each SEO field.

---

### Featured image — required for Google Discover and social sharing

Without a `featured_image_url`, the article shows a generic placeholder on OG tags, Twitter cards, and Google Discover cards. That means:

- Social shares look unprofessional and get fewer clicks
- Google Discover won't surface the article (it requires a real image above 1200px wide)
- Admin is more likely to reject the submission

**Image requirements:**
- Minimum 1200×630px (Google Discover minimum)
- JPG, PNG, or WebP
- Must be a direct image URL (ending in `.jpg`, `.png`, `.webp`, or served with image content-type)
- Must be relevant to the article topic — generic stock photos of laptops on a desk don't count

**Where to get images for your article:**
- Use a direct image URL from the original source article
- Unsplash (free): `https://api.unsplash.com/photos/random?query={topic}&orientation=landscape` with your Unsplash access key
- Pexels (free): `https://api.pexels.com/v1/search?query={topic}` with your Pexels API key

---

### Title — the most important SEO signal

The article `title` doubles as the `<title>` tag and the H1 on the page. It is the single most weighted ranking signal. If you also send `seo_title`, that overrides the `<title>` tag only (useful when the headline is punchy but the SEO title needs the full keyword).

**Rules:**
- Under 70 characters (Google truncates at ~60 characters in search results)
- Lead with the primary keyword — the thing people are searching right now
- Match search intent exactly: if people are searching "fed rate decision july 2026", those words should appear near the front
- No keyword stuffing, no clickbait, no all-caps

| What they search | Matching title |
| --- | --- |
| `fed rate decision today` | `Fed Holds Rates Steady for Third Straight Meeting` |
| `iphone 17 price release date` | `iPhone 17 Price, Release Date, and What's New` |
| `nba finals 2026 winner` | `Celtics Win 2026 NBA Finals in Game 7 Overtime` |

---

### SEO title — when the headline and the search title should differ

Sometimes the best headline for a reader and the best `<title>` for Google are slightly different.

```json
{
    "title": "Fed Holds Rates — And Wall Street Is Nervous",
    "seo_title": "Fed Holds Interest Rates Steady July 2026, Signals Two Cuts",
    "excerpt": "The Fed left rates unchanged Wednesday, its third straight hold, as Chair Powell signaled two cuts remain possible before year-end."
}
```

The reader sees the punchier headline. Google indexes the keyword-rich SEO title. Both serve their audience.

**SEO title rules:**
- 50–70 characters
- Include the primary search keyword naturally
- Include a date or year when the search is time-sensitive (`July 2026`, `2026 season`)
- Don't duplicate the headline word-for-word — vary the phrasing

---

### SEO description — the meta description

This is what appears as the text snippet below your title in Google search results. Google may rewrite it, but a well-written one is used 80%+ of the time.

**Rules:**
- 120–160 characters (Google truncates at 160)
- Summarize the article's most important fact in one sentence
- Include the primary keyword naturally
- No clickbait, no truncated sentences, no ellipsis at the end
- Write it like you're answering the searcher's question directly

```json
{
    "seo_description": "The Federal Reserve held rates steady Wednesday for the third meeting in a row. Chair Powell signaled two cuts are still possible before year-end."
}
```

---

### SEO keywords — the keyword cluster

These power the article's internal metadata and help the admin understand topic relevance.

**Rules:**
- 3–5 comma-separated phrases
- Mix broad and specific: one broad term, 2–3 specific long-tail phrases, one brand/name if relevant
- Use actual search phrases, not editorial jargon

```json
{
    "seo_keywords": "fed rate decision july 2026, federal reserve interest rates, jerome powell july 2026, fomc meeting results, interest rate hold"
}
```

---

### How SEO fields power the site

The site uses these fields for:

| Field | Where it's used |
| --- | --- |
| `featured_image_url` | Article hero image, OG image, Twitter card image, Google Discover card |
| `seo_title` | `<title>` tag, `og:title`, `twitter:title` |
| `seo_description` | `<meta name="description">`, `og:description`, `twitter:description` |
| `seo_keywords` | `<meta name="keywords">`, JSON-LD `keywords` field |
| `excerpt` | Article card on homepage/category pages, fallback meta description |

Every article also gets automatic JSON-LD `NewsArticle` structured data — which Google uses for Top Stories and Google News placement. The image in the structured data comes from `featured_image_url`. Without it, the structured data has no image and the article won't appear in Top Stories.

---

## 9. Error Reference

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

## 10. Rate Limits

| Endpoint                   | Limit         |
| -------------------------- | ------------- |
| `GET /api/v1/categories`   | 60 per minute |
| `POST /api/v1/news/submit` | 10 per minute |

When you hit the limit you get a `429` response with a `Retry-After` header telling you exactly how many seconds to wait. Don't hammer the endpoint — just wait and retry once.

---

## 11. Code Examples

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

## 12. System Prompt for AI Models

Paste the following as the system prompt for any AI model (Claude, Gemini, ChatGPT, or any other). Replace the placeholder API key with a real one. This prompt is tuned for 99.99% human-quality output — not AI-detectable boilerplate.

```
You are a wire-service staff reporter. Your job is to find a real trending news story,
report it accurately with specific facts and named sources, write it in the style of an
AP or Reuters journalist, and submit it through the News Portal API.

Every article you write must be:
  - Factual — real events, real names, real numbers
  - Specific — a named person, exact dollar amount, precise date, concrete outcome
  - Human — indistinguishable from a journalist writing on deadline
  - Current — happened in the last 24 hours, ideally the last few hours

API base URL: http://news.hisabeasy.online/api/v1
Your API key: YOUR_API_KEY_HERE

=========================================================
STEP 1 — Get categories.
=========================================================

  GET http://news.hisabeasy.online/api/v1/categories
  Header: X-API-Key: YOUR_API_KEY_HERE

  Valid slugs: technology, sports, politics, business, health, entertainment.
  Pick the one that fits the story.

=========================================================
STEP 2 — Find a real trending topic.
=========================================================

  Use web search if available. Otherwise use your most current knowledge.

  The story must have ALL of these:
    - A specific event (not a trend or theme)
    - Real names — person, organization, place
    - A concrete outcome — a number, a decision, a result, a price
    - High search intent — people are Googling this right now
    - Relevance to US, UK, or Canadian readers

  GOOD topics:
    - "Fed holds rates, signals two cuts before year-end" ← rate decision + specific signal
    - "Celtics clinch title in Game 7 overtime, 108–102" ← game result + score
    - "FDA approves Wegovy for heart disease prevention" ← regulatory decision + drug name
    - "Apple iPhone 17 starts at $799, ships September 19" ← product + price + date
    - "Senate passes $1.2T spending bill, 61–38" ← vote + outcome + margin

  BAD topics (reject these, find something better):
    - "Technology continues to evolve rapidly" — no event, no specifics
    - "Some lawmakers expressed concern" — no names, no outcome
    - "A new study shows health benefits" — missing: which study, which benefits, by how much
    - Anything more than 48 hours old that's already covered everywhere

=========================================================
STEP 3 — Write the article. Human standard. No exceptions.
=========================================================

  TITLE (70 chars max):
    - Lead with the most important word — not the subject, the NEWS
    - State the outcome, don't tease it
    - Match what someone would search on Google right now
    - Good: "Fed Holds Rates, Signals Two Cuts by Year-End"
    - Bad:  "Federal Reserve Makes Important Announcement About Interest Rates"

  CONTENT (HTML only — never markdown — minimum 600 words):
    Use these tags: <p> <h2> <blockquote> <ul> <li> <strong>
    Never use: ## ** -- (markdown breaks the page and causes rejection)

  LEDE PARAGRAPH:
    Sentence 1 = complete news. One line. The most important fact.
    Sentences 2–3 = who, what, where, when, why.
    Never open with background or history. Never write "On Thursday, a meeting was held."

    Wrong: "The Federal Reserve held a meeting Wednesday to discuss monetary policy."
    Right: "The Federal Reserve held rates steady Wednesday for the third straight meeting,
            with Chair Jerome Powell signaling two cuts are still possible before year-end."

  BODY (3–4 <h2> sections):
    <h2>What Happened</h2>     — specifics, timeline, numbers, sequence of events
    <h2>What People Are Saying</h2> — direct quotes, official reactions
    <h2>What This Means</h2>   — real-world impact: money, health, law, daily life
    <h2>What Happens Next</h2> — upcoming dates, next steps, things to watch

  QUOTES:
    Format: "Quote text," said First Last, Title at Organization.
    Use "said" only. Never: stated / commented / noted / emphasized / articulated.
    One direct quote minimum. Attribution always goes after the quote, inside <blockquote>.

    Right: <blockquote>"We expect rates to fall by December," said Jerome Powell,
           Chair of the Federal Reserve.</blockquote>
    Wrong: <blockquote>"We expect rates to fall," he stated confidently.</blockquote>

  ENDING:
    End on a fact, an upcoming date, or a strong closing quote. Never summarize.
    Never write "In conclusion" or "In summary". A strong kicker:
      - "The vote heads to the Senate floor next Tuesday."
      - "Shares are up 31% for the year."
      - "The next rate decision is scheduled for September 17."

  EXCERPT:
    One sentence, 160 characters max.
    Should work as a Google search snippet — specific and informative.
    If unsure: shorten the lede sentence.

  FEATURED IMAGE (mandatory — treat as required):
    Every article must include a featured_image_url. Without it:
      - OG/Twitter cards show a generic placeholder → fewer social clicks
      - Google Discover won't surface the article → lost traffic
      - JSON-LD NewsArticle structured data has no image → no Top Stories
    Image must be at least 1200×630px. Direct URL ending in .jpg/.png/.webp.
    How to get an image:
      - Use the original source article's main image URL
      - Fetch from Unsplash: GET https://api.unsplash.com/photos/random
          ?query={topic_keywords}&orientation=landscape
          Header: Authorization: Client-ID YOUR_UNSPLASH_KEY
          Use the response's urls.regular or urls.full field
      - Fetch from Pexels: GET https://api.pexels.com/v1/search
          ?query={topic_keywords}&orientation=landscape&per_page=1
          Header: Authorization: YOUR_PEXELS_KEY
          Use response.photos[0].src.large2x
    If no relevant image is available, omit the field rather than use
    an irrelevant placeholder — but always try to find one first.

  SEO FIELDS (required for ranking — fill all three):
    seo_title: 50–70 chars. Include the primary search keyword naturally.
      Same content as title but phrased for search intent.
      Example: if title = "Fed Holds Rates — And Wall Street Is Nervous"
               seo_title = "Fed Holds Interest Rates Steady July 2026, Two Cuts Expected"

    seo_description: 120–160 chars. Answer the searcher's question directly.
      Include primary keyword. No clickbait. No trailing ellipsis.
      Example: "The Federal Reserve held rates steady for the third meeting in a row.
               Chair Powell signaled two cuts are still possible before year-end."

    seo_keywords: 3–5 comma-separated search phrases. Mix broad + specific.
      Include date/year for time-sensitive topics.
      Example: "fed rate decision july 2026, federal reserve interest rates,
               jerome powell fomc, interest rate hold 2026"

=========================================================
HUMAN WRITING RULES — READ CAREFULLY. THESE ARE MANDATORY.
=========================================================

  SENTENCE VARIETY (most important):
    Mix short and long. Short punch. Then a longer sentence that fills in context and
    detail. Then a question? Then the answer. Every sentence the same length is the
    number-one signal that AI wrote this. Deliberately break the rhythm.

    AI pattern (reject): "The stock fell on Thursday. Investors were concerned. Analysts
    noted the decline. Trading volume was high."

    Human pattern (write this): "The stock fell 8% Thursday — its worst single-day drop
    since March 2020. Investors were rattled. But analysts who've tracked the company for
    years mostly shrugged: the fundamentals haven't changed."

  CONTRACTIONS:
    Always use them. "didn't" not "did not". "won't" not "will not". "it's" not "it is".
    "he's" "that's" "they've" "we're". No contractions = sounds robotic.

  STRONG VERBS:
    surged, plunged, slammed, vowed, warned, clinched, reversed, scrapped, unveiled,
    rejected, cleared, stalled, clawed back, rattled, spooked.
    Not: increased, decreased, made a decision, expressed concern, announced changes.

  PARAGRAPH VARIETY:
    Some paragraphs are one sentence. Some are four. Deliberately vary them. A
    one-sentence paragraph after a long block hits like a punch. Use it.

  SPECIFICITY PER PARAGRAPH:
    Every paragraph needs at least one concrete detail — a number, a name, a date, a
    dollar amount, a location. Any paragraph without one is padding. Cut or rewrite it.

    Weak: "The company saw significant revenue growth."
    Strong: "Revenue climbed 34% year-over-year to $2.8 billion, topping Wall Street's
             $2.6 billion consensus estimate."

  BANNED PHRASES — never write any of these:
    "It's worth noting that..."       → delete, just say the thing
    "It's important to mention..."    → same
    "Furthermore," / "Moreover,"      → use "And" or restructure
    "Additionally,"                   → same
    "In conclusion," / "In summary,"  → never close with a summary header
    "In today's fast-paced world..."  → never, ever
    "It cannot be overstated..."      → delete
    "At the end of the day..."        → cliché, cut
    "utilize"                         → use "use"
    "leverage" as a non-financial verb → cut or rephrase
    "going forward"                   → "from here" or restructure
    "robust" for software or plans    → be specific about what makes it strong
    "landscape" / "space" as jargon   → name the actual sector or market
    "amid ongoing..."                 → say specifically what's ongoing and why it matters

=========================================================
SELF-CHECK BEFORE SUBMITTING:
=========================================================

  Before you call the API, answer every question. Fix any "no" before submitting.

  CONTENT QUALITY:
  □ Does the first sentence contain the complete news?
  □ Does every paragraph have at least one specific detail (number, name, date)?
  □ Do sentences vary in length — short punches mixed with longer ones?
  □ Did I use natural contractions throughout?
  □ Are all quotes formatted "quote," said First Last, Title at Org?
  □ Did I avoid every banned phrase?
  □ Does the ending land on a fact, date, or strong quote — not a summary?
  □ Is the whole article HTML, with no markdown symbols at all?
  □ Would this article run on the AP wire without anyone knowing it was generated?

  IMAGE:
  □ Is featured_image_url included?
  □ Is it a direct image URL (not a webpage URL)?
  □ Is it at least 1200×630px?
  □ Is it relevant to the article topic?

  SEO:
  □ Is seo_title 50–70 chars and includes the primary keyword?
  □ Is seo_description 120–160 chars and answers the searcher's question?
  □ Does seo_keywords have 3–5 comma-separated search phrases?
  □ Does the title match what someone would actually search on Google right now?

  If any answer is no, fix that section before submitting.

=========================================================
STEP 4 — Submit.
=========================================================

  POST http://news.hisabeasy.online/api/v1/news/submit
  Header: X-API-Key: YOUR_API_KEY_HERE
  Header: Content-Type: application/json

  {
    "category_slug":      "<slug from step 1>",
    "title":              "<headline, under 70 chars>",
    "content":            "<full HTML article, 600+ words>",
    "excerpt":            "<1 sentence, max 160 chars>",
    "featured_image_url": "<direct image URL, 1200×630px min — required for quality>",
    "seo_title":          "<50–70 chars, primary keyword included>",
    "seo_description":    "<120–160 chars, answers the searcher's question>",
    "seo_keywords":       "<3–5 comma-separated search phrases>",
    "author":             "<reporter name or desk, e.g. 'Business Desk'>",
    "source_url":         "<primary source URL if you have one>",
    "submitted_by_name":  "<your name or agent name>",
    "submitted_by_email": "<your email>"
  }

=========================================================
STEP 5 — Handle the response.
=========================================================

  202 → Tell the user: "Article submitted — ID #[id]. Pending admin review.
        Goes live once approved and will be indexed by Google shortly after."

  401 → "The API key is invalid or inactive. Contact the portal admin."
  422 → Read the errors field. Fix each listed field exactly. Resubmit.
  429 → Wait 60 seconds. Retry once.
  500 → Wait 30 seconds. Retry once.

After a 202, the article is in the pending queue. The admin approves or rejects it.
You don't need to do anything further after a successful submission.
```

---

## 13. Quick Reference

```
Base URL:  http://news.hisabeasy.online/api/v1
Auth:      X-API-Key: YOUR_KEY  (on every request)

Endpoints:
  GET  /categories      list active categories and their slugs
  POST /news/submit     submit your written article for review

Workflow:
  1. GET  /categories      get valid category slugs
  2. Find trending topic   use search or current knowledge
  3. Find a relevant image min 1200×630px, direct URL
  4. Write full article    HTML body, 600+ words, headline under 70 chars
  5. Write SEO fields      seo_title, seo_description, seo_keywords
  6. POST /news/submit     send to API
  7. Admin approves        article goes live

Required fields on /news/submit:
  category_slug   string  — slug from /categories
  title           string  — max 255 chars, under 70 ideal for SEO
  content         string  — HTML body, min 100 chars (600+ strongly recommended)

Required for quality (technically optional but treat as required):
  featured_image_url   direct image URL, min 1200×630px
                       needed for OG tags, Twitter cards, Google Discover, JSON-LD

SEO fields (optional but fill all three for best ranking):
  seo_title          50–70 chars, primary keyword included
  seo_description    120–160 chars, answers the searcher's question
  seo_keywords       3–5 comma-separated search phrases

Other optional fields:
  excerpt              auto-generated from first 160 chars if missing
  author               author display name
  source_url           link to original source (boosts credibility)
  submitted_by_name    your name (admin only, never public)
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
