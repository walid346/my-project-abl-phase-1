<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;

class TranslateArticles extends Command
{
    protected $signature = 'articles:translate {--dry-run : Show what would be translated without making changes}';
    protected $description = 'Translate French articles to English';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        // Articles à traduire (ajoutez vos traductions ici)
        $translations = [
            // Exemple de traduction
            'Titre français de votre article' => [
                'title' => 'English title of your article',
                'content' => 'English content of your article...',
                'excerpt' => 'English excerpt...'
            ],
            
            // Ajoutez vos autres traductions ici
            // 'Ancien titre français' => [
            //     'title' => 'New English title',
            //     'content' => 'New English content...',
            //     'excerpt' => 'New English excerpt...'
            // ],
        ];

        $this->info('Starting article translation...');
        $translatedCount = 0;

        foreach ($translations as $frenchTitle => $englishData) {
            $article = Article::where('title', $frenchTitle)->first();
            
            if ($article) {
                $this->line("Found article: {$frenchTitle}");
                
                if (!$dryRun) {
                    $article->update([
                        'title' => $englishData['title'],
                        'content' => $englishData['content'],
                        'excerpt' => $englishData['excerpt'] ?? substr(strip_tags($englishData['content']), 0, 150),
                    ]);
                    $this->info("✓ Translated: {$englishData['title']}");
                } else {
                    $this->info("Would translate to: {$englishData['title']}");
                }
                
                $translatedCount++;
            } else {
                $this->warn("Article not found: {$frenchTitle}");
            }
        }

        $this->info("Translation complete! {$translatedCount} articles processed.");
        
        if ($dryRun) {
            $this->info('Run without --dry-run to apply changes.');
        }
    }
}
