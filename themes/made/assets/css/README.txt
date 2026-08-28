MADE CSS Architecture
======================

Bootstrap remains the base framework. MADE only adds visual styling and
section-specific presentation.

Recommended load order:

1. bootstrap.min.css
2. variables.css
3. theme.css
4. hero.css
5. intro.css
6. business.css
7. products.css
8. clients.css
9. faq.css
10. footer.css
11. responsive.css

Files:
- variables.css  -> colors, fonts, spacing, radius, shadows and motion
- theme.css      -> global foundation, typography, buttons, header/navigation
- hero.css       -> hero / Bootstrap carousel
- intro.css      -> intro / mission / about
- business.css   -> business cards
- products.css   -> products grid/cards
- clients.css    -> clients, featured brands and logo carousels
- faq.css        -> FAQ accordion
- footer.css     -> contact and footer
- responsive.css -> global responsive rules only

To change the site's visual identity, start with variables.css.
Do not duplicate colors or font families inside section files unless a
component genuinely requires a one-off value.
