import React from 'react'
import BlogCard from '@/Pages/Blog/Partials/BlogCard'
import { Link } from '@inertiajs/react'
// start lang
import { useTranslation } from '@/contexts/TranslationContext'
// end lang

export default function OurLatestPosts({ blogs = [] }) {
  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang
  return (
    <div className='container'>
      <div className='mb-16'>
        <div className="flex items-center gap-2 max-sm:text-xs font-semibold">
          <div className="bg-[#004AAD] w-3 h-[2px]"></div>{tr['news_feeds']}
        </div>
        <h2 style={{ fontFamily: 'Times New Roman' }} className='text-2xl sm:text-4xl font-bold mt-2'>
          {tr['our_latest_posts']}
        </h2>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-x-8 gap-y-16">
        {blogs.slice(0, 6).map((blog, index) => (
          <div
            key={blog.id}
            className={index > 2 ? 'hidden xl:block' : ''}
          >
            <BlogCard blog={blog} />
          </div>
        ))}
      </div>

      <div className="justify-center flex mt-12">
        <Link href={route('blog')} className="font-semibold underline">
          <div className='flex items-center gap-2'>
            {tr['visit_blog_to_view_more']}
            <svg className='rtl:rotate-180' width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M3 10.0112L17.8242 10.0003M12.1775 15.8337L18 10.0003L12.1767 4.16699" stroke="black" strokeWidth="1.25" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </div>
        </Link>
      </div>
    </div>

  )
}
