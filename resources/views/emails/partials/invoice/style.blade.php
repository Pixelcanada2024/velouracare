<style>
  body {
    margin: 0 auto;
    padding: 0;
  }


  .container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0;
  }

  .container table {
    width: 100%;
    padding: 0 32px;
    box-sizing: border-box;
  }

  table {
    border-collapse: collapse;
    width: 100%;
  }

  .header-icons {
    padding: 3px 0;
  }

  .header-icons span {
    font-size: 15px;
    font-weight: normal;
  }

  .header-icons .img {
    width: 15px;
    height: 15px;
    padding-right: 5px;
  }

  .header-icons a {
    color: black;
  }


  a {
    text-decoration: none;
    font-weight: normal;
  }


  .top-header {
    padding: 32px;
    background: #282A31;
  }

  .contact-header {
    padding: 16px;
    background: #383B44;
  }

  .logo-section {
    display: table-cell;
    vertical-align: middle;
  }



  .invoice-info {
    text-align: left;
    margin-top: 18px
  }

  .invoice-title {
    font-size: 32px;
    font-weight: bold;
    color: white;
    margin-bottom: 8px;
  }


  .order-info .order-info-label
  {
    color: #818181;
    font-weight: 600;
  }

  /* Content Sections */
  .content-section {
    padding-top: 32px;
    padding-bottom: 32px;
    margin: 0;
  }

  .section-title {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin: 0 0 24px 0;
    padding: 0;
  }



  .info-row {
    display: table;
    width: 100%;
    margin-bottom: 12px;
  }

  .info-row:last-child {
    margin-bottom: 0;
  }

  .info-label {
    display: table-cell;
    width: 40%;
    font-size: 16px;
    color: #666;
    font-weight: normal;
    vertical-align: top;
    padding-right: 16px;
  }

  .info-value {
    display: table-cell;
    width: 60%;
    font-size: 16px;
    color: #333;
    font-weight: normal;
    text-align: right;
    vertical-align: top;
  }


  .mobile-address-cards {
    display: none;
  }

  /* Addresses Section */
  .addresses-section {
    padding-top: 32px;
    padding-bottom: 32px;
    margin: 0;
  }

  .addresses-container {
    width: 100%;
    border-collapse: collapse;
  }

  .address-column {
    width: 50%;
    vertical-align: top;
  }

  .address-box{

    padding: 24px;
    border: 2px solid #E5E7EB;
    border-radius: 12px;

  }



  .address-title {
    font-size: 24px;
    font-weight: bold;
    color: #666666;
    margin: 0 0 24px 0;
    padding: 0;
  }

  .address-value-row
  {
    margin-bottom: 8px;
  }

  /* Products Section */
  .products-table {
    display: table;
    width: 100%;
  }

  .mobile-products {
    display: none;
  }

  /* Responsive styles */
  @media screen and (max-width: 768px) {
    .header {
      padding: 16px;
    }

    .content-section,
    .addresses-section {
      padding-top: 16px;
      padding-bottom: 16px;
    }

    .section-title,
    .address-title {
      font-size: 20px;
      margin-bottom: 16px;
    }



    /* Hide desktop addresses container on mobile */
    .addresses-container {
      display: none;
    }

    /* Show mobile address cards */
    .mobile-address-cards {
      display: block;
    }

    .mobile-address-card {
      border-radius: 8px;
      padding: 16px;
      margin: 16px 8px;
    }

    .mobile-address-card h3 {
      font-weight: 600;
      font-size: 18px;
      color: #111827;
      margin: 0 0 12px 0;
    }

    .mobile-address-card table {
      width: 100%;
      font-size: 14px;
      color: #374151;
      border-collapse: collapse;
      padding: 0;
    }

    .mobile-address-card td {
      padding: 6px 0;
      border-bottom: none;
    }



    .logo-section {
      display: table-cell;
      vertical-align: middle;
    }

    .invoice-title {
      font-size: 18px;
    }





    /* Products responsive */
    .products-table {
      display: none;
    }

    .mobile-products {
      display: block;
    }
    .mobile-products table td{
        padding-bottom: 10px ;
    }
  }

  @media screen and (max-width: 500px) {
    .header,.order-info{
      padding:8px
    }

    .contact-item {
      font-size: 10px !important;
      white-space: normal !important;
      word-break: break-word !important;
    }

    .header{
      border-bottom: #E5E7EB solid 1px;
      padding-bottom:10px;
      margin-top: 10px;
    }

    .header-icons {
      padding: 0;
      height: fit-content;
      line-height: 1;
      color: black;
    }

    .header-icons td {
      height: fit-content;
    }

    .header-icons span {
      font-size: 12px;
    }

    .logo {
      width: 140px;
      height: 50px;
    }

    .header-icons .img {
      width: 12px;
      height: 12px;
    }
  }





  .thank-you-message {
    margin-top: 20px;
  }

  .thank-you-message p {
    font-size: 16px;
    color: #FFFFFFCC;
    line-height: 1.5;
    margin: 0 0 16px 0;
  }

  .thank-you-message .signature {
    font-size: 15px;
    color: #FFFFFFCC;
    margin: 16px 0 0 0;
  }


  /* Footer responsive */
  @media screen and (max-width: 768px) {
    .header,.order-info{
      padding:8px
    }
    .footer-message {
      padding: 16px;
    }

    .footer-message table {
      display: block !important;
    }

    .footer-message td {
      display: block !important;
      width: 100% !important;
      text-align: center !important;
      padding: 0 !important;
      margin-bottom: 16px;
    }

    .footer-message td:last-child {
      margin-bottom: 0;
    }


  }

  @media screen and (max-width: 500px) {


    .thank-you-message p {
      font-size: 14px;
    }

    .thank-you-message .signature {
      font-size: 13px;
    }
  }
</style>
