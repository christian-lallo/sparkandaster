# Spark + Aster - Development Notes

## Project Overview
Marketing agency website emphasizing human-centered, empathetic marketing approaches.

**Tech Stack:**
- Flight PHP micro-framework (routing and templating)
- Twig/HTML templating engine
- Laravel Mix for asset compilation
- Fullpage.js for section scrolling (GPLV3 licensed)
- Mapbox GL JS for interactive maps
- Waypoints for scroll-triggered animations
- ParticleNetwork for animated particle effects
- Custom SVG animations (Stella logo transformations)

---

## Development Timeline

### Early Development (Pre-October 2025)
Initial setup of Flight framework, Twig templating, and basic site structure. Implemented click-triggered Stella transformations and Mapbox integration.

### October 2025 - Major Feature Release
Significant redesign and feature additions completed October 1-5, 2025.

### January 2026 - Current
Experimental about page animation work (uncommitted).

---

## Feature Documentation

### 1. Stella Logo Transformations (Homepage)

**Evolution:**
- **Phase 1 (Early):** Click-triggered transformations
  - User clicked brand value icons
  - Stella SVG transformed to represent that value
- **Phase 2 (Oct 4, 2025):** Scroll-triggered transformations
  - Converted to scroll-based activation
  - Each fullpage.js section represents a company value
  - Stella transforms automatically as user scrolls through sections
  - Smooth 0.7s transitions synchronized with page scroll timing

**Current Implementation:**
- **Location:** `views/index.html` (homepage sections)
- **JavaScript:** `src/js/scripts.js`
- **Values:** Humanity, Empathy, Love, Connection ~~, Inspiration~~ (removed Oct 5)
- **How it works:**
  - Homepage restructured into fullpage.js sections
  - Fixed Stella container stays centered during scroll
  - CSS classes added to `.node-stella` elements trigger transformations
  - Normalized SVG center points for consistent transformations
  - Transformations clear when leaving value sections

**Files:**
- SVG assets: `dist/img/stella/`
- Styles: `src/scss/pages/_index.scss`

---

### 2. Navigation System

**Implemented:** October 4, 2025

**Features:**
- Hamburger button toggle
- Scroll overlay menu with links: Home, About, Work
- Current page highlighting (magenta color)
- JavaScript null checks prevent errors on subpages

**Location:** `views/layout.html`

---

### 3. Mapbox Integration

**Location:** `views/index.html` - "Ready to be Understood?" section (formerly)
**JavaScript:** `src/js/scripts.js`

**Features:**
- Interactive map displayed on iPhone mockup
- Markers for Baltimore and Detroit locations
- Predefined "chapters" (camera positions):
  - Detroit, Baltimore, City Garage, Union, Katie
- Click on iPhone triggers fly-to animation to City Garage
- Custom Mapbox style: `mapbox://styles/spark-and-aster/cjkggw2lq2wkk2suv774rd9td`

**TODO:** Move Mapbox access token to environment variable for production

---

### 4. ParticleNetwork Animations

**Implemented:** October 4, 2025

**Features:**
- Animated particle effects in page headers
- Floating/rotating icon particles on About page
- Creates dynamic, engaging visual effects

---

### 5. Client Work Pages

**Implemented:** October 4, 2025

**Portfolio Case Studies Created:**
1. **Goldwell** (`views/work/goldwell.html`)
   - Dual-video crossfade (reconstruct/repower themes)
   - Color transitions between videos
   - Brand color: `#cc9b9c`

2. **X-Fuel** (`views/work/x-fuel.html`)
   - Sustainable fuel brand
   - Green accent color

3. **ETS Racing Fuels** (`views/work/ets.html`)
   - High-performance racing
   - Magenta accent

4. **Paretta Autosport** (`views/work/paretta-autosport.html`)
   - Female-first racing team
   - Robin's egg blue accent

5. **MoneyLion** (`views/work/moneylion.html`)
   - #womenwhoroar campaign
   - Magenta accent

6. **Modera Wealth** (`views/work/modera.html`)
   - Human-centered wealth management
   - Blue accent

7. **MICA** (`views/work/mica.html`)
   - Art education campaign
   - Gold accent

**Additional Portfolio (from earlier work):**
- Grace (color: `#7222AF`)
- Miami (color: `#F47403`)

**Technical Implementation:**
- CSS scroll-snap for fullpage snap behavior (no JS complexity)
- Responsive hero layout: side-by-side desktop, stacked mobile
- Video crossfade system (12s intervals) for multi-video pages
- Per-client color theming via CSS modifiers
- 16:9 aspect ratio video frames with mobile scaling
- Background video with blur, zoom, and optional color tints
- Typography: Neue Haas Grotesk at responsive scales (4.5em-10em)

---

### 6. Work Index Showcase

**Implemented:** October 5, 2025
**Location:** `views/work/index.html`

**Features:**
- Cinematic fullpage.js experience combining all hero sections
- Smooth-scrolling showcase with snap behavior
- Navigation dots for quick section jumping
- "View Case Study →" CTAs with deep links to `#case-study-content`
- Floating scroll indicator with animated arrow
- Safari GPU compositing fixes for text visibility
- Goldwell video crossfade initialized in `afterLoad` callback

