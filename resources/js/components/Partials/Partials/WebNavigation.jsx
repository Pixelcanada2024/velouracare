import NavLink from '@/components/ui/NavLink'
import { Link, usePage } from '@inertiajs/react'
import React, { useState } from 'react'

// start lang
import { useTranslation } from '@/contexts/TranslationContext'
// end lang


export default function WebNavigation() {

  // Categories submenu state
  const [isCategoriesMenuOpen, setIsCategoriesMenuOpen] = useState(false);

  const toggleCategoriesMenu = () => {
    setIsCategoriesMenuOpen(!isCategoriesMenuOpen);
  };

  // start lang
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  const { homeCategories } = usePage().props;

  return (
    <div className='max-xl:hidden'>
      <ul className="flex gap-8 items-center uppercase">
        <li>
          <NavLink href={route('react.home')}
            active={route().current("react.home") || route().current("home")}
            className="font-[500]">{tr['home']}</NavLink>
        </li>
        <li className="relative  z-30">
          <button onClick={toggleCategoriesMenu} className="font-[500] flex items-center gap-1 cursor-pointer uppercase">
            {tr['categories']}
            <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" className={` ${isCategoriesMenuOpen ? 'rotate-180' : ''}`}>
              <path d="M1.25 1.5L5 5.25L8.75 1.5" stroke="#222222" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
            </svg>
          </button>
          <div className={`absolute  w-40 bg-white shadow-lg mt-1  ${isCategoriesMenuOpen ? 'block' : 'hidden'}`}>
            {homeCategories.map(cat => (
              <NavLink key={cat.id} active={(usePage().props.currentFullUrl.toLowerCase()) == encodeURI(route('react.products') + '?categories[0]=' + cat.name).toLowerCase()} href={route('react.products') + '?categories[]=' + cat.name} className="block px-4 py-2 hover:bg-gray-100">{cat.name}</NavLink>
            ))}
          </div>
        </li>
        <li>
          <NavLink href={route('react.brands')}
            active={route().current("react.brands")} className="font-[500]">{tr['brands']}</NavLink>
        </li>
        <li>
          <NavLink href={route('react.promotions')} active={route().current("react.promotions")}
            className="font-[500]">{tr['promotions']}</NavLink>
        </li>
        <li>
          <NavLink href={route('react.about-us')}
            active={route().current("react.about-us")} className="font-[500]">{tr['about_us']}</NavLink>
        </li>
        <li>
          <NavLink href={route('blog')}
            active={route().current("blog")} className="font-[500]">{tr['blog']}</NavLink>
        </li>

        <Link href={route('react.contact-us')} className="border border-[#0D0D0D] text-[#0D0D0D] px-4 py-1 rounded-full font-[500] hover:bg-[#0D0D0D] hover:text-white transition-colors duration-200">
          {tr['footer_contact_us']}
        </Link>
      </ul>
    </div>
  )
}
