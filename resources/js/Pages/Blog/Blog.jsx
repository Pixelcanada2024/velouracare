import React from 'react'
import BlogCard from './Partials/BlogCard'
import Layout from '@/components/Layout/Layout'
import { usePage } from '@inertiajs/react'
import Pagination from '@/components/ui/Pagination'
// start lang
import { useTranslation } from '@/contexts/TranslationContext'
// end lang
export default function Blog({ blogs }) {

  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();

  // end lang
  return (
    <Layout pageTitle={tr["blog"]}
      breadcrumbs={[
        { label: tr["home"], url: route('react.home') },
        { label: tr["blog"], url: "#" },
      ]}
    >
      <div className="container mx-auto py-8">
        <h1 className="text-4xl font-bold mb-6 text-center">{tr["explore_our_blog"]}</h1>
        {blogs && blogs.data && blogs.data.length > 0 ? (
          <>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              {blogs.data.map(blog => (
                <div key={blog.id}>
                  <BlogCard blog={blog} />
                </div>
              ))}
            </div>

            <Pagination totalPages={blogs.last_page} links={blogs.links} />
          </>
        ) : (
          <div className="text-center py-12">
            <h3 className="text-xl font-medium text-gray-600">{tr['no_blog_posts_found']}</h3>
            <p className="mt-2 text-gray-500">{tr['check_back_later']}</p>
          </div>
        )}

      </div>
    </Layout>
  )
}
