export const initGA = (measurementId) => {
  if (!window.gtag) {
    // Load the GA script dynamically
    const script = document.createElement('script');
    script.async = true;
    script.src = `https://www.googletagmanager.com/gtag/js?id=${measurementId}`;
    document.head.appendChild(script);

    // Initialize gtag
    window.dataLayer = window.dataLayer || [];
    function gtag(){window.dataLayer.push(arguments);}
    window.gtag = gtag;
    gtag('js', new Date());
  }

  // Configure GA
  window.gtag('config', measurementId, {
    cookie_domain: window.location.hostname
      .split('.')
      .slice(-2)
      .join('.'), // supports all subdomains
    page_path: window.location.pathname,
  });
};

export const trackPageView = (url, measurementId) => {
  if (window.gtag) {
    window.gtag('config', measurementId, {
      page_path: url,
    });
  }
};