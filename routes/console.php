<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Product;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('products:backfill-images {--dry-run : Show what would be updated without saving}', function () {
    $dryRun = (bool) $this->option('dry-run');
    $updated = 0;

    Product::query()
        ->whereIn('source_type', ['qc', 'return_replacement'])
        ->where(function ($query) {
            $query->whereNull('image')->orWhere('image', '');
        })
        ->with(['inboundItem.masterProduct', 'masterProduct'])
        ->orderBy('id')
        ->chunkById(100, function ($products) use (&$updated, $dryRun) {
            foreach ($products as $product) {
                $sourceImage = $product->inboundItem?->product_photo
                    ?? $product->inboundItem?->masterProduct?->image
                    ?? $product->masterProduct?->image;

                if (!filled($sourceImage)) {
                    $this->line("SKIP #{$product->id} {$product->name} - no source image found");
                    continue;
                }

                $this->line(($dryRun ? '[DRY RUN] ' : '') . "UPDATE #{$product->id} {$product->name} => {$sourceImage}");

                if (! $dryRun) {
                    $product->update(['image' => $sourceImage]);
                }

                $updated++;
            }
        });

    $this->info(($dryRun ? 'Dry run completed' : 'Backfill completed') . ". {$updated} product image(s) processed.");
})->purpose('Backfill missing product images from inbound or master product images');
