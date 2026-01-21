import NavLink from "@/components/ui/NavLink";
import HeaderLogo from "./HeaderLogo";
import TopHeader from "./TopHeader";
import { Link } from "@inertiajs/react";
import WebNavigation from "./Partials/WebNavigation";
import TabletNavigation from "./Partials/TabletNavigation";


export default function Header({ pageTitle, breadcrumbs, hasTitleHeader = false }) {

  return (
    <>

      {/* Top Header */}
      <TopHeader />

      <header className="shadow-[0_4px_10px_rgba(0,0,0,0.1)]">
        <div className="container py-3 mx-auto flex justify-between items-center">

          {/* Logo */}
          <div className="flex items-center">
            <Link href={route("react.home")} className="w-[120px] lg:w-[180px]">
              <HeaderLogo />
            </Link>
          </div>

          {/* Navigation */}
          <WebNavigation />
          <TabletNavigation />

        </div>

      </header >

      {/* Breadcrumbs */}
      {breadcrumbs && (
        <div className="text-sm md:text-base text-primary-300 container mx-auto mt-4 sm:mt-6">
          {breadcrumbs.map((item, index) => (
            <span key={index}>
              {index > 0 && <span className="px-2">/</span>}
              {item.url ? (
                <Link href={item.url} className="transition-colors duration-200 hover:text-secondary-500">
                  {item.label}
                </Link>
              ) : (
                <span className="text-primary-300">{item.label}</span>
              )}
            </span>
          ))}
        </div>
      )}
      {
        hasTitleHeader && !!pageTitle && (
          <div className="text-2xl font-bold text-primary-500 container mx-auto my-8">
            {pageTitle}
          </div>
        )
      }
    </>
  );
}
