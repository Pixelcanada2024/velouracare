import React from 'react'
import header_logo from '/public/website-assets/logo/header_logo.png';
import header_logo_icon from '/public/website-assets/logo/logo-icon.png';
import { usePage } from "@inertiajs/react";

export default function HeaderLogo() {
  const meta = usePage().props.meta;
  const logo = meta.system_logo_white;
  const mobileLogo = meta.mobile_system_logo_white;

  return (
    <div className="relative">
      {/* <img src={mobileLogo ||  header_logo_icon} alt="Veloura Care" className="lg:hidden" /> */}
      <img src={logo || header_logo } alt="Veloura Care" className="" />
    </div>
  )
}
