import React, { useState } from 'react'
import Layout from '@/components/layout/Layout'
import { Link } from '@inertiajs/react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext'
// end lang
const alphabetTabs = [
  'ALL', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
  'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
]

export default function Brands({ brands }) {
  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang
  const [activeTab, setActiveTab] = useState('ALL')

  const preparedBrands = brands.map(brand => ({
    ...brand,
    letter: brand?.name_trans?.['en']?.charAt(0)?.toUpperCase() ?? brand?.name?.charAt(0)?.toUpperCase()
  })).sort((a, b) => {
    const aName = a.name_trans?.en || a.name || '';
    const bName = b.name_trans?.en || b.name || '';
    return aName.localeCompare(bName);
  })

  const filteredBrands = activeTab === 'ALL'
    ? preparedBrands
    : preparedBrands.filter(brand => brand.letter === activeTab)

  return (
    <Layout
      breadcrumbs={[
        { label: tr["home"], url: "#" },
        { label: tr["brands"], url: "#" },
      ]}
    >
      <div className="min-h-screen ">

        {/* Alphabet Tabs */}
        <div className="container mx-auto px-4 bg-[#F2F4F7] py-6 shadow-sm border-b border-[#CECECE]">
          <div className="flex flex-wrap justify-center gap-2 md:gap-3">
            {alphabetTabs.map((letter) => (
              <button
                key={letter}
                onClick={() => setActiveTab(letter)}
                className={`px-4 py-2 text-sm font-medium rounded-lg transition-all duration-200 min-w-[40px] ${activeTab === letter
                  ? 'text-[#004AAD] transform scale-105'
                  : 'text-gray-700 hover:bg-gray-200 hover:text-gray-900 hover:shadow-sm'
                  }`}
              >
                {letter === 'ALL' ? tr['all'] : letter}

              </button>
            ))}
          </div>
        </div>

        {/* Brands Grid */}
        <div className="container mx-auto px-4 py-12">
          {filteredBrands.length > 0 ? (
            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
              {filteredBrands.map((brand, index) => (
                <Link
                  key={index}
                  href={route('react.products') + "?brands[0]=" + brand.name}
                >
                  <div
                    className="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 p-6 border border-gray-100 group cursor-pointer"
                  >
                    <div className="flex flex-col items-center justify-center h-full">
                      <div className="w-full h-24 flex items-center justify-center overflow-hidden">
                        {brand.logo ? (
                          <img
                            src={brand.logo}
                            alt={brand.name}
                            className="max-w-full max-h-full object-contain transition-transform duration-300 group-hover:scale-110"
                          />
                        ) : (
                          <div className="w-full h-full bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 text-sm font-medium">
                            {brand.name}
                          </div>
                        )}
                      </div>
                    </div>
                  </div>
                </Link>

              ))}
            </div>
          ) : (
            <div className="text-center py-20">
              <h3 className="text-xl font-medium text-gray-900 mb-2">{tr['no_brands_found']}</h3>
              <p className="text-gray-500">
                {tr['no_brands_found']} "{activeTab}". {tr['try_selecting_different_letter']}
              </p>
            </div>
          )}
        </div>
      </div>
    </Layout>
  )
}
