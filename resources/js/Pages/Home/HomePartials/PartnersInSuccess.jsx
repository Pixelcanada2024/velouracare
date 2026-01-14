import React from 'react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang

export default function PartnersInSuccess({ partnersImageWeb, partnersImageTablet, partnersImageMobile, textContent }) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  return (
    <div className='bg-[#F8F8F8]'>
      <div className='container justify-between flex max-xl:flex-col gap-10 py-12 xl:py-24'>
        <div className='w-full lg:w-1/2'>
          <div>
            <div className="flex items-center gap-2 max-sm:text-xs font-semibold"><div className="bg-[#0D0D0D] w-3 h-[2px]"></div>{tr['who_we_work_with']}</div>
            <h2 style={{ fontFamily: 'Times New Roman' }} className='text-2xl sm:text-4xl font-bold mt-2'>{tr['partners_in_success']}</h2>
            <p className='text-[#222222] mt-6 sm:mt-10 text-base sm:text-lg'>{textContent.home_partners_in_success_desc}</p>
          </div>
        </div>
        <div>
          <>
            {partnersImageWeb && (
              <img
                src={partnersImageWeb}
                alt="Partners In Success"
                className="hidden xl:block w-full h-full  object-cover"
              />
            )}

            {partnersImageTablet && (
              <img
                src={partnersImageTablet}
                alt="Partners In Success"
                className="hidden md:block xl:hidden w-full h-full  object-cover"
              />
            )}

            <img
              src={
                partnersImageMobile ||
                partnersImageTablet ||
                partnersImageWeb ||
                '/public/website-assets/home/partners_with_us/en_web.png'
              }
              alt="Partners In Success"
              className="md:hidden w-full h-full  object-cover"
            />
          </>
        </div>
      </div>
    </div>
  )
}