**Technical:**
- GPU acceleration (translateZ, backface-visibility, will-change)
- Case study anchors skip hero sections on deep links
- Scroll indicator with CSS bounce animation

---

### 7. Routing System

**Implemented:** October 1, 2025

**Features:**
- Enhanced Flight PHP routing to support both flat views and folder structures
- Smart view resolution in `PageController.php`
- Clean URLs via `.htaccess` configuration
- 404 error page (`views/errors/404.html`)
- Case pager partial for work navigation (`views/partials/_case_pager.twig`)

**Files:**
- `app/Controllers/PageController.php` (lines 1-115+)
- `routes.php`
- `.htaccess`

---

### 8. Homepage Sections & Fullpage.js Configuration

**Current Sections/Anchors:**
1. `video` - Video background hero
2. `we-understand` - Understanding section
3. `what-we-do` - Human approach section
4. `humanity` - Stella transformation: Humanity value
5. `empathy` - Stella transformation: Empathy value
6. `love` - Stella transformation: Love value
7. `connection` - Stella transformation: Connection value
8. ~~`inspiration`~~ - Removed October 5, 2025

**Custom Behavior:**
- `afterLoad`: Triggers animations for waypoint elements in new section
- `onLeave`: Removes animations and resets Stella classes when leaving sections

---

## Current Status (January 30, 2026)

### Uncommitted Experimental Work

**About Page Mission Statement Animation**
- **Route:** `/about-mission-experiment` added to `routes.php`
- **Template:** `views/about-mission-experiment.html` (new file)
- **Styles:** `src/scss/pages/_about.scss` (progressive reveal animation added)

**Animation Concept:**
- Mission statement reveals progressively line by line
- Staggered timing: line 1 (immediate), line 2 (+2s), line 3 (+4s), line 4 (+6s)
- Each line fades in and slides up (0.8s ease-out)

**Status:** Work in progress - HTML references classes for typewriter/rotating effects but JavaScript not yet implemented. Holding for decision on whether to proceed with this approach.

---

## Known Issues & TODOs

### Bugs to Fix
1. ~~**Typo in HTML:** `views/index.html:103` - `data-name="connectiom"` should be `data-name="connection"`~~ (May have been fixed during scroll conversion)
2. **Verify Stella transformations:** Ensure all active values (Humanity, Empathy, Love, Connection) have proper scroll triggers

### Current Decisions Needed
1. **About page animation:** Decide whether to complete/commit experimental mission statement animation or try different approach

### Enhancement Opportunities

#### Stella Transformations
- [ ] Add smooth CSS transitions between transformations (may already be implemented)
- [ ] Add visual feedback for scroll progress
- [ ] Review transformation timing synchronization

#### Mapbox Integration
- [ ] Add more interactive features (click markers for info, etc.)
- [ ] Consider adding route animations between locations
- [ ] Move Mapbox access token to environment variable
- [ ] Test responsiveness of map on phone mockup across devices

#### Work Pages
- [ ] Ensure all case study content is complete and reviewed
- [ ] Test video performance across browsers
- [ ] Verify mobile responsiveness for all hero sections

#### Performance
- [ ] Optimize SVG files in `dist/img/stella/` directory
- [ ] Consider lazy-loading video backgrounds
- [ ] Review and optimize CSS bundle size
- [ ] Test fullpage.js performance on slower devices

#### Content
- [ ] Review all copy for typos and consistency
- [ ] Ensure portfolio pieces are complete and accurate
- [ ] Verify all client information and credits

---

## File Structure Reference

```
├── app/
│   └── Controllers/
│       └── PageController.php     # View resolution, routing logic
├── src/
│   ├── js/
│   │   └── scripts.js             # Main JS (Mapbox, Stella, animations, fullpage.js)
│   ├── scss/
│   │   └── pages/
│   │       ├── _index.scss        # Homepage styles including Stella
│   │       └── _about.scss        # About page + experimental animation
│   └── img/
│       └── stella/                # SVG files for transformations
├── views/
│   ├── index.html                 # Main landing page (Stella scroll animations)
│   ├── about.html                 # About page (standard)
│   ├── about-mission-experiment.html  # Experimental about page (uncommitted)
│   ├── layout.html                # Base template with navigation
│   ├── work/
│   │   ├── index.html             # Work showcase (fullpage.js)
│   │   ├── goldwell.html          # Client case studies...
│   │   ├── x-fuel.html
│   │   ├── ets.html
│   │   ├── paretta-autosport.html
│   │   ├── moneylion.html
│   │   ├── modera.html
│   │   ├── mica.html
│   │   ├── grace.html
│   │   └── miami.html
│   ├── partials/                  # Reusable components
│   │   └── _case_pager.twig       # Work navigation
│   └── errors/
│       └── 404.html               # 404 error page
├── routes.php                     # Application routing
├── .htaccess                      # Clean URLs, rewrites
├── dist/                          # Compiled assets (CSS, JS, images)
├── webpack.mix.js                 # Laravel Mix configuration
└── DEV-NOTES.md                   # This file
```

