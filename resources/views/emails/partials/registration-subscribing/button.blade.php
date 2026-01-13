<a href="{{ $link ?? '#' }}"
  style="
    background:black;
    color:white;
    text-decoration:none;
    padding:8px 16px;
    border-radius:12px;
    display:inline-block;
    font-size:14px;
    font-weight:600;
    max-width:300px;
    min-width:180px;
    width:auto;
    text-align:center;
    transition:all 0.3s ease;
  "
>
  <span class="text-desktop">{{ $text ?? 'Shop now' }}</span>
  <span class="text-mobile" style="display:none;">{{ $textMobile ?? $text}}</span>
</a>

<style>
@media only screen and (max-width: 425px) {
  a[style] {
    display: block !important;
    width: calc(100% - 40px) !important;
    max-width: none !important;
    margin: 0 20px !important;
    padding: 14px 0 !important;
    text-align: center !important;
  }

  .text-desktop {
    display: none !important;
  }

  .text-mobile {
    display: inline !important;
  }
}
</style>
