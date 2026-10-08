<?php

namespace App\Services;

use App\Models\ServiceCitySeo;

class SchemaMarkupService
{
    /**
     * Generate BreadcrumbList schema
     */
    public static function breadcrumbSchema(ServiceCitySeo $page): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Home',
                    'item' => route('home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => $page->service->title,
                    'item' => route('service.show', $page->service->slug),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $page->city->name,
                    'item' => route('city.detail', $page->city->slug),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 4,
                    'name' => "{$page->service->title} in {$page->city->name}",
                    'item' => $page->url,
                ],
            ],
        ];
    }

    /**
     * Generate LocalBusiness schema
     */
    public static function localBusinessSchema(ServiceCitySeo $page): array
    {
        $settings = \App\Models\Setting::query()->pluck('value', 'key')->toArray();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => config('app.name') . " - {$page->service->title}",
            'description' => $page->meta_description,
            'url' => $page->url,
            'telephone' => $settings['phone'] ?? '',
            'email' => $settings['email'] ?? '',
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => $page->city->name,
                'addressCountry' => 'IN',
                'postalCode' => $settings['postal_code'] ?? '',
            ],
            'image' => [
                '@type' => 'ImageObject',
                'url' => asset('logo.png'),
                'width' => 600,
                'height' => 600,
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => $page->city->name,
            ],
            'serviceType' => $page->service->title,
        ];
    }

    /**
     * Generate FAQPage schema
     */
    public static function faqSchema(ServiceCitySeo $page): array
    {
        $faqs = $page->faq;

        if (empty($faqs)) {
            return [];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq['question'] ?? '',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'] ?? '',
                    ],
                ];
            }, $faqs),
        ];
    }

    /**
     * Generate Organization schema
     */
    public static function organizationSchema(): array
    {
        $settings = \App\Models\Setting::query()->pluck('value', 'key')->toArray();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => route('home'),
            'logo' => asset('logo.png'),
            'description' => $settings['short_description'] ?? '',
            'telephone' => $settings['phone'] ?? '',
            'email' => $settings['email'] ?? '',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings['address'] ?? '',
                'addressCountry' => 'IN',
            ],
            'sameAs' => [
                $settings['facebook_url'] ?? '',
                $settings['instagram_url'] ?? '',
                $settings['twitter_url'] ?? '',
            ],
        ];
    }

    /**
     * Generate AggregateRating schema
     */
    public static function aggregateRatingSchema(float $rating = 4.8, int $reviews = 250): array
    {
        return [
            '@context' => 'https://schema.org/',
            '@type' => 'AggregateRating',
            'ratingValue' => $rating,
            'reviewCount' => $reviews,
        ];
    }

    /**
     * Encode schema as JSON-LD
     */
    public static function toJsonLd(array $schema): string
    {
        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
