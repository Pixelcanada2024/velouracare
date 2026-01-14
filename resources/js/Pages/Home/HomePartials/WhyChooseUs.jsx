import React from 'react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang

export default function WhyChooseUs({ whyChooseUsImages, textContent }) {
  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang

  return (
    <div className='container xl:space-y-15 mx-auto'>
      <div>
        <div className="flex items-center gap-2 max-sm:text-xs font-semibold"><div className="bg-[#0D0D0D] w-3 h-[2px]"></div>{tr['our_values']}</div>
        <h2 style={{ fontFamily: 'Times New Roman' }} className='text-2xl sm:text-4xl font-bold mt-2'>{tr['why_choose_us']}</h2>
      </div>

      <div className='flex max-xl:flex-col gap-4 mt-12'>
        <div className='relative'>
          <img src={whyChooseUsImages?.one || '/public/website-assets/home/WhyChooseUsOne.png'} alt="Why Choose Us One" className='w-full h-full rounded-xl' />
          <div className='absolute max-xl:-top-3 rtl:-right-3 ltr:-left-3 w-[100px] h-[100px] rtl:max-xl:rounded-tr-3xl ltr:max-xl:rounded-tl-3xl xl:-bottom-3 xl:-left-3 xl:w-[300px] xl:h-[300px] rtl:xl:rounded-br-3xl ltr:xl:rounded-bl-3xl  -z-50 bg-[#F0FAFF]'></div>
        </div>
        <div className='flex flex-col top-20 gap-4 xl:gap-8 xl:relative rtl:xl:left-10 ltr:xl:-left-10 -z-5 '>
          <div className='bg-[#F0FAFF] p-4 sm:p-8 rounded-2xl'>
            <h3 style={{ fontFamily: 'Times New Roman' }} className='font-bold text-[20px] sm:text-2xl mb-2 '>{textContent?.premium_product_title || 'Premium Product Selection'}</h3>
            <p className=' text-lg text-[#222222]'>{textContent?.premium_product_description || 'We offer a curated range of top-quality brands and products to meet your business needs.'}</p>
          </div>
          <div className='bg-[#FFF8F8] p-8 rounded-2xl'>
            <h3 style={{ fontFamily: 'Times New Roman' }} className='font-bold text-[20px] sm:text-2xl mb-2'>{textContent?.competitive_pricing_title || 'Competitive Pricing'}</h3>
            <p className=' text-lg text-[#222222]'>{textContent?.competitive_pricing_description || 'Our pricing ensures you get the best value without compromising on quality.'}</p>
          </div>
        </div>
      </div>
      <div className='flex max-xl:flex-col-reverse gap-4 mt-4'>
        <div className='flex flex-col top-20  gap-4 xl:gap-8 xl:relative rtl:xl:right-10 ltr:xl:-right-10 -z-5'>
          <div className='bg-[#F0FAFF] p-4 sm:p-8 rounded-2xl'>
            <h3 style={{ fontFamily: 'Times New Roman' }} className='font-bold text-[20px] sm:text-2xl mb-2'>{textContent?.fast_delivery_title || 'Fast & Reliable Delivery'}</h3>
            <p className=' text-lg text-[#222222]'>{textContent?.fast_delivery_description || 'We leverage an efficient logistics network to ensure prompt and accurate fulfillment.'}</p>
          </div>
          <div className='bg-[#FFF8F8] p-8 rounded-2xl'>
            <h3 style={{ fontFamily: 'Times New Roman' }} className='font-bold text-[20px] sm:text-2xl mb-2'>{textContent?.customer_support_title || 'Dedicated Customer Support'}</h3>
            <p className=' text-lg text-[#222222]'>{textContent?.customer_support_description || 'Our expert team is here to provide personalized assistance every step of the way.'}</p>
          </div>
        </div>
        <div className='relative'>
          <img src={whyChooseUsImages?.two || '/public/website-assets/home/WhyChooseUsTwo.png'} alt="Why Choose Us Two" className='w-full h-full rounded-xl' />
          <div className='max-xl:hidden absolute -bottom-3 rtl:-left-3 ltr:-right-3 w-[300px] h-[300px] -z-50 bg-[#F0FAFF] rtl:rounded-bl-3xl ltr:rounded-br-3xl'></div>
        </div>
      </div>
    </div>
  )
}
