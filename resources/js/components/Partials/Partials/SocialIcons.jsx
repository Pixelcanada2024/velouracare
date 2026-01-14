import { Link, usePage } from '@inertiajs/react'
import React from 'react'

export default function SocialIcons() {

  const links = usePage().props.footer_links;

  if (!links.show) return <></>;
  return (
    <div className="flex items-center gap-2 lg:gap-4 2xl:gap-8">

      {/* Facebook */}
      <a href={links.facebook_link} className="w-10 h-10 sm:w-[60px] sm:h-[60px] block">
        <svg className="w-full h-full" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g filter="url(#filter0_d_3063_5109)">
            <rect x="6" y="6" width="60" height="60" rx="30" fill="white" />
            <path d="M38.3307 37.7499H41.2474L42.4141 33.0833H38.3307V30.7499C38.3307 29.5483 38.3307 28.4166 40.6641 28.4166H42.4141V24.4966C42.0337 24.4464 40.5976 24.3333 39.0809 24.3333C35.9134 24.3333 33.6641 26.2664 33.6641 29.8166V33.0833H30.1641V37.7499H33.6641V47.6666H38.3307V37.7499Z" fill="#0D0D0D" />
          </g>
          <defs>
            <filter id="filter0_d_3063_5109" x="0" y="0" width="72" height="72" filterUnits="userSpaceOnUse" colorInterpolationFilters="sRGB">
              <feFlood floodOpacity="0" result="BackgroundImageFix" />
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
              <feMorphology radius="2" operator="dilate" in="SourceAlpha" result="effect1_dropShadow_3063_5109" />
              <feOffset />
              <feGaussianBlur stdDeviation="2" />
              <feComposite in2="hardAlpha" operator="out" />
              <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
              <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_3063_5109" />
              <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_3063_5109" result="shape" />
            </filter>
          </defs>
        </svg>
      </a>

      {/* X */}
      <a href={links.twitter_link} className="w-10 h-10 sm:w-[60px] sm:h-[60px] block">
        <svg className="w-full h-full" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g filter="url(#filter0_d_3063_5106)">
            <rect x="6" y="6" width="60" height="60" rx="30" fill="white" />
            <path d="M42.6318 25.5735L36.8031 32.2363L31.7631 25.5735H24.4609L33.1841 36.9788L24.9171 46.4277H28.4568L34.8373 39.136L40.4139 46.4277H47.5329L38.4399 34.4063L46.1691 25.5735H42.6318ZM41.3904 44.3102L28.5933 27.579H30.6968L43.3504 44.309L41.3904 44.3102Z" fill="#0D0D0D" />
          </g>
          <defs>
            <filter id="filter0_d_3063_5106" x="0" y="0" width="72" height="72" filterUnits="userSpaceOnUse" colorInterpolationFilters="sRGB">
              <feFlood floodOpacity="0" result="BackgroundImageFix" />
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
              <feMorphology radius="2" operator="dilate" in="SourceAlpha" result="effect1_dropShadow_3063_5106" />
              <feOffset />
              <feGaussianBlur stdDeviation="2" />
              <feComposite in2="hardAlpha" operator="out" />
              <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
              <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_3063_5106" />
              <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_3063_5106" result="shape" />
            </filter>
          </defs>
        </svg>
      </a>

      {/* Instagram */}
      <a href={links.instagram_link} className="w-10 h-10 sm:w-[60px] sm:h-[60px] block">
        <svg className="w-full h-full" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g filter="url(#filter0_d_3063_5100)">
            <rect x="6" y="6" width="60" height="60" rx="30" fill="white" />
            <path d="M27.5417 24.1875C26.8842 24.1875 26.2536 24.4487 25.7886 24.9136C25.3237 25.3786 25.0625 26.0092 25.0625 26.6667C25.0625 27.3242 25.3237 27.9548 25.7886 28.4197C26.2536 28.8846 26.8842 29.1458 27.5417 29.1458C28.1992 29.1458 28.8298 28.8846 29.2947 28.4197C29.7596 27.9548 30.0208 27.3242 30.0208 26.6667C30.0208 26.0092 29.7596 25.3786 29.2947 24.9136C28.8298 24.4487 28.1992 24.1875 27.5417 24.1875ZM25.2083 31.1875C25.1697 31.1875 25.1326 31.2029 25.1052 31.2302C25.0779 31.2576 25.0625 31.2947 25.0625 31.3333V46.5C25.0625 46.5805 25.1278 46.6458 25.2083 46.6458H29.875C29.9137 46.6458 29.9508 46.6305 29.9781 46.6031C30.0055 46.5758 30.0208 46.5387 30.0208 46.5V31.3333C30.0208 31.2947 30.0055 31.2576 29.9781 31.2302C29.9508 31.2029 29.9137 31.1875 29.875 31.1875H25.2083ZM32.7917 31.1875C32.753 31.1875 32.7159 31.2029 32.6885 31.2302C32.6612 31.2576 32.6458 31.2947 32.6458 31.3333V46.5C32.6458 46.5805 32.7112 46.6458 32.7917 46.6458H37.4583C37.497 46.6458 37.5341 46.6305 37.5615 46.6031C37.5888 46.5758 37.6042 46.5387 37.6042 46.5V38.3333C37.6042 37.7532 37.8346 37.1968 38.2449 36.7865C38.6551 36.3763 39.2115 36.1458 39.7917 36.1458C40.3718 36.1458 40.9282 36.3763 41.3385 36.7865C41.7487 37.1968 41.9792 37.7532 41.9792 38.3333V46.5C41.9792 46.5805 42.0445 46.6458 42.125 46.6458H46.7917C46.8303 46.6458 46.8674 46.6305 46.8948 46.6031C46.9221 46.5758 46.9375 46.5387 46.9375 46.5V36.4433C46.9375 33.6118 44.4758 31.3975 41.6583 31.653C40.7867 31.733 39.9329 31.9487 39.1278 32.2923L37.6042 32.9457V31.3333C37.6042 31.2947 37.5888 31.2576 37.5615 31.2302C37.5341 31.2029 37.497 31.1875 37.4583 31.1875H32.7917Z" fill="#0D0D0D" />
          </g>
          <defs>
            <filter id="filter0_d_3063_5100" x="0" y="0" width="72" height="72" filterUnits="userSpaceOnUse" colorInterpolationFilters="sRGB">
              <feFlood floodOpacity="0" result="BackgroundImageFix" />
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
              <feMorphology radius="2" operator="dilate" in="SourceAlpha" result="effect1_dropShadow_3063_5100" />
              <feOffset />
              <feGaussianBlur stdDeviation="2" />
              <feComposite in2="hardAlpha" operator="out" />
              <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
              <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_3063_5100" />
              <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_3063_5100" result="shape" />
            </filter>
          </defs>
        </svg>
      </a>

      {/* LinkedIn */}
      <a href={links.linkedin_link} className="w-10 h-10 sm:w-[60px] sm:h-[60px] block">
        <svg className="w-full h-full" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g filter="url(#filter0_d_3063_5103)">
            <rect x="6" y="6" width="60" height="60" rx="30" fill="white" />
            <path d="M27.5417 24.1875C26.8842 24.1875 26.2536 24.4487 25.7886 24.9136C25.3237 25.3786 25.0625 26.0092 25.0625 26.6667C25.0625 27.3242 25.3237 27.9548 25.7886 28.4197C26.2536 28.8846 26.8842 29.1458 27.5417 29.1458C28.1992 29.1458 28.8298 28.8846 29.2947 28.4197C29.7596 27.9548 30.0208 27.3242 30.0208 26.6667C30.0208 26.0092 29.7596 25.3786 29.2947 24.9136C28.8298 24.4487 28.1992 24.1875 27.5417 24.1875ZM25.2083 31.1875C25.1697 31.1875 25.1326 31.2029 25.1052 31.2302C25.0779 31.2576 25.0625 31.2947 25.0625 31.3333V46.5C25.0625 46.5805 25.1278 46.6458 25.2083 46.6458H29.875C29.9137 46.6458 29.9508 46.6305 29.9781 46.6031C30.0055 46.5758 30.0208 46.5387 30.0208 46.5V31.3333C30.0208 31.2947 30.0055 31.2576 29.9781 31.2302C29.9508 31.2029 29.9137 31.1875 29.875 31.1875H25.2083ZM32.7917 31.1875C32.753 31.1875 32.7159 31.2029 32.6885 31.2302C32.6612 31.2576 32.6458 31.2947 32.6458 31.3333V46.5C32.6458 46.5805 32.7112 46.6458 32.7917 46.6458H37.4583C37.497 46.6458 37.5341 46.6305 37.5615 46.6031C37.5888 46.5758 37.6042 46.5387 37.6042 46.5V38.3333C37.6042 37.7532 37.8346 37.1968 38.2449 36.7865C38.6551 36.3763 39.2115 36.1458 39.7917 36.1458C40.3718 36.1458 40.9282 36.3763 41.3385 36.7865C41.7487 37.1968 41.9792 37.7532 41.9792 38.3333V46.5C41.9792 46.5805 42.0445 46.6458 42.125 46.6458H46.7917C46.8303 46.6458 46.8674 46.6305 46.8948 46.6031C46.9221 46.5758 46.9375 46.5387 46.9375 46.5V36.4433C46.9375 33.6118 44.4758 31.3975 41.6583 31.653C40.7867 31.733 39.9329 31.9487 39.1278 32.2923L37.6042 32.9457V31.3333C37.6042 31.2947 37.5888 31.2576 37.5615 31.2302C37.5341 31.2029 37.497 31.1875 37.4583 31.1875H32.7917Z" fill="#0D0D0D" />
          </g>
          <defs>
            <filter id="filter0_d_3063_5103" x="0" y="0" width="72" height="72" filterUnits="userSpaceOnUse" colorInterpolationFilters="sRGB">
              <feFlood floodOpacity="0" result="BackgroundImageFix" />
              <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
              <feMorphology radius="2" operator="dilate" in="SourceAlpha" result="effect1_dropShadow_3063_5103" />
              <feOffset />
              <feGaussianBlur stdDeviation="2" />
              <feComposite in2="hardAlpha" operator="out" />
              <feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.1 0" />
              <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_3063_5103" />
              <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_3063_5103" result="shape" />
            </filter>
          </defs>
        </svg>
      </a>

    </div>
  )
}
