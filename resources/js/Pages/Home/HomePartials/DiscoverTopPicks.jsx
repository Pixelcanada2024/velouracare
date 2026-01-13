import React, { useState } from 'react';
import ProductsSwiper from './Components/ProductsSwiper';
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang

export default function DiscoverTopPicks({ featured_products, new_arrivals, top_deals }) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const [activeTab, setActiveTab] = useState('top-sellers');

  return (
    <section className='mt-16 bg-[#F2F4F7] p-4 sm:p-8 '>
      <div className="flex flex-col container">
        <div className="flex items-center gap-2 text-lg font-semibold justify-center" ><div className="bg-[#004AAD] w-3 h-[2px]"></div>{tr['discover_our_top_picks']}</div>

        {/* Tabs Navigation */}
        <div className="flex justify-center my-6">
          <div className="flex gap-5 p-1 "
          >
            {featured_products.length > 0 && <button
              className={`px-4 py-2 font-medium rounded-full sm:text-nowrap cursor-pointer ${activeTab === 'top-sellers'
                ? 'bg-white text-black font-semibold'
                : 'text-[#3B474F] hover:bg-gray-100'
                }`}
              onClick={() => setActiveTab('top-sellers')}
            >
              {tr['top_sellers']}
            </button>}
            {new_arrivals.length > 0 && <button
              className={`px-4 py-2 font-medium rounded-full sm:text-nowrap cursor-pointer ${activeTab === 'new-arrivals'
                ? 'bg-white text-black font-semibold'
                : 'text-[#3B474F] hover:bg-gray-100'
                }`}
              onClick={() => setActiveTab('new-arrivals')}
            >
              {tr['new_arrivals']}
            </button>}
            {top_deals.length > 0 && <button
              className={`px-4 py-2 font-medium rounded-full sm:text-nowrap cursor-pointer ${activeTab === 'best-offers'
                ? 'bg-white text-black font-semibold'
                : 'text-[#3B474F] hover:bg-gray-100'
                }`}
              onClick={() => setActiveTab('best-offers')}
            >
              {tr['best_offers']}
            </button>}
          </div>
        </div>

        {/* Tab Content */}
        <div>
          {activeTab === 'top-sellers' && (
            <div>
              <ProductsSwiper products={featured_products} />
            </div>
          )}
          {activeTab === 'new-arrivals' && (
            <div>
              <ProductsSwiper products={new_arrivals} />

            </div>
          )}
          {activeTab === 'best-offers' && (
            <div>
              <ProductsSwiper products={top_deals} />

            </div>
          )}
        </div>
      </div>
    </section>
  );
}
