import { Link } from "@inertiajs/react";

export default function NavLink({ href, active = false, children, className = "", ...props }) {
  const baseClasses = " hover:text-secondary-500 transition-colors cursor-pointer text-nowrap text-[15px] sm:text-base";
  const activeClasses = active ? " text-secondary-500 font-bold " : "";

  return (
    <Link
      href={href}
      className={` ${baseClasses} ${className} ${activeClasses}`.trim()}
      {...props}
    >
      {children}
    </Link>
  );
}
