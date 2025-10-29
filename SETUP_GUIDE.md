# Quick Setup Guide - PGA Play-Style Frontend

## 🚀 Quick Start (5 minutes)

### Step 1: Add Google Maps API Key

Add these two lines to your `.env` file:

```env
GOOGLE_MAPS_API_KEY=your_google_maps_api_key_here
VITE_GOOGLE_MAPS_API_KEY=your_google_maps_api_key_here
```

**Don't have a Google Maps API Key?**
1. Go to: https://console.cloud.google.com/
2. Create a new project or select existing
3. Enable these APIs:
   - Maps JavaScript API
   - Geocoding API
4. Create credentials → API Key
5. Copy the key to your `.env` file

**Note:** The map view won't work without this key, but the list view and all other features will work fine.

### Step 2: Seed Sample Data

Run this command to populate your database with 5 sample cricket academies across the UK:

```bash
php artisan db:seed --class=CompaniesSeeder
```

This creates:
- 5 cricket academies (London, Birmingham, Manchester, Leeds, Bristol)
- 7 coaches with full profiles
- Real UK addresses with coordinates
- Variety of specialties, facilities, and technologies

### Step 3: Test the Features

1. **Visit the homepage:**
   ```
   http://localhost:8000
   ```

2. **Try "Use my location":**
   - Click the "Use my location" button
   - Allow location access in your browser
   - See coaches sorted by distance from you

3. **Search by location:**
   - Try: "London", "Birmingham", "NW8 8QN"
   - Adjust the distance slider in the sidebar

4. **Apply filters:**
   - Check "Batting" under Specialties
   - Check "In-person" under Lesson Types
   - See results update automatically

5. **Toggle Map View:**
   - Click "Map" button in results header
   - See coaches plotted on map with distance radius
   - Click markers to view coach details

## 🎯 Feature Checklist

After setup, you should have:

- [x] Homepage with geolocation button
- [x] Search bar with location and keyword inputs
- [x] Filter sidebar with 4 categories
- [x] Distance slider (5-50 miles)
- [x] List view with coach cards showing distance
- [x] Map view with interactive markers
- [x] Sample data with 5 academies and 7 coaches
- [x] Responsive design on mobile and desktop

## 🧪 Test Scenarios

### Scenario 1: Find Nearby Coaches
1. Go to homepage
2. Click "Use my location"
3. Allow browser location access
4. See results sorted by distance
5. Verify distance badges on cards

### Scenario 2: Search by Specialty
1. Go to /companies
2. Check "Bowling" under Specialties
3. See only coaches with bowling specialty
4. Note: Lord's Cricket Academy should appear (James Anderson)

### Scenario 3: Location-Based Search
1. Search for "London"
2. Set distance to 10 miles
3. See Lord's Cricket Academy
4. Increase to 50 miles
5. See more results from surrounding areas

### Scenario 4: Map View
1. Search for "Birmingham"
2. Click "Map" toggle
3. See Birmingham Cricket Centre marker
4. Click marker to see info window
5. Adjust distance slider and watch radius circle change

## 📁 Key Files Reference

### If you need to modify...

**Search filters:**
- `resources/js/Components/Sites/Public/SearchFilters.vue`

**Coach card design:**
- `resources/js/Components/Sites/Public/CoachCard.vue`

**Map functionality:**
- `resources/js/Components/Sites/Public/SearchMap.vue`

**Search results page:**
- `resources/js/Pages/Sites/Public/Companies/Index.vue`

**Backend search logic:**
- `app/Http/Controllers/Sites/Public/CompanyController.php`

**Geolocation service:**
- `app/Services/GeolocationService.php`

**Sample data:**
- `database/seeders/CompaniesSeeder.php`

## 🎨 Customization

### Change Color Scheme

The current theme uses green (cricket field colors). To change:

**Find:** `green-600`, `green-700`, `green-500`, etc.
**Replace with:** `blue-600`, `purple-600`, or any Tailwind color