---

## Development Commands

```bash
# Watch for changes and auto-compile
npm run watch

# Build for development
npm run dev

# Build for production
npm run production

# View git history
git log --oneline

# View uncommitted changes
git status
git diff
```

---

## Git Workflow

**Current Branch:** `main`

**Important Commits:**
- `c21c396` (Oct 5, 2025) - feat(work): create immersive fullpage.js work index
- `189b083` (Oct 4, 2025) - feat(work): add hero sections for new client work pages
- `eeac947` (Oct 4, 2025) - feat: convert Stella animations to scroll-based and add navigation
- `18335f3` (Oct 1, 2025) - feat(work): resolve views from Flight config; support flat and folder cases
- `0c1731d` (Oct 1, 2025) - Initial Commit

**Note:** All commits marked with `Co-Authored-By: Claude <noreply@anthropic.com>` indicate work completed with Claude Code assistance.

---

## Design System

### Brand Values & Their Meanings
- **Humanity:** Human joy and connection
- **Empathy:** Understanding others' perspectives
- **Love:** Care and compassion in work
- **Connection:** Building relationships
- ~~**Inspiration:** Creative spark~~ (removed from homepage)
- **Care:** Thoughtfulness and attention
- **Guidance:** Direction and mentorship
- **Artistry:** Craft and creativity

### Color Palette
**Brand Colors:**
- Magenta (primary accent)
- White
- Dark grey
- Black

**Portfolio Colors:**
- Goldwell: `#cc9b9c`
- Grace: `#7222AF`
- Miami: `#F47403`
- X-Fuel: Green
- Paretta Autosport: Robin's egg blue
- MoneyLion: Magenta
- Modera Wealth: Blue
- MICA: Gold

### Typography
- Primary: Neue Haas Grotesk
- Hero sizes: 4.5em - 10em (responsive)

---

## External Dependencies & Licenses

### JavaScript Libraries
- **Fullpage.js** - GPLV3 license (commercial license key in use)
- **Mapbox GL JS** - Proprietary (requires access token)
- **Waypoints** - MIT license
- **ParticleNetwork** - Check license

### Build Tools
- **Laravel Mix** - MIT license
- **Webpack** - MIT license

---

## Next Session Priorities

### If Continuing Experimental Work
1. Complete or discard the mission statement animation experiment
2. Decide on typewriter vs. progressive reveal vs. rotating text approach
3. Implement JavaScript if proceeding with animation

### General Priorities
1. Review and test all client work pages across browsers
2. Verify Stella scroll transformations work smoothly
3. Test mobile responsiveness across all pages
4. Consider performance optimizations for video backgrounds
5. Move sensitive tokens (Mapbox) to environment variables
6. Review all content for accuracy and polish

### Nice-to-Have
1. Add loading states for videos
2. Consider accessibility improvements (keyboard navigation, ARIA labels)
3. Add meta tags and OpenGraph data for social sharing
4. Consider adding Google Analytics or similar tracking

---

## Technical Notes

### Video Implementation
- Background videos use blur, zoom, and color tint effects
- Crossfade system runs on 12-second intervals
- 16:9 aspect ratio maintained across viewports
- Consider lazy-loading for performance

### SVG Transformations
- All Stella SVGs normalized to consistent center points
- CSS transforms applied via class modifiers
- Transitions: 0.7s timing synchronized with fullpage.js scroll

### Fullpage.js Configuration
- Scroll snap behavior
- GPU acceleration for smooth transitions
- Custom callbacks: `afterLoad`, `onLeave`
- Navigation dots enabled
- Deep linking with anchors

---

## Troubleshooting

### Common Issues
1. **Stella not transforming:** Check that fullpage.js sections have correct anchor names matching value names
2. **Videos not loading:** Check file paths and ensure video files are in `dist/` directory
3. **Navigation not highlighting:** Verify current page detection in JavaScript
4. **Map not loading:** Check Mapbox access token and network connection

### Browser Compatibility
- Safari: GPU compositing fixes applied for text visibility
- Mobile: Touch events and responsive scaling tested
- Consider testing on older browsers if needed

---

## Resources & References

### Documentation
- [Flight PHP Framework](https://flightphp.com/)
- [Fullpage.js Docs](https://github.com/alvarotrigo/fullPage.js)
- [Mapbox GL JS Docs](https://docs.mapbox.com/mapbox-gl-js/)
- [Laravel Mix Docs](https://laravel-mix.com/)

### Custom Mapbox Style
- Style ID: `mapbox://styles/spark-and-aster/cjkggw2lq2wkk2suv774rd9td`

---

*Last Updated: 2026-01-30*
*Current Branch: main*
*Status: Stable (with uncommitted experimental work)*

---

## Session Continuity Notes

**For future Claude Code sessions:**
- This project had major work done in October 2025 (see git history)
- Previous conversation lost, reconstructed from git commits
- Current uncommitted work is experimental and pending decision
- Review this file first to understand project context and history
- Check git log for detailed commit history
- Use `git diff` to see current uncommitted changes
