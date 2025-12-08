# Changelog

All notable changes to `filament-icon-picker` will be documented in this file.

## [Unreleased]

## [1.4.4] - 2025-12-08

### Fixed
- Icon Picker modal now works correctly inside Filament action modals (CreateAction, EditAction, etc.)
- Fixed z-index conflict when Icon Picker is used inside another modal
- Fixed `inert` attribute conflict with Filament's `x-trap` directive
- Modal now properly handles focus and keyboard navigation inside nested modals
- Escape key now correctly closes only the Icon Picker modal without affecting parent modals

## [1.4.3] - 2025-12-07

### Fixed
- Search input now accepts text input correctly (changed from `type="search"` to `type="text"`)
- Fixed Alpine.js `x-if` template issue preventing input interaction
- Optimized API response to include SVG data, reducing HTTP requests per icon

## [1.4.1] - 2025-12-06

### Added
- `icon()` method on `IconPickerColumn` and `IconPickerEntry` for fixed/static icons without database
- Support for closures in `icon()` method for dynamic icons based on record

### Changed
- Overrode `getState()` method to support fixed icons alongside database values

## [1.4.0] - 2025-12-06

### Added
- `HasIconAnimation` trait with animation support (spin, pulse)
- `HasIconColor` trait with semantic color support (primary, success, danger, warning, info)
- `HasIconSize` trait with size shortcuts (extraSmall, small, medium, large, extraLarge)
- Animation methods: `animation()`, `spin()`, `pulse()`
- Color methods: `color()`, `primary()`, `secondary()`, `success()`, `warning()`, `danger()`, `info()`
- Size methods: `size()`, `extraSmall()`, `small()`, `medium()`, `large()`, `extraLarge()`
- Support for CSS color values (hex, rgb, named colors)
- Support for closure-based color and animation (dynamic values based on record)
- Support for custom Tailwind color classes
- `showLabel()` method on `IconPickerColumn` to display icon name
- `showIconName()` method on `IconPickerEntry` to toggle icon name visibility
- Comprehensive unit tests for all components (69 tests, 147 assertions)
- PHPUnit configuration for package testing
- GitHub Actions CI/CD workflow for automated testing
- Laravel Pint for code style formatting

### Changed
- Refactored size, color, and animation logic into reusable traits
- Updated views to support dynamic colors and animations
- Added `2xl` size option (w-12 h-12)

## [1.3.0] - 2025-12-06

### Added
- `filament-icon-picker:generate-enums` command to generate PHP Enums for icon sets
- Generated enums in `Wallacemartinss\FilamentIconPicker\Enums` namespace
- Enums implement `Filament\Support\Contracts\ScalableIcon` interface
- Support for using icons as `BackedEnum` in navigation, actions, and pages
- `Icon` helper class for dynamic icon creation without enum generation
- `IconEnums` facade for quick access to generated enums
- `--all` option to generate enums for all icon sets
- `--path` option to customize enum output directory
- `--with-facade` and `--no-facade` options for facade generation control
- `--no-enums` and `--no-facade` options in install command
- Enum methods: `options()`, `search()`, `getIconForSize()`, `toString()`
- PHP reserved words validation (class, array, etc.) for enum case names

### Changed
- Enums are now automatically generated after package installation (no prompts)
- Enums namespace changed from `App\Enums\Icons` to `Wallacemartinss\FilamentIconPicker\Enums`
- Updated README with comprehensive Icon Enums documentation
- Added usage examples for navigation icons, actions, and dynamic icons

## [1.2.0] - 2025-12-06

### Added
- Interactive `filament-icon-picker:install-icons` command for easy icon package installation
- `--list` option to show available and installed packages
- `--all` option to install all icon packages at once
- `--no-update-config` option to skip config file updates
- Automatic config update with selected icon sets after installation
- Support for 10 icon packages: Heroicons, FontAwesome, Phosphor, Material Design, Tabler, Lucide, Remix, Bootstrap, Octicons, Feather

### Changed
- Improved README with comprehensive installation guide
- Better documentation for set names vs package names

### Fixed
- IconPickerField now respects `allowed_sets` from config file
- Correct set names: `heroicons`, `phosphor-icons`, `google-material-design-icons`
- Dropdown filter now shows only configured icon sets

## [1.1.5] - 2025-12-05

### Fixed
- Correct icon set names in configuration examples
- Better handling of FontAwesome subsets (solid, regular, brands)

## [1.1.4] - 2025-12-05

### Changed
- Renamed components for better clarity:
  - `IconPicker` → `IconPickerField`
  - `IconColumn` → `IconPickerColumn`
  - `IconEntry` → `IconPickerEntry`

## [1.1.3] - 2025-12-05

### Added
- Provider dropdown filter to filter icons by their set
- `showSetFilter()` method to toggle the filter visibility

### Changed
- Improved modal UI with better organization

## [1.1.2] - 2025-12-05

### Added
- Infinite scroll for better performance with large icon sets
- `icons_per_page` config option

### Changed
- Icons are now loaded in batches for smoother scrolling

## [1.1.1] - 2025-12-05

### Added
- Modal interface for icon selection
- Real-time search filtering
- Grid layout with configurable columns

### Changed
- Improved Alpine.js integration
- Better responsive design

## [1.1.0] - 2025-12-05

### Added
- Support for multiple icon sets
- `allowedSets()` method to restrict available icons
- Configuration file with customizable options

## [1.0.0] - 2025-12-04

### Added
- Initial release
- `IconPickerField` form component
- `IconPickerColumn` table column
- `IconPickerEntry` infolist entry
- Support for blade-ui-kit/blade-icons
- Preview of selected icon
- 14 language translations (AR, DE, EN, ES, FA, FR, HI, IT, JA, KO, NL, PT_BR, RU, ZH_CN)
- Form field component with modal icon picker
- Table column component for displaying icons
- Infolist entry component for read-only display
- Support for all blade-icons packages
- Search/filter functionality
- Set filtering
- Lazy loading with infinite scroll
- Dark mode support
- Configurable allowed icon sets
- Icon caching for performance
