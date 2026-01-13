import HeaderLogo from '@/components/Partials/HeaderLogo'
import { useTranslation } from '@/contexts/TranslationContext';
import { usePage } from '@inertiajs/react';
import React from 'react'
import header_logo from '/public/website-assets/logo/header_logo.png';

export default function PrintHeader({ info }) {
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  const meta = usePage().props.meta;
  const logo = meta.system_logo_white;

  return (
    <div className='border-b border-[#E5E7EB] py-4 md:py-6 px-4'>
      <div className="max-w-4xl mx-auto ">

        <div className='flex items-center justify-between'>
          <a href={info.url} className="w-[169px] sm:w-[216px]">
            <img src={logo || header_logo} className='w-full h-full' alt="Veloura Care" />
          </a>

          <div className="text-[22px] sm:text-[30px] font-bold uppercase">
            {tr["invoice"]}
          </div>
        </div>

      </div>
    </div>
  )
}

