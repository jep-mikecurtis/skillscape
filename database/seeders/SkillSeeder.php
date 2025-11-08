<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Physical Skills
            [
                'name' => 'Running',
                'description' => 'Track your running and cardio sessions',
                'icon' => '🏃',
                'category' => 'Physical',
                'xp_rate' => 15,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Couch to 5K Program', 'url' => 'https://www.youtube.com/@couchto5k'],
                    ['type' => 'youtube', 'title' => 'Running Form Tips', 'url' => 'https://www.youtube.com/results?search_query=proper+running+form'],
                    ['type' => 'app', 'title' => 'Strava - Track Your Runs', 'url' => 'https://www.strava.com'],
                ],
            ],
            [
                'name' => 'Strength Training',
                'description' => 'Weight lifting and resistance training',
                'icon' => '💪',
                'category' => 'Physical',
                'xp_rate' => 15,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Jeff Nippard - Science-Based Training', 'url' => 'https://www.youtube.com/@JeffNippard'],
                    ['type' => 'youtube', 'title' => 'AthleanX', 'url' => 'https://www.youtube.com/@athleanx'],
                    ['type' => 'website', 'title' => 'StrongLifts 5x5', 'url' => 'https://stronglifts.com'],
                ],
            ],
            [
                'name' => 'Yoga',
                'description' => 'Practice yoga and flexibility',
                'icon' => '🧘',
                'category' => 'Physical',
                'xp_rate' => 12,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Yoga With Adriene', 'url' => 'https://www.youtube.com/@yogawithadriene'],
                    ['type' => 'youtube', 'title' => 'Breathe and Flow', 'url' => 'https://www.youtube.com/@BreatheAndFlow'],
                    ['type' => 'app', 'title' => 'Down Dog Yoga App', 'url' => 'https://www.downdogapp.com'],
                ],
            ],
            [
                'name' => 'Swimming',
                'description' => 'Swimming and water activities',
                'icon' => '🏊',
                'category' => 'Physical',
                'xp_rate' => 14,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Swim Like A Pro', 'url' => 'https://www.youtube.com/results?search_query=swim+technique'],
                    ['type' => 'website', 'title' => 'SwimSmooth', 'url' => 'https://www.swimsmooth.com'],
                ],
            ],

            // Creative Skills
            [
                'name' => 'Guitar',
                'description' => 'Learn and practice guitar',
                'icon' => '🎸',
                'category' => 'Creative',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Justin Guitar - Free Lessons', 'url' => 'https://www.youtube.com/@JustinGuitarSongs'],
                    ['type' => 'youtube', 'title' => 'Marty Music', 'url' => 'https://www.youtube.com/@MartyMusic'],
                    ['type' => 'website', 'title' => 'Ultimate Guitar Tabs', 'url' => 'https://www.ultimate-guitar.com'],
                ],
            ],
            [
                'name' => 'Piano',
                'description' => 'Piano practice and music theory',
                'icon' => '🎹',
                'category' => 'Creative',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Piano With Jonny', 'url' => 'https://www.youtube.com/@PianoWithJonny'],
                    ['type' => 'website', 'title' => 'Flowkey - Learn Piano', 'url' => 'https://www.flowkey.com'],
                    ['type' => 'youtube', 'title' => 'Andrew Huang - Music Theory', 'url' => 'https://www.youtube.com/@andrewhuang'],
                ],
            ],
            [
                'name' => 'Drawing',
                'description' => 'Sketching, drawing, and visual arts',
                'icon' => '🎨',
                'category' => 'Creative',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Proko - Drawing Fundamentals', 'url' => 'https://www.youtube.com/@ProkoTV'],
                    ['type' => 'website', 'title' => 'Drawabox - Free Lessons', 'url' => 'https://drawabox.com'],
                    ['type' => 'youtube', 'title' => 'Sinix Design', 'url' => 'https://www.youtube.com/@sinixdesign'],
                ],
            ],
            [
                'name' => 'Writing',
                'description' => 'Creative writing and journaling',
                'icon' => '✍️',
                'category' => 'Creative',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Brandon Sanderson Lectures', 'url' => 'https://www.youtube.com/results?search_query=brandon+sanderson+lectures'],
                    ['type' => 'website', 'title' => 'r/writing Community', 'url' => 'https://www.reddit.com/r/writing'],
                    ['type' => 'website', 'title' => 'NaNoWriMo', 'url' => 'https://nanowrimo.org'],
                ],
            ],
            [
                'name' => 'Photography',
                'description' => 'Photography skills and editing',
                'icon' => '📷',
                'category' => 'Creative',
                'xp_rate' => 12,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Peter McKinnon', 'url' => 'https://www.youtube.com/@PeterMcKinnon'],
                    ['type' => 'youtube', 'title' => 'Mango Street', 'url' => 'https://www.youtube.com/@MangoStreet'],
                    ['type' => 'website', 'title' => 'r/photoclass', 'url' => 'https://www.reddit.com/r/photoclass'],
                ],
            ],

            // Professional Skills
            [
                'name' => 'Programming',
                'description' => 'Coding and software development',
                'icon' => '💻',
                'category' => 'Professional',
                'xp_rate' => 12,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'freeCodeCamp', 'url' => 'https://www.youtube.com/@freecodecamp'],
                    ['type' => 'website', 'title' => 'The Odin Project', 'url' => 'https://www.theodinproject.com'],
                    ['type' => 'website', 'title' => 'LeetCode Practice', 'url' => 'https://leetcode.com'],
                    ['type' => 'youtube', 'title' => 'Traversy Media', 'url' => 'https://www.youtube.com/@TraversyMedia'],
                ],
            ],
            [
                'name' => 'Design',
                'description' => 'UI/UX and graphic design',
                'icon' => '🎨',
                'category' => 'Professional',
                'xp_rate' => 12,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Figma Tutorial', 'url' => 'https://www.youtube.com/results?search_query=figma+tutorial'],
                    ['type' => 'website', 'title' => 'Dribbble Inspiration', 'url' => 'https://dribbble.com'],
                    ['type' => 'website', 'title' => 'Laws of UX', 'url' => 'https://lawsofux.com'],
                ],
            ],
            [
                'name' => 'Reading',
                'description' => 'Books, articles, and learning',
                'icon' => '📚',
                'category' => 'Knowledge',
                'xp_rate' => 8,
                'resources' => [
                    ['type' => 'website', 'title' => 'Goodreads', 'url' => 'https://www.goodreads.com'],
                    ['type' => 'website', 'title' => 'Blinkist Book Summaries', 'url' => 'https://www.blinkist.com'],
                    ['type' => 'website', 'title' => 'Project Gutenberg - Free Books', 'url' => 'https://www.gutenberg.org'],
                ],
            ],
            [
                'name' => 'Business',
                'description' => 'Entrepreneurship and business skills',
                'icon' => '💼',
                'category' => 'Professional',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Y Combinator Startup School', 'url' => 'https://www.youtube.com/@ycombinator'],
                    ['type' => 'website', 'title' => 'Indie Hackers', 'url' => 'https://www.indiehackers.com'],
                    ['type' => 'youtube', 'title' => 'Ali Abdaal Productivity', 'url' => 'https://www.youtube.com/@aliabdaal'],
                ],
            ],

            // Language Skills
            [
                'name' => 'Spanish',
                'description' => 'Learning Spanish language',
                'icon' => '🇪🇸',
                'category' => 'Language',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'app', 'title' => 'Duolingo', 'url' => 'https://www.duolingo.com'],
                    ['type' => 'youtube', 'title' => 'SpanishDict', 'url' => 'https://www.youtube.com/@spanishdict'],
                    ['type' => 'youtube', 'title' => 'Dreaming Spanish', 'url' => 'https://www.youtube.com/@DreamingSpanish'],
                ],
            ],
            [
                'name' => 'French',
                'description' => 'Learning French language',
                'icon' => '🇫🇷',
                'category' => 'Language',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'app', 'title' => 'Duolingo French', 'url' => 'https://www.duolingo.com/course/fr/en/Learn-French'],
                    ['type' => 'youtube', 'title' => 'Learn French with Alexa', 'url' => 'https://www.youtube.com/@learnfrenchwithalexa'],
                    ['type' => 'website', 'title' => 'Coffee Break French Podcast', 'url' => 'https://coffeebreaklanguages.com'],
                ],
            ],
            [
                'name' => 'Japanese',
                'description' => 'Learning Japanese language',
                'icon' => '🇯🇵',
                'category' => 'Language',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'website', 'title' => 'WaniKani - Learn Kanji', 'url' => 'https://www.wanikani.com'],
                    ['type' => 'youtube', 'title' => 'JapanesePod101', 'url' => 'https://www.youtube.com/@JapanesePod101'],
                    ['type' => 'app', 'title' => 'HelloTalk Language Exchange', 'url' => 'https://www.hellotalk.com'],
                ],
            ],

            // Life Skills
            [
                'name' => 'Cooking',
                'description' => 'Culinary skills and recipes',
                'icon' => '🍳',
                'category' => 'Life',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Binging with Babish', 'url' => 'https://www.youtube.com/@bingingwithbabish'],
                    ['type' => 'youtube', 'title' => 'Joshua Weissman', 'url' => 'https://www.youtube.com/@JoshuaWeissman'],
                    ['type' => 'website', 'title' => 'SeriousEats Recipes', 'url' => 'https://www.seriouseats.com'],
                ],
            ],
            [
                'name' => 'Meditation',
                'description' => 'Mindfulness and meditation practice',
                'icon' => '🧘',
                'category' => 'Wellness',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'app', 'title' => 'Headspace', 'url' => 'https://www.headspace.com'],
                    ['type' => 'youtube', 'title' => 'Mindful Movement', 'url' => 'https://www.youtube.com/@TheMindfulMovement'],
                    ['type' => 'website', 'title' => 'r/Meditation', 'url' => 'https://www.reddit.com/r/Meditation'],
                ],
            ],
            [
                'name' => 'Gardening',
                'description' => 'Growing plants and gardening',
                'icon' => '🌱',
                'category' => 'Life',
                'xp_rate' => 8,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Epic Gardening', 'url' => 'https://www.youtube.com/@epicgardening'],
                    ['type' => 'youtube', 'title' => 'MIgardener', 'url' => 'https://www.youtube.com/@MIgardener'],
                    ['type' => 'website', 'title' => 'r/gardening', 'url' => 'https://www.reddit.com/r/gardening'],
                ],
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
