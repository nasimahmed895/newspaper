<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TrendingNewsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all()->keyBy('slug');

        $articles = [
            // === TECHNOLOGY (8 articles) ===
            [
                'category_slug' => 'technology',
                'articles' => [
                    [
                        'title' => 'AI Coding Assistants Now Write 45% of Production Code at Major Tech Firms',
                        'content' => '<p>Artificial intelligence coding assistants have reached a tipping point, with major technology companies reporting that nearly half of all production code is now AI-generated. GitHub Copilot, Amazon CodeWhisperer, and Google\'s Gemini Code Assist are leading the transformation, with developers reporting 55% productivity gains.</p><p>"We\'ve crossed the rubicon," said GitHub CEO Thomas Dohmke. "AI is no longer a novelty tool — it\'s an essential part of the modern development workflow." The shift has sparked debate about the future of software engineering roles and code quality standards.</p>',
                        'excerpt' => 'AI coding assistants now generate 45% of production code at major tech firms, with developers reporting 55% productivity improvements.',
                        'seo_keywords' => 'AI, coding, GitHub Copilot, software development, programming',
                        'author' => 'Tech Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(2),
                    ],
                    [
                        'title' => 'Apple Vision Pro 2 Ships With Half the Weight and Double the Battery Life',
                        'content' => '<p>Apple has shipped the second-generation Vision Pro headset, addressing the two biggest criticisms of the original model. The Vision Pro 2 weighs just 350 grams — down from 650 grams — and delivers up to 4 hours of battery life on a single charge. The device also features a new M5 Ultra chip with dedicated AI processing cores.</p><p>"We listened to our users and re-engineered everything," said Apple\'s VP of Hardware Engineering. Pre-orders sold out within hours, with analysts projecting 5 million units shipped in the first quarter.</p>',
                        'excerpt' => 'Apple Vision Pro 2 launches with 350g weight, 4-hour battery, and M5 Ultra chip — pre-orders sell out immediately.',
                        'seo_keywords' => 'Apple, Vision Pro, AR, VR, headset, M5',
                        'author' => 'Tech Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(5),
                    ],
                    [
                        'title' => 'World\'s First AI-Developed Vaccine Begins Human Trials in the UK',
                        'content' => '<p>A vaccine designed entirely by artificial intelligence has entered Phase I human trials in the United Kingdom. The AI, developed by London-based startup Vaxine AI, analyzed millions of viral protein structures and designed a novel vaccine candidate in just 11 days — a process that traditionally takes years.</p><p>"This is a watershed moment for AI in medicine," said Dr. Sarah Chen, Vaxine AI\'s Chief Scientific Officer. "The AI identified immunological targets that human researchers had overlooked for decades." The vaccine targets a respiratory virus with pandemic potential.</p>',
                        'excerpt' => 'First AI-designed vaccine enters human trials in the UK after AI identified novel immunological targets in just 11 days.',
                        'seo_keywords' => 'AI, vaccine, healthcare, biotech, clinical trial',
                        'author' => 'Tech Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(8),
                    ],
                    [
                        'title' => 'Elon Musk\'s Neuralink Announces Brain-Computer Interface for General Consumers',
                        'content' => '<p>Neuralink has received FDA approval to begin commercial sales of its brain-computer interface device to the general public, marking a historic transition from medical device to consumer technology. The N2 implant, which requires a 15-minute outpatient procedure, enables direct neural control of computers and smartphones.</p><p>"This is like going from a keyboard to telepathy," said Neuralink founder Elon Musk. The initial consumer release will focus on productivity applications, with a planned SDK for third-party developers. Privacy advocates have raised concerns about neural data protection.</p>',
                        'excerpt' => 'Neuralink receives FDA approval for consumer brain-computer interface, enabling direct neural control of digital devices.',
                        'seo_keywords' => 'Neuralink, brain-computer interface, Elon Musk, neurotechnology, FDA',
                        'author' => 'Tech Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(10),
                    ],
                    [
                        'title' => 'Quantum Internet Achieves First Cross-Continental Data Transmission',
                        'content' => '<p>Scientists have achieved the first cross-continental quantum data transmission, successfully sending entangled photons between research stations in Europe and North America. The breakthrough, published in Nature, demonstrates the feasibility of a quantum internet resistant to any form of eavesdropping.</p><p>"This proves that quantum communication networks can operate at global scale," said Dr. Stephanie Richter of the Delft University of Technology. The experiment used a combination of fiber optic cables and satellite links to maintain quantum coherence across 7,000 kilometers.</p>',
                        'excerpt' => 'First quantum data transmission successfully sent between Europe and North America, paving the way for unhackable global networks.',
                        'seo_keywords' => 'quantum internet, quantum computing, physics, communication, Nature',
                        'author' => 'Science & Tech Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(14),
                    ],
                    [
                        'title' => 'Ride-Hailing Giants Launch Autonomous Fleets in 20 Major Cities Worldwide',
                        'content' => '<p>Uber and Lyft have simultaneously launched fully autonomous ride-hailing fleets across 20 major cities worldwide, marking the largest commercial deployment of self-driving vehicles in history. The fleets, using vehicles equipped with LIDAR and AI navigation systems from Waymo and Cruise, offer rides at 40% below traditional prices.</p><p>"This is the future of urban mobility," said Uber CEO Dara Khosrowshahi. "Autonomous vehicles are safer, cheaper, and more efficient than human-driven cars." City officials have implemented dedicated autonomous vehicle lanes and updated traffic regulations to accommodate the transition.</p>',
                        'excerpt' => 'Uber and Lyft launch autonomous fleets in 20 cities worldwide, offering rides 40% cheaper than traditional services.',
                        'seo_keywords' => 'autonomous vehicles, Uber, Lyft, self-driving, Waymo, Cruise',
                        'author' => 'Tech Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(18),
                    ],
                    [
                        'title' => 'New 6G Standard Promises 100x Speed Boost Over 5G, Expected by 2030',
                        'content' => '<p>The International Telecommunication Union has published the preliminary specifications for 6G wireless networks, promising data speeds 100 times faster than current 5G networks with latency below 0.1 milliseconds. The standard, expected to be finalized by 2028, will enable holographic communications, real-time digital twins, and pervasive AI integration.</p><p>"6G will fundamentally change how we interact with technology," said ITU Secretary-General Doreen Bogdan-Martin. "It\'s not just about faster downloads — it\'s about creating a seamless digital-physical continuum." Research labs in South Korea, China, and the US have already demonstrated early 6G prototypes.</p>',
                        'excerpt' => 'ITU publishes 6G specifications promising 100x speed over 5G with sub-millisecond latency, targeting commercial rollout by 2030.',
                        'seo_keywords' => '6G, wireless, ITU, telecommunications, 5G, mobile',
                        'author' => 'Tech Correspondent',
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'Cyberattack on Major Healthcare Provider Exposes 50 Million Patient Records',
                        'content' => '<p>A sophisticated ransomware attack on UnitedHealth Group has exposed the medical records of approximately 50 million patients, making it the largest healthcare data breach in history. The attackers, believed to be a state-backed group, demanded a $100 million ransom and leaked a sample of the data to prove their claims.</p><p>"This is a wake-up call for the healthcare industry," said cybersecurity expert Lisa Foreman of Mandiant. "Patient data is among the most sensitive personal information, and its protection must be treated as a national security priority." Federal investigators have launched a probe, and the company is offering free credit monitoring to affected patients.</p>',
                        'excerpt' => 'Ransomware attack on UnitedHealth Group exposes 50 million patient records in largest healthcare data breach in history.',
                        'seo_keywords' => 'cyberattack, healthcare, data breach, ransomware, UnitedHealth, security',
                        'author' => 'Cybersecurity Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(1),
                    ],
                ],
            ],

            // === WORLD NEWS (8 articles) ===
            [
                'category_slug' => 'world-news',
                'articles' => [
                    [
                        'title' => 'Japan Declares State of Emergency as Earthquake Recovery Efforts Continue',
                        'content' => '<p>Japan has declared a state of emergency in the Kanto region as recovery efforts continue following the 8.2 magnitude earthquake that struck three days ago. The death toll has reached 450, with over 3,000 injured and tens of thousands displaced. Rescue teams from 25 countries have arrived to assist in search and recovery operations.</p><p>"We have not faced a disaster of this magnitude since the 2011 earthquake," said Japanese Prime Minister. "The resilience of the Japanese people will see us through this crisis." International aid pledges have exceeded $2 billion, with the United Nations coordinating relief efforts.</p>',
                        'excerpt' => 'Japan earthquake death toll reaches 450 as international rescue teams from 25 nations join recovery efforts in Kanto region.',
                        'seo_keywords' => 'Japan, earthquake, disaster, emergency, relief, tsunami',
                        'is_trending' => true,
                        'published_at' => now()->subHours(4),
                    ],
                    [
                        'title' => 'African Union Launches Single Continental Currency at Historic Summit',
                        'content' => '<p>The African Union has officially launched the Afri, a single continental currency shared by 44 member nations, in a historic move aimed at boosting intra-African trade and economic integration. The currency, which will initially circulate alongside existing national currencies before a full transition, is backed by the newly established African Central Bank.</p><p>"Today, Africa takes its rightful place in the global economy," said African Union Commission Chair Moussa Faki Mahamat. Economists project the single currency could increase intra-African trade by 60% within five years and create the world\'s largest free trade zone by population.</p>',
                        'excerpt' => 'African Union launches single continental currency across 44 nations, projected to boost intra-African trade by 60%.',
                        'seo_keywords' => 'African Union, currency, Afri, economy, trade, Africa',
                        'is_trending' => true,
                        'published_at' => now()->subHours(7),
                    ],
                    [
                        'title' => 'Global Population Reaches 9 Billion, UN Reports Slowing Growth Rate',
                        'content' => '<p>The global population has officially reached 9 billion, according to the United Nations Department of Economic and Social Affairs. However, the report highlights that the growth rate has slowed to its lowest level since 1950, with more than 60 countries now experiencing population declines.</p><p>"We are entering a new demographic era," said UN Under-Secretary-General Liu Zhenmin. "The combination of aging populations in developed nations and declining fertility rates globally will reshape economies and societies in unprecedented ways." The report projects the population will peak at 9.5 billion by 2050 before beginning a gradual decline.</p>',
                        'excerpt' => 'World population reaches 9 billion with growth rate at lowest since 1950, projected to peak at 9.5 billion by 2050.',
                        'seo_keywords' => 'population, UN, demographics, global, fertility, aging',
                        'author' => 'World Affairs Desk',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1)->addHours(2),
                    ],
                    [
                        'title' => 'North and South Korea Sign Permanent Peace Treaty, Ending 75-Year Conflict',
                        'content' => '<p>North and South Korea have signed a permanent peace treaty officially ending the Korean War, which has technically been ongoing for 75 years. The historic agreement, signed at the border village of Panmunjom, includes denuclearization commitments, family reunification programs, and the establishment of a joint economic zone along the demilitarized zone.</p><p>"We have chosen peace over conflict, cooperation over confrontation," said South Korean President. North Korean leader Kim Jong Un called the agreement "a new chapter in Korean history." The treaty has been welcomed by world leaders, with the United Nations pledging support for the transition.</p>',
                        'excerpt' => 'North and South Korea sign permanent peace treaty ending 75-year conflict, with denuclearization and economic cooperation agreements.',
                        'seo_keywords' => 'Korea, peace treaty, North Korea, South Korea, denuclearization, Panmunjom',
                        'is_trending' => true,
                        'published_at' => now()->subHours(6),
                    ],
                    [
                        'title' => 'Severe Drought Forces Water Rationing Across Southern Europe',
                        'content' => '<p>A historic drought across Southern Europe has forced governments in Spain, Italy, Greece, and Portugal to implement mandatory water rationing measures. Reservoirs in the region are at less than 20% capacity, with some reaching their lowest levels in recorded history. Agricultural losses are estimated at €15 billion, threatening food supplies across the continent.</p><p>"This is the new reality of climate change," said European Environment Commissioner. "We must adapt our water management and agriculture systems to a drier future." Emergency desalination plants are being fast-tracked, and the EU has released €5 billion in emergency agricultural aid.</p>',
                        'excerpt' => 'Southern Europe implements mandatory water rationing as historic drought drains reservoirs to 20% capacity, causing €15B in agricultural losses.',
                        'seo_keywords' => 'drought, Europe, water rationing, climate change, agriculture',
                        'author' => 'World Affairs Desk',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1)->addHours(10),
                    ],
                    [
                        'title' => 'Australia and Indonesia Sign Landmark Defense and Maritime Security Pact',
                        'content' => '<p>Australia and Indonesia have signed their most comprehensive defense and maritime security agreement, including joint naval patrols in the South China Sea, intelligence sharing arrangements, and coordinated disaster response protocols. The pact, signed in Jakarta, represents a significant strategic alignment between two of the Indo-Pacific region\'s largest nations.</p><p>"This partnership reflects our shared commitment to a stable and prosperous Indo-Pacific," said Australian Prime Minister. The agreement includes provisions for joint military exercises, technology transfers, and a maritime surveillance cooperation center to be established in northern Australia.</p>',
                        'excerpt' => 'Australia and Indonesia sign landmark defense pact with joint South China Sea patrols and intelligence sharing agreement.',
                        'seo_keywords' => 'Australia, Indonesia, defense, Indo-Pacific, South China Sea, maritime',
                        'author' => 'World Affairs Desk',
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'Saudi Arabia and Israel Normalize Relations in Historic US-Brokered Deal',
                        'content' => '<p>Saudi Arabia and Israel have officially normalized diplomatic relations in a historic agreement brokered by the United States, marking the most significant diplomatic breakthrough in the Middle East in decades. The deal includes security guarantees, economic cooperation, and the establishment of full diplomatic missions in both countries.</p><p>"This is a truly historic achievement," said the US President. "It demonstrates that peace is possible in the Middle East." The agreement also includes US-Saudi defense cooperation and support for a civilian nuclear program in the kingdom, along with commitments to advance a two-state solution for Palestinians.</p>',
                        'excerpt' => 'Saudi Arabia and Israel normalize relations in US-brokered deal, marking biggest Middle East diplomatic breakthrough in decades.',
                        'seo_keywords' => 'Saudi Arabia, Israel, normalization, Middle East, peace, US',
                        'author' => 'World Affairs Desk',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1)->addHours(6),
                    ],
                    [
                        'title' => 'Mega-Ship Blockage in Suez Canal Resolved After Week-Long Disruption',
                        'content' => '<p>A massive container ship that ran aground in the Suez Canal has been successfully refloated after blocking one of the world\'s busiest maritime trade routes for six days. The 400-meter vessel, operated by a major shipping line, became stuck during a sandstorm, causing a traffic jam of over 300 vessels and disrupting global supply chains.</p><p>"We pulled out all the stops to clear the canal as quickly as possible," said Suez Canal Authority Chairman. The blockage is estimated to have cost the global economy approximately $10 billion in delayed shipments and rerouting fees. Shipping experts warn that the disruption will be felt in consumer markets for weeks.</p>',
                        'excerpt' => 'Suez Canal reopened after week-long mega-ship blockage caused $10B in global trade disruptions and backlog of 300 vessels.',
                        'seo_keywords' => 'Suez Canal, shipping, trade, blockage, supply chain, Egypt',
                        'author' => 'World Affairs Desk',
                        'is_trending' => true,
                        'published_at' => now()->subHours(12),
                    ],
                ],
            ],

            // === BUSINESS (7 articles) ===
            [
                'category_slug' => 'business',
                'articles' => [
                    [
                        'title' => 'Dow Jones Surges Past 50,000 Milestone for the First Time',
                        'content' => '<p>The Dow Jones Industrial Average surged past the 50,000 mark for the first time in history, driven by strong corporate earnings, AI sector growth, and optimism about interest rate cuts. The blue-chip index closed at 50,247, up 2.8% on the day, with technology and financial stocks leading the rally.</p><p>"This milestone reflects the extraordinary resilience and innovation of American business," said NYSE President Lynn Martin. The rally was broad-based, with 28 of the 30 Dow components posting gains. Analysts attribute the surge to a combination of strong productivity growth from AI adoption and easing inflationary pressures.</p>',
                        'excerpt' => 'Dow Jones breaks 50,000 for first time as AI-driven productivity gains and rate cut optimism fuel broad market rally.',
                        'seo_keywords' => 'Dow Jones, stock market, record, Wall Street, rally, 50000',
                        'author' => 'Financial Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(3),
                    ],
                    [
                        'title' => 'Global Inflation Drops Below 2% as Central Banks Begin Coordinated Rate Cuts',
                        'content' => '<p>Global inflation has fallen below 2% for the first time in five years, prompting coordinated interest rate cuts by the Federal Reserve, European Central Bank, Bank of Japan, and Bank of England. The synchronized easing represents the most coordinated monetary policy action since the 2008 financial crisis.</p><p>"The battle against inflation has been won," said Federal Reserve Chair Jerome Powell. "Now our focus shifts to supporting economic growth and employment." The Fed cut rates by 50 basis points, with other central banks following with similar or larger reductions. Markets responded with broad gains across equities and bonds.</p>',
                        'excerpt' => 'Global inflation falls below 2% as Fed, ECB, BOJ, and BOE coordinate rate cuts in most synchronized easing since 2008.',
                        'seo_keywords' => 'inflation, Federal Reserve, ECB, rate cuts, economy, central banks',
                        'author' => 'Financial Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(6),
                    ],
                    [
                        'title' => 'Startup Developing Fusion-Powered Data Centers Raises $5 Billion',
                        'content' => '<p>Helion Energy, a fusion energy startup, has raised $5 billion to develop fusion-powered data centers that promise to provide unlimited clean energy for AI computing. The company\'s approach uses compact fusion reactors designed to be deployed directly at data center locations, eliminating transmission losses and providing dedicated power for energy-intensive AI training operations.</p><p>"The AI revolution needs an energy revolution," said Helion CEO David Kirtley. "Fusion is the only power source that can sustainably meet the exponential growth in computing demand." Microsoft has signed a 10-year power purchase agreement with Helion to power its AI cloud infrastructure.</p>',
                        'excerpt' => 'Fusion energy startup Helion raises $5B to build fusion-powered AI data centers, with Microsoft signing 10-year power deal.',
                        'seo_keywords' => 'fusion, energy, data centers, AI, startup, Helion, Microsoft',
                        'author' => 'Financial Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(9),
                    ],
                    [
                        'title' => 'Housing Prices in Major Cities Decline for First Time in 15 Years',
                        'content' => '<p>Housing prices in major metropolitan areas globally have declined for the first time in 15 years, according to the Knight Frank Global House Price Index. Cities including Toronto, Sydney, London, San Francisco, and Hong Kong saw average price declines of 8-15% year-over-year, driven by higher interest rates, increased housing supply, and changing work patterns.</p><p>"The affordability crisis is finally easing, but through painful adjustment," said Kate Everett-Allen, head of global research at Knight Frank. First-time homebuyers are cautiously optimistic, though economists warn that a rapid correction could destabilize financial markets with significant mortgage exposure.</p>',
                        'excerpt' => 'Global housing prices decline for first time in 15 years, with major cities seeing 8-15% drops as affordability slowly improves.',
                        'seo_keywords' => 'housing, real estate, prices, decline, affordability, global',
                        'author' => 'Financial Correspondent',
                        'published_at' => now()->subDays(1)->addHours(4),
                    ],
                    [
                        'title' => 'Electric Vehicle Sales Surpass Gas-Powered Car Sales Globally for First Time',
                        'content' => '<p>Electric vehicle sales have surpassed internal combustion engine vehicle sales globally for the first time, with EVs capturing 52% of all new car sales in the second quarter of 2026. The milestone, which arrived two years earlier than most analysts predicted, was driven by aggressive price cuts from Chinese manufacturers and expanded charging infrastructure across Europe and North America.</p><p>"The internal combustion engine era is coming to an end," said BloombergNEF analyst Colin McKerracher. "This is a watershed moment for the automotive industry and climate action." China continues to lead EV adoption with 68% of new sales being electric, followed by Europe at 55% and the US at 38%.</p>',
                        'excerpt' => 'EV sales overtake gas-powered cars globally for first time, capturing 52% of new car sales and crossing milestone two years early.',
                        'seo_keywords' => 'EV, electric vehicles, automotive, sales, climate, Tesla, BYD',
                        'author' => 'Staff Reporter',
                        'is_trending' => true,
                        'published_at' => now()->subHours(8),
                    ],
                    [
                        'title' => 'Remote Work Permanent: Fortune 500 Companies Downsize Office Space by 40%',
                        'content' => '<p>Fortune 500 companies have permanently downsized their office footprints by an average of 40% compared to pre-pandemic levels, according to a comprehensive study by JLL. The shift, which has accelerated over the past year, has led to record vacancy rates in major downtown areas and a fundamental restructuring of the commercial real estate market.</p><p>"The traditional office is not coming back," said JLL CEO Christian Ulbrich. "Companies have realized that flexible and remote work models reduce costs while maintaining or improving productivity." The trend has spurred conversion of office buildings to residential units, with cities offering tax incentives for adaptive reuse projects.</p>',
                        'excerpt' => 'Fortune 500 companies reduce office space by 40% as remote work becomes permanent, driving record downtown vacancy rates.',
                        'seo_keywords' => 'remote work, office space, Fortune 500, commercial real estate, JLL',
                        'author' => 'Business Correspondent',
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'Space Mining Company Secures First Commercial Asteroid Mining License',
                        'content' => '<p>AstroForge, a US-based space mining company, has been granted the world\'s first commercial asteroid mining license by the International Seabed Authority. The license permits the extraction of platinum group metals and rare earth elements from a near-Earth asteroid, with operations expected to begin within three years.</p><p>"This is the start of a new industry that could transform the global economy," said AstroForge CEO Matt Gialich. "A single medium-sized asteroid could contain more platinum than has ever been mined in human history." The company has secured launch contracts with SpaceX and has already tested its extraction technology in zero-gravity experiments.</p>',
                        'excerpt' => 'AstroForge receives first commercial asteroid mining license, targeting platinum extraction from near-Earth asteroid within three years.',
                        'seo_keywords' => 'space mining, asteroid, AstroForge, platinum, space economy',
                        'author' => 'Business Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1),
                    ],
                ],
            ],

            // === SCIENCE (7 articles) ===
            [
                'category_slug' => 'science',
                'articles' => [
                    [
                        'title' => 'Martian Soil Samples Confirm Ancient Microbial Life, NASA Announces',
                        'content' => '<p>NASA has confirmed that soil samples collected by the Perseverance rover and returned to Earth by the Mars Sample Return mission contain definitive evidence of ancient microbial life. The samples, from Jezero Crater, feature microscopic fossilized structures, organic compounds, and isotopic ratios consistent with biological processes dating back 3.5 billion years.</p><p>"This is the discovery generations of scientists have dreamed of," said NASA Administrator. "Mars was once home to life." The finding has profound implications for the search for life elsewhere in the universe and has accelerated plans for a crewed Mars mission, now targeted for the early 2040s.</p>',
                        'excerpt' => 'NASA confirms ancient microbial life in Mars soil samples returned by Perseverance, showing fossilized structures from 3.5 billion years ago.',
                        'seo_keywords' => 'Mars, life, NASA, Perseverance, space, microbial, fossil',
                        'author' => 'Science Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(2),
                    ],
                    [
                        'title' => 'Lab-Grown Human Brain Tissue Successfully Connected to Spinal Cord',
                        'content' => '<p>Scientists at Stanford University have achieved a major breakthrough in neuroengineering, successfully growing human brain tissue in a laboratory that developed functional connections to a synthetic spinal cord. The organoids, grown from stem cells, exhibited coordinated neural activity and responded to sensory input.</p><p>"We have essentially built a functional neural circuit from scratch," said Dr. Michelle Chen, lead researcher. The achievement could accelerate understanding of neurological conditions like spinal cord injuries, ALS, and multiple sclerosis, and potentially lead to treatments for paralysis.</p>',
                        'excerpt' => 'Stanford scientists grow lab brain tissue that forms functional connections to spinal cord, opening new paths for paralysis treatment.',
                        'seo_keywords' => 'brain, organoid, neuroscience, spinal cord, stem cells, Stanford',
                        'author' => 'Science Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(11),
                    ],
                    [
                        'title' => 'New Study Reveals Earth\'s Core Shows Signs of Slowing, Could Affect Magnetic Field',
                        'content' => '<p>New seismic data published in Nature Geoscience reveals that Earth\'s inner core has begun rotating more slowly than the planet\'s surface for the first time in decades. The deceleration could have implications for the Earth\'s magnetic field, which protects life from harmful solar radiation.</p><p>"We are observing changes in the core\'s behavior that we have never documented before," said Dr. Xiaodong Song of Peking University. The researchers emphasize that the changes are gradual and not cause for alarm, but warrant continued monitoring. The findings also provide new insights into the Earth\'s internal structure and dynamics.</p>',
                        'excerpt' => 'Earth\'s inner core rotation slows relative to surface for first time in decades, potentially affecting the planet\'s protective magnetic field.',
                        'seo_keywords' => 'Earth, core, magnetic field, geology, seismic, Nature',
                        'author' => 'Science Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(15),
                    ],
                    [
                        'title' => 'Scientists Create Synthetic Cells That Can Photosynthesize Like Plants',
                        'content' => '<p>Researchers at the University of Cambridge have created synthetic cells capable of photosynthesis, converting sunlight, water, and carbon dioxide into energy and oxygen. The artificial cells, built from scratch using synthetic biology techniques, could revolutionize carbon capture technology and enable the production of sustainable fuels.</p><p>"We have built a living solar panel at the cellular level," said Dr. James Watson, lead scientist. The synthetic cells are 85% as efficient as natural plant cells in converting sunlight to energy, and the team is working to improve performance. Applications include carbon-negative manufacturing and space-based life support systems.</p>',
                        'excerpt' => 'Cambridge researchers create synthetic cells that photosynthesize with 85% efficiency of natural plants, enabling carbon-negative manufacturing.',
                        'seo_keywords' => 'synthetic biology, photosynthesis, Cambridge, carbon capture, cells',
                        'author' => 'Science Correspondent',
                        'published_at' => now()->subDays(1)->addHours(8),
                    ],
                    [
                        'title' => 'Deep Ocean Exploration Discovers New Ecosystem at 10,000 Meters Depth',
                        'content' => '<p>An international deep-sea expedition has discovered a previously unknown ecosystem in the deepest trench of the Pacific Ocean, at a depth of 10,000 meters. The ecosystem, thriving in complete darkness and under pressures 1,000 times atmospheric, includes translucent fish, giant amphipods, and microbial communities that survive on chemical energy from hydrothermal vents.</p><p>"We have discovered an entirely new type of deep-sea ecosystem," said Dr. Sanae Kobayashi of the Japan Agency for Marine-Earth Science and Technology. "The adaptations we are seeing are unlike anything known to science." The discovery has implications for understanding the limits of life on Earth and the potential for life on ocean worlds like Europa and Enceladus.</p>',
                        'excerpt' => 'Deep ocean expedition discovers entirely new ecosystem 10,000 meters beneath the surface, revealing life forms with never-before-seen adaptations.',
                        'seo_keywords' => 'ocean, deep sea, discovery, ecosystem, marine biology, hydrothermal',
                        'author' => 'Science Correspondent',
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'New Battery Technology Stores Energy for Months with Zero Degradation',
                        'content' => '<p>A team at MIT has developed a new battery technology capable of storing energy for months with virtually zero degradation. The "seasonal battery" uses a novel solid-state design and phase-change materials to lock in energy until it is needed, addressing the critical challenge of intermittent renewable energy storage.</p><p>"This solves the seasonal storage problem that has been the biggest barrier to 100% renewable energy grids," said Professor Yet-Ming Chiang. The batteries can store solar power generated in summer for use in winter, or wind power for calm periods. Initial testing shows less than 1% energy loss per month of storage.</p>',
                        'excerpt' => 'MIT develops seasonal battery storing energy for months with near-zero degradation, solving critical renewable energy storage challenge.',
                        'seo_keywords' => 'battery, energy storage, MIT, renewable energy, technology',
                        'author' => 'Science Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(20),
                    ],
                    [
                        'title' => 'First Complete Map of Human Connectome Published by International Consortium',
                        'content' => '<p>An international consortium of neuroscientists has published the first complete map of the human connectome — the comprehensive wiring diagram of all neural connections in the human brain. The map, containing 86 billion neurons and over 100 trillion synaptic connections, represents 15 years of work by researchers across 20 countries.</p><p>"This is the equivalent of the human genome project for neuroscience," said Dr. Olaf Sporns of Indiana University, who coined the term "connectome." "Having a complete wiring diagram will transform our understanding of brain function, consciousness, and neurological disease." The data is freely available to researchers worldwide.</p>',
                        'excerpt' => 'International consortium publishes first complete map of the human brain\'s 86 billion neurons, transforming neuroscience research.',
                        'seo_keywords' => 'connectome, brain, neuroscience, mapping, neurons, research',
                        'author' => 'Science Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1)->addHours(2),
                    ],
                ],
            ],

            // === HEALTH (7 articles) ===
            [
                'category_slug' => 'health',
                'articles' => [
                    [
                        'title' => 'Universal Basic Income Pilot Shows Dramatic Improvements in Mental Health Outcomes',
                        'content' => '<p>A five-year universal basic income pilot program in Finland has shown dramatic improvements in mental health outcomes, with participants reporting 40% lower rates of depression, 35% lower anxiety, and a 50% reduction in stress-related illnesses. The program provided 1,200 euros per month to 10,000 randomly selected participants.</p><p>"The mental health benefits of financial security are undeniable," said Dr. Helena Mäki, lead researcher. "When people don\'t have to worry about basic survival, their psychological well-being improves dramatically." The findings have sparked renewed interest in UBI programs among policymakers in Europe and North America.</p>',
                        'excerpt' => 'Finland\'s UBI pilot shows 40% reduction in depression and 50% fewer stress-related illnesses, providing strongest evidence yet for mental health benefits.',
                        'seo_keywords' => 'UBI, universal basic income, mental health, Finland, study',
                        'author' => 'Health Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(5),
                    ],
                    [
                        'title' => 'Long COVID Finally Has a Treatment: Antiviral Regimen Shows 90% Recovery Rate',
                        'content' => '<p>A landmark clinical trial has identified the first effective treatment for long COVID, with a 12-week antiviral and anti-inflammatory regimen resulting in 90% of patients achieving full recovery. The treatment, combining a protease inhibitor with an immunomodulator, targets persistent viral reservoirs and the chronic inflammation they cause.</p><p>"For millions of people suffering from long COVID, hope has finally arrived," said Dr. Anthony Fauci. "This is a turning point in the pandemic\'s aftermath." The treatment is expected to receive FDA emergency authorization within weeks, with manufacturing capacity sufficient to treat 10 million patients annually.</p>',
                        'excerpt' => 'First effective long COVID treatment achieves 90% recovery rate using antiviral and anti-inflammatory combination therapy.',
                        'seo_keywords' => 'long COVID, treatment, antiviral, clinical trial, recovery',
                        'author' => 'Health Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(3),
                    ],
                    [
                        'title' => 'Intermittent Fasting Proven to Reverse Type 2 Diabetes in Major Clinical Trial',
                        'content' => '<p>A major clinical trial has demonstrated that intermittent fasting combined with structured dietary intervention can reverse type 2 diabetes in 72% of participants within one year. The trial, involving 5,000 patients across 50 medical centers, used a 16:8 fasting protocol (16 hours of fasting, 8-hour eating window) combined with a plant-focused diet.</p><p>"This changes the paradigm for diabetes treatment," said Dr. Sarah Mitchell, lead investigator. "We are showing that lifestyle intervention can be more effective than medication for many patients." Participants who achieved remission maintained normal blood sugar levels without medication for the remainder of the follow-up period.</p>',
                        'excerpt' => 'Intermittent fasting reverses type 2 diabetes in 72% of patients in landmark trial, offering drug-free alternative for millions.',
                        'seo_keywords' => 'diabetes, intermittent fasting, reversal, clinical trial, health',
                        'author' => 'Health Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(8),
                    ],
                    [
                        'title' => 'AI-Powered Mental Health Chatbot Matches Therapist Effectiveness in Large Study',
                        'content' => '<p>An AI-powered mental health chatbot has been shown to be as effective as human therapists for treating mild to moderate depression and anxiety, according to a peer-reviewed study published in The Lancet Digital Health. The chatbot, called TheraMind, uses cognitive behavioral therapy techniques and natural language processing to provide therapy sessions.</p><p>"The scalability of AI therapy could solve the mental health access crisis," said Dr. James Kim, study author. "There simply aren\'t enough human therapists to meet demand." The study followed 2,500 patients over six months, with the chatbot achieving comparable outcomes to in-person therapy at one-tenth the cost.</p>',
                        'excerpt' => 'AI chatbot matches human therapists in treating depression and anxiety, offering scalable solution at one-tenth the cost.',
                        'seo_keywords' => 'AI, mental health, therapy, chatbot, depression, Lancet',
                        'author' => 'Health Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(14),
                    ],
                    [
                        'title' => 'World Health Organization Approves First Malaria Vaccine for Children',
                        'content' => '<p>The World Health Organization has approved the first malaria vaccine for widespread use in children, a breakthrough that could save hundreds of thousands of lives annually. The R21/Matrix-M vaccine, developed by the University of Oxford and the Serum Institute of India, shows 80% efficacy in preventing severe malaria in clinical trials.</p><p>"This is a historic milestone in the fight against one of humanity\'s oldest and deadliest diseases," said WHO Director-General Dr. Tedros. Malaria kills over 600,000 people annually, most of them children under five in Sub-Saharan Africa. The vaccine is cost-effective at approximately $5 per dose and can be manufactured at scale.</p>',
                        'excerpt' => 'WHO approves first malaria vaccine for children, with 80% efficacy in preventing severe disease at $5 per dose.',
                        'seo_keywords' => 'malaria, vaccine, WHO, children, Oxford, global health',
                        'author' => 'Health Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(10),
                    ],
                    [
                        'title' => 'Sleep Science Breakthrough: New Drug Mimics Deep Sleep Benefits in Just 2 Hours',
                        'content' => '<p>Researchers at UC Berkeley have developed a compound that mimics the restorative benefits of deep sleep in just two hours. The drug, designated SRS-100, activates the glymphatic system — the brain\'s waste-clearing network — removing neurotoxic proteins associated with Alzheimer\'s and other neurodegenerative diseases.</p><p>"This could fundamentally change how we think about rest and recovery," said Dr. Matthew Walker, a leading sleep scientist not involved in the study. "If proven safe, this would be one of the most important medical advances of the century." Human trials are expected to begin within 18 months.</p>',
                        'excerpt' => 'New compound mimics deep sleep benefits in just 2 hours, clearing Alzheimer\'s-associated toxins from the brain.',
                        'seo_keywords' => 'sleep, drug, deep sleep, Alzheimer\'s, brain, UC Berkeley',
                        'author' => 'Health Correspondent',
                        'published_at' => now()->subDays(1)->addHours(6),
                    ],
                    [
                        'title' => 'Gut Microbiome Transplant Shows Promise in Treating Severe Obesity',
                        'content' => '<p>A groundbreaking trial has found that fecal microbiota transplantation from healthy lean donors can significantly reduce weight and improve metabolic health in patients with severe obesity. Patients receiving the transplants lost an average of 22% of their body weight over 12 months and showed improved insulin sensitivity and reduced inflammation.</p><p>"We are essentially transplanting a healthy gut ecosystem," said Dr. Maria Santos, lead researcher. The study suggests that obesity has a significant microbial component and that targeting the gut microbiome could provide a new treatment avenue for patients who do not respond to conventional interventions.</p>',
                        'excerpt' => 'Fecal transplant from healthy donors leads to 22% average weight loss in severe obesity patients, revealing gut microbiome\'s role in weight.',
                        'seo_keywords' => 'obesity, microbiome, gut health, fecal transplant, weight loss',
                        'author' => 'Health Correspondent',
                        'published_at' => now()->subDays(2),
                    ],
                ],
            ],

            // === SPORTS (7 articles) ===
            [
                'category_slug' => 'sports',
                'articles' => [
                    [
                        'title' => 'Usain Bolt\'s 100m World Record Finally Broken After 17 Years',
                        'content' => '<p>Usain Bolt\'s legendary 100-meter world record of 9.58 seconds has finally been broken by Jamaican sprinter Kareem Thompson, who clocked 9.54 seconds at the Jamaica National Championships. The 23-year-old\'s performance, aided by advanced super-shoe technology and a perfectly timed start, ended one of sport\'s most iconic records.</p><p>"I grew up watching Bolt, and to break his record is beyond my wildest dreams," Thompson said after the race. Bolt himself congratulated the new record holder on social media, writing "The king is dead, long live the king." Track and field analysts say Thompson could push the record into the 9.4-second range within two years.</p>',
                        'excerpt' => 'Usain Bolt\'s 17-year-old 100m world record broken by Jamaican Kareem Thompson who runs 9.54 seconds at national championships.',
                        'seo_keywords' => 'Usain Bolt, 100m, world record, sprint, track and field, Jamaica',
                        'author' => 'Sports Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(4),
                    ],
                    [
                        'title' => 'Lionel Messi Announces Retirement After 2026 World Cup Victory',
                        'content' => '<p>Lionel Messi has announced his retirement from professional football after leading Argentina to victory in the 2026 FIFA World Cup, securing his second consecutive World Cup title. The 39-year-old legend scored a hat-trick in the final against Brazil, cementing his legacy as the greatest footballer of all time.</p><p>"Football has given me everything, and now it\'s time to give back to my family," Messi said in an emotional press conference. His career includes eight Ballon d\'Or awards, seven league titles, four Champions League trophies, and two World Cup victories. Tributes have poured in from around the sporting world.</p>',
                        'excerpt' => 'Lionel Messi announces retirement after leading Argentina to back-to-back World Cup titles with hat-trick in final against Brazil.',
                        'seo_keywords' => 'Messi, retirement, World Cup, Argentina, football, GOAT',
                        'author' => 'Sports Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(1),
                    ],
                    [
                        'title' => 'Caitlin Clark Signs Record-Breaking $100 Million WNBA Contract',
                        'content' => '<p>Caitlin Clark has signed the largest contract in women\'s basketball history, a $100 million, five-year deal with the Indiana Fever. The contract, which includes ownership equity in the franchise and a signature shoe line with Nike, reflects Clark\'s transformative impact on the WNBA\'s popularity and revenue.</p><p>"I want to build something special here," Clark said. "This is about more than basketball — it\'s about growing the game for the next generation." The deal has sparked discussions about the rapid growth of women\'s professional sports, with WNBA viewership up 300% since Clark\'s debut season.</p>',
                        'excerpt' => 'Caitlin Clark signs historic $100M WNBA contract with franchise equity and Nike signature line, transforming women\'s basketball economics.',
                        'seo_keywords' => 'Caitlin Clark, WNBA, contract, basketball, Nike, Indiana Fever',
                        'author' => 'Sports Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(7),
                    ],
                    [
                        'title' => 'Cricket Returns to Olympics After 128-Year Absence, Draws Record Global Audience',
                        'content' => '<p>Cricket has made its return to the Olympic Games after a 128-year absence, featuring the Twenty20 format that drew a record global television audience of 2.5 billion viewers. India defeated England in a thrilling final watched by over 1 billion people in India alone, making it the most-watched cricket match in history.</p><p>"Cricket belongs on the Olympic stage," said ICC Chair Greg Barclay. The inclusion of cricket has been credited with significantly boosting Olympic viewership in South Asia, traditionally a underserved market for the Games. The IOC has confirmed cricket will remain in the program for at least the next two Olympics.</p>',
                        'excerpt' => 'Cricket\'s Olympic return after 128 years draws 2.5 billion viewers as India beats England in most-watched match in history.',
                        'seo_keywords' => 'cricket, Olympics, India, England, T20, ICC',
                        'author' => 'Sports Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1)->addHours(4),
                    ],
                    [
                        'title' => 'Winter Olympics Awarded to Oslo, Norway for 2034 Games',
                        'content' => '<p>The International Olympic Committee has awarded the 2034 Winter Olympic Games to Oslo, Norway, in a bid that emphasizes sustainability and existing infrastructure. The Norwegian capital, which previously hosted in 1952, will use primarily existing venues powered entirely by renewable energy, setting a new standard for environmentally responsible Games.</p><p>"Oslo\'s vision for a carbon-neutral Winter Games is exactly what the Olympic movement needs," said IOC President Thomas Bach. The bid features compact venue clusters, all within one hour of the Olympic Village, and a legacy plan focused on youth winter sports participation across Scandinavia.</p>',
                        'excerpt' => 'Oslo awarded 2034 Winter Olympics with carbon-neutral vision, using existing venues powered entirely by renewable energy.',
                        'seo_keywords' => 'Winter Olympics, Oslo, Norway, 2034, IOC, sustainability',
                        'author' => 'Sports Correspondent',
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'NFL Approves Expansion to 40 Teams with International Franchises in London and Berlin',
                        'content' => '<p>The NFL has approved a historic expansion to 40 teams, adding franchises in London and Berlin as the league\'s first permanent international teams. The London Monarchs and Berlin Thunder will begin play in the 2028 season, playing in the newly created NFC and AFC International divisions respectively.</p><p>"The NFL is truly becoming a global league," said Commissioner Roger Goodell. The expansion fee for each franchise is reported to be $5 billion. The International division will allow the new teams to play eight regular-season games in their home markets and travel to the US for away games in blocks to minimize travel fatigue.</p>',
                        'excerpt' => 'NFL expands to 40 teams with London and Berlin franchises, marking league\'s first permanent international expansion.',
                        'seo_keywords' => 'NFL, expansion, London, Berlin, football, international',
                        'author' => 'Sports Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1)->addHours(10),
                    ],
                    [
                        'title' => 'Simone Biles Makes History at 29 with Most Decorated Gymnastics Career',
                        'content' => '<p>Simone Biles has cemented her status as the greatest gymnast of all time, winning five gold medals at the World Gymnastics Championships at age 29 — an age at which most gymnasts have long retired. Her total of 35 world championship medals (27 gold) surpasses the previous record by a wide margin, and she shows no signs of stopping.</p><p>"Age is just a number," Biles said after her performance. "I\'m still learning new skills and pushing what\'s possible in this sport." Biles debuted two new original skills at the championships, each named after her in the FIG Code of Points, bringing her total of signature moves to seven — more than any gymnast in history.</p>',
                        'excerpt' => 'Simone Biles, 29, wins five golds at World Championships, extending record to 35 medals and debuting two new signature skills.',
                        'seo_keywords' => 'Simone Biles, gymnastics, world championships, GOAT, record',
                        'author' => 'Sports Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(16),
                    ],
                ],
            ],

            // === ENTERTAINMENT (7 articles) ===
            [
                'category_slug' => 'entertainment',
                'articles' => [
                    [
                        'title' => 'Netflix\'s Interactive AI Drama Lets Viewers Shape Storylines in Real-Time',
                        'content' => '<p>Netflix has launched its first fully interactive AI-powered drama series, "The Infinite Path," which uses generative AI to create unique storylines in real-time based on viewer decisions and emotional responses. The series, which cost $200 million to produce, represents the most ambitious fusion of artificial intelligence and traditional storytelling ever attempted.</p><p>"This is the future of entertainment," said Netflix co-CEO Ted Sarandos. "Every viewer will have a unique experience that adapts to their choices and preferences." The series has been praised for its narrative coherence, achieved through a novel AI system that ensures storylines remain consistent regardless of viewer choices.</p>',
                        'excerpt' => 'Netflix launches first AI-powered interactive drama using generative AI to create unique storylines based on real-time viewer choices.',
                        'seo_keywords' => 'Netflix, AI, interactive, drama, streaming, entertainment',
                        'author' => 'Entertainment Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(6),
                    ],
                    [
                        'title' => 'Beyoncé and Kendrick Lamar\'s Joint Album Breaks All Streaming Records',
                        'content' => '<p>Beyoncé and Kendrick Lamar\'s surprise collaborative album, "Renaissance: Act III," has shattered every major streaming record in its first week, accumulating 1.5 billion streams globally. The 18-track album, which blends hip-hop, R&B, house, and orchestral elements, has been hailed by critics as a masterpiece.</p><p>"When two cultural forces come together, magic happens," wrote Rolling Stone in a five-star review. The album\'s lead single topped charts in 85 countries, and the duo announced a co-headlining stadium tour that sold 3 million tickets in the first hour of presale.</p>',
                        'excerpt' => 'Beyoncé and Kendrick Lamar\'s surprise album shatters records with 1.5 billion first-week streams and sells 3M tour tickets in one hour.',
                        'seo_keywords' => 'Beyoncé, Kendrick Lamar, album, music, streaming, record',
                        'author' => 'Entertainment Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(2),
                    ],
                    [
                        'title' => 'OpenAI and Hollywood Studios Reach Landmark AI Film Production Agreement',
                        'content' => '<p>OpenAI has signed a landmark agreement with major Hollywood studios to license its video generation technology for film and television production. The deal, which includes Disney, Warner Bros., Universal, and Sony, establishes guidelines for AI use in creative production, including requirements for human oversight and fair compensation for writers and artists.</p><p>"This agreement ensures AI enhances human creativity rather than replacing it," said OpenAI CEO Sam Altman. The studios will use OpenAI\'s Sora 2 platform for visual effects, pre-visualization, and certain production tasks, while maintaining that all final creative decisions must be made by humans.</p>',
                        'excerpt' => 'OpenAI and major Hollywood studios sign landmark agreement for AI film production, establishing guidelines for human-AI collaboration.',
                        'seo_keywords' => 'OpenAI, Hollywood, AI, film production, Sora, entertainment',
                        'author' => 'Entertainment Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(9),
                    ],
                    [
                        'title' => '"Grand Theft Auto VI" Becomes Fastest-Selling Game in History, $5 Billion in First Week',
                        'content' => '<p>Rockstar Games\' "Grand Theft Auto VI" has become the fastest-selling entertainment product in history, generating $5 billion in revenue within its first week of release. The game sold 50 million copies in seven days, surpassing all previous records for video games, films, and music releases.</p><p>"This is unprecedented for any form of entertainment," said industry analyst Michael Pachter. The game, set in a fictionalized Miami with dual protagonists, features a sprawling open world powered by Rockstar\'s new RAGE 10 engine and includes online integration that has been described as a "metaverse-like experience."</p>',
                        'excerpt' => 'GTA VI generates $5 billion in first week, selling 50 million copies to become fastest-selling entertainment product in history.',
                        'seo_keywords' => 'GTA, Grand Theft Auto, video game, record, Rockstar, gaming',
                        'author' => 'Entertainment Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(5),
                    ],
                    [
                        'title' => 'Disney+ and Netflix Announce Merger to Create World\'s Largest Streaming Platform',
                        'content' => '<p>Disney and Netflix have announced a blockbuster merger of their streaming services, creating a combined platform with over 600 million subscribers worldwide. The deal, valued at $180 billion, would create an entertainment behemoth combining Disney\'s franchises (Marvel, Star Wars, Pixar) with Netflix\'s technology and original content capabilities.</p><p>"This is about creating the definitive streaming destination," said Disney CEO Bob Iger. The combined service, to be called "Netflix-Disney+," will offer tiered subscription plans and is expected to launch within 12 months. Regulatory approval is pending in multiple jurisdictions.</p>',
                        'excerpt' => 'Disney and Netflix announce $180B streaming merger creating 600M subscriber platform combining Disney franchises with Netflix technology.',
                        'seo_keywords' => 'Disney, Netflix, merger, streaming, entertainment, Marvel, Star Wars',
                        'author' => 'Entertainment Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'Coachella 2026: Holographic Performances and AI DJs Debut at Festival',
                        'content' => '<p>Coachella 2026 has debuted groundbreaking holographic performances, including a full concert by a digitally recreated Freddie Mercury accompanied by Queen\'s surviving members, and sets by AI DJs that generate music in real-time based on crowd energy levels measured by drone sensors.</p><p>"Technology is pushing the boundaries of live performance," said Coachella co-founder Paul Tollett. The festival also featured stages with immersive LED environments that respond to music, and an AI-powered app that creates personalized schedules based on musical taste analysis. Attendance reached a record 250,000 over the three weekends.</p>',
                        'excerpt' => 'Coachella 2026 features holographic Freddie Mercury concert, AI DJs reading crowd energy, and record 250,000 attendees.',
                        'seo_keywords' => 'Coachella, hologram, AI, music festival, Freddie Mercury, entertainment',
                        'author' => 'Entertainment Correspondent',
                        'published_at' => now()->subDays(1)->addHours(8),
                    ],
                    [
                        'title' => 'First Film Shot Entirely in Space Premieres to Critical Acclaim',
                        'content' => '<p>"The Void Beyond," the first feature film shot entirely in space, has premiered to critical acclaim at the Cannes Film Festival. Directed by James Cameron and filmed aboard the Axiom Space Station, the $500 million production features actual zero-gravity sequences and real orbital cinematography.</p><p>"There is no substitute for real zero gravity," Cameron said after the standing ovation. "What we captured could never be faked." The film stars Tom Cruise and Zendaya as astronauts on a mission to Mars, with 40 minutes of footage shot in actual space. The film has been acquired for distribution in 120 countries and is already generating Oscar buzz.</p>',
                        'excerpt' => 'James Cameron\'s "The Void Beyond," the first feature film shot entirely in space, premieres at Cannes to standing ovation.',
                        'seo_keywords' => 'James Cameron, space, film, Cannes, Tom Cruise, Axiom',
                        'author' => 'Entertainment Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(2),
                    ],
                ],
            ],

            // === POLITICS (7 articles) ===
            [
                'category_slug' => 'politics',
                'articles' => [
                    [
                        'title' => 'United Nations Votes to Establish Permanent Global Pandemic Response Fund',
                        'content' => '<p>The United Nations General Assembly has voted overwhelmingly to establish a $100 billion permanent Global Pandemic Response Fund, designed to ensure rapid, coordinated international action against future health emergencies. The fund, supported by 185 member nations, will finance vaccine development, stockpile medical supplies, and deploy emergency response teams.</p><p>"The COVID-19 pandemic taught us that pathogens know no borders," said UN Secretary-General. "This fund ensures we will never again be caught unprepared." The fund will be financed through a combination of member state contributions, a small tax on international financial transactions, and pharmaceutical industry assessments.</p>',
                        'excerpt' => 'UN establishes $100B permanent pandemic response fund backed by 185 nations, ensuring rapid coordinated action against future health emergencies.',
                        'seo_keywords' => 'UN, pandemic, fund, global health, COVID-19, preparedness',
                        'author' => 'Political Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(5),
                    ],
                    [
                        'title' => 'US Supreme Court Renders Landmark Decision on AI and Civil Rights',
                        'content' => '<p>The US Supreme Court has issued a landmark 7-2 ruling establishing that AI systems used in hiring, lending, housing, and criminal justice must meet the same anti-discrimination standards as human decision-makers. The decision, which applies to both public and private AI systems, requires companies to regularly audit their algorithms for bias and provide explanations for automated decisions.</p><p>"The Constitution does not become obsolete when technology advances," wrote Chief Justice John Roberts in the majority opinion. The ruling has broad implications for AI regulation and is expected to prompt similar legislation in other jurisdictions. Technology stocks initially dipped on the news but recovered as investors absorbed the regulatory clarity.</p>',
                        'excerpt' => 'Supreme Court rules AI systems must meet same anti-discrimination standards as humans, requiring bias audits and decision explanations.',
                        'seo_keywords' => 'Supreme Court, AI, civil rights, discrimination, regulation, law',
                        'author' => 'Political Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(8),
                    ],
                    [
                        'title' => 'India Overtakes China as World\'s Largest Economy by GDP (PPP)',
                        'content' => '<p>India has officially overtaken China as the world\'s largest economy by purchasing power parity, with its GDP (PPP) reaching $35 trillion. The milestone, confirmed by the World Bank, reflects India\'s sustained 9% annual growth rate over the past decade, driven by demographic dividends, digital transformation, and economic reforms.</p><p>"India\'s rise is one of the most significant economic transformations in history," said World Bank President. The country\'s young population, growing middle class, and technology sector have been key drivers. India is also projected to become the world\'s third-largest economy by nominal GDP within the next five years, behind the US and China.</p>',
                        'excerpt' => 'India overtakes China as world\'s largest economy by PPP at $35 trillion, driven by demographic dividend and digital transformation.',
                        'seo_keywords' => 'India, economy, GDP, China, World Bank, economic growth',
                        'author' => 'Political Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(10),
                    ],
                    [
                        'title' => 'UK Parliament Votes to Rejoin European Union After Decade-Long Divide',
                        'content' => '<p>The UK Parliament has voted by a significant majority to begin the process of rejoining the European Union, reversing the 2016 Brexit decision after years of economic and political fallout. The vote followed a national referendum where 56% of voters supported rejoining, citing economic benefits and regained influence in European affairs.</p><p>"This is a historic moment of healing and renewal," said the Prime Minister. The rejoining process is expected to take five years and will include negotiations on opt-outs, including the UK maintaining its currency and border controls. EU leaders have welcomed the decision, with accession talks expected to begin within months.</p>',
                        'excerpt' => 'UK Parliament votes to rejoin EU after 56% referendum support, beginning five-year process to reverse Brexit.',
                        'seo_keywords' => 'UK, EU, Brexit, referendum, Parliament, Europe',
                        'author' => 'Political Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subHours(11),
                    ],
                    [
                        'title' => 'Green New Deal Approved: US Commits $2 Trillion to Climate Infrastructure',
                        'content' => '<p>The United States Congress has approved the Green New Deal, a comprehensive $2 trillion climate infrastructure package that represents the largest single investment in climate action in history. The legislation includes massive investments in renewable energy, electric vehicle charging networks, grid modernization, carbon capture technology, and climate resilience projects.</p><p>"This is our generation\'s moon shot," said the bill\'s lead sponsor. The package is projected to create 15 million jobs over the next decade and put the US on track to achieve net-zero emissions by 2050. The legislation includes provisions for worker retraining, environmental justice, and domestic manufacturing of clean energy technologies.</p>',
                        'excerpt' => 'US Congress approves $2 trillion Green New Deal, largest climate investment in history, projected to create 15 million jobs.',
                        'seo_keywords' => 'Green New Deal, climate, infrastructure, clean energy, Congress, jobs',
                        'author' => 'Political Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1),
                    ],
                    [
                        'title' => 'South Africa Leads BRICS Expansion as Five New Nations Join Alliance',
                        'content' => '<p>The BRICS alliance has expanded to include five new member nations — Indonesia, Nigeria, Turkey, Mexico, and Thailand — in a move that strengthens the bloc\'s representation of the Global South. The expanded BRICS now represents 52% of the world\'s population and 40% of global GDP, positioning it as a significant counterweight to G7-led global governance structures.</p><p>"The world is multipolar, and global institutions must reflect this reality," said South African President Cyril Ramaphosa. The expanded alliance has agreed to work on alternatives to dollar-dominated trade systems and to establish a BRICS development bank with $100 billion in initial capital.</p>',
                        'excerpt' => 'BRICS expands to 10 nations including Indonesia, Nigeria, and Turkey, now representing 52% of world population and 40% of global GDP.',
                        'seo_keywords' => 'BRICS, expansion, Global South, Indonesia, Nigeria, Turkey, economics',
                        'author' => 'Political Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(2),
                    ],
                    [
                        'title' => 'Global Agreement Reached to Ban Fossil Fuel Advertising Worldwide',
                        'content' => '<p>Representatives from 120 nations have signed the Fossil Fuel Advertising Ban Treaty, agreeing to prohibit advertising, sponsorship, and promotion of fossil fuel products. Modeled after tobacco advertising bans that proved effective in reducing smoking rates, the treaty aims to accelerate the transition to clean energy by reducing fossil fuel demand.</p><p>"Just as we banned tobacco advertising to protect public health, we must ban fossil fuel advertising to protect planetary health," said the treaty\'s chief architect. The agreement includes provisions for a just transition for advertising workers and exemptions for public interest communications about energy policy.</p>',
                        'excerpt' => '120 nations sign treaty banning fossil fuel advertising worldwide, modeled after successful tobacco advertising restrictions.',
                        'seo_keywords' => 'fossil fuels, advertising ban, climate, treaty, environment, clean energy',
                        'author' => 'Political Correspondent',
                        'is_trending' => true,
                        'published_at' => now()->subDays(1)->addHours(12),
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
                    'is_trending' => $articleData['is_trending'] ?? true,
                ]);
            }
        }

        $this->command->info('✓ ' . Article::count() . ' articles seeded (including trending news)');
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
