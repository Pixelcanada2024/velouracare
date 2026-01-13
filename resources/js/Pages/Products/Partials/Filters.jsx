import React, { useEffect, useRef } from 'react'
import PrimarySelect from '@/components/ui/PrimarySelect';
import Button from '@/components/ui/Button';
import { route } from 'ziggy-js';
// start lang
import { Link, usePage } from '@inertiajs/react';
import { useTranslation } from '@/contexts/TranslationContext';
// end lang

export default function Filters({ filterOptionsData = {}, queryParams = {}, filterDataState = [], toggleSelect, addSelectedItemsToCart , isEmptyProducts }) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const [showFilters, setShowFilters] = React.useState(true);
  const [filterData, setFilterData] = filterDataState;

  const formRef = useRef(null);

  useEffect(() => {
    const formEl = formRef.current;

    if (!formEl) return;

    const handleChange = () => {
      formEl.submit();
    };

    const formElements = document.querySelectorAll(' select[form="filter-form"] ');

    formElements.forEach((formElement) => formElement.addEventListener("change", handleChange));

    return () => {
      formElements.forEach((formElement) => formElement.removeEventListener("change", handleChange));
    };
  }, []);

  const isAuthenticated = usePage().props?.auth?.user != undefined;

  const PrimarySelectClassNames = "flex gap-2 items-center justify-start [&_select]:lg:!w-[8em] [&_select]:xl:!w-[12em] [&_select]:2xl:!w-[16em] [&_select]:max-lg:!w-full ";
  const selectContainerClasses = " lg:!w-fit ";

  return (
    <div className='container mx-auto my-5 space-y-5 font-sans'>
      {/* Filters Form */}
      <div className="flex flex-col border border-[#E5E7EB] rounded-md">
        <div className="flex items-center justify-between p-4 bg-[#F2F4F7]">
          <div className="flex gap-4 items-center">
            <svg className={'cursor-pointer hover:scale-150 transition-all duration-500 ' + (showFilters ? 'rotate-180' : '')}
              onClick={() => setShowFilters(!showFilters)}
              width="14" height="11" viewBox="0 0 14 11" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6.45318 10.0459C6.51434 10.1347 6.59618 10.2074 6.69164 10.2576C6.7871 10.3077 6.89333 10.3339 7.00118 10.3339C7.10903 10.3339 7.21525 10.3077 7.31072 10.2576C7.40618 10.2074 7.48802 10.1347 7.54918 10.0459L13.5492 1.37925C13.6186 1.27929 13.6594 1.16221 13.6669 1.04072C13.6745 0.919239 13.6487 0.798001 13.5922 0.690181C13.5357 0.582362 13.4507 0.492084 13.3465 0.429157C13.2423 0.366231 13.1229 0.333061 13.0012 0.333253H1.00118C0.879741 0.333754 0.760737 0.367351 0.656966 0.430428C0.553194 0.493506 0.46858 0.583678 0.412224 0.691248C0.355868 0.798818 0.329902 0.919716 0.337118 1.04094C0.344334 1.16216 0.384459 1.27913 0.453178 1.37925L6.45318 10.0459Z" fill="black" />
            </svg>
            <span className="font-bold font-sans text-xl">{tr['filters']}</span>
          </div>
          <Link href={route('react.products')} className='underline cursor-pointer text-gray-900 hover:text-gray-600 font-semibold ' >{tr['reset_all']}</Link>
        </div>
        <div className={"flex max-lg:flex-col lg:justify-between gap-2 transition-all duration-300 overflow-hidden " + (!showFilters ? '  opacity-0 invisible max-h-0 p-0 ' : ' p-4 ')}>
          {/* brand select */}
          {
            <div className={PrimarySelectClassNames}>
              <label htmlFor="brand" className="w-[130px] font-semibold ">{tr['brand']}:</label>
              <PrimarySelect
                selectContainerClasses={selectContainerClasses}
                id="brand"
                name="brands[]"
                hasBottomMargin={false}
                hasLeftBorder={false}
                placeholder={tr['select_brand']}
                options={filterOptionsData?.brands?.length > 0 ? filterOptionsData?.brands : (filterData?.brand ? [{ label: filterData?.brand, value: filterData?.brand }] : [])}
                form='filter-form'
                value={filterData?.brand ?? ''}
                onChange={(e) => setFilterData({ ...filterData, brand: e.target.value })}
                className='!p-2 !text-sm'
              />
            </div>
          }
          {/* category select */}
          {
            <div className={PrimarySelectClassNames}>
              <label htmlFor="category" className="w-[130px] font-semibold">{tr['category']}:</label>
              <PrimarySelect
                selectContainerClasses={selectContainerClasses}
                id="category"
                name="categories[]"
                placeholder={tr['select_category']}
                options={filterOptionsData?.categories?.length > 0 ? filterOptionsData?.categories : (filterData?.category ? [{ label: tr[filterData?.category], value: filterData?.category }] : [])}
                hasBottomMargin={false}
                hasLeftBorder={false}
                form='filter-form'
                value={filterData?.category ?? ''}
                onChange={(e) => setFilterData({ ...filterData, category: e.target.value })}
                className='!p-2 !text-sm'
              />
            </div>
          }
          {/* availability select */}
          {
            <div className={PrimarySelectClassNames}>
              <label htmlFor="availability" className="w-[130px] font-semibold">{tr['availability']}:</label>
              <PrimarySelect
                selectContainerClasses={selectContainerClasses}
                id="availability"
                name="availability"
                placeholder={tr['select_availability']}
                options={filterOptionsData?.availability}
                hasBottomMargin={false}
                hasLeftBorder={false}
                form='filter-form'
                value={filterData?.availability ?? ''}
                onChange={(e) => setFilterData({ ...filterData, availability: e.target.value })}
                className='!p-2 !text-sm'
              />
            </div>
          }

          {/* <Button
            size='sm'
            className='self-end px-10'
            type='submit'
            form='filter-form'
          >
            Apply
          </Button> */}
        </div>
      </div>
      {/* Options Form */}
      <div className="border border-[#E5E7EB] rounded-md p-4 flex flex-wrap gap-4 justify-between items-center ">
        {/* Display Style */}
        <div className="flex gap-2 items-center">
          <p className=" text-nowrap w-[130px] text-sm font-bold max-sm:hidden">
            {tr['display_style']} :
          </p>
          <input type="hidden" name="displayStyle" value={filterData?.displayStyle} form='filter-form' />
          <div className={  "grid place-items-center" + (filterData?.displayStyle === 'table' ? ' text-white bg-black p-[5px] rounded-md ' : ' text-[#888888]')}>
            <svg width="20" height="20" viewBox="0 0 20 16" fill="none" xmlns="http://www.w3.org/2000/svg"
              onClick={() => setFilterData({ ...filterData, displayStyle: 'table' })}
              className={' cursor-pointer hover:scale-110 transition-all duration-300 '}>
              <path d="M1.83501 12.3086C1.35348 12.3086 0.927263 12.4941 0.556358 12.8652C0.185453 13.2363 0 13.6628 0 14.1445C0 14.6393 0.185453 15.0723 0.556358 15.4434C0.927263 15.8145 1.35348 16 1.83501 16C2.32955 16 2.76227 15.8145 3.13317 15.4434C3.50408 15.0723 3.68953 14.6393 3.68953 14.1445C3.68953 13.6628 3.50408 13.2363 3.13317 12.8652C2.76227 12.4941 2.32955 12.3086 1.83501 12.3086ZM6.14922 15.375H19.6775V12.9141H6.14922V15.375ZM1.83501 6.15625C1.35348 6.15625 0.927263 6.3418 0.556358 6.71289C0.185453 7.08398 0 7.51042 0 7.99219C0 8.48698 0.185453 8.91992 0.556358 9.29102C0.927263 9.66211 1.35348 9.84766 1.83501 9.84766C2.32955 9.84766 2.76227 9.66211 3.13317 9.29102C3.50408 8.91992 3.68953 8.48698 3.68953 7.99219C3.68953 7.51042 3.50408 7.08398 3.13317 6.71289C2.76227 6.3418 2.32955 6.15625 1.83501 6.15625ZM6.14922 9.22266H19.6775V6.76172H6.14922V9.22266ZM1.83501 0.00390625C1.35348 0.00390625 0.927263 0.189453 0.556358 0.560547C0.185453 0.931641 0 1.35807 0 1.83984C0 2.33464 0.185453 2.76758 0.556358 3.13867C0.927263 3.50977 1.35348 3.69531 1.83501 3.69531C2.32955 3.69531 2.76227 3.50977 3.13317 3.13867C3.50408 2.76758 3.68953 2.33464 3.68953 1.83984C3.68953 1.35807 3.50408 0.931641 3.13317 0.560547C2.76227 0.189453 2.32955 0.00390625 1.83501 0.00390625ZM6.14922 0.609375V3.07031H19.6775V0.609375H6.14922Z" fill="currentColor" />
            </svg>
          </div>
          <div className={ "grid place-items-center" + (!(filterData?.displayStyle === 'table') ? ' text-white bg-black p-[5px] rounded-md ' : ' text-[#888888]')}>
            <svg width="20" height="20" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg"
              onClick={() => setFilterData({ ...filterData, displayStyle: 'grid' })}
              className={' cursor-pointer hover:scale-110 transition-all duration-300 ' }>
              <path d="M2.03125 4.83203C1.47135 4.83203 0.992839 4.63346 0.595703 4.23633C0.198568 3.83919 0 3.36068 0 2.80078C0 2.24089 0.198568 1.76237 0.595703 1.36523C0.992839 0.9681 1.47135 0.769531 2.03125 0.769531C2.59115 0.769531 3.06966 0.9681 3.4668 1.36523C3.86393 1.76237 4.0625 2.24089 4.0625 2.80078C4.0625 3.36068 3.86393 3.83919 3.4668 4.23633C3.06966 4.63346 2.59115 4.83203 2.03125 4.83203ZM2.03125 10.9062C1.47135 10.9062 0.992839 10.7109 0.595703 10.3203C0.198568 9.92969 0 9.44792 0 8.875C0 8.3151 0.198568 7.83984 0.595703 7.44922C0.992839 7.05859 1.47135 6.86328 2.03125 6.86328C2.59115 6.86328 3.06966 7.05859 3.4668 7.44922C3.86393 7.83984 4.0625 8.3151 4.0625 8.875C4.0625 9.44792 3.86393 9.92969 3.4668 10.3203C3.06966 10.7109 2.59115 10.9062 2.03125 10.9062ZM2.03125 17C1.47135 17 0.992839 16.8014 0.595703 16.4043C0.198568 16.0072 0 15.5286 0 14.9688C0 14.4089 0.198568 13.9303 0.595703 13.5332C0.992839 13.1361 1.47135 12.9375 2.03125 12.9375C2.59115 12.9375 3.06966 13.1361 3.4668 13.5332C3.86393 13.9303 4.0625 14.4089 4.0625 14.9688C4.0625 15.5286 3.86393 16.0072 3.4668 16.4043C3.06966 16.8014 2.59115 17 2.03125 17ZM7.98828 4.83203C7.42839 4.83203 6.95312 4.63346 6.5625 4.23633C6.17188 3.83919 5.97656 3.36068 5.97656 2.80078C5.97656 2.24089 6.17188 1.76237 6.5625 1.36523C6.95312 0.9681 7.42839 0.769531 7.98828 0.769531C8.5612 0.769531 9.04297 0.9681 9.43359 1.36523C9.82422 1.76237 10.0195 2.24089 10.0195 2.80078C10.0195 3.36068 9.82422 3.83919 9.43359 4.23633C9.04297 4.63346 8.5612 4.83203 7.98828 4.83203ZM8.00781 10.9062C7.4349 10.9062 6.95312 10.7109 6.5625 10.3203C6.17188 9.92969 5.97656 9.44792 5.97656 8.875C5.97656 8.3151 6.17188 7.83984 6.5625 7.44922C6.95312 7.05859 7.42839 6.86328 7.98828 6.86328C8.5612 6.86328 9.04297 7.05859 9.43359 7.44922C9.82422 7.83984 10.0195 8.3151 10.0195 8.875C10.0195 9.44792 9.82422 9.92969 9.43359 10.3203C9.04297 10.7109 8.56771 10.9062 8.00781 10.9062ZM8.00781 17C7.4349 17 6.95312 16.8014 6.5625 16.4043C6.17188 16.0072 5.97656 15.5286 5.97656 14.9688C5.97656 14.4089 6.17188 13.9303 6.5625 13.5332C6.95312 13.1361 7.42839 12.9375 7.98828 12.9375C8.5612 12.9375 9.04297 13.1361 9.43359 13.5332C9.82422 13.9303 10.0195 14.4089 10.0195 14.9688C10.0195 15.5286 9.82422 16.0072 9.43359 16.4043C9.04297 16.8014 8.56771 17 8.00781 17ZM13.9648 4.83203C13.4049 4.83203 12.9264 4.63346 12.5293 4.23633C12.1322 3.83919 11.9336 3.36068 11.9336 2.80078C11.9336 2.24089 12.1322 1.76237 12.5293 1.36523C12.9264 0.9681 13.4049 0.769531 13.9648 0.769531C14.5247 0.769531 15.0033 0.9681 15.4004 1.36523C15.7975 1.76237 15.9961 2.24089 15.9961 2.80078C15.9961 3.36068 15.7975 3.83919 15.4004 4.23633C15.0033 4.63346 14.5247 4.83203 13.9648 4.83203ZM13.9648 10.9062C13.4049 10.9062 12.9264 10.7109 12.5293 10.3203C12.1322 9.92969 11.9336 9.44792 11.9336 8.875C11.9336 8.3151 12.1322 7.83984 12.5293 7.44922C12.9264 7.05859 13.4049 6.86328 13.9648 6.86328C14.5247 6.86328 15.0033 7.05859 15.4004 7.44922C15.7975 7.83984 15.9961 8.3151 15.9961 8.875C15.9961 9.44792 15.7975 9.92969 15.4004 10.3203C15.0033 10.7109 14.5247 10.9062 13.9648 10.9062ZM13.9648 17C13.4049 17 12.9264 16.8014 12.5293 16.4043C12.1322 16.0072 11.9336 15.5286 11.9336 14.9688C11.9336 14.4089 12.1322 13.9303 12.5293 13.5332C12.9264 13.1361 13.4049 12.9375 13.9648 12.9375C14.5247 12.9375 15.0033 13.1361 15.4004 13.5332C15.7975 13.9303 15.9961 14.4089 15.9961 14.9688C15.9961 15.5286 15.7975 16.0072 15.4004 16.4043C15.0033 16.8014 14.5247 17 13.9648 17Z" fill="currentColor" />
            </svg>
          </div>
        </div>
        {/* Sort By */}
        <div className={PrimarySelectClassNames + " max-sm:max-w-[80%] max-sm:flex-1 max-sm:ps-[22px] "}>
          <label className=" text-nowrap w-[130px] text-sm font-bold max-sm:hidden">
            {tr['sort_by']}:
          </label>
          <PrimarySelect
            selectContainerClasses={selectContainerClasses}
            id="sortBy"
            name="sortBy"
            placeholder={tr['select_sort_by']}
            options={filterOptionsData?.sortBy}
            hasBottomMargin={false}
            hasLeftBorder={false}
            form='filter-form'
            value={filterData?.sortBy ?? ''}
            onChange={(e) => setFilterData({ ...filterData, sortBy: e.target.value })}
            className='!p-2 !text-sm'
          />
        </div>
      </div>
      {/* Add To Cart Form */}

      { !isEmptyProducts && <div className={"border border-[#E5E7EB] rounded-md p-4 flex flex-wrap gap-4 justify-between items-center " + (isAuthenticated ? "  " : " hidden")}>
        {/* Select All */}
        <Button
          size='md'
          variant='primary'
          className='!py-2 max-sm:!px-5 sm:!px-15 !rounded-md !text-sm'
          onClick={toggleSelect}
          id='select-all-product-btn'
        >
          {tr['select_all_products']}
        </Button>
        {/* Add To Cart */}
        <Button
          size='md'
          variant='primary'
          onClick={() => addSelectedItemsToCart()}
          className=' !bg-[#004AAD] !py-2 max-sm:!px-5 sm:!px-15 !rounded-md !text-sm'
          id='add-selected-to-cart-btn'
        >
          <span className='max-sm:hidden'>
            {tr['add_to_cart']} &nbsp;
          </span>
          <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g clipPath="url(#clip0_854_23532)">
              <path d="M5.83268 14.6667C6.20087 14.6667 6.49935 14.3682 6.49935 14C6.49935 13.6319 6.20087 13.3334 5.83268 13.3334C5.46449 13.3334 5.16602 13.6319 5.16602 14C5.16602 14.3682 5.46449 14.6667 5.83268 14.6667Z" stroke="white" strokeWidth="1.33333" strokeLinecap="round" strokeLinejoin="round" />
              <path d="M13.1667 14.6667C13.5349 14.6667 13.8333 14.3682 13.8333 14C13.8333 13.6319 13.5349 13.3334 13.1667 13.3334C12.7985 13.3334 12.5 13.6319 12.5 14C12.5 14.3682 12.7985 14.6667 13.1667 14.6667Z" stroke="white" strokeWidth="1.33333" strokeLinecap="round" strokeLinejoin="round" />
              <path d="M1.86523 1.3667H3.19857L4.9719 9.6467C5.03695 9.94994 5.20568 10.221 5.44904 10.4133C5.6924 10.6055 5.99517 10.7069 6.30523 10.7H12.8252C13.1287 10.6995 13.4229 10.5956 13.6593 10.4053C13.8956 10.215 14.06 9.94972 14.1252 9.65336L15.2252 4.70003H3.9119" stroke="white" strokeWidth="1.33333" strokeLinecap="round" strokeLinejoin="round" />
            </g>
            <defs>
              <clipPath id="clip0_854_23532">
                <rect width="16" height="16" fill="white" transform="translate(0.5)" />
              </clipPath>
            </defs>
          </svg>
        </Button>
      </div>}



      {/* the form of the filter */}
      <form id='filter-form' ref={formRef}></form>
    </div>
  )
}
