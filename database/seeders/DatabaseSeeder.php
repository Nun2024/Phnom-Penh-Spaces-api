<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Space;
use App\Models\SpaceType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@creative.com',
            'password' => Hash::make('password123'),
            'phone' => '+85512345678',
            'role' => 'ADMIN',
        ]);

        // Create Test Client User
        User::create([
            'name' => 'Sok Hem',
            'email' => 'sokhem@example.com',
            'password' => Hash::make('password123'),
            'phone' => '+85599887766',
            'role' => 'CLIENT',
        ]);

        // Create Space Types
        $meetingType = SpaceType::create(['name' => 'Meeting Room', 'description' => 'Professional meeting rooms for business']);
        $workshopType = SpaceType::create(['name' => 'Workshop', 'description' => 'Spacious areas for workshops and training']);
        $podcastType = SpaceType::create(['name' => 'Podcast Studio', 'description' => 'Soundproof studios for audio recording']);
        $galleryType = SpaceType::create(['name' => 'Gallery', 'description' => 'Exhibition spaces for art and events']);

        // Seed Creative Spaces
        Space::create([
            'name' => 'The Glass Studio',
            'location' => 'BKK1, Phnom Penh',
            'space_type_id' => $meetingType->id,
            'price_per_hour' => 25.00,
            'capacity' => 10,
            'description' => 'A premium glass-walled creative studio filled with natural light, perfect for brainstorming sessions and client presentations.',
            'status' => 'Active',
            'images' => [
                'https://lh3.googleusercontent.com/aida-public/AB6AXuD1J7BkwGxvVieSNADcYJreb5fxUOWWlw1sQIjv15tW-PYERNab14Q2y4m__1jtDHuCWHgjcV_rJcyD93QyqvWtuWzkBgwCoCRi2dh-4Mr44FezAHGtD0YddtFVGWX94xp5K1NtkNMnrgV_jgf4LRSd5lYD7leZjD14tN7CUhTMgTUKA0Ckd3BtzUy_v5MqOx4SS11hIvoi4QbhEwEJpWJs3Att8U2Pm2bAjKD7-Ccc6w48_Hco0yYIZy1ilDIKwNS3zsHaAe6rc1Y'
            ],
            'wifi' => true,
            'whiteboard' => true,
            'ac' => true,
            'soundproofing' => false,
            'natural_light' => true,
            'refreshments' => true,
        ]);

        Space::create([
            'name' => 'Industrial Loft',
            'location' => 'Tuol Kork, Phnom Penh',
            'space_type_id' => $workshopType->id,
            'price_per_hour' => 35.00,
            'capacity' => 15,
            'description' => 'Spacious industrial design venue for creative workshops, corporate team-building events, and art sessions.',
            'status' => 'Active',
            'images' => [
                'https://lh3.googleusercontent.com/aida-public/AB6AXuCZ3CMOoun7cSIcvw24UX5iobOOS2kV5tVYKmjeikcWwn6LzDxGZ674UlO1u9Jj6GKPC0ebnggu6pYlBTKvo8ZqSiH9nCBlFVOzNaTQy8gdRZKm3szxwPmExMxDmOCAULDvz4x2wUcenU4a540mo2Vczq1AnMgq21uFnLM2eWsHVCP4Z62w-oeaSb3QNS44_1QJy96tncoi-o-h255KD3VJ35gfeQ8v4sRNV-nIRUhqZC3AvrvKm6D5hwXG_bJxpLBKNUACoo-LNFI'
            ],
            'wifi' => true,
            'whiteboard' => true,
            'ac' => true,
            'soundproofing' => true,
            'natural_light' => false,
            'refreshments' => false,
        ]);

        Space::create([
            'name' => 'Sonic Wave Suite',
            'location' => 'Daun Penh, Phnom Penh',
            'space_type_id' => $podcastType->id,
            'price_per_hour' => 50.00,
            'capacity' => 4,
            'description' => 'Professional sound-isolated acoustics recording studio equipped for top-tier audio recording and podcasts.',
            'status' => 'Active',
            'images' => [
                'https://lh3.googleusercontent.com/aida-public/AB6AXuCHZBl0mcr4FB8opUHFHQjC_0B2mZKKgRC4TtIt7OyLPftATiPFK8J51r_07NbS4rOkl9uGMWWx0Iauezl6pQrFYhPBe2IT7p0Kyv7Fe8lFH8dwd2vMouo3xCoW7AOQi8eE62-K8PIZbEKzYVyuca5BtUJCDDYGiHMjaTlNyQZECFN3AYps8e2NmO7XJiy-X03Wr9q0DQxrZwEjVTyttSCRlo1in6H-etEgGwVgooy0dUF2EseQN7YKdcGKNADnCNkoUr-T3_IDx1k'
            ],
            'wifi' => true,
            'whiteboard' => false,
            'ac' => true,
            'soundproofing' => true,
            'natural_light' => false,
            'refreshments' => false,
        ]);

        Space::create([
            'name' => 'Minimalist Workshop',
            'location' => 'BKK1, Phnom Penh',
            'space_type_id' => $workshopType->id,
            'price_per_hour' => 15.00,
            'capacity' => 8,
            'description' => 'Perfect distraction-free workshop or meeting space to bring your core ideas to life with high speed WiFi and comfortable seating.',
            'status' => 'Active',
            'images' => [
                'https://lh3.googleusercontent.com/aida-public/AB6AXuApCqKB97pQMigDG4PXYMWpdEdBWZUpxDsEC7Yy5s09yBEYax2QnvJAtNsRiHwedKORP9qT5ox1IaPa_PmtammtYF1MQXwwVL_V8sgxDbUeAGX27moxj9SI2Ps4_b8-xjlXNjR-9KYzbueDPry5ziTLMUB9vBuBKftBm_AabmZbrVrRZJnj_T63FpOLMWBtRrmtnteU28Gi2E1e2IDmTXH8MORjX1atP7SRim3Gb_Sh1OBK4hyB1vwvqvxbBYC8WYjbqpLKXuGb9PU'
            ],
            'wifi' => true,
            'whiteboard' => true,
            'ac' => true,
            'soundproofing' => false,
            'natural_light' => true,
            'refreshments' => false,
        ]);

        $moreSpaces = [
            ['name' => 'The Green Room', 'location' => 'Toul Tom Poung, Phnom Penh', 'type' => $meetingType->id, 'price' => 20.00, 'cap' => 6, 'desc' => 'Eco-friendly meeting room with indoor plants.', 'img' => 'https://images.unsplash.com/photo-1572025442646-866d16c84a54?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Tech Hub Alpha', 'location' => 'Tuol Kork, Phnom Penh', 'type' => $workshopType->id, 'price' => 40.00, 'cap' => 20, 'desc' => 'High-tech workshop space for developer meetups.', 'img' => 'https://images.unsplash.com/photo-1531482615713-2afd69097998?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Urban Canvas', 'location' => 'Daun Penh, Phnom Penh', 'type' => $galleryType->id, 'price' => 60.00, 'cap' => 50, 'desc' => 'Minimalist art gallery for exhibitions.', 'img' => 'https://images.unsplash.com/photo-1582555172866-f73bb12a2ab3?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Riverside Studio', 'location' => 'Chroy Changvar, Phnom Penh', 'type' => $podcastType->id, 'price' => 35.00, 'cap' => 3, 'desc' => 'Cozy podcast studio overlooking the river.', 'img' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Executive Boardroom', 'location' => 'BKK1, Phnom Penh', 'type' => $meetingType->id, 'price' => 50.00, 'cap' => 12, 'desc' => 'Premium boardroom for executive meetings.', 'img' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'The Garage', 'location' => 'Toul Tom Poung, Phnom Penh', 'type' => $workshopType->id, 'price' => 25.00, 'cap' => 15, 'desc' => 'Industrial-style garage space for hands-on workshops.', 'img' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Heritage Gallery', 'location' => 'Daun Penh, Phnom Penh', 'type' => $galleryType->id, 'price' => 70.00, 'cap' => 40, 'desc' => 'Historic venue converted into a modern art space.', 'img' => 'https://images.unsplash.com/photo-1518998053401-a4149021eb3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'VoiceBox', 'location' => 'Tuol Kork, Phnom Penh', 'type' => $podcastType->id, 'price' => 45.00, 'cap' => 2, 'desc' => 'Compact and soundproof voiceover booth.', 'img' => 'https://images.unsplash.com/photo-1590602847861-f357a9332bbc?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Skyline Meet', 'location' => 'Chamkar Mon, Phnom Penh', 'type' => $meetingType->id, 'price' => 30.00, 'cap' => 8, 'desc' => 'Meeting room with a stunning city view.', 'img' => 'https://images.unsplash.com/photo-1497366754035-f200968a6e72?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Creative Hub', 'location' => 'BKK1, Phnom Penh', 'type' => $workshopType->id, 'price' => 35.00, 'cap' => 25, 'desc' => 'Vibrant space for creative agencies and freelancers.', 'img' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Lumina Space', 'location' => 'Chroy Changvar, Phnom Penh', 'type' => $galleryType->id, 'price' => 80.00, 'cap' => 100, 'desc' => 'Spacious gallery flooded with natural light.', 'img' => 'https://images.unsplash.com/photo-1542027959-866416972044?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Podcast Pro', 'location' => 'BKK1, Phnom Penh', 'type' => $podcastType->id, 'price' => 55.00, 'cap' => 4, 'desc' => 'Fully equipped studio for video and audio podcasts.', 'img' => 'https://images.unsplash.com/photo-1611162617474-5b21e879e113?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Focus Room', 'location' => 'Tuol Kork, Phnom Penh', 'type' => $meetingType->id, 'price' => 15.00, 'cap' => 4, 'desc' => 'Small, quiet room for focused work and 1-on-1s.', 'img' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'The Atrium', 'location' => 'Daun Penh, Phnom Penh', 'type' => $workshopType->id, 'price' => 60.00, 'cap' => 40, 'desc' => 'Large open atrium suitable for seminars.', 'img' => 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Artisan Alley', 'location' => 'Toul Tom Poung, Phnom Penh', 'type' => $galleryType->id, 'price' => 45.00, 'cap' => 30, 'desc' => 'Rustic gallery space for local artisans.', 'img' => 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Echo Chamber', 'location' => 'Chamkar Mon, Phnom Penh', 'type' => $podcastType->id, 'price' => 40.00, 'cap' => 2, 'desc' => 'Intimate audio recording space.', 'img' => 'https://images.unsplash.com/photo-1559523161-0fc0d6b28f7a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
            ['name' => 'Summit Meet', 'location' => 'BKK1, Phnom Penh', 'type' => $meetingType->id, 'price' => 25.00, 'cap' => 8, 'desc' => 'Modern meeting room with high-speed internet.', 'img' => 'https://images.unsplash.com/photo-1431540015161-0bf868a2d407?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'],
        ];

        foreach ($moreSpaces as $spaceData) {
            Space::create([
                'name' => $spaceData['name'],
                'location' => $spaceData['location'],
                'space_type_id' => $spaceData['type'],
                'price_per_hour' => $spaceData['price'],
                'capacity' => $spaceData['cap'],
                'description' => $spaceData['desc'],
                'status' => 'Active',
                'images' => [$spaceData['img']],
                'wifi' => true,
                'whiteboard' => rand(0, 1) == 1,
                'ac' => true,
                'soundproofing' => rand(0, 1) == 1,
                'natural_light' => rand(0, 1) == 1,
                'refreshments' => rand(0, 1) == 1,
            ]);
        }
    }
}
