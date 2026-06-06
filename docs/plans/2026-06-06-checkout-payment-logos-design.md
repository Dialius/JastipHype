# Design Document: JastipHype Checkout Payment Logos Integration

## Context & Objectives
To improve user trust and interface clarity, the payment method selection UI in the checkout page (`resources/views/checkout/index.blade.php`) is being upgraded to show official payment provider logos. 

Specifically:
- Replace remote external Wikipedia logo URLs with high-performance local assets.
- Group payment assets under clean, organized subfolders in `public/images/payment/`.
- Replace collapsed accordion header text/badge previews with actual local logo images.
- Add DANA as an option in the E-Wallet & QRIS category.

## Design Details

### 1. File Structure for Payment Images
All payment logos have been organized under `public/images/payment/`:
- **Virtual Accounts (`banks/`)**:
  - BCA: `banks/bca.svg`
  - BNI: `banks/bni-va.webp`
  - BRI: `banks/bri-va.webp`
  - Mandiri: `banks/mandiri-va.webp`
- **E-Wallets & QRIS (`ewallet/`)**:
  - QRIS (All-in-one): `ewallet/qrisfinal.webp`
  - GoPay: `ewallet/qris-gopay.webp`
  - ShopeePay: `ewallet/qris-shopee.webp`
  - DANA: `ewallet/qris-dana.webp`
- **Convenience Stores (`cstore/`)**:
  - Indomaret: `cstore/indomaret.png`
  - Alfamart: `cstore/alfamart.png`

### 2. Collapsed Accordion Header Previews
We will replace the current text-based badges with a series of small, inline images in the header of each accordion panel:
- **Virtual Account (VA)**:
  - Preview logos: Mandiri (`mandiri-va.webp`) & BRI (`bri-va.webp`)
  - Styled as `h-5 object-contain`
- **E-Wallet & QRIS**:
  - Preview logo: QRIS (`qrisfinal.webp`)
  - Styled as `h-5 object-contain`
- **Convenience Store**:
  - Preview logos: Alfamart (`alfamart.png`) & Indomaret (`indomaret.png`)
  - Styled as `h-5 object-contain`

### 3. Expanded Payment Choice Item Template
Each payment option card (when expanded) will be rendered with:
- A rounded-xl container (`border-2 border-gray-200 hover:border-gray-300`)
- A white flex container for the logo (`w-16 h-10 flex items-center justify-center border border-gray-100 bg-white p-1 rounded-lg`)
- The payment option label text
- Fallback text handling via `onerror` attribute in case of image load failures.

## Verification
- Load checkout page and verify that all logos display correctly.
- Expand/collapse each accordion section to ensure dynamic behavior and proper visual alignments.
