import React from 'react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang

export default function WhoWeAre({ whoWeAreImages, textContent }) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  return (
    <section className='container'>
      {/* Grid */}
      <div className="grid grid-cols-1 xl:grid-cols-4 gap-y-8 xl:gap-8">
        {/* Left */}
        <div className="xl:col-span-3 space-y-8">

          {/* First row */}
          <div className="space-y-4 bg-[#F2F4F7] p-4 sm:p-8 rounded-2xl">
            <div>
              <div className="flex items-center gap-2 font-semibold "><div className="bg-[#004AAD] w-3 h-[2px]"></div>{tr['who_we_are']}</div>
              <h2 className="text-2xl sm:text-4xl font-bold ">{textContent?.who_we_are_title || 'We Are Distributors'}</h2>
            </div>
            <p className='text-[#222222]'>
              {textContent?.who_we_are_description || 'At SKY BUSINESS, we are dedicated to empowering health and beauty businesses by providing them with premium wholesale solutions that drive growth and expand their market presence. With a focus on toiletries, skincare, hair care, healthcare, and makeup, we proudly serve businesses across all regions with reliability, efficiency, and excellence.'}
            </p>

            {/* Stats grid - Hardcoded */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4 mt-8 text-[#222222]">
              <div className="text-center bg-white p-8 sm:p-4 pt-6 rounded-lg">
                <p className="text-3xl font-bold ">20 <sup>+</sup></p>
                <p>{tr['years_of_experience']}</p>
              </div>
              <div className="text-center bg-white p-4 pt-6 rounded-lg">
                <p className="text-3xl font-bold ">6 <sup>+</sup></p>
                <p >{tr['brands']}</p>
              </div>
              <div className="text-center bg-white p-4 pt-6 rounded-lg">
                <p className="text-3xl font-bold ">50 <sup>+</sup></p>
                <p >{tr['products']}</p>
              </div>
              <div className="text-center bg-white p-4 pt-6 rounded-lg">
                <p className="text-3xl font-bold ">350 <sup>+</sup></p>
                <p >{tr['clients']}</p>
              </div>
            </div>
          </div>

          {/* Second row */}
          <div className=" rounded-2xl bg-[#FFF8F8] flex max-sm:flex-col-reverse">
            <div className='space-y-4 sm:w-1/2 p-4 sm:p-8 flex flex-col justify-center'>
              <h3 className="text-2xl font-bold">{textContent?.we_help_partners_title || 'We Help Partners'}</h3>
              <p className='text-[#222222]'>{textContent?.we_help_partners_description || 'Our success is built on a deep understanding of industry trends, customer needs, and the unique demands of the fast-moving consumer goods sector. By leveraging our extensive market expertise and strong network, we help our partners thrive in a competitive landscape.'}</p>
            </div>
            <div className='sm:w-1/2'>
              <img src={whoWeAreImages?.one || '/public/website-assets/home/WhoWeAreOne.png'} className='w-full h-full max-xl:object-cover rounded-xl' />
            </div>
          </div>

        </div>

        {/* Right */}
        <div className="xl:col-span-1 bg-[#F0EEF9] rounded-lg  ">
          <div className='flex flex-col sm:flex-row xl:flex-col'>
            <div className='sm:w-1/2 overflow-hidden h-[300px] xl:h-auto xl:w-full'>
              <img
                src={whoWeAreImages?.two || '/public/website-assets/home/WhoWeAreTwo.png'}
                className='w-full h-full object-cover object-center rounded-xl'
              />
            </div>

            <div className='p-4 sm:p-8 space-y-4 sm:w-1/2 xl:w-full flex flex-col justify-center'>
              <h3 className="text-2xl font-bold mb-4">{textContent?.we_deliver_value_title || 'We Deliver Value'}</h3>
              <p className='text-[#222222]'>
                {textContent?.we_deliver_value_description || 'Through strategic partnerships, innovative approaches, and a commitment to quality, SKY BUSINESS ensures the highest levels of satisfaction for our clients.'}
              </p>
            </div>
          </div>
        </div>

      </div>
    </section>
  )
}
