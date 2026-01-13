import { Link } from '@inertiajs/react';
import React from 'react'

export default function CategoryCard({ category }) {
  return (
    <div className="relative rounded-lg  shadow-lg hover:shadow-xl transition-shadow duration-300 overflow-hidden aspect-square">
      {/* Use img tag instead of background image */}
      <Link  href={route('react.products') + '?categories[]=' + category.text}>
        <img
          src={category['image']}
          alt={category['text']}
          className="absolute inset-0 w-full h-full object-cover rounded-2xl"
        />

        {/* Dark overlay */}
        <div className="absolute inset-0 bg-black opacity-30 rounded-2xl"></div>

        {/* Category title */}

        <div className="relative h-full flex items-center justify-center">
          <h3 className="text-white sm:text-2xl text-center font-bold uppercase tracking-wider">
            {category['text']}
          </h3>
        </div>
      </Link>
    </div>
  );
}
