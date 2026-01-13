import Layout from '@/components/Layout/Layout'
import HtmlStringRenderer from '@/components/ui/HtmlStringRenderer'
import { Link, usePage } from '@inertiajs/react'
import React from 'react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext'
// end lang
export default function Policy({ pageTitle, pageContent, updated_at }) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  return (
    <Layout
      pageTitle={tr[pageTitle]}
    >
      <div className='bg-[#F2F4F7]'>
        <div className='container mx-auto py-3 sm:py-6 flex flex-col gap-10'>
          <div className="text-sm text-gray-600 mb-2">
            <span><Link href={route('react.home')}>{tr['home']}</Link></span> / <span className="text-black"><Link href="#">{tr[pageTitle]}</Link></span>
          </div>
          <h1 className="text-[28px] sm:text-4xl font-semibold" style={{ fontFamily: 'Times New Roman' }}>{tr[pageTitle]}</h1>
        </div>
      </div>
      <div className="container py-10 ">
        <div>{tr['last_update']}: {updated_at}</div>
        <HtmlStringRenderer htmlString={pageContent} />
      </div>
    </Layout>
  )
}
