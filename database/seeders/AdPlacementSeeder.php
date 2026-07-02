<?php

namespace Database\Seeders;

use App\Models\AdPlacement;
use Illuminate\Database\Seeder;

class AdPlacementSeeder extends Seeder
{
    public function run(): void
    {
        $ads = [
            [
                'name' => 'Header Ad',
                'location' => 'header',
                'order' => 1,
                'image_url' => 'https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=728&h=90&fit=crop',
                'link_url' => 'https://example.com/ad/header',
                'code' => '<a href="https://example.com/ad/header" target="_blank" rel="sponsored"><img src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?w=728&h=90&fit=crop" alt="Sponsored Ad" style="width:728px;height:90px;border-radius:8px;"></a>',
            ],
            [
                'name' => 'Sidebar Top',
                'location' => 'sidebar-top',
                'order' => 2,
                'image_url' => 'https://images.unsplash.com/photo-1574169208507-84376144848b?w=300&h=250&fit=crop',
                'link_url' => 'https://example.com/ad/sidebar-top',
                'code' => '<div class="ad-container" style="text-align:center;margin-bottom:20px;"><a href="https://example.com/ad/sidebar-top" target="_blank" rel="sponsored"><img src="https://images.unsplash.com/photo-1574169208507-84376144848b?w=300&h=250&fit=crop" alt="Sponsored Ad" style="width:300px;height:250px;border-radius:8px;"></a><p style="font-size:11px;color:#999;margin-top:4px;">Advertisement</p></div>',
            ],
            [
                'name' => 'Sidebar Bottom',
                'location' => 'sidebar-bottom',
                'order' => 3,
                'image_url' => 'https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=300&h=600&fit=crop',
                'link_url' => 'https://example.com/ad/sidebar-bottom',
                'code' => '<div class="ad-container" style="text-align:center;margin-top:20px;"><a href="https://example.com/ad/sidebar-bottom" target="_blank" rel="sponsored"><img src="https://images.unsplash.com/photo-1611532736597-de2d4265fba3?w=300&h=600&fit=crop" alt="Sponsored Ad" style="width:300px;height:600px;border-radius:8px;"></a><p style="font-size:11px;color:#999;margin-top:4px;">Advertisement</p></div>',
            ],
            [
                'name' => 'In Article',
                'location' => 'in-article',
                'order' => 4,
                'image_url' => 'https://images.unsplash.com/photo-1504711434969-e33886168d6c?w=468&h=60&fit=crop',
                'link_url' => 'https://example.com/ad/in-article',
                'code' => '<div class="in-article-ad" style="text-align:center;padding:20px 0;border-top:1px solid #eee;border-bottom:1px solid #eee;margin:24px 0;"><a href="https://example.com/ad/in-article" target="_blank" rel="sponsored"><img src="https://images.unsplash.com/photo-1504711434969-e33886168d6c?w=468&h=60&fit=crop" alt="Sponsored Ad" style="width:468px;height:60px;border-radius:6px;"></a><p style="font-size:11px;color:#999;margin-top:4px;">Advertisement</p></div>',
            ],
            [
                'name' => 'Footer Ad',
                'location' => 'footer',
                'order' => 5,
                'image_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?w=728&h=90&fit=crop',
                'link_url' => 'https://example.com/ad/footer',
                'code' => '<a href="https://example.com/ad/footer" target="_blank" rel="sponsored"><img src="https://images.unsplash.com/photo-1497366216548-37526070297c?w=728&h=90&fit=crop" alt="Sponsored Ad" style="width:728px;height:90px;border-radius:8px;"></a>',
            ],
            [
                'name' => 'Above Content',
                'location' => 'above-content',
                'order' => 6,
                'image_url' => 'https://images.unsplash.com/photo-1542744094-3a31f272c490?w=728&h=90&fit=crop',
                'link_url' => 'https://example.com/ad/above-content',
                'code' => '<div class="above-content-ad" style="text-align:center;margin-bottom:24px;"><a href="https://example.com/ad/above-content" target="_blank" rel="sponsored"><img src="https://images.unsplash.com/photo-1542744094-3a31f272c490?w=728&h=90&fit=crop" alt="Sponsored Ad" style="width:728px;height:90px;border-radius:8px;"></a><p style="font-size:11px;color:#999;margin-top:4px;">Advertisement</p></div>',
            ],
            [
                'name' => 'Below Content',
                'location' => 'below-content',
                'order' => 7,
                'image_url' => 'https://images.unsplash.com/photo-1559526324-593bc073d938?w=728&h=90&fit=crop',
                'link_url' => 'https://example.com/ad/below-content',
                'code' => '<div class="below-content-ad" style="text-align:center;margin-top:24px;"><a href="https://example.com/ad/below-content" target="_blank" rel="sponsored"><img src="https://images.unsplash.com/photo-1559526324-593bc073d938?w=728&h=90&fit=crop" alt="Sponsored Ad" style="width:728px;height:90px;border-radius:8px;"></a><p style="font-size:11px;color:#999;margin-top:4px;">Advertisement</p></div>',
            ],
            [
                'name' => 'Between Articles',
                'location' => 'between-articles',
                'order' => 8,
                'image_url' => 'https://images.unsplash.com/photo-1432821596592-e2c18b78144f?w=468&h=60&fit=crop',
                'link_url' => 'https://example.com/ad/between-articles',
                'code' => '<div class="between-articles-ad" style="text-align:center;padding:16px 0;"><a href="https://example.com/ad/between-articles" target="_blank" rel="sponsored"><img src="https://images.unsplash.com/photo-1432821596592-e2c18b78144f?w=468&h=60&fit=crop" alt="Sponsored Ad" style="width:468px;height:60px;border-radius:6px;"></a><p style="font-size:11px;color:#999;margin-top:4px;">Advertisement</p></div>',
            ],
        ];

        foreach ($ads as $ad) {
            AdPlacement::updateOrCreate(
                ['location' => $ad['location']],
                [
                    'name' => $ad['name'],
                    'order' => $ad['order'],
                    'image_url' => $ad['image_url'],
                    'link_url' => $ad['link_url'],
                    'code' => $ad['code'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('✓ ' . AdPlacement::where('is_active', true)->count() . ' ad placements seeded with data and activated');
    }
}
