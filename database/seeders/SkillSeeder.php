<?php

namespace Database\Seeders;

use App\Models\Skill;
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

            // Additional Physical Skills
            [
                'name' => 'Gymnastics',
                'description' => 'Gymnastics and bodyweight skills',
                'icon' => '🤸',
                'category' => 'Physical',
                'xp_rate' => 14,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'GMB Fitness', 'url' => 'https://www.youtube.com/@GMBFitnessSkills'],
                    ['type' => 'youtube', 'title' => 'FitnessFAQs', 'url' => 'https://www.youtube.com/@FitnessFAQs'],
                    ['type' => 'website', 'title' => 'Gold Medal Bodies', 'url' => 'https://gmb.io'],
                    ['type' => 'youtube', 'title' => 'Antranik', 'url' => 'https://www.youtube.com/@AntranikDotOrg'],
                ],
            ],

            // Additional Knowledge Skills
            [
                'name' => 'History',
                'description' => 'Learning about history and civilizations',
                'icon' => '📜',
                'category' => 'Knowledge',
                'xp_rate' => 8,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'History Matters', 'url' => 'https://www.youtube.com/@HistoryMatters'],
                    ['type' => 'youtube', 'title' => 'Crash Course History', 'url' => 'https://www.youtube.com/@crashcourse'],
                    ['type' => 'website', 'title' => 'Khan Academy History', 'url' => 'https://www.khanacademy.org/humanities/world-history'],
                    ['type' => 'youtube', 'title' => 'HistoryExtra', 'url' => 'https://www.youtube.com/@HistoryExtra'],
                ],
            ],
            [
                'name' => 'Science',
                'description' => 'Learning about physics, chemistry, and biology',
                'icon' => '🔬',
                'category' => 'Knowledge',
                'xp_rate' => 9,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Kurzgesagt', 'url' => 'https://www.youtube.com/@kurzgesagt'],
                    ['type' => 'youtube', 'title' => 'Veritasium', 'url' => 'https://www.youtube.com/@veritasium'],
                    ['type' => 'website', 'title' => 'Khan Academy Science', 'url' => 'https://www.khanacademy.org/science'],
                    ['type' => 'youtube', 'title' => 'SciShow', 'url' => 'https://www.youtube.com/@SciShow'],
                ],
            ],
            [
                'name' => 'Mathematics',
                'description' => 'Learning mathematics and problem-solving',
                'icon' => '🔢',
                'category' => 'Knowledge',
                'xp_rate' => 10,
                'resources' => [
                    ['type' => 'youtube', 'title' => '3Blue1Brown', 'url' => 'https://www.youtube.com/@3blue1brown'],
                    ['type' => 'website', 'title' => 'Khan Academy Math', 'url' => 'https://www.khanacademy.org/math'],
                    ['type' => 'website', 'title' => 'Brilliant.org', 'url' => 'https://brilliant.org'],
                    ['type' => 'youtube', 'title' => 'Numberphile', 'url' => 'https://www.youtube.com/@numberphile'],
                ],
            ],
            [
                'name' => 'Philosophy',
                'description' => 'Exploring philosophical ideas and critical thinking',
                'icon' => '💭',
                'category' => 'Knowledge',
                'xp_rate' => 8,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Wireless Philosophy', 'url' => 'https://www.youtube.com/@WirelessPhilosophy'],
                    ['type' => 'youtube', 'title' => 'Academy of Ideas', 'url' => 'https://www.youtube.com/@academyofideas'],
                    ['type' => 'website', 'title' => 'Stanford Encyclopedia of Philosophy', 'url' => 'https://plato.stanford.edu'],
                    ['type' => 'website', 'title' => 'Philosophy Basics', 'url' => 'https://www.philosophybasics.com'],
                ],
            ],
            [
                'name' => 'Psychology',
                'description' => 'Understanding human behavior and mental processes',
                'icon' => '🧠',
                'category' => 'Knowledge',
                'xp_rate' => 9,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Crash Course Psychology', 'url' => 'https://www.youtube.com/playlist?list=PL8dPuuaLjXtOPRKzVLY0jJY-uHOH9KVU6'],
                    ['type' => 'youtube', 'title' => 'Psych2Go', 'url' => 'https://www.youtube.com/@Psych2go'],
                    ['type' => 'website', 'title' => 'Khan Academy Psychology', 'url' => 'https://www.khanacademy.org/test-prep/mcat/behavior/psych-stats'],
                    ['type' => 'website', 'title' => 'Psychology Today', 'url' => 'https://www.psychologytoday.com'],
                ],
            ],
            [
                'name' => 'Economics',
                'description' => 'Understanding economics and financial systems',
                'icon' => '💰',
                'category' => 'Knowledge',
                'xp_rate' => 9,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'Economics Explained', 'url' => 'https://www.youtube.com/@EconomicsExplained'],
                    ['type' => 'youtube', 'title' => 'Crash Course Economics', 'url' => 'https://www.youtube.com/playlist?list=PL8dPuuaLjXtPNZwz5_o_5uirJ8gQXnhEO'],
                    ['type' => 'website', 'title' => 'Khan Academy Economics', 'url' => 'https://www.khanacademy.org/economics-finance-domain'],
                    ['type' => 'website', 'title' => 'Investopedia', 'url' => 'https://www.investopedia.com'],
                ],
            ],
            [
                'name' => 'Astronomy',
                'description' => 'Exploring space, stars, and the universe',
                'icon' => '🔭',
                'category' => 'Knowledge',
                'xp_rate' => 8,
                'resources' => [
                    ['type' => 'youtube', 'title' => 'PBS Space Time', 'url' => 'https://www.youtube.com/@pbsspacetime'],
                    ['type' => 'youtube', 'title' => 'Astrum', 'url' => 'https://www.youtube.com/@astrumspace'],
                    ['type' => 'website', 'title' => 'NASA', 'url' => 'https://www.nasa.gov'],
                    ['type' => 'app', 'title' => 'SkySafari Star Chart', 'url' => 'https://skysafariastronomy.com'],
                ],
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}
