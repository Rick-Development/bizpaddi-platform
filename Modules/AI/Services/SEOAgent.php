<?php

namespace Modules\AI\Services;

use Modules\Blog\Models\Post;
use EchoLabs\Prism\Prism;
use Illuminate\Support\Str;

/**
 * SEOAgent - AI-powered SEO optimization service
 * 
 * This service handles:
 * - Metadata optimization
 * - Keyword research and optimization
 * - Content SEO analysis
 * - Internal linking suggestions
 */
class SEOAgent
{
    /**
     * Optimize post for SEO.
     *
     * @param Post $post
     * @return Post
     */
    public function optimizePost(Post $post): Post
    {
        // Generate optimized meta title
        if (empty($post->meta_title)) {
            $post->meta_title = $this->generateMetaTitle($post->title, $post->content);
        }
        
        // Generate optimized meta description
        if (empty($post->meta_description)) {
            $post->meta_description = $this->generateMetaDescription($post->content, $post->excerpt);
        }
        
        // Generate keywords
        if (empty($post->meta_keywords)) {
            $post->meta_keywords = $this->generateKeywords($post->content);
        }
        
        $post->save();
        
        return $post;
    }

    /**
     * Generate SEO-optimized meta title.
     *
     * @param string $title
     * @param string $content
     * @return string
     */
    protected function generateMetaTitle(string $title, string $content): string
    {
        try {
            $contentSnippet = Str::limit(strip_tags($content), 200);
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Create an SEO-optimized meta title (50-60 characters) for a blog post titled '{$title}'. Context: {$contentSnippet}. Return only the meta title.")
                ->withMaxTokens(30)
                ->generate();

            return Str::limit(trim($response->text), 60);
        } catch (\Exception $e) {
            \Log::error('AI meta title generation failed: ' . $e->getMessage());
            return substr($title, 0, 60);
        }
    }

    /**
     * Generate SEO-optimized meta description.
     *
     * @param string $content
     * @param string|null $excerpt
     * @return string
     */
    protected function generateMetaDescription(string $content, ?string $excerpt): string
    {
        try {
            $text = $excerpt ?? Str::limit(strip_tags($content), 300);
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Create a compelling SEO meta description (150-160 characters) with a call-to-action from this text: {$text}. Return only the description.")
                ->withMaxTokens(50)
                ->generate();

            return Str::limit(trim($response->text), 160);
        } catch (\Exception $e) {
            \Log::error('AI meta description generation failed: ' . $e->getMessage());
            $text = $excerpt ?? strip_tags($content);
            return substr($text, 0, 160);
        }
    }

    /**
     * Generate relevant keywords.
     *
     * @param string $content
     * @return string
     */
    protected function generateKeywords(string $content): string
    {
        try {
            $contentSnippet = Str::limit(strip_tags($content), 500);
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Extract 8-12 relevant SEO keywords from this content. Return as comma-separated list: {$contentSnippet}")
                ->withMaxTokens(60)
                ->generate();

            return trim($response->text);
        } catch (\Exception $e) {
            \Log::error('AI keyword generation failed: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Analyze content for SEO score.
     *
     * @param Post $post
     * @return array
     */
    public function analyzeContent(Post $post): array
    {
        $score = 0;
        $suggestions = [];
        
        // Check title length
        if (strlen($post->title) >= 30 && strlen($post->title) <= 60) {
            $score += 10;
        } else {
            $suggestions[] = 'Title should be between 30-60 characters';
        }
        
        // Check meta description
        if ($post->meta_description && strlen($post->meta_description) >= 120 && strlen($post->meta_description) <= 160) {
            $score += 10;
        } else {
            $suggestions[] = 'Meta description should be between 120-160 characters';
        }
        
        // Check content length
        $wordCount = str_word_count(strip_tags($post->content));
        if ($wordCount >= 300) {
            $score += 20;
        } else {
            $suggestions[] = 'Content should be at least 300 words';
        }
        
        // Check for images
        if ($post->featured_image) {
            $score += 10;
        } else {
            $suggestions[] = 'Add a featured image';
        }
        
        // Check for headings
        if (preg_match('/<h[2-6]/', $post->content)) {
            $score += 10;
        } else {
            $suggestions[] = 'Add headings (H2, H3) to structure content';
        }
        
        // Check for external links
        if (preg_match('/<a[^>]+href=["\']https?:\/\//', $post->content)) {
            $score += 10;
        } else {
            $suggestions[] = 'Add relevant external links';
        }
        
        // AI-powered content quality check
        try {
            $aiSuggestions = $this->getAISuggestions($post);
            $suggestions = array_merge($suggestions, $aiSuggestions);
            $score += 20; // Bonus for AI analysis
        } catch (\Exception $e) {
            \Log::error('AI SEO analysis failed: ' . $e->getMessage());
        }
        
        return [
            'score' => min($score, 100),
            'suggestions' => $suggestions,
            'grade' => $this->getGrade($score),
        ];
    }

    /**
     * Get AI-powered SEO suggestions.
     *
     * @param Post $post
     * @return array
     */
    protected function getAISuggestions(Post $post): array
    {
        try {
            $contentSnippet = Str::limit(strip_tags($post->content), 500);
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Analyze this blog content for SEO and provide 2-3 specific improvement suggestions:\n\nTitle: {$post->title}\nContent: {$contentSnippet}\n\nReturn as a numbered list.")
                ->withMaxTokens(150)
                ->generate();

            // Parse numbered list into array
            $suggestions = array_filter(array_map('trim', explode("\n", $response->text)));
            return array_values($suggestions);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get SEO grade based on score.
     *
     * @param int $score
     * @return string
     */
    protected function getGrade(int $score): string
    {
        if ($score >= 90) return 'A+';
        if ($score >= 80) return 'A';
        if ($score >= 70) return 'B';
        if ($score >= 60) return 'C';
        if ($score >= 50) return 'D';
        return 'F';
    }

    /**
     * Suggest internal links for a post.
     *
     * @param Post $post
     * @return array
     */
    public function suggestInternalLinks(Post $post): array
    {
        try {
            // Get recent published posts
            $recentPosts = Post::published()
                ->where('id', '!=', $post->id)
                ->limit(20)
                ->get(['id', 'title', 'excerpt', 'slug']);

            if ($recentPosts->isEmpty()) {
                return [];
            }

            // Use AI to find most relevant posts
            $postsList = $recentPosts->map(fn($p) => "{$p->id}: {$p->title}")->implode("\n");
            $contentSnippet = Str::limit(strip_tags($post->content), 500);

            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Given this blog post content:\n{$contentSnippet}\n\nWhich of these posts are most relevant for internal linking? Return only the IDs (comma-separated):\n{$postsList}")
                ->withMaxTokens(50)
                ->generate();

            // Parse IDs from response
            $ids = array_map('trim', explode(',', $response->text));
            $ids = array_filter($ids, 'is_numeric');

            return Post::whereIn('id', $ids)->limit(5)->get()->toArray();
        } catch (\Exception $e) {
            \Log::error('AI internal link suggestion failed: ' . $e->getMessage());
            return Post::published()
                ->where('id', '!=', $post->id)
                ->limit(5)
                ->get()
                ->toArray();
        }
    }

    /**
     * Generate schema markup for a post.
     *
     * @param Post $post
     * @return array
     */
    public function generateSchemaMarkup(Post $post): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->meta_description ?? $post->excerpt,
            'author' => [
                '@type' => 'Person',
                'name' => $post->author->name ?? 'Unknown',
            ],
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
        ];
    }
}
