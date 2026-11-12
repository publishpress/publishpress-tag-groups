"""Generate include/helpers/icons/fontawesome-icons.php from Font Awesome Free SVGs.

Usage:
  npm pack @fortawesome/fontawesome-free@6.7.1 && tar -xzf fortawesome-fontawesome-free-6.7.1.tgz
  python3 bin/build-icons.py package/svgs include/helpers/icons/fontawesome-icons.php

Add or remove icons in SOLID / REGULAR below, then regenerate.
"""
import os, re, sys

svg_root, out_path = sys.argv[1], sys.argv[2]

# Curated topic icons. 'fas' = solid, 'far' = regular.
SOLID = """
utensils mug-hot mug-saucer pizza-slice burger bowl-food bowl-rice cake-candles cookie carrot apple-whole lemon pepper-hot fish shrimp egg cheese bread-slice ice-cream wine-glass beer-mug-empty martini-glass champagne-glasses blender kitchen-set fire-burner fire clock hourglass-half stopwatch calendar calendar-days
plane plane-departure car bus train bicycle motorcycle ship sailboat taxi truck rocket suitcase suitcase-rolling passport map map-location-dot location-dot compass globe earth-europe earth-americas earth-asia mountain mountain-sun tree umbrella-beach campground tent hotel building city house landmark monument archway bridge road signs-post flag
book book-open bookmark graduation-cap school chalkboard-user pencil pen-nib highlighter flask atom microscope calculator language lightbulb brain puzzle-piece award trophy medal certificate
music headphones microphone guitar drum film video camera camera-retro image photo-film palette paintbrush masks-theater ticket tv radio podcast gamepad dice chess
futbol basketball baseball-bat-ball football volleyball table-tennis-paddle-ball golf-ball-tee person-running person-swimming person-biking person-hiking dumbbell person-skiing
laptop desktop mobile-screen tablet-screen-button keyboard code terminal server database cloud wifi microchip robot gear wrench screwdriver-wrench hammer toolbox plug battery-full
briefcase chart-line chart-pie chart-column coins money-bill wallet credit-card piggy-bank sack-dollar handshake bullhorn envelope phone comments comment inbox paper-plane at
cart-shopping bag-shopping basket-shopping store tag tags gift box truck-fast barcode receipt
leaf seedling spa sun moon cloud-sun cloud-rain snowflake bolt wind water droplet temperature-half rainbow
heart-pulse stethoscope hospital pills syringe tooth user-doctor notes-medical kit-medical virus hand-holding-heart
paw dog cat dove crow fish-fins horse frog dragon bug spider hippo otter kiwi-bird
user users user-group child baby people-group person person-dress
heart star thumbs-up face-smile fire-flame-curved bell circle-info circle-question circle-check triangle-exclamation lock shield-halved key eye magnifying-glass link newspaper rss list-check clipboard folder file file-lines download upload share-nodes
couch bed bath shower toilet-paper kitchen-set sink lightbulb fan broom soap shirt shoe-prints glasses hat-cowboy gem ring crown
"""
REGULAR = """
heart star bookmark calendar clock comment comments envelope file file-lines folder image lightbulb bell face-smile thumbs-up user circle-check circle-question map newspaper gem sun moon snowflake
"""

def load(style, name):
    path = os.path.join(svg_root, style, name + '.svg')
    if not os.path.isfile(path):
        sys.exit('missing icon: %s/%s' % (style, name))
    svg = open(path, encoding='utf-8').read()
    viewbox = re.search(r'viewBox="([^"]+)"', svg).group(1)
    paths = re.findall(r'<path d="([^"]+)"', svg)
    if not paths:
        sys.exit('no path in %s' % path)
    return viewbox, ' '.join(paths)

entries = []
seen = set()
for prefix, style, names in (('fas', 'solid', SOLID), ('far', 'regular', REGULAR)):
    for name in names.split():
        key = '%s fa-%s' % (prefix, name)
        if key in seen:
            continue
        seen.add(key)
        viewbox, d = load(style, name)
        label = name.replace('-', ' ').capitalize()
        if 'far' == prefix:
            label += ' (outline)'
        entries.append((key, label, viewbox, d))

def php(s):
    return "'" + s.replace('\\', '\\\\').replace("'", "\\'") + "'"

lines = ["<?php", "", "/**",
 " * Icons for tags: a curated subset of Font Awesome Free 6.7.1, embedded as SVG.",
 " *",
 " * Font Awesome Free 6.7.1 by @fontawesome - https://fontawesome.com",
 " * License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License)",
 " * Copyright 2024 Fonticons, Inc.",
 " *",
 " * Keys use the Font Awesome class names, the same format PublishPress Authors uses.",
 " * Generated file: do not edit by hand. %d icons." % len(entries),
 " *",
 " * @package     Tag Groups",
 " */",
 "",
 "if (! defined('ABSPATH')) {",
 "    exit;",
 "}",
 "",
 "return array("]
for key, label, viewbox, d in entries:
    lines.append("    %s => array( %s, %s, %s )," % (php(key), php(label), php(viewbox), php(d)))
lines.append(");")
lines.append("")
open(out_path, 'w', encoding='utf-8').write('\n'.join(lines))
print(len(entries), 'icons written')
