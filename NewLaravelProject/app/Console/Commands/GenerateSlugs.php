<?php

namespace App\Console\Commands;

use App\Models\Festivity;
use App\Models\Locality;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:generate-slugs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera slugs para localidades y festividades existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Generando slugs para localidades...');
        
        $localities = Locality::whereNull('slug')->orWhere('slug', '')->get();
        $count = 0;
        
        foreach ($localities as $locality) {
            $slug = Str::slug($locality->name);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Locality::where('slug', $slug)->where('id', '!=', $locality->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $locality->slug = $slug;
            $locality->save();
            $count++;
        }
        
        $this->info("✓ {$count} localidades procesadas.");
        
        $this->info('Generando slugs para festividades...');
        
        $festivities = Festivity::whereNull('slug')->orWhere('slug', '')->get();
        $count = 0;
        
        foreach ($festivities as $festivity) {
            $slug = Str::slug($festivity->name);
            $originalSlug = $slug;
            $counter = 1;
            
            while (Festivity::where('slug', $slug)->where('id', '!=', $festivity->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $festivity->slug = $slug;
            $festivity->save();
            $count++;
        }
        
        $this->info("✓ {$count} festividades procesadas.");
        $this->info('¡Slugs generados exitosamente!');
        
        return Command::SUCCESS;
    }
}
