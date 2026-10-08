<?php

namespace App\Console\Commands;

use App\Models\ServiceCitySeo;
use App\Models\Service;
use App\Models\City;
use App\Services\SitemapGeneratorService;
use Illuminate\Console\Command;

class GenerateServiceCitySeoPages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:generate-pages
                            {--auto-approve : Automatically approve generated pages}
                            {--service= : Generate for specific service ID}
                            {--city= : Generate for specific city ID}
                            {--stats : Show statistics only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate SEO pages for service-city combinations';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('stats')) {
            return $this->showStatistics();
        }

        $this->info('🚀 Starting SEO page generation...');

        $serviceId = $this->option('service');
        $cityId = $this->option('city');
        $autoApprove = $this->option('auto-approve');

        if ($serviceId && $cityId) {
            return $this->generateSinglePage($serviceId, $cityId, $autoApprove);
        }

        if ($serviceId) {
            return $this->generateForService($serviceId, $autoApprove);
        }

        if ($cityId) {
            return $this->generateForCity($cityId, $autoApprove);
        }

        return $this->generateAllPages($autoApprove);
    }

    /**
     * Generate page for specific service-city combination
     */
    private function generateSinglePage(int $serviceId, int $cityId, bool $autoApprove): int
    {
        $service = Service::findOrFail($serviceId);
        $city = City::findOrFail($cityId);

        $existing = ServiceCitySeo::where('service_id', $serviceId)
            ->where('city_id', $cityId)
            ->first();

        if ($existing) {
            $this->warn("Page already exists for {$service->title} in {$city->name}");
            return 1;
        }

        try {
            ServiceCitySeo::create([
                'service_id' => $serviceId,
                'city_id' => $cityId,
                'is_active' => $autoApprove,
            ]);

            $url = route('services.city', ['serviceSlug' => $service->slug, 'citySlug' => $city->slug]);
            $this->info("✅ Created: {$service->title} in {$city->name}");
            $this->line("   URL: {$url}");
            $this->line("   Status: " . ($autoApprove ? 'Active' : 'Pending'));

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Error: {$e->getMessage()}");
            return 1;
        }
    }

    /**
     * Generate pages for specific service
     */
    private function generateForService(int $serviceId, bool $autoApprove): int
    {
        $service = Service::findOrFail($serviceId);
        $cities = City::where('is_active', true)->get();

        $this->info("Generating pages for service: {$service->title}");
        $created = 0;
        $skipped = 0;

        foreach ($cities as $city) {
            $existing = ServiceCitySeo::where('service_id', $serviceId)
                ->where('city_id', $city->id)
                ->first();

            if ($existing) {
                $this->line("⊘ Skipped: {$city->name} (already exists)");
                $skipped++;
                continue;
            }

            try {
                ServiceCitySeo::create([
                    'service_id' => $serviceId,
                    'city_id' => $city->id,
                    'is_active' => $autoApprove,
                ]);
                $this->line("✅ Created: {$city->name}");
                $created++;
            } catch (\Exception $e) {
                $this->line("❌ Failed: {$city->name} - {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Summary: Created {$created}, Skipped {$skipped}");

        return 0;
    }

    /**
     * Generate pages for specific city
     */
    private function generateForCity(int $cityId, bool $autoApprove): int
    {
        $city = City::findOrFail($cityId);
        $services = Service::where('is_active', true)->get();

        $this->info("Generating pages for city: {$city->name}");
        $created = 0;
        $skipped = 0;

        foreach ($services as $service) {
            $existing = ServiceCitySeo::where('service_id', $service->id)
                ->where('city_id', $cityId)
                ->first();

            if ($existing) {
                $this->line("⊘ Skipped: {$service->title} (already exists)");
                $skipped++;
                continue;
            }

            try {
                ServiceCitySeo::create([
                    'service_id' => $service->id,
                    'city_id' => $cityId,
                    'is_active' => $autoApprove,
                ]);
                $this->line("✅ Created: {$service->title}");
                $created++;
            } catch (\Exception $e) {
                $this->line("❌ Failed: {$service->title} - {$e->getMessage()}");
            }
        }

        $this->newLine();
        $this->info("Summary: Created {$created}, Skipped {$skipped}");

        return 0;
    }

    /**
     * Generate all missing pages
     */
    private function generateAllPages(bool $autoApprove): int
    {
        $bar = $this->output->createProgressBar();
        $result = SitemapGeneratorService::createMissingPages($autoApprove);

        $bar->finish();
        $this->newLine(2);

        $this->info('═══════════════════════════════');
        $this->info("✅ Created: {$result['created']}");
        $this->info("❌ Failed: {$result['failed']}");
        $this->info("📊 Total: {$result['total']}");
        $this->info('═══════════════════════════════');

        return 0;
    }

    /**
     * Show statistics
     */
    private function showStatistics(): int
    {
        $stats = SitemapGeneratorService::getStatistics();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Active Services', $stats['active_services']],
                ['Active Cities', $stats['active_cities']],
                ['Active Combinations', $stats['active_combinations']],
                ['Potential Combinations', $stats['potential_combinations']],
                ['Coverage %', $stats['coverage_percentage'] . '%'],
                ['Missing Combinations', $stats['missing_combinations']],
            ]
        );

        return 0;
    }
}
