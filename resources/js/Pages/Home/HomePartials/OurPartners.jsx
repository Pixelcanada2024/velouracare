import { Link } from '@inertiajs/react';
import React from 'react';
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang
const OurPartners = ({ top_brands }) => {

  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang

  // Use dynamic data from backend, fallback to sample data if none provided
  const brands = top_brands ;

  return (
    <section className="p-4 sm:p-8 xl:p-16 bg-[#F2F4F7] rounded-2xl container">

      <div >
        <div className="flex justify-center items-center gap-2 max-sm:text-xs font-semibold"><div className="bg-[#004AAD] w-3 h-[2px]"></div>{tr['experience_reliable_quality']}</div>
        <h2 style={{ fontFamily: 'Times New Roman' }} className='text-2xl text-center sm:text-4xl font-bold mt-2'>{tr['our_trusted_brands']}</h2>
      </div>

      <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12">
        {brands.slice(0, 9).map((brand, index) => {
          // Determine visibility
          let visibility = ''
          if (index === 8) {
            visibility = 'sm:hidden'
          }

          return (
            <Link
              href={route('react.products') + `?brands[]=${brand.name}`}
              key={brand.id}
              className={`p-4 xl:p-8 h-32 xl:h-48 overflow-hidden rounded-lg flex items-center justify-center bg-white ${visibility}`}
            >
              <img
                src={brand.logo}
                alt={brand.name}
                className="sm:w-3/4 object-contain"
              />
            </Link>
          )
        })}
      </div>


      <div className="justify-center flex mt-12">
        <Link href={route('react.brands')} className="font-semibold underline">
          <div className='flex items-center gap-2'>
            {tr['view_all_brands']}
            <svg className="rtl:rotate-180"width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M3 10.0112L17.8242 10.0003M12.1775 15.8337L18 10.0003L12.1767 4.16699" stroke="black" strokeWidth="1.25" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </div>
        </Link>
      </div>

    </section>
  );
};

export default OurPartners;
