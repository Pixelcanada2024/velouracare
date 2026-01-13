import { Link } from '@inertiajs/react'
import React from 'react'

export default function BlogCard({ blog }) {

  return (
    <div className="flex rounded-2xl flex-col overflow-hidden ">

      <Link href={route('blog.details', blog.id)} className="block">
        <div className="overflow-hidden relative h-90 bg-gray-100 flex items-center justify-center">

          {/* {blog.banner && blog.banner_upload ? ( */}
          {blog.banner ? (
            <div className="relative w-full h-full">
              <img
                src={blog.banner}
                className="w-full h-full object-cover"
                alt={blog.title}
              />
              {/* White gradient overlay at the bottom */}
              <div className="absolute bottom-0 left-0 right-0 h-1/3 bg-gradient-to-t from-white to-transparent opacity-80"></div>
            </div>

          ) : (
            <div className="w-full h-full bg-blue-100 flex items-center justify-center">
            </div>
          )}
          <div className='absolute top-0 left-0 bg-[#3B474F59] px-5 py-2 rounded-br-xl'>
            <div className='flex flex-col items-center text-white font-semibold'>
              {blog.created_At}
            </div>
          </div>
        </div>

        <div className='px-3 py-6'>
          <h3 className="text-balance text-xl lg:text-xl font-bold text-primary-500" >
            {blog.title}
          </h3>
        </div>
      </Link>
    </div>

  )
}
