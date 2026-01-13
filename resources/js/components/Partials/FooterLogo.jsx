import React from 'react'
import footer_logo from '/public/website-assets/logo/footer_logo.png';
import { usePage } from "@inertiajs/react";

export default function FooterLogo() {
  const meta = usePage().props.meta;
  const logo = meta.system_logo_white;

  return (
    <div className="relative">
      <img src={logo || footer_logo} alt="Sky Business" className="w-full" />
    </div>
  )
}
