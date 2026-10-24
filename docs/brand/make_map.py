"""Generate an Australia outline filled with the Australian flag as an SVG fragment.

Outputs:
  au-flag-map.svg   standalone map (used for the hero illustration and logo)
"""
import math, pathlib

OUT = pathlib.Path(__file__).parent

# Approximate coastline (lon, lat), clockwise from North West Cape.
MAINLAND = [
    (113.6, -22.0), (114.1, -21.8), (115.1, -21.6), (116.7, -20.6), (118.6, -20.3), (120.2, -19.7),
    (121.5, -18.7), (122.2, -18.0), (122.3, -17.1), (122.9, -16.4), (123.6, -16.9), (123.9, -16.3),
    (124.6, -15.4), (125.2, -14.6), (126.1, -14.1), (126.9, -13.8), (127.8, -14.3), (128.2, -15.0),
    (129.0, -14.9), (129.6, -14.8), (129.7, -13.6), (130.3, -12.9), (130.9, -12.4), (131.5, -12.2),
    (132.3, -11.4), (132.9, -11.3), (133.6, -11.8), (134.6, -12.0), (135.4, -12.2), (136.1, -11.9),
    (136.8, -12.3), (136.6, -13.2), (135.9, -13.6), (135.5, -14.6), (135.5, -15.0), (136.4, -15.6),
    (137.2, -16.0), (138.3, -16.8), (139.3, -17.4), (140.2, -17.7), (140.9, -17.4), (141.4, -16.6),
    (141.6, -15.4), (141.5, -14.0), (141.7, -12.8), (141.9, -12.0), (142.2, -11.0), (142.5, -10.7),
    (142.8, -11.2), (143.2, -12.2), (143.5, -12.9), (143.6, -14.0), (144.5, -14.3), (145.3, -15.0),
    (145.4, -16.0), (145.9, -16.9), (146.1, -18.0), (146.4, -19.0), (147.4, -19.4), (148.6, -20.4),
    (149.2, -21.1), (149.7, -22.3), (150.6, -22.6), (150.8, -23.4), (151.7, -24.0), (152.5, -25.0),
    (153.1, -25.6), (153.1, -26.6), (153.4, -27.5), (153.6, -28.6), (153.1, -30.3), (152.9, -31.4),
    (152.5, -32.4), (151.8, -32.9), (151.3, -33.9), (150.9, -34.6), (150.7, -35.1), (150.2, -35.9),
    (149.9, -37.1), (149.9, -37.5), (148.6, -37.8), (147.6, -38.0), (146.7, -38.7), (146.4, -39.1),
    (145.5, -38.6), (144.9, -38.3), (144.3, -38.3), (143.5, -38.8), (142.4, -38.4), (141.6, -38.4),
    (140.6, -38.0), (140.0, -37.5), (139.6, -37.0), (139.3, -36.0), (138.9, -35.6), (138.2, -35.6),
    (138.5, -34.9), (138.1, -34.2), (137.8, -32.9), (137.5, -33.9), (137.2, -34.5), (136.8, -35.2),
    (136.4, -34.7), (135.9, -34.8), (135.2, -34.0), (134.6, -33.2), (134.0, -32.7), (133.6, -32.1),
    (132.4, -31.9), (131.1, -31.5), (129.9, -31.6), (128.9, -31.7), (127.4, -32.2), (126.0, -32.3),
    (124.9, -32.9), (123.8, -33.8), (121.9, -33.9), (120.3, -34.0), (119.0, -34.4), (117.9, -35.1),
    (116.6, -35.0), (115.6, -34.4), (115.1, -34.4), (115.0, -33.5), (115.7, -33.3), (115.7, -32.0),
    (115.3, -31.0), (114.9, -29.6), (114.6, -28.8), (114.1, -27.6), (113.4, -26.3), (113.8, -25.8),
    (113.6, -24.9), (113.4, -24.1), (113.8, -23.1),
]
TASMANIA = [
    (144.6, -40.7), (145.3, -40.8), (146.6, -41.1), (147.6, -40.9), (148.3, -40.9), (148.3, -41.9),
    (148.1, -42.4), (147.9, -43.2), (147.0, -43.6), (146.2, -43.5), (145.6, -42.9), (145.2, -42.2),
    (144.9, -41.6), (144.7, -41.1),
]

