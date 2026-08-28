<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Client;
use App\Models\GalleryItem;
use App\Models\Project;
use App\Models\Update;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'admin@aapengineerings.com'],
            [
                'name' => 'AAP Admin',
                'password' => Hash::make('Admin@123'),
            ]
        );

        $cities = collect([
            ['name' => 'Pune', 'state' => 'Maharashtra'],
            ['name' => 'Mumbai', 'state' => 'Maharashtra'],
            ['name' => 'Nagpur', 'state' => 'Maharashtra'],
            ['name' => 'Nashik', 'state' => 'Maharashtra'],
            ['name' => 'Aurangabad', 'state' => 'Maharashtra'],
        ])->map(fn ($c) => City::query()->updateOrCreate(
            ['name' => $c['name']],
            ['state' => $c['state'], 'is_active' => true]
        ));

        $samples = [
            [
                'title' => 'Industrial HT/LT Electrical Installation',
                'status' => 'completed',
                'city' => 'Pune',
                'client_name' => 'Precision Manufacturing Ltd',
                'project_type' => 'Industrial Electrical',
                'short_description' => 'Complete HT/LT distribution, panel installation and commissioning for a manufacturing plant.',
                'description' => "AAP Engineerings delivered end-to-end electrical infrastructure for a new industrial facility.\n\nScope included HT/LT cabling, transformer yard coordination, main and sub distribution panels, earthing, lighting, and final testing & commissioning.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1400&q=80', 'caption' => 'Site electrical works'],
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=1400&q=80', 'caption' => 'Panel installation'],
                    ['type' => 'video_youtube', 'url' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ', 'caption' => 'Project walkthrough'],
                ],
            ],
            [
                'title' => 'Commercial Building Power & Lighting',
                'status' => 'ongoing',
                'city' => 'Mumbai',
                'client_name' => 'Skyline Developers',
                'project_type' => 'Commercial Fit-out',
                'short_description' => 'Full electrical package for a multi-floor commercial building including power, lighting and safety systems.',
                'description' => "Ongoing turnkey electrical works for a commercial tower including floor-wise DB installation, lighting design execution, emergency systems and coordination with civil and MEP teams.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=80', 'caption' => 'Commercial facade'],
                    ['type' => 'video_cdn', 'url' => 'https://interactive-examples.mdn.mozilla.net/media/cc0-videos/flower.mp4', 'thumbnail_url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80', 'caption' => 'CDN sample clip'],
                ],
            ],
            [
                'title' => 'Hospital Electrical Infrastructure Upgrade',
                'status' => 'upcoming',
                'city' => 'Nagpur',
                'client_name' => 'City Care Hospital',
                'project_type' => 'Healthcare Electrical',
                'short_description' => 'Upcoming upgrade of critical power systems, UPS coordination and distribution for a hospital campus.',
                'description' => "Planned upgrade covering critical load segregation, UPS and DG integration support, medical area electrical compliance, and phased execution to minimise downtime.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => true,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1400&q=80', 'caption' => 'Healthcare facility'],
                ],
            ],
            [
                'title' => 'Warehouse Electrical & Fire Alarm Works',
                'status' => 'completed',
                'city' => 'Nashik',
                'client_name' => 'LogiHub Warehousing',
                'project_type' => 'Warehouse Electrical',
                'short_description' => 'Complete electrical and fire alarm installation for a logistics warehouse.',
                'description' => "Delivered power distribution, high-bay lighting, earthing and fire alarm system installation with testing and handover documentation.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => false,
                'media' => [
                    ['type' => 'image', 'url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1400&q=80', 'caption' => 'Warehouse bay'],
                ],
            ],
        ];

        foreach ($samples as $i => $sample) {
            $city = $cities->firstWhere('name', $sample['city']);
            $media = $sample['media'];
            unset($sample['city'], $sample['media']);

            $project = Project::query()->updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($sample['title'])],
                array_merge($sample, [
                    'city_id' => $city?->id,
                    'is_published' => true,
                    'sort_order' => $i,
                    'start_date' => now()->subMonths(6 - $i)->toDateString(),
                    'end_date' => $sample['status'] === 'completed' ? now()->subMonths(1)->toDateString() : null,
                ])
            );

            $project->media()->delete();
            foreach ($media as $order => $item) {
                $project->media()->create(array_merge($item, ['sort_order' => $order]));
            }
        }

        $clientRows = [
            ['name' => 'Precision Manufacturing Ltd', 'industry' => 'Manufacturing', 'logo_url' => 'https://images.unsplash.com/photo-1560179707-f14ea90d4564?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'Skyline Developers', 'industry' => 'Real Estate', 'logo_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'City Care Hospital', 'industry' => 'Healthcare', 'logo_url' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'LogiHub Warehousing', 'industry' => 'Logistics', 'logo_url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'GreenGrid Energy', 'industry' => 'Energy', 'logo_url' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=400&q=80'],
            ['name' => 'Metro Infra Works', 'industry' => 'Infrastructure', 'logo_url' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?auto=format&fit=crop&w=400&q=80'],
        ];

        foreach ($clientRows as $i => $row) {
            Client::query()->updateOrCreate(
                ['name' => $row['name']],
                array_merge($row, ['sort_order' => $i, 'is_active' => true])
            );
        }

        $galleryRows = [
            ['title' => 'Panel commissioning', 'category' => 'Industrial', 'url' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=1400&q=80'],
            ['title' => 'Site cabling', 'category' => 'Industrial', 'url' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1400&q=80'],
            ['title' => 'Commercial tower works', 'category' => 'Commercial', 'url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=80'],
            ['title' => 'Warehouse lighting', 'category' => 'Warehouse', 'url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1400&q=80'],
            ['title' => 'Project walkthrough', 'category' => 'Videos', 'type' => 'video_youtube', 'url' => 'https://www.youtube.com/watch?v=aqz-KE-bpKQ'],
            ['title' => 'Switchgear bay', 'category' => 'Industrial', 'url' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?auto=format&fit=crop&w=1400&q=80'],
            ['title' => 'Office fit-out', 'category' => 'Commercial', 'url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1400&q=80'],
            ['title' => 'Healthcare upgrade', 'category' => 'Healthcare', 'url' => 'https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1400&q=80'],
        ];

        foreach ($galleryRows as $i => $row) {
            GalleryItem::query()->updateOrCreate(
                ['title' => $row['title'], 'url' => $row['url']],
                [
                    'type' => $row['type'] ?? 'image',
                    'category' => $row['category'],
                    'caption' => $row['title'],
                    'sort_order' => $i,
                    'is_active' => true,
                ]
            );
        }

        $updateRows = [
            [
                'title' => 'AAP Engineerings expands industrial project capacity',
                'excerpt' => 'We have strengthened our execution team to take on larger HT/LT industrial scopes across Maharashtra.',
                'body' => "AAP Engineerings continues to scale delivery capability for full electrical project packages.\n\nOur focus remains clear ownership from planning coordination through installation, testing and handover.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'New commercial electrical packages in Mumbai',
                'excerpt' => 'Multiple floor-wise distribution and lighting packages are now in active execution.',
                'body' => "Commercial tower works require tight coordination and staged energization.\n\nOur teams are currently delivering power and lighting packages with live site safety controls.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80',
            ],
            [
                'title' => 'Safety-first commissioning checklist rolled out',
                'excerpt' => 'A standardized commissioning checklist is now used across all completed project handovers.',
                'body' => "Every completed project now follows a documented testing and commissioning checklist before final handover.\n\nThis improves consistency for clients and reduces punch-list delays.",
                'cover_image_url' => 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=1200&q=80',
            ],
        ];

        foreach ($updateRows as $i => $row) {
            Update::query()->updateOrCreate(
                ['slug' => Str::slug($row['title'])],
                array_merge($row, [
                    'published_at' => now()->subDays(10 - ($i * 3)),
                    'is_published' => true,
                ])
            );
        }
    }
}
