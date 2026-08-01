<?php

namespace Modules\AI\Services;

use Modules\Blog\Models\Post;
use Modules\Blog\Models\Category;
use Modules\Blog\Models\Tag;
use Illuminate\Support\Str;
use EchoLabs\Prism\Prism;

/**
 * ContentAgent - AI-powered content generation service
 * 
 * This service handles:
 * - Blog topic generation
 * - Content drafting and rewriting
 * - Grammar and tone improvement
 * - SEO optimization
 */
class ContentAgent
{
    /**
     * Generate a blog post using AI.
     *
     * @param array $params Parameters for content generation
     * @return Post
     */
    public function generateBlogPost(array $params): Post
    {
        $title = $params['title'] ?? $this->generateTitle($params['topic'] ?? 'General Topic');
        $slug = Str::slug($title);
        
        // Generate content using AI
        $content = $this->generateContent($title, $params);
        $excerpt = $this->generateExcerpt($content);
        
        // Create the post
        $post = Post::create([
            'title' => $title,
            'slug' => $slug,
            'excerpt' => $excerpt,
            'content' => $content,
            'category_id' => $params['category_id'] ?? null,
            'author_id' => $params['author_id'],
            'meta_title' => $this->generateMetaTitle($title),
            'meta_description' => $this->generateMetaDescription($excerpt),
            'meta_keywords' => $this->generateKeywords($content),
            'ai_generated' => true,
            'ai_model' => $params['ai_model'] ?? config('ai.default_model', 'gpt-4'),
            'ai_generated_at' => now(),
            'ai_metadata' => [
                'prompt' => $params['prompt'] ?? null,
                'temperature' => $params['temperature'] ?? 0.7,
                'max_tokens' => $params['max_tokens'] ?? 2000,
            ],
            'featured_image_prompt' => $params['image_prompt'] ?? $this->generateImagePrompt($title),
            'status' => $params['status'] ?? 'draft',
        ]);
        
        // Attach tags if provided
        if (isset($params['tags'])) {
            $post->tags()->attach($params['tags']);
        }
        
        // Calculate reading time
        $post->calculateReadingTime();
        
        return $post;
    }

    /**
     * Generate a title based on topic.
     *
     * @param string $topic
     * @return string
     */
    protected function generateTitle(string $topic): string
    {
        try {
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Generate a compelling, SEO-friendly blog post title about: {$topic}. Return only the title, nothing else.")
                ->withMaxTokens(50)
                ->generate();

            return trim($response->text);
        } catch (\Exception $e) {
            \Log::error('AI title generation failed: ' . $e->getMessage());
            return ucwords($topic);
        }
    }

    /**
     * Generate content for the blog post.
     *
     * @param string $title
     * @param array $params
     * @return string
     */
    protected function generateContent(string $title, array $params): string
    {
        try {
            $prompt = $params['prompt'] ?? "Write a comprehensive, well-structured blog post about: {$title}. Include an introduction, main sections with headings, and a conclusion. Use HTML formatting with <h2>, <h3>, <p>, <ul>, <li> tags.";
            
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt($prompt)
                ->withMaxTokens($params['max_tokens'] ?? config('ai.max_tokens', 2048))
                ->withTemperature($params['temperature'] ?? config('ai.temperature', 0.7))
                ->generate();

            return $response->text;
        } catch (\Exception $e) {
            \Log::error('AI content generation failed: ' . $e->getMessage());
            return "<h2>Introduction</h2>\n<p>This is an AI-generated blog post about {$title}.</p>\n\n" .
                   "<h2>Main Content</h2>\n<p>Content generation encountered an error. Please try again.</p>\n\n" .
                   "<h2>Conclusion</h2>\n<p>Summary and final thoughts.</p>";
        }
    }

    /**
     * Generate excerpt from content.
     *
     * @param string $content
     * @return string
     */
    protected function generateExcerpt(string $content): string
    {
        $text = strip_tags($content);
        return Str::limit($text, 200);
    }

    /**
     * Generate SEO-optimized meta title.
     *
     * @param string $title
     * @return string
     */
    protected function generateMetaTitle(string $title): string
    {
        try {
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Optimize this title for SEO (max 60 characters): {$title}. Return only the optimized title.")
                ->withMaxTokens(30)
                ->generate();

            return Str::limit(trim($response->text), 60);
        } catch (\Exception $e) {
            \Log::error('AI meta title generation failed: ' . $e->getMessage());
            return Str::limit($title, 60);
        }
    }

    /**
     * Generate meta description.
     *
     * @param string $excerpt
     * @return string
     */
    protected function generateMetaDescription(string $excerpt): string
    {
        try {
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Create a compelling meta description (max 160 characters) from this text: {$excerpt}. Return only the description.")
                ->withMaxTokens(50)
                ->generate();

            return Str::limit(trim($response->text), 160);
        } catch (\Exception $e) {
            \Log::error('AI meta description generation failed: ' . $e->getMessage());
            return Str::limit($excerpt, 160);
        }
    }

    /**
     * Generate keywords from content.
     *
     * @param string $content
     * @return string
     */
    protected function generateKeywords(string $content): string
    {
        try {
            $text = Str::limit(strip_tags($content), 500);
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Extract 5-10 relevant SEO keywords from this text, return as comma-separated list: {$text}")
                ->withMaxTokens(50)
                ->generate();

            return trim($response->text);
        } catch (\Exception $e) {
            \Log::error('AI keyword generation failed: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Generate image prompt for featured image.
     *
     * @param string $title
     * @return string
     */
    protected function generateImagePrompt(string $title): string
    {
        return "Professional, high-quality featured image for blog post: {$title}";
    }

    /**
     * Rewrite existing content with AI.
     *
     * @param Post $post
     * @param array $params
     * @return Post
     */
    public function rewriteContent(Post $post, array $params = []): Post
    {
        try {
            $tone = $params['tone'] ?? 'professional';
            $prompt = "Rewrite the following content in a {$tone} tone while maintaining the key points:\n\n{$post->content}";
            
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt($prompt)
                ->withMaxTokens($params['max_tokens'] ?? 2048)
                ->generate();

            $post->update(['content' => $response->text]);
            return $post->fresh();
        } catch (\Exception $e) {
            \Log::error('AI content rewrite failed: ' . $e->getMessage());
            return $post;
        }
    }

    /**
     * Improve grammar and tone.
     *
     * @param string $content
     * @param string $tone
     * @return string
     */
    public function improveContent(string $content, string $tone = 'professional'): string
    {
        try {
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Improve the grammar, clarity, and {$tone} tone of this text:\n\n{$content}")
                ->withMaxTokens(2048)
                ->generate();

            return $response->text;
        } catch (\Exception $e) {
            \Log::error('AI content improvement failed: ' . $e->getMessage());
            return $content;
        }
    }

    /**
     * Generate topic suggestions.
     *
     * @param array $params
     * @return array
     */
    public function suggestTopics(array $params = []): array
    {
        try {
            $industry = $params['industry'] ?? 'technology';
            $count = $params['count'] ?? 5;
            
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Suggest {$count} trending blog post topics for the {$industry} industry. Return as a numbered list.")
                ->withMaxTokens(200)
                ->generate();

            // Parse the response into an array
            $topics = array_filter(array_map('trim', explode("\n", $response->text)));
            return array_values($topics);
        } catch (\Exception $e) {
            \Log::error('AI topic suggestion failed: ' . $e->getMessage());
            return [
                'Topic 1: Industry Trends',
                'Topic 2: Best Practices',
                'Topic 3: Case Studies',
            ];
        }
    }
}
