<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionYear extends Model
{
    //
    use HasFactory;

    protected $guarded = [];
    
    public function classSubjectTerms()
    {
        return $this->hasMany(ClassSubjectTerm::class);
    }

    public function scores()
    {
        return $this->hasMany(StudentScore::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Determine the next session year using dates if present, otherwise by parsing the name.
     */
    public function next(): ?self
    {
        // Prefer explicit sequence if available
        if (!is_null($this->sequence)) {
            $candidate = self::whereNotNull('sequence')
                ->where('sequence', '>', $this->sequence)
                ->orderBy('sequence')
                ->first();
            if ($candidate) return $candidate;
        }

        // Fallback to date-based ordering if start_date is present
        if (!empty($this->start_date)) {
            $candidate = self::query()
                ->whereNotNull('start_date')
                ->where('start_date', '>', $this->start_date)
                ->orderBy('start_date')
                ->first();
            if ($candidate) return $candidate;
        }

        // Fallback to name parsing
        $nextName = self::computeNextNameFrom($this->name);
        if ($nextName) return self::where('name', $nextName)->first();

        return null;
    }

    /**
     * Compute next session year name from common patterns like:
     * - "2023/2024" -> "2024/2025"
     * - "2023-2024" -> "2024-2025"
     * - "2023" -> "2024"
     */
    public static function computeNextNameFrom(string $name): ?string
    {
        $trimmed = trim($name);

        // Slash separated (e.g., 2023/2024)
        if (preg_match('/^(\d{4})\s*\/\s*(\d{4})$/', $trimmed, $m)) {
            $y1 = (int) $m[1];
            $y2 = (int) $m[2];
            if ($y2 === $y1 + 1) {
                return ($y1 + 1) . '/' . ($y2 + 1);
            }
        }

        // Hyphen separated (e.g., 2023-2024)
        if (preg_match('/^(\d{4})\s*-\s*(\d{4})$/', $trimmed, $m)) {
            $y1 = (int) $m[1];
            $y2 = (int) $m[2];
            if ($y2 === $y1 + 1) {
                return ($y1 + 1) . '-' . ($y2 + 1);
            }
        }

        // Single year
        if (preg_match('/^(\d{4})$/', $trimmed, $m)) {
            $y = (int) $m[1];
            return (string) ($y + 1);
        }

        return null;
    }
}