**Files to update:**
- `resources/js/Pages/Sites/Public/Companies/Index.vue`
- `resources/js/Pages/Sites/Public/Home/Master.vue`
- `resources/js/Components/Sites/Public/CoachCard.vue`
- `resources/js/Components/Sites/Public/SearchFilters.vue`

### Add New Specialty

1. **Update filter options** in `CompanyController@index`:
```php
'filterOptions' => [
    'specialties' => ['batting', 'bowling', 'your-new-specialty'],
    // ...
],
```

2. **Update sample data** in `CompaniesSeeder`:
```php
'specialties' => ['batting', 'your-new-specialty'],
```

3. **Re-seed**:
```bash
php artisan migrate:fresh
php artisan db:seed --class=CompaniesSeeder
```

### Change Distance Units (Miles to Kilometers)

1. **GeolocationService.php:**
```php
// Change
$earthRadiusMiles = 3959;
// To
$earthRadiusKm = 6371;

// Change distance conversion
$radiusMeters = $radiusMiles * 1609.34;
// To
$radiusMeters = $radiusKm * 1000;
```

2. **Update UI labels** to show "km" instead of "mi"

## 🔧 Troubleshooting

### Map not showing?
- Check Google Maps API key in `.env`
- Verify both `GOOGLE_MAPS_API_KEY` and `VITE_GOOGLE_MAPS_API_KEY` are set
- Run `npm run build` after changing `.env`
- Check browser console for errors

### No results showing?
- Verify seeder ran: `php artisan db:seed --class=CompaniesSeeder`
- Check database: 5 companies should exist
- Try searching without location first
- Check Laravel logs: `storage/logs/laravel.log`

### Filters not working?
- Clear browser cache
- Run `npm run build`
- Check network tab for API responses
- Verify filter values match database data

### Location button not working?
- Allow location access in browser
- Check browser console for errors
- Try HTTPS (geolocation requires secure context)
- Use Chrome/Firefox (best browser support)

### Build errors?
```bash
# Clean and rebuild
rm -rf node_modules package-lock.json
npm install
npm run build
```

## 🚨 Common Issues

### Issue: "Class 'App\Services\GeolocationService' not found"
**Solution:** Run `composer dump-autoload`

### Issue: "VITE_GOOGLE_MAPS_API_KEY is undefined"
**Solution:**
1. Restart Vite dev server: `npm run dev`
2. Rebuild: `npm run build`

### Issue: "No companies near me"
**Solution:** Increase distance slider to 50 miles (sample data is UK-based)

### Issue: Map markers not appearing
**Solution:** Check that companies have latitude/longitude in database

## 📊 Performance Tips

1. **Enable caching for geocoding:**
```php
// In GeolocationService, cache results
Cache::remember("geocode_{$address}", 3600, function() {
    // geocoding logic
});
```

2. **Add database indexes:**
```bash
php artisan make:migration add_indexes_to_companies_table
```
```php
$table->index(['latitude', 'longitude']);
$table->index('is_active');
```

3. **Use eager loading:**
Already implemented in `CompanyController@index` with `->with(['staff'])`

## 🎓 Learning Resources

- **Google Maps API:** https://developers.google.com/maps/documentation
- **Vue 3 Composition API:** https://vuejs.org/guide/extras/composition-api-faq.html
- **Inertia.js:** https://inertiajs.com/
- **Tailwind CSS:** https://tailwindcss.com/docs
- **Laravel 11:** https://laravel.com/docs/11.x

## ✅ Next Steps

After completing setup:

1. **Add your own data:**
   - Create companies through admin panel
   - Add staff with specialties and facilities
   - Set latitude/longitude for location-based search

2. **Customize filters:**
   - Add/remove specialties
   - Modify distance options
   - Add price filtering

3. **Enhance map:**
   - Custom marker icons
   - Cluster markers for many results
   - Street View integration

4. **Improve SEO:**
   - Add meta tags
   - Create sitemap
   - Implement structured data

5. **Add analytics:**
   - Track popular searches
   - Monitor filter usage
   - Analyze booking conversions

---

**Need Help?** Check `IMPLEMENTATION_SUMMARY.md` for detailed technical documentation.