LON0, LAT0, KX, KY = 112.8, -10.3, 10.0, 11.0


def project(pt):
    lon, lat = pt
    return round((lon - LON0) * KX, 1), round((-lat + LAT0) * KY, 1)


def path(points):
    pts = [project(p) for p in points]
    return 'M' + ' L'.join(f'{x} {y}' for x, y in pts) + ' Z'


all_pts = [project(p) for p in MAINLAND + TASMANIA]
W = math.ceil(max(x for x, _ in all_pts) + 4)
H = math.ceil(max(y for _, y in all_pts) + 4)


def star(cx, cy, r, points=7, inner=0.42):
    coords = []
    for i in range(points * 2):
        rad = r if i % 2 == 0 else r * inner
        a = math.pi / points * i - math.pi / 2
        coords.append(f'{cx + rad * math.cos(a):.1f},{cy + rad * math.sin(a):.1f}')
    return f'<polygon points="{" ".join(coords)}" fill="#fff"/>'


def flag(w, h):
    cw, ch = w * 0.52, h * 0.45  # canton (Union Jack)
    navy, red, white = '#012169', '#C8102E', '#FFFFFF'
    sw = ch * 0.2  # saltire width
    parts = [f'<rect width="{w}" height="{h}" fill="{navy}"/>']
    parts.append(f'<g clip-path="url(#canton)">')
    parts.append(f'<path d="M0 0 L{cw} {ch} M{cw} 0 L0 {ch}" stroke="{white}" stroke-width="{sw:.1f}"/>')
    parts.append(f'<path d="M0 0 L{cw} {ch} M{cw} 0 L0 {ch}" stroke="{red}" stroke-width="{sw / 3:.1f}"/>')
    parts.append(f'<path d="M{cw / 2} 0 V{ch} M0 {ch / 2} H{cw}" stroke="{white}" stroke-width="{ch / 3:.1f}"/>')
    parts.append(f'<path d="M{cw / 2} 0 V{ch} M0 {ch / 2} H{cw}" stroke="{red}" stroke-width="{ch / 5:.1f}"/>')
    parts.append('</g>')
    # Commonwealth Star under the canton, Southern Cross on the fly side.
    parts.append(star(cw * 0.5, h * 0.585, h * 0.095, 7))
    fly = lambda fx, fy: (w * 0.52 + (w * 0.48) * fx, h * fy)
    for fx, fy, r, n in [(0.50, 0.24, 0.06, 7), (0.50, 0.74, 0.06, 7), (0.20, 0.48, 0.055, 7), (0.80, 0.42, 0.055, 7), (0.66, 0.58, 0.033, 5)]:
        x, y = fly(fx, fy)
        parts.append(star(x, y, h * r, n, 0.42 if n == 7 else 0.4))
    defs = f'<clipPath id="canton"><rect width="{cw:.1f}" height="{ch:.1f}"/></clipPath>'
    return defs, ''.join(parts)


defs, flag_svg = flag(W, H)
land = path(MAINLAND) + ' ' + path(TASMANIA)
svg = f'''<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 {W} {H}" width="{W}" height="{H}" role="img" aria-label="Australia">
<defs>{defs}<clipPath id="land"><path d="{land}"/></clipPath></defs>
<g clip-path="url(#land)">{flag_svg}</g>
<path d="{land}" fill="none" stroke="#012169" stroke-width="3" stroke-linejoin="round"/>
</svg>
'''
(OUT / 'au-flag-map.svg').write_text(svg, encoding='utf-8')
print('map', W, H)
