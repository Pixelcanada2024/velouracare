import { Link, usePage } from '@inertiajs/react';
import React from 'react';
// Pagination Component
export default function Pagination({ totalPages = 1, links = [], className }) {
  const currentPageNum = usePage().props.queryParams['page'] ?? 1;
  const pageNumbers = [1];
  if (+currentPageNum > 3) pageNumbers.push('...');
  if (+currentPageNum - 1 > 1) pageNumbers.push(+currentPageNum - 1);
  if (+currentPageNum > 1 && +currentPageNum < +totalPages) pageNumbers.push(+currentPageNum);
  if (+currentPageNum + 1 < +totalPages) pageNumbers.push(+currentPageNum + 1);
  if (+currentPageNum < +totalPages - 2) pageNumbers.push('...');
  if (+totalPages > 1) pageNumbers.push(+totalPages);

  return (
    <>
      {+totalPages > 1 && (<nav className="flex justify-center items-center space-x-2 my-5">
        {/* Previous Button */}
        <Link
          href={links[0]?.url ?? '#'}
          disabled={+currentPageNum == 1}
          className={`flex items-center justify-center p-2 rounded-md transition-all duration-200
          ${+currentPageNum == 1 ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-200'}
        `}
        >
          <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 rtl:rotate-180" viewBox="0 0 20 20" fill="currentColor">
            <path fillRule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clipRule="evenodd" />
          </svg>
        </Link>


        {/* Page Numbers */}
        {pageNumbers.map((page, index) => (
          <React.Fragment key={index}>
            {page == "..."
              ? <span className="px-3 py-2 rounded-full text-sm font-medium transition-all duration-200 bg-gray-200 text-gray-700">...</span>
              : (<Link
                href={links.find(link => link.label == page.toString())?.url ?? '#'}
                className={`w-10 h-10 flex items-center justify-center rounded-full text-sm font-medium transition-all duration-200 cursor-pointer
                  ${currentPageNum == page
                                    ? 'bg-black text-white shadow-md'
                                    : 'bg-[#F9F9F9] text-gray-700 hover:bg-gray-300'
                                  }
                `}

              >
                {page}
              </Link>)}
          </React.Fragment>
        ))}

        {/* Next Button */}
        <Link
          href={links[+totalPages + 1]?.url ?? '#'}
          disabled={+currentPageNum == +totalPages}
          className={`flex items-center justify-center p-2 rounded-md transition-all duration-200
          ${currentPageNum == totalPages ? 'text-gray-400 cursor-not-allowed' : 'text-gray-700 hover:bg-gray-200'}
        `}
        >
          <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 rtl:rotate-180" viewBox="0 0 20 20" fill="currentColor">
            <path fillRule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clipRule="evenodd" />
          </svg>
        </Link>
      </nav>)}
    </>
  );
};