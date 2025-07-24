<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Article;
use App\Models\Tag;
use App\Models\Comment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create users
        $user1 = User::create([
            'username' => 'johndoe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'bio' => 'I am a software developer passionate about Laravel and modern web technologies.',
            'image' => 'https://api.realworld.io/images/demo-avatar.png',
        ]);

        $user2 = User::create([
            'username' => 'janedoe',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
            'bio' => 'Frontend developer who loves React and Vue.js',
            'image' => 'https://api.realworld.io/images/smiley-cyrus.jpeg',
        ]);

        $user3 = User::create([
            'username' => 'devblog',
            'email' => 'dev@example.com',
            'password' => Hash::make('password'),
            'bio' => 'Tech blogger sharing insights about modern development practices.',
            'image' => null,
        ]);

        // Create tags
        $tags = [
            'laravel', 'php', 'javascript', 'react', 'vue', 'tailwind',
            'api', 'backend', 'frontend', 'tutorial', 'tips', 'database'
        ];

        $tagModels = [];
        foreach ($tags as $tagName) {
            $tagModels[] = Tag::create(['name' => $tagName]);
        }

        // Create articles
        $articles = [
            [
                'title' => 'Getting Started with Laravel Sanctum',
                'description' => 'A comprehensive guide to setting up API authentication with Laravel Sanctum',
                'body' => "Laravel Sanctum provides a featherweight authentication system for SPAs and mobile applications. In this article, we'll explore how to set up Sanctum for your Laravel application.\n\n## Installation\n\nFirst, install Sanctum via Composer:\n\n```bash\ncomposer require laravel/sanctum\n```\n\n## Configuration\n\nPublish the Sanctum configuration and migrations:\n\n```bash\nphp artisan vendor:publish --provider=\"Laravel\\Sanctum\\SanctumServiceProvider\"\n```\n\nThen run the migrations:\n\n```bash\nphp artisan migrate\n```\n\n## Usage\n\nAdd the HasApiTokens trait to your User model and you're ready to start issuing tokens!",
                'user_id' => $user1->id,
                'tags' => ['laravel', 'php', 'api', 'tutorial']
            ],
            [
                'title' => 'Modern Frontend Development with React and Tailwind CSS',
                'description' => 'Building beautiful, responsive UIs with React and Tailwind CSS',
                'body' => "React and Tailwind CSS make a powerful combination for building modern web applications. Let's explore how to leverage both technologies effectively.\n\n## Why React + Tailwind?\n\n- Component-based architecture\n- Utility-first CSS framework\n- Rapid development\n- Consistent design system\n\n## Setting Up\n\nStart by creating a new React app and installing Tailwind CSS:\n\n```bash\nnpx create-react-app my-app\ncd my-app\nnpm install -D tailwindcss postcss autoprefixer\n```\n\n## Best Practices\n\n1. Use semantic component names\n2. Leverage Tailwind's responsive design utilities\n3. Create reusable components\n4. Optimize for performance",
                'user_id' => $user2->id,
                'tags' => ['react', 'javascript', 'tailwind', 'frontend']
            ],
            [
                'title' => 'Database Design Best Practices',
                'description' => 'Essential principles for designing efficient and scalable databases',
                'body' => "Good database design is crucial for application performance and maintainability. Here are key principles to follow:\n\n## Normalization\n\nNormalize your database to reduce redundancy:\n\n- First Normal Form (1NF)\n- Second Normal Form (2NF)\n- Third Normal Form (3NF)\n\n## Indexing Strategy\n\nProper indexing can dramatically improve query performance:\n\n```sql\nCREATE INDEX idx_user_email ON users(email);\nCREATE INDEX idx_article_slug ON articles(slug);\n```\n\n## Performance Considerations\n\n- Use appropriate data types\n- Avoid N+1 queries\n- Implement proper caching\n- Monitor slow queries\n\n## Security\n\n- Use parameterized queries\n- Implement proper access controls\n- Regular security audits",
                'user_id' => $user3->id,
                'tags' => ['database', 'backend', 'tips']
            ]
        ];

        foreach ($articles as $articleData) {
            $tagNames = $articleData['tags'];
            unset($articleData['tags']);

            $article = Article::create($articleData);

            // Attach tags
            $articleTags = collect($tagNames)->map(function ($tagName) use ($tagModels) {
                return collect($tagModels)->firstWhere('name', $tagName);
            })->filter();

            $article->tags()->attach($articleTags->pluck('id'));

            // Add some comments
            Comment::create([
                'body' => 'Great article! Very informative and well-written.',
                'user_id' => $user2->id,
                'article_id' => $article->id,
            ]);

            Comment::create([
                'body' => 'Thanks for sharing this. I learned a lot from your explanation.',
                'user_id' => $user3->id,
                'article_id' => $article->id,
            ]);
        }

        // Create some follow relationships
        $user1->follow($user2);
        $user1->follow($user3);
        $user2->follow($user3);

        // Create some favorites
        $firstArticle = Article::first();
        $secondArticle = Article::skip(1)->first();

        $user1->favorite($secondArticle);
        $user2->favorite($firstArticle);
        $user3->favorite($firstArticle);

        $this->command->info('Blog seeder completed! Created users, articles, comments, and relationships.');
    }
}
