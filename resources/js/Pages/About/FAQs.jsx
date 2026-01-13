import React, { useState } from 'react';
import Layout from "@/components/Layout/Layout";
import { Link } from '@inertiajs/react';
import Accordion from "@/components/ui/Accordion";
import AccordionGroup from "@/components/ui/AccordionGroup";
import { useTranslation } from '@/contexts/TranslationContext';

export default function FAQs({ categories }) {
  const [{lang, currency, tr}, _setTranslation] = useTranslation();

  const [selectedCategory, setSelectedCategory] = useState(categories[0]?.id || null);

  const handleCategoryChange = (categoryId) => {
    setSelectedCategory(categoryId);
  };

  const selectedCategoryData = categories.find(cat => cat.id === selectedCategory);

  return (
    <Layout pageTitle="FAQs">

      {/* Header */}
      <div className='bg-[#F2F4F7]'>
        <div className='container mx-auto py-3 sm:py-6 flex flex-col gap-10'>
          <div className="text-sm text-gray-600 mb-2">
            <span><Link href={route('react.home')}>{tr['home']}</Link></span> / <span className="text-black"><Link href={route('react.faqs')}>{tr['faqs']}</Link></span>
          </div>
          <h1 className="text-[28px] sm:text-4xl font-semibold" style={{ fontFamily: 'Times New Roman' }}>{tr['faqs']}</h1>
        </div>
      </div>

      <div className="container mx-auto my-20">
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8 ">
          {/* Sidebar */}
          <div className="lg:col-span-1">
            <div className="border border-[#E5E7EB]  sticky top-20 rounded-lg">
              <div >
                {categories.map((category) => (
                  <button
                    key={category.id}
                    onClick={() => handleCategoryChange(category.id)}
                    className={`w-full border border-[#E5E7EB] text-left px-4 py-4 transition-colors duration-200 ${selectedCategory === category.id
                      ? 'bg-[#F2F4F7] text-secondary-500'
                      : ' hover:bg-gray-200'
                      }`}
                  >
                    <div className="flex justify-between items-center">
                      <span className="font-medium">{category.name}</span>
                    </div>
                  </button>
                ))}
              </div>
            </div>
          </div>

          {/* Content */}
          <div className="lg:col-span-3">
            {selectedCategoryData ? (
              <div>
                {selectedCategoryData.faqs.length > 0 ? (
                  <div className="space-y-4">
                    <AccordionGroup>
                      {selectedCategoryData.faqs.map((faq) => (
                          <Accordion className='shadow-md rounded-lg' key={faq.id} title={faq.question}>
                            <div
                              className="text-gray-600 leading-relaxed prose prose-sm max-w-none"
                              dangerouslySetInnerHTML={{ __html: faq.answer }}
                            />
                          </Accordion>
                      ))}
                    </AccordionGroup>
                  </div>
                ) : (
                  <div className="text-center py-12">
                    <div className="text-gray-400 text-lg mb-2">
                      {tr['faq_no_available']}
                    </div>
                  </div>
                )}
              </div>
            ) : (
              <div>
                <div className="text-center py-12">
                  <div className="text-gray-400 text-lg mb-2">
                    <svg className="mx-auto h-12 w-12 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    {tr['faq_no_categories_available']}
                  </div>
                </div>
              </div>
            )}
          </div>
        </div>
      </div>
    </Layout>
  );
}
