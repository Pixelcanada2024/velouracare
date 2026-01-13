import React from 'react'
import Layout from '@/components/Layout/Layout'
import HtmlStringRenderer from '@/components/ui/HtmlStringRenderer';
import { useTranslation } from '@/contexts/TranslationContext';

export default function BlogShow({ blog }) {
  const [{lang, currency, tr}, _setTranslation] = useTranslation();

  return (
    <Layout
      pageTitle={blog.title}
      breadcrumbs={[
        { label: tr["home"], url: route('react.home') },
        { label: tr["blog"], url: route('blog') },
        { label: blog.title, url: "#" },
      ]}
    >
      <div className="container mx-auto pt-8">
        {/* Image */}
        <div className='w-full'>
          <div className="overflow-hidden relative rounded-2xl">
            {blog.banner ? (

              <div className="relative w-full max-lg:object-contain lg:h-165 lg:object-cover">
                <img
                  src={blog.banner}
                  className="w-full h-full object-cover"
                  alt={blog.title}
                />
                {/* White gradient overlay at the bottom */}
                <div className="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-white to-transparent opacity-80"></div>
              </div>

            ) : (
              <div className="w-full h-64 lg:h-165 bg-blue-100 flex items-center justify-center">

              </div>
            )
            }
            <div className='absolute top-0 left-0 bg-[#3B474F59] px-5 py-2 rounded-br-xl'>
              <div className='flex flex-col items-center text-white font-semibold'>
                {blog.created_At}
              </div>
            </div>
          </div>

        </div>

        {/* Content */}
        <HtmlStringRenderer className="container py-8" htmlString={blog.description} />
      </div>

    </Layout >
  )
}
