<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PromotionBanner;
use App\Http\Controllers\PromotionBannerController;
use Carbon\Carbon;

class ResetExpiredPromotionDiscounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'promotion:reset-expired-discounts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset discounts for brands with expired promotion banners.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $expiredBanners = PromotionBanner::where('end_at', '<=', $now)->get();

        $controller = new PromotionBannerController();

        foreach ($expiredBanners as $banner) {
            $controller->applyBrandDiscount($banner->brand_id, 'amount', 0);
            $banner->update(['discount_percent' => 0]);
        }

    }
}
