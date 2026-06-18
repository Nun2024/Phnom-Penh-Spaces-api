<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Space;
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

        // Seed Creative Spaces
        Space::create([
            'name' => 'The Glass Studio',
            'location' => 'BKK1, Phnom Penh',
            'space_type' => 'meeting',
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
            'space_type' => 'workshop',
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
            'space_type' => 'podcast',
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
            'space_type' => 'workshop',
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
    }
}
