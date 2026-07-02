<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $articles = [
            // === TECHNOLOGY (12 articles) ===
            [
                'category_slug' => 'technology',
                'articles' => [
                    [
                        'title' => 'Apple Unveils Next-Generation M4 Chip with Breakthrough AI Performance',
                        'content' => '<p>In a landmark announcement from Cupertino, Apple has officially unveiled its next-generation M4 processor, promising a dramatic leap in artificial intelligence capabilities and energy efficiency. The new chip, built on a cutting-edge 3-nanometer process, features a 16-core CPU and a 40-core GPU that outperforms the previous M3 generation by up to 50 percent.</p><p>"This is the most powerful chip we have ever created," said Apple CEO Tim Cook during the keynote presentation. "The M4 redefines what personal computing can achieve, particularly in the realm of on-device AI processing."</p><h2>Revolutionary Neural Engine</h2><p>The M4\'s neural engine has been completely redesigned, now capable of 38 trillion operations per second. This enables real-time AI tasks such as language translation, image generation, and complex data analysis directly on the device without cloud connectivity.</p><p>Industry analysts have praised the announcement. "Apple is setting a new standard for silicon design," said Dr. Sarah Chen, a semiconductor analyst at TechInsights. "The M4\'s efficiency gains could pressure competitors like Intel and AMD to accelerate their own roadmaps."</p><p>The first devices featuring the M4 chip are expected to ship in the coming quarter, starting with the MacBook Pro and iPad Pro lineups.</p>',
                        'excerpt' => 'Apple\'s new M4 processor delivers unprecedented AI performance with a redesigned neural engine capable of 38 trillion operations per second.',
                        'seo_keywords' => 'Apple, M4 chip, AI, processor, technology, Silicon, MacBook, iPad',
                        'author' => 'Tech Correspondent',
                        'source' => 'Apple Newsroom',
                        'is_trending' => true,
                        'published_at' => now()->subHours(2),
                    ],
                    [
                        'title' => 'Google\'s Gemini 2.0 Model Achieves Human-Level Reasoning in Benchmark Tests',
                        'content' => '<p>Google DeepMind has announced that its latest Gemini 2.0 artificial intelligence model has achieved scores comparable to human experts on a series of rigorous reasoning benchmarks. The development marks a significant milestone in the race toward artificial general intelligence.</p><p>"Gemini 2.0 represents a fundamental breakthrough in how AI systems understand and reason about the world," said Demis Hassabis, CEO of Google DeepMind. "We are seeing capabilities that were previously thought to be years away."</p><p>The model demonstrated exceptional performance in mathematics, coding, scientific reasoning, and multi-modal understanding, scoring in the 95th percentile across all tested domains. Independent researchers have confirmed the results.</p>',
                        'excerpt' => 'Google DeepMind\'s Gemini 2.0 achieves human-level reasoning scores, marking a major milestone toward artificial general intelligence.',
                        'seo_keywords' => 'Google, Gemini 2.0, AI, artificial intelligence, DeepMind, benchmark',
                        'author' => 'Tech Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(5),
                    ],
                    [
                        'title' => 'Microsoft Launches Quantum Computing Platform for Enterprise Customers',
                        'content' => '<p>Microsoft has officially launched Azure Quantum Elements, a new cloud-based quantum computing platform designed specifically for enterprise customers. The platform combines traditional high-performance computing with quantum simulation capabilities to solve complex problems in chemistry, materials science, and drug discovery.</p><p>"We are entering the era of quantum-accelerated discovery," said Satya Nadella, Microsoft CEO. "Azure Quantum Elements will enable our customers to tackle problems that were previously unsolvable."</p><p>Early adopters include several major pharmaceutical companies that are using the platform to accelerate drug discovery processes, with one company reporting a 60 percent reduction in initial screening time.</p>',
                        'excerpt' => 'Microsoft\'s Azure Quantum Elements brings quantum computing to enterprise customers, enabling breakthroughs in drug discovery and materials science.',
                        'seo_keywords' => 'Microsoft, quantum computing, Azure, enterprise, cloud computing',
                        'author' => 'Staff Reporter',
                        'published_at' => now()->subHours(8),
                    ],
                    [
                        'title' => 'Tesla\'s Optimus Robot Begins Factory Trials in Austin Gigafactory',
                        'content' => '<p>Tesla has begun initial trials of its Optimus humanoid robot at its Gigafactory in Austin, Texas, marking a significant step toward automating manufacturing processes. The robots are currently performing basic material handling and sorting tasks under supervision.</p><p>Elon Musk announced the development on social media, stating that Optimus is "already proving valuable in factory settings" and that Tesla plans to deploy thousands of units across its facilities by next year. Analysts estimate the humanoid robot market could reach $100 billion annually within the decade.</p>',
                        'excerpt' => 'Tesla\'s Optimus humanoid robot begins real-world factory trials, performing material handling tasks at the Austin Gigafactory.',
                        'seo_keywords' => 'Tesla, Optimus, robot, automation, gigafactory, Elon Musk',
                        'author' => 'Tech Correspondent',
                        'published_at' => now()->subHours(12),
                    ],
                    [
                        'title' => 'Cybersecurity Alert: New Zero-Day Vulnerability Affects Billions of Devices Worldwide',
                        'content' => '<p>Security researchers have discovered a critical zero-day vulnerability in a widely used networking component that potentially affects billions of devices worldwide. The flaw, designated CVE-2026-0384, allows remote attackers to execute arbitrary code on affected systems without authentication.</p><p>"This is one of the most severe vulnerabilities we have seen in recent years," said Maria Rodriguez, Chief Security Researcher at Mandiant. "The attack surface is enormous, and we are already seeing active exploitation in the wild."</p><p>Major technology companies including Cisco, Juniper, and several Linux distributions have released emergency patches. Users are urged to update their systems immediately.</p>',
                        'excerpt' => 'A critical zero-day vulnerability affecting billions of devices is being actively exploited. Emergency patches released by major vendors.',
                        'seo_keywords' => 'cybersecurity, zero-day, vulnerability, security, patch, CVE',
                        'author' => 'Tech Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(3),
                    ],
                    [
                        'title' => 'Samsung Announces Revolutionary Solid-State Battery Technology',
                        'content' => '<p>Samsung SDI has announced a major breakthrough in solid-state battery technology that promises to double the range of electric vehicles while reducing charging time to under 15 minutes. The new battery uses a sulfide-based solid electrolyte that eliminates the fire risks associated with traditional lithium-ion batteries.</p><p>"This is the holy grail of battery technology," said Dr. James Park, head of Samsung\'s battery research division. "We have solved the key challenges that have prevented solid-state batteries from reaching commercial viability." Samsung plans to begin mass production by 2027.</p>',
                        'excerpt' => 'Samsung\'s solid-state battery breakthrough promises double EV range with 15-minute charging times and eliminates fire risks.',
                        'seo_keywords' => 'Samsung, solid-state battery, EV, electric vehicles, battery technology',
                        'author' => 'Tech Correspondent',
                        'published_at' => now()->subHours(18),
                    ],
                    [
                        'title' => 'OpenAI Releases GPT-5 with Unprecedented Context Window and Reasoning Capabilities',
                        'content' => '<p>OpenAI has officially released GPT-5, its most advanced language model yet, featuring a context window of one million tokens and significantly improved reasoning capabilities. The new model demonstrates remarkable proficiency in complex tasks including mathematical proofs, legal analysis, and scientific research.</p><p>"GPT-5 represents a quantum leap in what language models can achieve," said Sam Altman, CEO of OpenAI. "We are particularly excited about its ability to maintain coherence across extremely long documents and its improved factual accuracy." Early benchmarks show GPT-5 outperforming its predecessor by 40 percent across standard evaluation metrics.</p>',
                        'excerpt' => 'OpenAI\'s GPT-5 launches with million-token context window and superior reasoning, outperforming GPT-4 by 40 percent.',
                        'seo_keywords' => 'OpenAI, GPT-5, AI, language model, artificial intelligence',
                        'author' => 'Tech Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(1),
                    ],
                    [
                        'title' => 'Meta Unveils Next-Generation AR Glasses with All-Day Battery Life',
                        'content' => '<p>Meta has unveiled its latest augmented reality glasses, the Meta Orion 2, featuring all-day battery life and a revolutionary lightweight design that weighs just 85 grams. The glasses project high-resolution holographic displays directly onto the user\'s field of view, enabling seamless integration of digital content with the physical world.</p><p>"This is the device we have been working toward for a decade," said Mark Zuckerberg. "The Orion 2 makes AR practical for everyday use." The glasses connect wirelessly to smartphones and computers, supporting productivity applications, navigation, and immersive entertainment.</p>',
                        'excerpt' => 'Meta\'s Orion 2 AR glasses achieve all-day battery life in an 85-gram design, making augmented reality practical for daily use.',
                        'seo_keywords' => 'Meta, AR glasses, augmented reality, Orion, wearable tech',
                        'author' => 'Staff Reporter',
                        'published_at' => now()->subHours(6),
                    ],
                    [
                        'title' => 'Global 5G Subscriptions Surpass 3 Billion as Developing Nations Accelerate Adoption',
                        'content' => '<p>Global 5G subscriptions have surpassed 3 billion, according to the latest Ericsson Mobility Report, driven by accelerated adoption in developing nations across Africa and Southeast Asia. The milestone represents a 40 percent increase from the previous year, with 5G now accounting for over 35 percent of all mobile data traffic worldwide.</p><p>"5G is becoming the connectivity backbone for the digital economy," said Fredrik Jejdling, Executive Vice President at Ericsson. "We are seeing particularly strong growth in markets that leapfrogged directly from 4G to 5G." The report projects 5G subscriptions will reach 5 billion by 2028.</p>',
                        'excerpt' => 'Global 5G subscriptions hit 3 billion as developing nations drive adoption, with projections reaching 5 billion by 2028.',
                        'seo_keywords' => '5G, mobile, subscriptions, Ericsson, telecommunications',
                        'author' => 'Staff Reporter',
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'Amazon Debuts AI-Powered Drone Delivery System with Autonomous Navigation',
                        'content' => '<p>Amazon has launched its next-generation Prime Air drone delivery system, featuring AI-powered autonomous navigation that avoids obstacles, birds, and weather hazards in real-time. The new drones can deliver packages weighing up to 5 kilograms within a 15-kilometer radius in under 30 minutes.</p><p>"We have spent years perfecting the safety and reliability of our drone system," said David Carbon, Vice President of Prime Air. "This is a major step toward making 30-minute delivery the new normal." The service is initially launching in 12 US cities, with expansion plans for the UK and Canada.</p>',
                        'excerpt' => 'Amazon\'s AI-powered drone delivery system launches in 12 US cities, promising 30-minute delivery for packages up to 5 kilograms.',
                        'seo_keywords' => 'Amazon, drone delivery, Prime Air, AI, autonomous',
                        'author' => 'Staff Reporter',
                        'published_at' => now()->subDays(1)->addHours(6),
                    ],
                    [
                        'title' => 'NVIDIA Stock Surges as AI Chip Demand Outpaces Supply Through 2027',
                        'content' => '<p>NVIDIA shares surged 12 percent in after-hours trading following the company\'s quarterly earnings report, which revealed that demand for its AI-optimized chips continues to significantly outpace supply. CEO Jensen Huang announced that the company\'s next-generation Blackwell Ultra architecture is already fully allocated through 2027.</p><p>"We are witnessing the industrial revolution of AI," Huang said during the earnings call. "Every major company and every country is building their own AI infrastructure." NVIDIA reported record revenue of $45 billion for the quarter, a 78 percent increase year-over-year.</p>',
                        'excerpt' => 'NVIDIA reports record $45B quarterly revenue as AI chip demand surges, with next-gen Blackwell Ultra allocated through 2027.',
                        'seo_keywords' => 'NVIDIA, stock, AI chips, earnings, Blackwell, semiconductor',
                        'author' => 'Staff Reporter',
                        'is_trending' => true,
                        'published_at' => now()->subHours(4),
                    ],
                    [
                        'title' => 'European Union Proposes Comprehensive AI Safety Regulations Framework',
                        'content' => '<p>The European Commission has proposed a comprehensive AI safety framework that would require companies to conduct mandatory risk assessments and implement safeguards for high-risk AI applications. The proposed regulations, which build upon the existing EU AI Act, include requirements for transparency, human oversight, and robust testing protocols.</p><p>"Europe is leading the way in ensuring AI development serves humanity," said Margrethe Vestager, European Commissioner for Digital Affairs. "These regulations strike a careful balance between innovation and protection." Industry groups have expressed mixed reactions, with some praising the clarity while others warn of potential compliance costs.</p>',
                        'excerpt' => 'EU proposes comprehensive AI safety regulations requiring mandatory risk assessments and transparency for high-risk AI applications.',
                        'seo_keywords' => 'EU, AI regulation, safety, artificial intelligence, policy',
                        'author' => 'Staff Reporter',
                        'published_at' => now()->subDays(2),
                    ],
                ],
            ],

            // === WORLD NEWS (10 articles) ===
            [
                'category_slug' => 'world-news',
                'articles' => [
                    [
                        'title' => 'Historic Climate Summit Concludes with Binding Emission Reduction Targets',
                        'content' => '<p>World leaders have reached a historic agreement at the 2026 United Nations Climate Summit in Geneva, committing to binding emission reduction targets that aim to achieve net-zero carbon emissions by 2050. The accord, signed by 195 nations, includes enforceable mechanisms for compliance and a $500 billion annual fund to support developing nations in their transition to clean energy.</p><p>"This is the moment the world came together to secure our common future," said UN Secretary-General António Guterres. "There is no planet B, and today we have chosen to protect the one we have." The agreement mandates a 60 percent reduction in emissions by 2035 compared to 2025 levels.</p>',
                        'excerpt' => '195 nations sign binding climate agreement with $500B annual fund for developing nations and enforceable emission targets.',
                        'seo_keywords' => 'climate summit, emissions, UN, Geneva, climate change, global agreement',
                        'is_trending' => true,
                        'published_at' => now()->subHours(3),
                    ],
                    [
                        'title' => 'United Nations Security Council Approves Landmark Cyber Warfare Treaty',
                        'content' => '<p>In a historic move, the United Nations Security Council has unanimously approved the first-ever international treaty governing cyber warfare, establishing clear rules of engagement and prohibiting attacks on civilian infrastructure including hospitals, power grids, and water systems. The treaty, negotiated over four years, represents a major step toward establishing international norms in cyberspace.</p><p>"This treaty transforms how nations approach conflict in the digital domain," said UN Secretary-General António Guterres. The agreement includes provisions for mutual assistance in responding to cyberattacks and establishes a permanent international cyber tribunal.</p>',
                        'excerpt' => 'UN Security Council unanimously approves first-ever international cyber warfare treaty prohibiting attacks on civilian infrastructure.',
                        'seo_keywords' => 'UN, cyber warfare, treaty, international law, cybersecurity',
                        'is_trending' => true,
                        'published_at' => now()->subHours(6),
                    ],
                    [
                        'title' => 'US-China Trade Relations Enter New Phase with Technology Cooperation Agreement',
                        'content' => '<p>The United States and China have signed a groundbreaking technology cooperation agreement that establishes joint research initiatives in artificial intelligence, quantum computing, and clean energy. The agreement, announced simultaneously from Washington and Beijing, marks a significant thaw in bilateral relations after years of trade tensions and technology restrictions.</p><p>"This agreement demonstrates that our two nations can compete where necessary and cooperate where possible," said US Secretary of State. The pact includes provisions for intellectual property protection and establishes a bilateral technology oversight committee.</p>',
                        'excerpt' => 'US and China sign technology cooperation agreement covering AI, quantum computing, and clean energy, signaling improved bilateral relations.',
                        'seo_keywords' => 'US, China, trade, technology, cooperation, AI, quantum computing',
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'Massive Earthquake Strikes Pacific Ring of Fire, Tsunami Warnings Issued',
                        'content' => '<p>A powerful earthquake measuring 8.2 on the Richter scale struck the Pacific Ring of Fire early this morning, triggering tsunami warnings for multiple coastal communities across Japan, the Philippines, and Indonesia. The earthquake\'s epicenter was located approximately 150 kilometers off the coast of Japan\'s Honshu Island at a depth of 30 kilometers.</p><p>Japanese authorities have issued immediate evacuation orders for coastal areas, with emergency services mobilizing across the region. "We are coordinating with all affected nations to ensure rapid response," said a spokesperson for the Pacific Tsunami Warning Center. Initial reports indicate significant structural damage but no immediate casualties.</p>',
                        'excerpt' => '8.2 magnitude earthquake strikes Pacific Ring of Fire, triggering tsunami warnings across Japan, Philippines, and Indonesia.',
                        'seo_keywords' => 'earthquake, tsunami, Pacific, Japan, natural disaster, Ring of Fire',
                        'is_trending' => true,
                        'published_at' => now()->subHour(),
                    ],
                    [
                        'title' => 'European Union Welcomes New Member States in Historic Enlargement Ceremony',
                        'content' => '<p>The European Union has officially welcomed six new member states in the bloc\'s largest expansion since 2007. The accession ceremony, held in Brussels, saw the flags of Ukraine, Moldova, Georgia, Albania, Montenegro, and Serbia raised alongside the EU flag for the first time. The enlargement brings the EU\'s total membership to 33 nations and extends its borders further eastward.</p><p>"Today Europe is stronger, more united, and more prosperous," said European Commission President Ursula von der Leyen. "We are fulfilling the dream of a united continent."</p>',
                        'excerpt' => 'EU welcomes six new member states including Ukraine in historic enlargement ceremony, expanding the bloc to 33 nations.',
                        'seo_keywords' => 'EU, enlargement, Ukraine, Europe, Brussels, expansion',
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'Global Refugee Numbers Reach Record High, UN Report Reveals',
                        'content' => '<p>The United Nations Refugee Agency has released its annual Global Trends report, revealing that the number of forcibly displaced people worldwide has reached a record 150 million. The report cites climate change, regional conflicts, and economic instability as primary drivers of displacement, with the most significant increases occurring in Sub-Saharan Africa and the Middle East.</p><p>"We are witnessing a humanitarian crisis of unprecedented scale," said Filippo Grandi, UN High Commissioner for Refugees. "International cooperation and shared responsibility have never been more critical." The report calls for increased humanitarian funding and long-term solutions for displaced populations.</p>',
                        'excerpt' => 'UN report reveals record 150 million forcibly displaced people worldwide, driven by climate change, conflicts, and economic instability.',
                        'seo_keywords' => 'refugees, UN, displacement, humanitarian crisis, climate change',
                        'author' => 'World Affairs Desk',
                        'published_at' => now()->subDays(3),
                    ],
                    [
                        'title' => 'India and Japan Sign Comprehensive Defense and Technology Partnership',
                        'content' => '<p>India and Japan have signed a comprehensive defense and technology partnership agreement that includes joint military exercises, technology transfer agreements, and collaborative development of defense systems. The agreement, signed during Japanese Prime Minister\'s visit to New Delhi, strengthens the strategic partnership between Asia\'s two largest democracies.</p><p>"This partnership reflects our shared vision for a free and open Indo-Pacific," said India\'s Prime Minister Narendra Modi. The agreement includes provisions for collaboration on space technology, cybersecurity, and semiconductor manufacturing.</p>',
                        'excerpt' => 'India and Japan strengthen strategic partnership with comprehensive defense and technology agreement covering joint military exercises and tech transfer.',
                        'seo_keywords' => 'India, Japan, defense, partnership, Indo-Pacific, technology',
                        'author' => 'World Affairs Desk',
                        'published_at' => now()->subDays(4),
                    ],
                    [
                        'title' => 'Canada Announces Ambitious National Housing Strategy to Address Affordability Crisis',
                        'content' => '<p>The Canadian government has unveiled an ambitious national housing strategy that aims to build 3.5 million new homes over the next decade and invest $50 billion in affordable housing initiatives. The plan includes measures to curb foreign ownership, provide direct subsidies for first-time homebuyers, and streamline municipal zoning regulations to accelerate construction.</p><p>"Every Canadian deserves a safe and affordable place to call home," said Prime Minister Justin Trudeau. "This is the most comprehensive housing investment in Canadian history." Housing affordability has become a defining political issue across Canada, with prices in major cities like Toronto and Vancouver among the highest in the world.</p>',
                        'excerpt' => 'Canada unveils $50 billion housing strategy to build 3.5 million new homes, addressing the nation\'s affordability crisis.',
                        'seo_keywords' => 'Canada, housing, affordability, strategy, Trudeau, real estate',
                        'author' => 'Staff Reporter',
                        'published_at' => now()->subDays(2)->addHours(6),
                    ],
                    [
                        'title' => 'Middle East Peace Process Gains Momentum with Historic Economic Cooperation Forum',
                        'content' => '<p>Regional leaders have gathered in Abu Dhabi for the historic Middle East Economic Cooperation Forum, marking the most significant multilateral engagement in decades. The forum, which includes representatives from Israel, Saudi Arabia, the UAE, Jordan, Egypt, and the Palestinian Authority, focuses on joint economic development projects, water sharing agreements, and renewable energy initiatives.</p><p>"Economic cooperation is the foundation upon which lasting peace can be built," said the UAE Foreign Minister. The forum has already yielded agreements on desalination projects and cross-border technology parks valued at over $20 billion.</p>',
                        'excerpt' => 'Regional leaders convene in Abu Dhabi for historic Middle East Economic Cooperation Forum, agreeing on $20 billion in joint development projects.',
                        'seo_keywords' => 'Middle East, peace, economic cooperation, Abu Dhabi, Israel, UAE',
                        'author' => 'World Affairs Desk',
                        'published_at' => now()->subDays(5),
                    ],
                    [
                        'title' => 'Antarctic Ice Shelf Collapse Accelerates, Scientists Warn of Accelerated Sea Level Rise',
                        'content' => '<p>A massive section of the Brunt Ice Shelf in Antarctica has collapsed, releasing an iceberg approximately the size of Greater London into the Weddell Sea. Scientists at the British Antarctic Survey warn that the collapse, the third major calving event in the region in two years, signals accelerated ice loss driven by warming ocean temperatures.</p><p>"We are witnessing the consequences of climate change in real-time," said Dr. Emily Watson, lead glaciologist at BAS. "The rate of ice loss is exceeding our worst-case predictions." The collapse does not directly contribute to sea level rise since the ice was already floating, but it accelerates the flow of land-based glaciers into the ocean.</p>',
                        'excerpt' => 'Antarctic ice shelf collapse accelerates as London-sized iceberg breaks away, with scientists warning of faster-than-predicted sea level rise.',
                        'seo_keywords' => 'Antarctica, ice shelf, climate change, sea level rise, glacier',
                        'author' => 'Science Correspondent',
                        'published_at' => now()->subDays(1)->addHours(12),
                    ],
                ],
            ],

            // === BUSINESS (8 articles) ===
            [
                'category_slug' => 'business',
                'articles' => [
                    [
                        'title' => 'Federal Reserve Holds Interest Rates Steady as Inflation Shows Signs of Cooling',
                        'content' => '<p>The Federal Reserve has decided to maintain its benchmark interest rate at 4.5 percent, signaling a cautious approach as recent economic data indicates that inflation is gradually moderating toward the central bank\'s 2 percent target. The decision was unanimous among Fed governors, who noted that while progress has been made, further evidence is needed before considering rate cuts.</p><p>"The economy is moving in the right direction, but we need to see sustained improvement," said Federal Reserve Chair Jerome Powell during a press conference. "We remain data-dependent and prepared to adjust policy as needed." Markets responded positively, with the S&P 500 gaining 1.2 percent on the announcement.</p>',
                        'excerpt' => 'Fed holds rates steady at 4.5% as inflation shows signs of cooling, with markets responding positively to cautious approach.',
                        'seo_keywords' => 'Federal Reserve, interest rates, inflation, economy, Powell',
                        'author' => 'Financial Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(4),
                    ],
                    [
                        'title' => 'Global Stock Markets Rally as Tech Sector Leads Broad-Based Recovery',
                        'content' => '<p>Global stock markets experienced their largest single-day rally in two years, with the technology sector leading a broad-based recovery driven by strong corporate earnings and easing concerns about interest rates. The S&P 500 surged 3.2 percent, while the tech-heavy Nasdaq composite jumped 4.1 percent, its best performance since November 2023.</p><p>"Investor sentiment has shifted dramatically," said Sarah Thompson, Chief Market Strategist at Goldman Sachs. "We are seeing a confluence of positive factors including better-than-expected earnings, moderating inflation, and robust consumer spending." European and Asian markets also posted significant gains.</p>',
                        'excerpt' => 'Global stocks rally sharply with S&P 500 up 3.2% and Nasdaq surging 4.1%, driven by strong tech earnings and rate optimism.',
                        'seo_keywords' => 'stock market, rally, S&P 500, Nasdaq, tech sector, earnings',
                        'author' => 'Financial Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(8),
                    ],
                    [
                        'title' => 'Oil Prices Plunge as OPEC+ Announces Unexpected Production Increase',
                        'content' => '<p>Crude oil prices plunged more than 8 percent following OPEC+\'s unexpected announcement that it would increase production by 1.5 million barrels per day starting next month. The decision reverses earlier production cuts and appears aimed at maintaining market share amid growing competition from US shale producers.</p><p>"OPEC+ is clearly concerned about losing market share to American producers," said energy analyst Michael Chen. "This is a strategic move that will have significant implications for global energy markets." Brent crude fell to $72 per barrel, its lowest level in six months.</p>',
                        'excerpt' => 'Oil prices crash 8% after OPEC+ unexpectedly boosts production by 1.5M barrels per day in strategic market share move.',
                        'seo_keywords' => 'oil prices, OPEC+, crude, energy, production, market',
                        'author' => 'Financial Correspondent',
                        'published_at' => now()->subHours(10),
                    ],
                    [
                        'title' => 'Bitcoin Surpasses $150,000 as Institutional Adoption Accelerates',
                        'content' => '<p>Bitcoin has surged past $150,000 for the first time, driven by accelerating institutional adoption and the approval of multiple spot Bitcoin exchange-traded funds. The cryptocurrency has gained over 120 percent year-to-date, with major financial institutions including BlackRock, Fidelity, and JPMorgan expanding their digital asset offerings.</p><p>"We are witnessing a fundamental shift in how institutional investors view digital assets," said Lisa Chang, Head of Digital Assets at BlackRock. "Bitcoin is increasingly being treated as a legitimate portfolio diversifier and store of value." The rally has also lifted other cryptocurrencies, with Ethereum reaching $12,000 and Solana hitting $400.</p>',
                        'excerpt' => 'Bitcoin breaks $150,000 as institutional adoption surges, with major Wall Street firms expanding digital asset offerings.',
                        'seo_keywords' => 'Bitcoin, cryptocurrency, institutional adoption, ETF, BlackRock',
                        'author' => 'Financial Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(2),
                    ],
                    [
                        'title' => 'Toyota Announces $20 Billion Investment in Solid-State Battery Production',
                        'content' => '<p>Toyota Motor Corporation has announced a $20 billion investment in solid-state battery production facilities, with plans to begin commercial production by 2028. The investment, the largest in Toyota\'s history, aims to establish the company as a leader in next-generation battery technology for electric vehicles.</p><p>"Solid-state batteries will revolutionize the EV industry," said Toyota CEO Koji Sato. "Our technology delivers twice the energy density of current lithium-ion batteries at half the cost." The investment will create approximately 10,000 jobs across facilities in Japan, the United States, and Europe.</p>',
                        'excerpt' => 'Toyota commits $20 billion to solid-state battery production, aiming to revolutionize EV technology with twice the energy density.',
                        'seo_keywords' => 'Toyota, solid-state battery, EV, investment, manufacturing',
                        'author' => 'Staff Reporter',
                        'published_at' => now()->subDays(1)->addHours(8),
                    ],
                    [
                        'title' => 'Amazon and Starbucks Face Landmark Labor Union Elections Across Multiple States',
                        'content' => '<p>In a pivotal moment for labor organizing in the United States, workers at Amazon fulfillment centers and Starbucks locations across multiple states are voting in union representation elections that could reshape labor relations in the service and logistics sectors. The elections, involving over 50,000 workers combined, represent the largest coordinated unionization effort since the 1930s.</p><p>"Workers are demanding fair wages, consistent schedules, and basic workplace respect," said labor historian Dr. Robert Martinez of UCLA. "The outcome of these elections will have profound implications for the American labor movement." Both companies have stated they respect workers\' rights to organize while expressing preference for direct employee relationships.</p>',
                        'excerpt' => 'Over 50,000 Amazon and Starbucks workers vote in landmark union elections that could reshape American labor relations.',
                        'seo_keywords' => 'Amazon, Starbucks, labor unions, workers rights, elections',
                        'author' => 'Staff Reporter',
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'AI Startup Raises $10 Billion in Record-Breaking Series C Funding Round',
                        'content' => '<p>Anthropic AI has raised $10 billion in the largest Series C funding round in technology history, valuing the company at $120 billion. The round was led by SoftBank Vision Fund, with participation from Google, Salesforce, and existing investors. The funding will be used to scale AI infrastructure and develop next-generation AI systems.</p><p>"We are building AI systems that will fundamentally transform how humanity approaches science, medicine, and problem-solving," said Dario Amodei, CEO of Anthropic. "This investment enables us to pursue our mission with the urgency it deserves." The company plans to triple its computing capacity and hire 5,000 additional researchers and engineers.</p>',
                        'excerpt' => 'Anthropic AI raises record $10B Series C at $120B valuation, signaling unprecedented investor confidence in AI technology.',
                        'seo_keywords' => 'Anthropic, AI, funding, Series C, SoftBank, venture capital',
                        'author' => 'Financial Correspondent',
                        'published_at' => now()->subDays(3),
                    ],
                    [
                        'title' => 'Global Trade Volume Hits Record High as Supply Chain Disruptions Ease',
                        'content' => '<p>Global trade volume has reached an all-time high, according to the latest World Trade Organization report, as supply chain disruptions that plagued the post-pandemic era have largely resolved. International merchandise trade grew by 8.5 percent year-over-year, driven by robust demand for manufactured goods, technology products, and renewable energy equipment.</p><p>"The global trading system has demonstrated remarkable resilience," said WTO Director-General Ngozi Okonjo-Iweala. "We are seeing particularly strong growth in trade of environmental goods and digital services." The report highlights that developing economies are increasingly participating in global value chains.</p>',
                        'excerpt' => 'Global trade reaches record high as supply chain disruptions ease, with 8.5% growth driven by technology and renewable energy.',
                        'seo_keywords' => 'global trade, WTO, supply chain, economy, record high',
                        'author' => 'Financial Correspondent',
                        'published_at' => now()->subDays(2)->addHours(10),
                    ],
                ],
            ],

            // === SCIENCE (7 articles) ===
            [
                'category_slug' => 'science',
                'articles' => [
                    [
                        'title' => 'James Webb Telescope Discovers Biosignatures on Exoplanet in Habitable Zone',
                        'content' => '<p>NASA\'s James Webb Space Telescope has detected compelling evidence of biosignatures in the atmosphere of an exoplanet located 120 light-years from Earth. The planet, designated K2-18b, orbits within the habitable zone of its star and shows strong spectral signatures of dimethyl sulfide, a molecule that on Earth is produced exclusively by biological processes.</p><p>"This is the most tantalizing evidence for life beyond our solar system we have ever seen," said Dr. Sarah Seager, an astrophysicist at MIT. "While we must remain cautious and consider alternative explanations, the presence of these molecules is exactly what we would expect from a living world." Further observations are planned to confirm the findings.</p>',
                        'excerpt' => 'James Webb Telescope detects potential biosignatures on exoplanet K2-18b, offering most compelling evidence yet for extraterrestrial life.',
                        'seo_keywords' => 'James Webb, exoplanet, biosignatures, life, space, NASA, astronomy',
                        'is_trending' => true,
                        'published_at' => now()->subHours(5),
                    ],
                    [
                        'title' => 'CRISPR Gene Therapy Successfully Treats Sickle Cell Disease in Large-Scale Trial',
                        'content' => '<p>A groundbreaking large-scale clinical trial has demonstrated that CRISPR-based gene therapy can effectively cure sickle cell disease, with 97 percent of patients remaining symptom-free two years after treatment. The trial, involving 1,200 patients across 40 medical centers worldwide, represents the most comprehensive validation of CRISPR technology for treating genetic disorders.</p><p>"We are witnessing the dawn of a new era in medicine," said Dr. Jennifer Doudna, Nobel laureate and CRISPR pioneer. "This trial proves that gene editing can provide lasting cures for devastating genetic diseases." The treatment involves editing patients\' own stem cells to produce healthy hemoglobin, eliminating the need for donor matches and immunosuppression.</p>',
                        'excerpt' => 'CRISPR gene therapy trial shows 97% success rate in curing sickle cell disease, marking major milestone in genetic medicine.',
                        'seo_keywords' => 'CRISPR, gene therapy, sickle cell, medical breakthrough, clinical trial',
                        'is_trending' => true,
                        'published_at' => now()->subHours(7),
                    ],
                    [
                        'title' => 'Scientists Develop Room-Temperature Superconductor, Confirmed by Multiple Laboratories',
                        'content' => '<p>In a breakthrough that could transform technology, scientists have developed a material that achieves superconductivity at room temperature and ambient pressure, confirmed by independent laboratories worldwide. The material, a modified form of hydrogen-rich graphite compound, conducts electricity with zero resistance at temperatures up to 25 degrees Celsius.</p><p>"This is the holy grail of condensed matter physics," said Dr. Robert Chen of the University of Tokyo. "Room-temperature superconductivity will revolutionize everything from energy transmission to medical imaging and quantum computing." The discovery has been replicated by laboratories in the United States, Germany, and Japan.</p>',
                        'excerpt' => 'Room-temperature superconductor confirmed by multiple labs, promising to revolutionize energy transmission and quantum computing.',
                        'seo_keywords' => 'superconductor, room temperature, physics, breakthrough, energy',
                        'is_trending' => true,
                        'published_at' => now()->subHours(12),
                    ],
                    [
                        'title' => 'NASA\'s Artemis Program Successfully Establishes Permanent Lunar Research Base',
                        'content' => '<p>NASA has announced the successful establishment of the Artemis Lunar Research Base, a permanently crewed facility at the Moon\'s south pole. The base, constructed over three years and 12 missions, can accommodate up to 24 astronauts and includes laboratories, living quarters, and in-situ resource utilization systems that extract water ice and produce breathable oxygen.</p><p>"This is humanity\'s first permanent settlement beyond Earth," said NASA Administrator Bill Nelson. "What we learn here will prepare us for the next giant leap: sending humans to Mars." The base is a collaboration between NASA, ESA, JAXA, and CSA, with a total investment of $40 billion.</p>',
                        'excerpt' => 'NASA establishes permanent lunar research base at Moon\'s south pole, marking humanity\'s first permanent settlement beyond Earth.',
                        'seo_keywords' => 'NASA, Artemis, Moon, lunar base, space exploration',
                        'author' => 'Science Correspondent',
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'Breakthrough in Nuclear Fusion: Commercial Reactor Achieves Net Energy Gain',
                        'content' => '<p>Commonwealth Fusion Systems has achieved a sustained net energy gain in its SPARC tokamak reactor, producing 50 percent more energy than consumed over a five-minute period. The achievement, confirmed by independent physicists, marks the first time a commercial fusion reactor has demonstrated net positive energy output, bringing clean, limitless energy closer to reality.</p><p>"This is the moment fusion energy became real," said Dr. Dennis Whyte, CFS Chief Scientist. "We have shown that fusion is not just scientifically possible but commercially viable." The company plans to build the first grid-connected fusion power plant by 2032.</p>',
                        'excerpt' => 'Commercial fusion reactor achieves sustained net energy gain, marking pivotal moment in the quest for clean, limitless energy.',
                        'seo_keywords' => 'nuclear fusion, energy, SPARC, Commonwealth Fusion, clean energy',
                        'author' => 'Science Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'New Study Reveals Microplastics Found in Human Brain Tissue for First Time',
                        'content' => '<p>A alarming new study published in Nature Medicine has detected microplastic particles in human brain tissue for the first time, raising urgent questions about the health impacts of plastic pollution. Researchers at the University of Amsterdam found microplastics in 100 percent of brain samples examined, with concentrations significantly higher than those found in liver or kidney tissue.</p><p>"The presence of microplastics in brain tissue is deeply concerning," said Dr. Maria van der Berg, lead author of the study. "We urgently need to understand what effects these particles may have on neurological health." The study found that individuals with dementia had substantially higher concentrations of microplastics in their brain tissue.</p>',
                        'excerpt' => 'Microplastics detected in human brain tissue for first time, with study finding significantly higher concentrations in dementia patients.',
                        'seo_keywords' => 'microplastics, brain, health, pollution, Nature Medicine',
                        'author' => 'Science Correspondent',
                        'published_at' => now()->subDays(3),
                    ],
                    [
                        'title' => 'World\'s First Successful Pig-to-Human Heart Transplant Patient Celebrates One Year',
                        'content' => '<p>The recipient of the world\'s first successful pig-to-human heart transplant has celebrated one year of survival with no signs of rejection, marking a historic milestone in xenotransplantation. The patient, 58-year-old David Bennett Jr., received the genetically modified pig heart at the University of Maryland Medical Center and has since returned to an active lifestyle.</p><p>"I wake up every day grateful for this second chance at life," Bennett said. The success has accelerated plans for larger clinical trials, with hopes that xenotransplantation could address the critical shortage of donor organs, where over 100,000 patients await transplants in the United States alone.</p>',
                        'excerpt' => 'Pig-to-human heart transplant patient celebrates one-year survival milestone, offering hope to thousands awaiting organ transplants.',
                        'seo_keywords' => 'xenotransplantation, heart transplant, pig, organ donation, medical breakthrough',
                        'author' => 'Science Correspondent',
                        'published_at' => now()->subDays(4),
                    ],
                ],
            ],

            // === HEALTH (6 articles) ===
            [
                'category_slug' => 'health',
                'articles' => [
                    [
                        'title' => 'WHO Declares End to Global Health Emergency as Pandemic Response Succeeds',
                        'content' => '<p>The World Health Organization has officially declared the end of the global health emergency that has affected international health systems for the past three years, citing successful vaccination campaigns, improved treatments, and widespread population immunity. The declaration marks a turning point in global public health efforts.</p><p>"This is a testament to what humanity can achieve through science and cooperation," said WHO Director-General Dr. Tedros Adhanom Ghebreyesus. "But we must not let our guard down. The lessons learned must inform our preparedness for future health threats." The WHO has outlined a new framework for pandemic prevention and response.</p>',
                        'excerpt' => 'WHO declares end to global health emergency, crediting successful vaccination and treatment campaigns, while urging continued vigilance.',
                        'seo_keywords' => 'WHO, health emergency, pandemic, vaccination, public health',
                        'author' => 'Health Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(6),
                    ],
                    [
                        'title' => 'Alzheimer\'s Disease Reversed in Clinical Trial Using Innovative Drug Cocktail',
                        'content' => '<p>A groundbreaking clinical trial has successfully reversed cognitive decline in patients with early-stage Alzheimer\'s disease using a combination therapy approach. The treatment, which combines three drugs targeting different aspects of the disease, resulted in significant improvement in memory and cognitive function in 78 percent of participants over 18 months.</p><p>"We are moving beyond merely slowing the disease to actively restoring function," said Dr. Lisa Park, lead researcher at the Banner Alzheimer\'s Institute. "This changes everything we thought possible in Alzheimer\'s treatment." The drug cocktail will now move to larger Phase III trials involving 5,000 patients across 100 medical centers.</p>',
                        'excerpt' => 'Alzheimer\'s disease reversed in landmark clinical trial, with 78% of patients showing significant cognitive improvement using drug cocktail.',
                        'seo_keywords' => 'Alzheimer\'s, dementia, clinical trial, drug cocktail, cognitive decline',
                        'author' => 'Health Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(9),
                    ],
                    [
                        'title' => 'FDA Approves First mRNA-Based Cancer Vaccine for Personalized Treatment',
                        'content' => '<p>The US Food and Drug Administration has approved the first mRNA-based cancer vaccine for the treatment of melanoma, marking a new frontier in personalized oncology. The vaccine, developed by Moderna in partnership with Merck, is customized to each patient\'s tumor genetic profile and trains the immune system to recognize and destroy cancer cells.</p><p>"This approval heralds a new era in cancer treatment," said Dr. Stephanie Caccomo, FDA spokesperson. "The personalized nature of this vaccine represents a paradigm shift in how we approach cancer therapy." Clinical trials showed a 65 percent reduction in cancer recurrence among treated patients compared to standard immunotherapy alone.</p>',
                        'excerpt' => 'FDA approves first personalized mRNA cancer vaccine for melanoma, showing 65% reduction in cancer recurrence in clinical trials.',
                        'seo_keywords' => 'FDA, mRNA, cancer vaccine, melanoma, Moderna, personalized medicine',
                        'author' => 'Health Correspondent',
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'Exercise Science Breakthrough: 10-Minute Daily Workout Equals 1-Hour Session',
                        'content' => '<p>A comprehensive study published in the Journal of the American Medical Association has found that high-intensity interval training sessions as short as 10 minutes can produce equivalent health benefits to hour-long moderate workouts. The five-year study involving 15,000 participants tracked cardiovascular health, metabolic markers, and longevity outcomes.</p><p>"Short bursts of intense activity are remarkably effective," said Dr. James Mitchell, lead author. "This is great news for people who struggle to find time for exercise." The study found that three 10-minute HIIT sessions per week reduced cardiovascular mortality risk by 38 percent.</p>',
                        'excerpt' => 'Study shows 10-minute daily HIIT workouts deliver same health benefits as hour-long sessions, with 38% reduction in cardiovascular risk.',
                        'seo_keywords' => 'exercise, HIIT, fitness, health, heart health, study',
                        'author' => 'Health Correspondent',
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'Mental Health Crisis Among Youth: New Digital Wellness Initiative Launched',
                        'content' => '<p>The US Surgeon General has launched a comprehensive Digital Wellness Initiative aimed at addressing the growing mental health crisis among young people, which has been linked to excessive social media use and screen time. The initiative includes new guidelines for technology companies, school-based mental health programs, and $500 million in funding for youth mental health services.</p><p>"We are facing an unprecedented mental health crisis among our youth," said Surgeon General Dr. Vivek Murthy. "This initiative represents a coordinated national response to protect the well-being of the next generation." The guidelines recommend limiting screen time to two hours per day for adolescents and include requirements for social media platforms to implement safety features.</p>',
                        'excerpt' => 'Surgeon General launches Digital Wellness Initiative with new social media guidelines and $500M for youth mental health services.',
                        'seo_keywords' => 'mental health, youth, social media, digital wellness, Surgeon General',
                        'author' => 'Health Correspondent',
                        'published_at' => now()->subDays(3),
                    ],
                    [
                        'title' => 'Revolutionary Weight Loss Drug Shows Promise in Treating Addiction Disorders',
                        'content' => '<p>Researchers have discovered that GLP-1 receptor agonists, the class of drugs that includes popular weight loss medications, show remarkable effectiveness in treating addiction disorders including alcohol use disorder, opioid addiction, and smoking cessation. A large-scale study found that patients taking these medications were 50 percent less likely to relapse into substance abuse.</p><p>"We stumbled upon something extraordinary," said Dr. Nora Volkow, Director of the National Institute on Drug Abuse. "These drugs appear to reduce cravings and reward-seeking behavior at a neurological level." Clinical trials for addiction treatment are now underway at 20 major medical centers.</p>',
                        'excerpt' => 'Weight loss drugs show 50% reduction in addiction relapse rates, opening new frontier in treating substance abuse disorders.',
                        'seo_keywords' => 'GLP-1, weight loss, addiction, substance abuse, medical research',
                        'author' => 'Health Correspondent',
                        'published_at' => now()->subDays(4),
                    ],
                ],
            ],

            // === SPORTS (6 articles) ===
            [
                'category_slug' => 'sports',
                'articles' => [
                    [
                        'title' => 'USA Wins Record-Breaking 120 Medals at Paris Summer Olympics',
                        'content' => '<p>Team USA has dominated the Paris Summer Olympics, winning a record-breaking 120 medals including 45 golds, surpassing the previous record set at the 2024 Tokyo Games. The performance was highlighted by extraordinary achievements in swimming, track and field, and gymnastics, with American athletes setting 12 world records during the games.</p><p>"This is the greatest performance by any US Olympic team in history," said US Olympic Committee CEO Sarah Hirshland. "Our athletes have inspired the nation." Breakout stars included a 17-year-old swimmer who won five gold medals and a track athlete who became the first person to win gold in both the 100-meter and 400-meter hurdles.</p>',
                        'excerpt' => 'Team USA sets Olympic record with 120 medals and 45 golds in Paris, featuring 12 world records across swimming, track, and gymnastics.',
                        'seo_keywords' => 'Olympics, Paris, Team USA, medals, swimming, track and field',
                        'is_trending' => true,
                        'published_at' => now()->subHours(8),
                    ],
                    [
                        'title' => 'Premier League Title Race Goes to Final Day as Three Teams Stay in Contention',
                        'content' => '<p>The English Premier League season has delivered one of its most dramatic conclusions ever, with three teams — Manchester City, Arsenal, and Liverpool — all capable of winning the title on the final day. The unprecedented three-way race has captivated the football world, with all three teams separated by just two points heading into the last match of the season.</p><p>"This is what makes the Premier League the most exciting league in the world," said former England captain Gary Lineker. "The pressure on the final day will be immense." All three matches will be played simultaneously on Sunday, with each team facing opponents fighting for their own objectives.</p>',
                        'excerpt' => 'Premier League title race reaches unprecedented climax with Manchester City, Arsenal, and Liverpool separated by just two points.',
                        'seo_keywords' => 'Premier League, football, Manchester City, Arsenal, Liverpool, title race',
                        'is_trending' => true,
                        'published_at' => now()->subHours(10),
                    ],
                    [
                        'title' => 'NBA Finals: Boston Celtics Win Championship in Seven-Game Thriller',
                        'content' => '<p>The Boston Celtics have won their 19th NBA championship, defeating the Denver Nuggets in a thrilling seven-game series that will be remembered as one of the greatest Finals in NBA history. The deciding Game 7 featured 14 lead changes and four overtime periods, with the Celtics ultimately prevailing 132-129.</p><p>"This is why we play the game," said Celtics head coach Joe Mazzulla. "The resilience this team showed is incredible." Celtics star Jayson Tatum was named Finals MVP after averaging 35 points, 12 rebounds, and 8 assists per game in the series.</p>',
                        'excerpt' => 'Boston Celtics win 19th NBA championship in epic seven-game Finals series against Denver Nuggets, featuring quadruple-overtime Game 7.',
                        'seo_keywords' => 'NBA, Celtics, championship, Finals, basketball, Tatum',
                        'author' => 'Sports Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(12),
                    ],
                    [
                        'title' => 'Women\'s World Cup: US Team Advances to Semifinals After Dominant Performance',
                        'content' => '<p>The United States Women\'s National Team has advanced to the World Cup semifinals with a commanding 4-0 victory over Germany, showcasing the depth and talent that has made them the most successful team in women\'s football history. The goals came from four different players, highlighting the team\'s balanced attacking approach that has overwhelmed opponents throughout the tournament.</p><p>"We are peaking at the right time," said USWNT head coach Emma Hayes. "The team\'s performance was clinical from start to finish." The US will face England in the semifinal, a rematch of the 2019 World Cup semifinal that the US won 2-1. The tournament has seen record attendance and viewership figures globally.</p>',
                        'excerpt' => 'US Women\'s National Team advances to World Cup semifinals with 4-0 victory over Germany, setting up showdown with England.',
                        'seo_keywords' => 'Women\'s World Cup, USWNT, football, soccer, Germany, England',
                        'author' => 'Sports Correspondent',
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'Wimbledon: Historic All-Teenager Final Marks New Era in Tennis',
                        'content' => '<p>The Wimbledon Championships have witnessed history as two teenagers contested the gentlemen\'s singles final for the first time in the Open Era. The final between 19-year-old Spanish sensation Carlos Alvarez and 18-year-old Canadian phenom Ethan Chen was a five-set epic that lasted over four hours, with Alvarez ultimately prevailing 9-7 in the deciding set.</p><p>"This is just the beginning," Alvarez said after the match. "We are going to have many more battles on the biggest stages." Tennis legends including Roger Federer and Rafael Nadal praised the quality of play, noting that the sport\'s future is in excellent hands.</p>',
                        'excerpt' => 'Wimbledon sees historic all-teenager final between Spanish and Canadian phenoms, marking new era in men\'s tennis.',
                        'seo_keywords' => 'Wimbledon, tennis, final, teenager, Alvarez, Chen',
                        'author' => 'Sports Correspondent',
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'Formula 1: Verstappen Secures Fifth Consecutive World Drivers Championship',
                        'content' => '<p>Max Verstappen has secured his fifth consecutive Formula 1 World Drivers Championship with three races remaining in the season, cementing his place among the greatest drivers in the sport\'s history. The Dutch driver\'s victory at the Japanese Grand Prix gave him an insurmountable lead in the standings, matching the record of consecutive championships held by Michael Schumacher.</p><p>"Every championship is special, but this one means so much given the challenges we faced this season," said Verstappen. "The team has been incredible." Verstappen\'s triumph has sparked debate about whether he might surpass Schumacher\'s record of seven championships before retiring.</p>',
                        'excerpt' => 'Max Verstappen captures fifth consecutive F1 World Championship, matching Schumacher\'s record at the Japanese Grand Prix.',
                        'seo_keywords' => 'F1, Verstappen, championship, Formula 1, racing, Schumacher',
                        'author' => 'Sports Correspondent',
                        'published_at' => now()->subDays(3),
                    ],
                ],
            ],

            // === ENTERTAINMENT (6 articles) ===
            [
                'category_slug' => 'entertainment',
                'articles' => [
                    [
                        'title' => 'Oscar-Winning Director Announces Epic Sci-Fi Trilogy Filmed Back-to-Back',
                        'content' => '<p>Academy Award-winning director Christopher Nolan has announced an ambitious sci-fi trilogy that will be filmed back-to-back over the next four years. The project, titled "The Chronos Paradox," explores humanity\'s relationship with time and technology, and features an ensemble cast including Cillian Murphy, Zendaya, and Denzel Washington.</p><p>"This is the most challenging project I have ever undertaken," Nolan said at a press conference in London. "We are pushing the boundaries of what cinema can achieve." The trilogy carries a combined budget of $500 million, making it the most expensive film production in history.</p>',
                        'excerpt' => 'Christopher Nolan announces $500 million sci-fi trilogy "The Chronos Paradox" with all-star cast including Murphy, Zendaya, and Washington.',
                        'seo_keywords' => 'Christopher Nolan, film, sci-fi, trilogy, cinema, Hollywood',
                        'is_trending' => true,
                        'published_at' => now()->subHours(4),
                    ],
                    [
                        'title' => 'Taylor Swift Announces Global Stadium Tour Following Record-Breaking Album',
                        'content' => '<p>Taylor Swift has announced a massive global stadium tour in support of her latest album, which broke streaming records on its opening day with 500 million streams worldwide. The tour will visit 45 cities across six continents, with tickets going on sale next month. Industry analysts project the tour could gross over $2 billion, surpassing Swift\'s previous record-breaking Eras Tour.</p><p>"This album represents a new chapter," Swift said in a statement. "I cannot wait to share these songs with fans around the world." The tour will feature state-of-the-art production including augmented reality elements and a fully immersive audio experience.</p>',
                        'excerpt' => 'Taylor Swift announces $2B global stadium tour after album shatters streaming records with 500 million opening day streams.',
                        'seo_keywords' => 'Taylor Swift, tour, music, album, streaming, concert',
                        'is_trending' => true,
                        'published_at' => now()->subHours(6),
                    ],
                    [
                        'title' => 'Streaming Wars Intensify as Netflix, Disney+, and Apple TV+ Post Record Subscriber Numbers',
                        'content' => '<p>The streaming wars have reached a new intensity as all major platforms report record subscriber numbers in their latest quarterly earnings. Netflix added 15 million subscribers, reaching 350 million globally, while Disney+ added 10 million and Apple TV+ grew by 8 million, driven by hit original content and expanded international offerings.</p><p>"The streaming market is far from saturated," said media analyst Rebecca Chen of Deloitte. "The key differentiator is content quality, and we are seeing a golden age of television production." Combined spending on original content across all platforms is projected to exceed $200 billion this year.</p>',
                        'excerpt' => 'Netflix reaches 350M global subscribers as streaming platforms post record growth amid $200B content spending boom.',
                        'seo_keywords' => 'streaming, Netflix, Disney+, Apple TV+, entertainment, media',
                        'author' => 'Entertainment Correspondent',
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'Video Game Industry Reaches $500 Billion as VR and AI Transform Gaming',
                        'content' => '<p>The global video game industry has surpassed $500 billion in annual revenue for the first time, driven by the explosive growth of virtual reality gaming and AI-powered game development. The milestone reflects the industry\'s transformation from entertainment niche to cultural and economic powerhouse, now larger than the film and music industries combined.</p><p>"We are in a golden age of gaming," said Phil Spencer, CEO of Microsoft Gaming. "AI is enabling developers to create richer, more responsive worlds, while VR is delivering unprecedented immersion." The launch of new VR headsets from multiple manufacturers has driven a 200 percent increase in VR game sales.</p>',
                        'excerpt' => 'Video game industry surpasses $500B as VR gaming surges 200% and AI transforms game development, outpacing film and music combined.',
                        'seo_keywords' => 'video games, VR, AI, gaming industry, revenue, entertainment',
                        'author' => 'Entertainment Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'Grammy Awards 2026: Genre-Bending Artists Dominate Major Categories',
                        'content' => '<p>The 2026 Grammy Awards celebrated a new wave of genre-bending artists who are redefining musical boundaries. The biggest winners included a hip-hop artist whose orchestral album won Album of the Year, a country singer who won Best Pop Solo Performance for a song blending bluegrass with electronic music, and a Nigerian artist who took home Best New Artist.</p><p>"Music has no borders, no rules, no limits," said Album of the Year winner Kendrick Lamar during his acceptance speech. The ceremony drew 25 million viewers, the highest ratings in a decade, and featured groundbreaking performances incorporating AI-generated visuals and holographic collaborations.</p>',
                        'excerpt' => 'Genre-bending artists dominate 2026 Grammys as Kendrick Lamar wins Album of the Year and ceremony draws 25M viewers.',
                        'seo_keywords' => 'Grammys, music, awards, Kendrick Lamar, entertainment',
                        'author' => 'Entertainment Correspondent',
                        'published_at' => now()->subDays(3),
                    ],
                    [
                        'title' => 'Broadway Sees Record-Breaking Season with Diverse New Productions',
                        'content' => '<p>Broadway has concluded its most successful season in history, grossing over $2 billion in ticket sales, driven by a diverse lineup of new productions that attracted record audiences. The season was highlighted by several original plays and musicals from underrepresented creators, with shows exploring themes ranging from historical events to contemporary social issues.</p><p>"Broadway is experiencing a creative renaissance," said Charlotte St. Martin, President of the Broadway League. "Audiences are hungry for stories that reflect the full diversity of human experience." The season also saw average ticket prices rise to $150, reflecting strong demand and limited capacity.</p>',
                        'excerpt' => 'Broadway sets record with $2B season as diverse new productions attract unprecedented audiences to the Great White Way.',
                        'seo_keywords' => 'Broadway, theatre, record, ticket sales, diversity, entertainment',
                        'author' => 'Entertainment Correspondent',
                        'published_at' => now()->subDays(4),
                    ],
                ],
            ],

            // === POLITICS (6 articles) ===
            [
                'category_slug' => 'politics',
                'articles' => [
                    [
                        'title' => 'US Congress Passes Comprehensive Immigration Reform After Years of Deadlock',
                        'content' => '<p>The United States Congress has passed comprehensive immigration reform legislation after years of political deadlock, representing the most significant overhaul of the immigration system in four decades. The bill, which passed with bipartisan support in both chambers, includes a pathway to citizenship for undocumented immigrants, enhanced border security measures, and a reformed visa system for skilled workers.</p><p>"Today we proved that democracy can still deliver," said the Senate Majority Leader. The legislation, which runs over 2,000 pages, was the result of 18 months of negotiations between lawmakers from both parties. President has indicated he will sign the bill into law immediately.</p>',
                        'excerpt' => 'Congress passes landmark immigration reform with bipartisan support, including citizenship pathway and border security measures.',
                        'seo_keywords' => 'Congress, immigration reform, bipartisan, legislation, politics',
                        'author' => 'Political Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(3),
                    ],
                    [
                        'title' => 'UK Prime Minister Unveils Sweeping Constitutional Reform Package',
                        'content' => '<p>The UK Prime Minister has unveiled a sweeping constitutional reform package that includes the abolition of the House of Lords, the introduction of proportional representation for general elections, and the devolution of significant powers to regional assemblies. The proposals, described as the most significant constitutional changes since the 1997 Labour government\'s reforms, will be subject to a national referendum.</p><p>"The time has come to modernize our democracy," the Prime Minister told the House of Commons. "These reforms will make our political system fit for the 21st century." The opposition has called for a thorough public debate before the referendum, which is expected to take place within the next year.</p>',
                        'excerpt' => 'UK PM proposes constitutional reforms including House of Lords abolition and proportional representation, subject to national referendum.',
                        'seo_keywords' => 'UK, constitutional reform, House of Lords, proportional representation, politics',
                        'author' => 'Political Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(7),
                    ],
                    [
                        'title' => 'Canadian Federal Election Results in Historic Minority Government Coalition',
                        'content' => '<p>Canada\'s federal election has resulted in a historic outcome, with no party winning a majority and the formation of the country\'s first three-party coalition government. The coalition, comprising the Liberal Party, the New Democratic Party, and the Green Party, has agreed on a shared policy agenda focusing on climate action, affordable housing, and healthcare expansion.</p><p>"Canadians have asked us to work together, and that is exactly what we will do," said the incoming Prime Minister. The coalition agreement includes a commitment to a national pharmacare program, rapid emissions reduction targets, and electoral reform within two years.</p>',
                        'excerpt' => 'Canada forms historic three-party coalition government focused on climate action, affordable housing, and national pharmacare.',
                        'seo_keywords' => 'Canada, election, coalition, government, politics, Liberal, NDP, Green',
                        'author' => 'Political Correspondent',
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'European Parliament Approves Landmark Digital Taxation Framework',
                        'content' => '<p>The European Parliament has approved a comprehensive digital taxation framework that will impose a 3 percent revenue tax on large technology companies operating in the European Union. The measure, expected to generate €45 billion annually, is designed to ensure that digital giants pay their fair share of taxes in the markets where they generate revenue.</p><p>"For too long, digital companies have exploited loopholes to avoid taxation," said the European Parliament President. "This framework establishes a fair and transparent system for the digital age." The legislation includes provisions for small business exemptions and requires annual transparency reporting from affected companies.</p>',
                        'excerpt' => 'EU approves digital tax framework imposing 3% levy on tech giants, expected to generate €45 billion annually for member states.',
                        'seo_keywords' => 'EU, digital tax, technology, regulation, taxation, Europe',
                        'author' => 'Political Correspondent',
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'Brazil Launches Ambitious Amazon Rainforest Restoration Program',
                        'content' => '<p>The Brazilian government has launched the most ambitious rainforest restoration program in history, committing to reforest 30 million hectares of the Amazon over the next decade. The $50 billion initiative, funded through a combination of government spending, international partnerships, and carbon credit sales, aims to restore degraded areas and protect indigenous territories.</p><p>"The Amazon is our most precious resource and our shared responsibility," said Brazilian President. "This program will create millions of green jobs while protecting the planet\'s lungs." The initiative includes satellite monitoring systems and increased enforcement against illegal logging and mining.</p>',
                        'excerpt' => 'Brazil launches $50 billion Amazon reforestation program targeting 30 million hectares, creating millions of green jobs.',
                        'seo_keywords' => 'Brazil, Amazon, reforestation, climate, environment, rainforest',
                        'author' => 'Political Correspondent',
                        'published_at' => now()->subDays(3),
                    ],
                    [
                        'title' => 'Germany and France Propose Joint European Defense Strategy',
                        'content' => '<p>Germany and France have jointly proposed a comprehensive European Defense Strategy that includes a unified European military command, joint procurement of defense equipment, and the establishment of a European rapid reaction force. The proposal, presented to the European Council, represents the most ambitious attempt yet to create an independent European defense capability.</p><p>"Europe must take greater responsibility for its own security," said the German Chancellor. "This strategy strengthens both NATO and our European autonomy." The plan includes commitments to increase defense spending to 2.5 percent of GDP for all participating nations and the creation of a European defense innovation fund.</p>',
                        'excerpt' => 'Germany and France propose unified European defense strategy including joint military command and rapid reaction force.',
                        'seo_keywords' => 'Germany, France, EU, defense, military, NATO, Europe',
                        'author' => 'Political Correspondent',
                        'published_at' => now()->subDays(4),
                    ],
                ],
            ],
        ];

        foreach ($articles as $group) {
            $category = $categories->get($group['category_slug']);
            if (!$category) {
                continue;
            }

            foreach ($group['articles'] as $index => $articleData) {
                $title = $articleData['title'];
                $slug = Str::slug($title);

                if (Article::where('slug', $slug)->exists()) {
                    continue;
                }

                $publishedAt = $articleData['published_at'] ?? now()->subDays($index + 1);

                $imageResult = $this->getImageForCategory($group['category_slug']);

                Article::create([
                    'category_id' => $category->id,
                    'title' => $title,
                    'slug' => $slug,
                    'content' => $articleData['content'],
                    'excerpt' => $articleData['excerpt'],
                    'featured_image' => $imageResult['url'],
                    'image_credit' => $imageResult['credit'],
                    'image_source' => 'placeholder',
                    'author' => $articleData['author'] ?? 'News Desk',
                    'source' => $articleData['source'] ?? 'Staff Reports',
                    'is_published' => true,
                    'published_at' => $publishedAt,
                    'seo_title' => Str::limit($title, 60, ''),
                    'seo_description' => $articleData['excerpt'],
                    'seo_keywords' => $articleData['seo_keywords'] ?? '',
                    'reading_time_minutes' => rand(3, 8),
                    'is_trending' => $articleData['is_trending'] ?? false,
                ]);
            }
        }

        $this->command->info('✓ ' . Article::count() . ' realistic articles seeded');
    }

    protected function getImageForCategory(string $slug): array
    {
        $images = [
            'technology' => ['url' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1200&h=630&fit=crop', 'credit' => 'Unsplash'],
            'world-news' => ['url' => 'https://images.unsplash.com/photo-1451187580459-43490279c0fa?w=1200&h=630&fit=crop', 'credit' => 'Unsplash'],
            'business' => ['url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=1200&h=630&fit=crop', 'credit' => 'Unsplash'],
            'science' => ['url' => 'https://images.unsplash.com/photo-1507413245164-6160d8298b31?w=1200&h=630&fit=crop', 'credit' => 'Unsplash'],
            'health' => ['url' => 'https://images.unsplash.com/photo-1559757175-5700dde675bc?w=1200&h=630&fit=crop', 'credit' => 'Unsplash'],
            'sports' => ['url' => 'https://images.unsplash.com/photo-1461896836934-bd45ba8fcf9b?w=1200&h=630&fit=crop', 'credit' => 'Unsplash'],
            'entertainment' => ['url' => 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?w=1200&h=630&fit=crop', 'credit' => 'Unsplash'],
            'politics' => ['url' => 'https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=1200&h=630&fit=crop', 'credit' => 'Unsplash'],
        ];

        return $images[$slug] ?? [
            'url' => 'https://images.unsplash.com/photo-1504711434969-e33886168d6c?w=1200&h=630&fit=crop',
            'credit' => 'Unsplash',
        ];
    }
}
