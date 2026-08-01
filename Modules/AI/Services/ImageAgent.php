<?php

namespace Modules\AI\Services;

use Modules\Blog\Models\Post;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use EchoLabs\Prism\Prism;

/**
 * ImageAgent - AI-powered image generation service
 * 
 * This service handles:
 * - Featured image generation for blog posts
 * - Content image generation
 * - Image optimization
 * - Alt text generation
 */
class ImageAgent
{
    /**
     * Generate featured image for a blog post.
     *
     * @param Post $post
     * @param string|null $prompt
     * @return void
     */
    public function generateFeaturedImage(Post $post, ?string $prompt = null): void
    {
        $prompt = $prompt ?? $post->featured_image_prompt ?? $this->createPromptFromTitle($post->title);
        
        $imageData = $this->callImageGenerationAPI($prompt);
        
        if ($imageData) {
            // Save the generated image
            $post->addMediaFromUrl($imageData['url'])
                ->withCustomProperties([
                    'ai_generated' => true,
                    'prompt' => $prompt,
                    'model' => $imageData['model'] ?? 'dall-e-3',
                ])
                ->toMediaCollection('featured_image');
            
            // Update the post with the prompt used
            $post->update(['featured_image_prompt' => $prompt]);
        }
    }

    /**
     * Generate content images for a blog post.
     *
     * @param Post $post
     * @param array $prompts
     * @return array
     */
    public function generateContentImages(Post $post, array $prompts): array
    {
        $generatedImages = [];
        
        foreach ($prompts as $prompt) {
            $imageData = $this->callImageGenerationAPI($prompt);
            
            if ($imageData) {
                $media = $post->addMediaFromUrl($imageData['url'])
                    ->withCustomProperties([
                        'ai_generated' => true,
                        'prompt' => $prompt,
                        'model' => $imageData['model'] ?? 'dall-e-3',
                    ])
                    ->toMediaCollection('content_images');
                
                $generatedImages[] = $media;
            }
        }
        
        return $generatedImages;
    }

    /**
     * Create image prompt from post title.
     *
     * @param string $title
     * @return string
     */
    protected function createPromptFromTitle(string $title): string
    {
        try {
            // Use AI to enhance the prompt
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Create a detailed image generation prompt for a blog post titled: '{$title}'. The prompt should describe a professional, modern, visually appealing image. Return only the prompt.")
                ->withMaxTokens(100)
                ->generate();

            return trim($response->text);
        } catch (\Exception $e) {
            \Log::error('AI prompt enhancement failed: ' . $e->getMessage());
            return "Create a professional, high-quality featured image for a blog post titled: '{$title}'. " .
                   "The image should be visually appealing, modern, and relevant to the topic. " .
                   "Style: professional, clean, vibrant colors.";
        }
    }

    /**
     * Call AI image generation API.
     *
     * @param string $prompt
     * @return array|null
     */
    protected function callImageGenerationAPI(string $prompt): ?array
    {
        try {
            $apiKey = config('ai.openai_api_key') ?? env('OPENAI_API_KEY');
            
            if (empty($apiKey)) {
                \Log::warning('OpenAI API key not configured for image generation');
                return null;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1792x1024',
                'quality' => 'hd',
            ]);
            
            if ($response->successful()) {
                return [
                    'url' => $response->json('data.0.url'),
                    'model' => 'dall-e-3',
                ];
            }

            \Log::error('Image generation API error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            \Log::error('Image generation failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate alt text for an image using AI.
     *
     * @param string $imageUrl
     * @param string|null $context
     * @return string
     */
    public function generateAltText(string $imageUrl, ?string $context = null): string
    {
        try {
            $prompt = "Generate a descriptive alt text for an image";
            if ($context) {
                $prompt .= " in the context of: {$context}";
            }
            $prompt .= ". The alt text should be concise (max 125 characters) and descriptive for accessibility.";

            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt($prompt)
                ->withMaxTokens(50)
                ->generate();

            return trim($response->text);
        } catch (\Exception $e) {
            \Log::error('AI alt text generation failed: ' . $e->getMessage());
            return 'AI-generated image';
        }
    }

    /**
     * Optimize image for web.
     *
     * @param string $imagePath
     * @return void
     */
    public function optimizeImage(string $imagePath): void
    {
        // TODO: Implement image optimization (compression, resizing, format conversion)
        // Could use libraries like Intervention Image or Spatie Image Optimizer
    }

    /**
     * Generate multiple image variations.
     *
     * @param string $prompt
     * @param int $count
     * @return array
     */
    public function generateVariations(string $prompt, int $count = 3): array
    {
        $variations = [];
        
        for ($i = 0; $i < $count; $i++) {
            $imageData = $this->callImageGenerationAPI($prompt);
            if ($imageData) {
                $variations[] = $imageData;
            }
        }
        
        return $variations;
    }

    /**
     * Enhance image prompt using AI.
     *
     * @param string $basicPrompt
     * @return string
     */
    public function enhancePrompt(string $basicPrompt): string
    {
        try {
            $response = Prism::text()
                ->using(config('ai.default_provider', 'openai'), config('ai.default_model', 'gpt-4'))
                ->withPrompt("Enhance this image generation prompt to be more detailed and specific: '{$basicPrompt}'. Return only the enhanced prompt.")
                ->withMaxTokens(150)
                ->generate();

            return trim($response->text);
        } catch (\Exception $e) {
            \Log::error('Prompt enhancement failed: ' . $e->getMessage());
            return $basicPrompt;
        }
    }
}
