---
version: alpha
name: LJN Dev Light
description: Editor-light, vibrant and professional, inspired by Lintas Jaringan Nusantara branding.
colors:
    primary: "#1A1A1A"      # Abu-abu gelap/hitam untuk teks utama (kontras tinggi)
    secondary: "#0033CC"    # Biru khas LJN untuk borders, link, atau metadata
    tertiary: "#FF6600"     # Orange khas LJN untuk tombol utama/call-to-action
    neutral: "#FAFAFA"      # Putih bersih/terang sebagai background halaman dasar
    surface: "#FFFFFF"      # Putih murni untuk komponen seperti card
    on-primary: "#FFFFFF"   # Warna teks di atas tombol aksen (tertiary)
typography:
    display:
        fontFamily: JetBrains Mono
        fontSize: 3.2rem
        fontWeight: 500
        letterSpacing: "-0.02em"
    h1:
        fontFamily: JetBrains Mono
        fontSize: 1.75rem
        fontWeight: 500
    body:
        fontFamily: Inter
        fontSize: 0.92rem
        lineHeight: 1.55
    label:
        fontFamily: JetBrains Mono
        fontSize: 0.72rem
rounded:
    sm: 3px
    md: 6px
    lg: 10px
spacing:
    sm: 8px
    md: 16px
    lg: 32px
components:
    button-primary:
        backgroundColor: "{colors.tertiary}"
        textColor: "{colors.on-primary}"
        rounded: "{rounded.md}"
        padding: 12px 20px
    card:
        backgroundColor: "{colors.surface}"
        textColor: "{colors.primary}"
        rounded: "{rounded.lg}"
        padding: 24px
---

```

## Overview

A corporate-developer light palette: crisp white base, professional blue secondary, and high-energy LJN orange accent.

## Colors

The palette is built around an accessible light mode foundation with corporate branding colors that drive engagement.

* **Primary (`#1A1A1A`):** Headlines and core body text for maximum readability.
* **Secondary (`#0033CC`):** LJN Blue for borders, subtle highlights, captions, and active states.
* **Tertiary (`#FF6600`):** LJN Orange. The primary driver for main actions (CTAs) and interactive elements.
* **Neutral (`#FAFAFA`):** The bright page foundation that gives the interface a clean, spacious feel.

## Typography

* **display:** JetBrains Mono 3.2rem
* **h1:** JetBrains Mono 1.75rem
* **body:** Inter 0.92rem
* **label:** JetBrains Mono 0.72rem

## Do's and Don'ts

* **Do** use Tertiary (Orange) for the main action button on the screen to draw immediate attention.
* **Do** leverage Neutral (Off-white) and Surface (White) to create clear visual hierarchy through soft contrast.
* **Don't** use light text colors on the Neutral background; maintain accessibility and high contrast.
* **Don't** mix other vivid color accents outside the LJN identity (Orange and Blue) to preserve brand integrity.