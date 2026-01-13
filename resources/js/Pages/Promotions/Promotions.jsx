import Layout from '@/components/Layout/Layout'
import { Link, usePage } from '@inertiajs/react'
import React from 'react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext'
// end lang
export default function Promotions({ promotions }) {
  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang

  return (
    <Layout pageTitle="Promotions"
      breadcrumbs={[
        { label: tr['home'], url: route('react.home') },
        { label: tr['promotions'], url: '#' },
      ]}>
      <div className='container mx-auto'>
        <div className='text-center'>
          <h1 className="text-3xl font-bold">{tr['promotions']}</h1>
          <div className='lg:text-lg mt-4'>
            <p>
              {tr['promotions_line_1']}
              <br />
              {tr['promotions_line_2']}
              <br />
              {tr['promotions_line_3']}
            </p>
          </div>
        </div>

        <div className='space-y-8 my-8'>
          {promotions.length > 0 ? promotions.map(promotion => (
            <div key={promotion.id} >
              <Link href={route('react.products') + `?promotion=${1}&brands[0]=${promotion.brand_name}`}>
                <img src={promotion.tablet_banner} alt="Become Customer" className='object-contain w-full h-full rounded-xl max-md:hidden' />
                <img src={promotion.mobile_banner} alt="Become Customer" className='object-contain w-full h-full rounded-xl md:hidden' />
              </Link>
            </div>
          )) : (
            <div className="text-center text-gray-500 text-2xl container bg-gray-100 rounded-xl py-20 ">
              <p>
                {tr['no_promotions_found']}
              </p>
            </div>
          )}
        </div>
      </div>
    </Layout>
  )
}
