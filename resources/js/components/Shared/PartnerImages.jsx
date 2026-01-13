import React from "react"

export default function PartnerImages({ bigImg , smallImg}) {
  return (
    <>
      <img src={bigImg} alt="Partner with Sky Business" className="w-full h-full max-md:hidden md:max-h-70 rounded-5xl" />
      <img src={smallImg} alt="Partner with Sky Business" className="w-full h-full md:hidden max-md:max-h-70 rounded-5xl" />
    </>
  )
}
