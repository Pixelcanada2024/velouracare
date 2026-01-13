import React from 'react'
import Layout from '@/components/layout/Layout'
import OurPartners from './HomePartials/OurPartners'
import Categories from './HomePartials/Categories'
import WhyChooseUs from './HomePartials/WhyChooseUs'
import WhoWeAre from './HomePartials/WhoWeAre'
import DiscoverTopPicks from './HomePartials/DiscoverTopPicks'
import OurLatestPosts from './HomePartials/OurLatestPosts'
import FAQ from './HomePartials/FAQ'
import PartnersInSuccess from './HomePartials/PartnersInSuccess'
import Hero from './HomePartials/Hero'
import { Link, usePage } from '@inertiajs/react'


// start lang
import { useTranslation } from '@/contexts/TranslationContext'
import PartnerImages from './../../components/Shared/PartnerImages';
// end lang

export default function Home({
  home_hero_main,
  home_hero_one,
  home_hero_two,
  home_hero_main_mobile,
  home_hero_one_mobile,
  home_hero_two_mobile,
  who_we_are_one,
  who_we_are_two,
  why_choose_us_one,
  why_choose_us_two,
  become_customer_desktop,
  become_customer_mobile,
  partner_sky_business_desktop,
  partner_sky_business_mobile,
  categories,
  top_brands,
  latest_blogs,
  text_content,
  imageLinks,
  featured_products,
  new_arrivals,
  top_deals,
  faqs,
  partners_in_success_web,
  partners_in_success_tablet,
  partners_in_success_mobile
}) {

  const [{ lang, currency, tr }, _setTranslation] = useTranslation();


  return (
    <Layout ShowFooterSwiper={false} pageTitle={tr['home']}>
      <div className=' mx-auto space-y-15 mt-10 mb-20'>

        {/* Hero Section */}
        <Hero home_hero_main={home_hero_main_mobile} home_hero_one={home_hero_one_mobile} home_hero_two={home_hero_two_mobile} containerClassName="sm:hidden" imageLinks={imageLinks} />
        <Hero home_hero_main={home_hero_main} home_hero_one={home_hero_one} home_hero_two={home_hero_two} containerClassName="max-sm:hidden" imageLinks={imageLinks} />

        {/* Category Section */}
        <Categories
          categories={categories}
        />

        {/* Who We Are */}
        <WhoWeAre
          whoWeAreImages={{
            one: who_we_are_one,
            two: who_we_are_two
          }}
          textContent={text_content}
        />

        {/* Discover Our Top Picks */}
        {!(!featured_products.length && !new_arrivals.length && !top_deals.length) && <DiscoverTopPicks featured_products={featured_products} new_arrivals={new_arrivals} top_deals={top_deals} />}

        {/* Become Customer */}
        <section className='container'>
          <Link href={route('react.register')}>
            <img src={become_customer_desktop} alt="Become Customer" className='w-full h-full rounded-x max-xl:hidden' />
            <img src={become_customer_mobile} alt="Become Customer" className='w-full h-full rounded-xl xl:hidden' />
          </Link>
        </section>

        {/* Our Partners */}
        {top_brands.length > 0 && <OurPartners top_brands={top_brands} />}

        {/* Why Choose Us */}
        <WhyChooseUs
          whyChooseUsImages={{
            one: why_choose_us_one,
            two: why_choose_us_two
          }}
          textContent={text_content}
        />

        {/* Partners In Success */}
        <PartnersInSuccess
          partnersImageWeb={partners_in_success_web}
          partnersImageTablet={partners_in_success_tablet}
          partnersImageMobile={partners_in_success_mobile}
          textContent={text_content}
        />

        {/* Partner with Sky Business */}
        <section className='container'>
          <Link href={route('react.contact-us')}>
            <PartnerImages bigImg={partner_sky_business_desktop} smallImg={partner_sky_business_mobile} />
          </Link>
        </section>

        {/* Our Latest Posts */}
        { latest_blogs.length > 0 && <OurLatestPosts blogs={latest_blogs} />}

        {/* FAQ Section */}
        <FAQ faqs={faqs} />

      </div>
    </Layout>
  )
}
