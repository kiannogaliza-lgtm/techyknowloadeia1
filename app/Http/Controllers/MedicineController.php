<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public static function categories(): array
    {
        return [
            'Analgesic / Antipyretic',
            'Antibiotic',
            'Antihistamine',
            'Cough and Cold',
            'Herbal Medicine',
            'Herbal Medicine / Diuretic',
            'Antidiarrheal / Rehydration',
            'Rehydration',
            'Vitamins and Supplements',
            'Antacid',
            'Acid Reducer',
            'Antihypertensive',
            'Antidiabetic',
            'Deworming',
            'Topical / Skin Medicine',
            'First Aid',
            'Maternal and Child Health',
            'Respiratory Medicine',
            // Keep previous legacy options for backwards compatibility
            'Pain Reliever',
            'Supplement',
            'Cough Medicine',
            'Topical',
        ];
    }

    /**
     * Store a newly created medicine.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', self::categories())],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'in:tablets,capsules,bottles,tubes,pieces'],
            'dosage' => ['nullable', 'string', 'max:255'],
            'dosage_times_per_day' => ['nullable', 'integer', 'min:1', 'max:24'],
            'dosage_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'min_age' => ['nullable', 'integer', 'min:0', 'max:130'],
            'max_age' => ['nullable', 'integer', 'min:0', 'max:130', 'gte:min_age'],
            'reorder_level' => ['required', 'integer', 'min:1'],
            'request_limit' => ['required', 'integer', 'min:1'],
            'release_date' => ['nullable', 'date'],
            'expiration_date' => ['nullable', 'date', 'after_or_equal:release_date'],
        ]);

        // Auto-generate human readable dosage string if empty but frequency fields provided
        if (empty($validated['dosage']) && !empty($validated['dosage_times_per_day'])) {
            $times = $validated['dosage_times_per_day'];
            $days = $validated['dosage_days'] ?? 1;
            $validated['dosage'] = "{$times} time(s) a day for {$days} day(s)";
        }

        Medicine::create($validated);

        return redirect()->route('nurse.inventory')->with('success', 'Medicine added successfully.');
    }

    /**
     * Update the specified medicine.
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'in:' . implode(',', self::categories())],
            'stock' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'string', 'in:tablets,capsules,bottles,tubes,pieces'],
            'dosage' => ['nullable', 'string', 'max:255'],
            'dosage_times_per_day' => ['nullable', 'integer', 'min:1', 'max:24'],
            'dosage_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'min_age' => ['nullable', 'integer', 'min:0', 'max:130'],
            'max_age' => ['nullable', 'integer', 'min:0', 'max:130', 'gte:min_age'],
            'reorder_level' => ['required', 'integer', 'min:1'],
            'request_limit' => ['required', 'integer', 'min:1'],
            'release_date' => ['nullable', 'date'],
            'expiration_date' => ['nullable', 'date', 'after_or_equal:release_date'],
        ]);

        if (empty($validated['dosage']) && !empty($validated['dosage_times_per_day'])) {
            $times = $validated['dosage_times_per_day'];
            $days = $validated['dosage_days'] ?? 1;
            $validated['dosage'] = "{$times} time(s) a day for {$days} day(s)";
        }

        $medicine->update($validated);

        return redirect()->route('nurse.inventory')->with('success', 'Medicine updated successfully.');
    }

    /**
     * Remove the specified medicine.
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        return redirect()->route('nurse.inventory')->with('success', 'Medicine deleted successfully.');
    }
}
