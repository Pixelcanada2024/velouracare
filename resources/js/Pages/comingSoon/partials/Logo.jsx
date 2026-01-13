import logo from '/public/website-assets/imgs/website-logo.png'
export default function Logo() {
  return (
    <>
      <img src={logo} alt="logo" className="w-36 sm:w-52 lg:w-64 h-auto mx-auto mb-8" />
    </>
  )
}
