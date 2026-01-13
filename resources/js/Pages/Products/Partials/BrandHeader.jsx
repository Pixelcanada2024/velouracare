import React from 'react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang
export default function BrandHeader({ brand }) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  return (
    <div>

      {/* Banner */}
      <div>

        {/* <div className='relative container'>
          <div className='absolute top-5'>
            <div className="text-sm text-gray-600">
              <span><Link href={route('react.home')}>Home</Link></span> / <span><Link href={route('react.brands')}>Brands</Link></span> / <span className='text-black font-medium'><Link href={route('react.brand_show', { brand: brand.id })}>{brand.name}</Link></span>
            </div>
          </div>
        </div> */}

        <div>
          {/* Web Banner */}
          {brand.web_banner ? (
            <img src={brand.web_banner} alt="Web Banner" className='max-sm:hidden w-full h-full' />
          ) : (
            <div className='max-sm:hidden w-full h-[500px] flex items-center justify-center bg-gray-100 text-gray-600 text-2xl'>
              {brand.name}
            </div>
          )}

          {/* Mobile Banner */}
          {brand.mobile_banner ? (
            <img src={brand.mobile_banner} alt="Mobile Banner" className='sm:hidden w-full h-full' />
          ) : (
            <div className='sm:hidden w-full h-[350px] flex items-center justify-center bg-gray-100 text-gray-600 text-xl'>
              {brand.name}
            </div>
          )}
        </div>

      </div>

      {/* Description */}
      {!!brand?.meta_description && (
      <div className='container my-8 mx-auto py-6 px-6 lg:px-12 bg-[#F8F7F2]'>
        <div className='flex items-center gap-5 py-6 border-b border-[#E5E7EC]'>
          <h1 className='text-2xl font-bold text-gray-800 uppercase'>{brand.name}</h1>
          <span>{tr['updated']} {brand.updated_at}</span>
        </div>

        <div className='space-y-6 my-6'>
          <div className='font-medium text-secondary-500'>{tr['available_in_all_countries']}</div>
          <div className='text-lg'>{tr['description']}</div>
          <div className='bg-[#FFFFFF] p-5 rounded-lg'>
            <p className='text-gray-700'>{brand.meta_description}</p>
          </div>
        </div>
      </div>
      )}
    </div>
  )
}
