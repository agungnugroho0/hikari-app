<?php

namespace App\Services;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class StudentNaturalLanguageSearch
{
    protected const CORE_FIELDS = [
        'nis' => ['column' => 'nis', 'operators' => ['=', 'contains']],
        'status' => ['column' => 'status', 'operators' => ['=', 'in']],
    ];

    protected const DETAIL_FIELDS = [
        'nama' => ['column' => 'nama_lengkap', 'operators' => ['contains']],
        'jenis_kelamin' => ['column' => 'gender', 'operators' => ['=']],
        'alamat' => ['column' => 'alamat', 'operators' => ['contains']],
        'desa' => ['column' => 'alamat', 'operators' => ['contains']],
        'kecamatan' => ['column' => 'alamat', 'operators' => ['contains']],
        'kabupaten' => ['column' => 'alamat', 'operators' => ['contains']],
        'provinsi' => ['column' => 'alamat', 'operators' => ['contains']],
        'tanggal_lahir' => ['column' => 'tgl_lahir', 'operators' => ['=', '>', '>=', '<', '<=']],
        'umur' => ['column' => 'tgl_lahir', 'operators' => ['=', '>', '>=', '<', '<=', 'between'], 'virtual' => true],
        'tinggi_badan' => ['column' => 'tinggi_badan', 'operators' => ['=', '>', '>=', '<', '<=', 'between'], 'optional' => true],
        'berat_badan' => ['column' => 'berat_badan', 'operators' => ['=', '>', '>=', '<', '<=', 'between'], 'optional' => true],
    ];

    protected const CLASS_FIELDS = [
        'kelas' => ['column' => 'nama_kelas', 'operators' => ['contains', '=']],
        'id_kelas' => ['column' => 'id_kelas', 'operators' => ['=']],
    ];

    protected const STOP_WORDS = [
        'cari', 'carikan', 'siswa', 'murid', 'santri', 'yang', 'dengan', 'berdasarkan',
        'data', 'tolong', 'dong', 'please', 'bernama', 'nama', 'rumah', 'rumahnya',
        'alamat', 'alamatnya', 'domisili',
    ];

    public function parse(string $input): array
    {
        $original = trim($input);
        $normalized = $this->normalize($original);

        if ($normalized === '') {
            return $this->emptyResult();
        }

        $filters = [];
        $notices = [];
        $working = $this->correctKnownTypos($normalized);

        $this->extractStatus($working, $filters);
        $this->extractGender($working, $filters);
        $this->extractAge($working, $filters);
        $this->extractBodyMeasurement($working, $filters, 'tinggi_badan', 'tinggi|tb|tinggi badan');
        $this->extractBodyMeasurement($working, $filters, 'berat_badan', 'berat|bb|berat badan');
        $this->extractAddressParts($working, $filters);
        $this->extractClass($working, $filters);

        $name = $this->extractNameKeyword($working, $filters);
        if ($name !== null) {
            $filters[] = ['field' => 'nama', 'operator' => 'contains', 'value' => $name];
        }

        $filters = $this->validateFilters($filters, $notices);

        if ($filters === []) {
            return [
                'intent' => 'search_students',
                'mode' => 'text',
                'keyword' => $original,
                'filters' => [],
                'summary' => null,
                'notices' => [],
            ];
        }

        return [
            'intent' => 'search_students',
            'mode' => 'structured',
            'keyword' => null,
            'filters' => $filters,
            'summary' => $this->summarize($filters),
            'notices' => $notices,
        ];
    }

    public function apply(Builder $query, array $parsed): Builder
    {
        foreach ($parsed['filters'] ?? [] as $filter) {
            $field = $filter['field'];
            $operator = $filter['operator'];
            $value = $filter['value'];

            if (isset(self::CORE_FIELDS[$field])) {
                $this->applyWhere($query, self::CORE_FIELDS[$field]['column'], $operator, $value);
                continue;
            }

            if (isset(self::DETAIL_FIELDS[$field])) {
                $meta = self::DETAIL_FIELDS[$field];
                $query->whereHas('detail', function (Builder $detailQuery) use ($meta, $operator, $value) {
                    if (($meta['virtual'] ?? false) && $meta['column'] === 'tgl_lahir') {
                        $this->applyAgeWhere($detailQuery, $operator, $value);
                    } else {
                        $this->applyWhere($detailQuery, $meta['column'], $operator, $value);
                    }
                });
                continue;
            }

            if (isset(self::CLASS_FIELDS[$field])) {
                $meta = self::CLASS_FIELDS[$field];
                $query->whereHas('kelas', function (Builder $classQuery) use ($meta, $operator, $value) {
                    $this->applyWhere($classQuery, $meta['column'], $operator, $value);
                });
            }
        }

        return $query;
    }

    public function hasStatusFilter(array $parsed): bool
    {
        return collect($parsed['filters'] ?? [])->contains(fn (array $filter) => $filter['field'] === 'status');
    }

    public function applyTextSearch(Builder $query, string $keyword): Builder
    {
        $keyword = trim($keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(function (Builder $searchQuery) use ($keyword) {
            $searchQuery
                ->where('nis', 'like', "%{$keyword}%")
                ->orWhereHas('detail', function (Builder $detailQuery) use ($keyword) {
                    $detailQuery
                        ->where('nama_lengkap', 'like', "%{$keyword}%")
                        ->orWhere('panggilan', 'like', "%{$keyword}%")
                        ->orWhere('alamat', 'like', "%{$keyword}%");
                })
                ->orWhereHas('kelas', function (Builder $classQuery) use ($keyword) {
                    $classQuery->where('nama_kelas', 'like', "%{$keyword}%");
                });
        });
    }

    protected function applyWhere(Builder $query, string $column, string $operator, mixed $value): void
    {
        match ($operator) {
            'contains' => $query->where($column, 'like', "%{$value}%"),
            'between' => $query->whereBetween($column, [(int) $value[0], (int) $value[1]]),
            'in' => $query->whereIn($column, (array) $value),
            default => $query->where($column, $operator, $value),
        };
    }

    protected function validateFilters(array $filters, array &$notices): array
    {
        return collect($filters)
            ->filter(function (array $filter) use (&$notices) {
                $field = $filter['field'] ?? null;
                $operator = $filter['operator'] ?? null;

                $meta = self::CORE_FIELDS[$field]
                    ?? self::DETAIL_FIELDS[$field]
                    ?? self::CLASS_FIELDS[$field]
                    ?? null;

                if ($meta === null || ! in_array($operator, $meta['operators'], true)) {
                    return false;
                }

                if (($meta['optional'] ?? false) && ! Schema::hasColumn('detail_siswa', $meta['column'])) {
                    $notices[] = "Filter {$this->label($field)} belum tersedia di struktur database.";
                    return false;
                }

                if ($field === 'umur') {
                    return $this->validAgeValue($operator, $filter['value'] ?? null);
                }

                return filled($filter['value'] ?? null);
            })
            ->values()
            ->all();
    }

    protected function extractStatus(string &$text, array &$filters): void
    {
        if (! preg_match('/\bstatus\s+(aktif|siswa|cuti|lolos)\b/', $text, $match)) {
            return;
        }

        $status = $match[1] === 'aktif' ? 'siswa' : $match[1];
        $filters[] = ['field' => 'status', 'operator' => '=', 'value' => $status];
        $text = str_replace($match[0], ' ', $text);
    }

    protected function extractGender(string &$text, array &$filters): void
    {
        if (preg_match('/\b(cowok|laki laki|laki-laki|pria|putra|lk)\b/', $text, $match)) {
            $filters[] = ['field' => 'jenis_kelamin', 'operator' => '=', 'value' => 'L'];
            $text = str_replace($match[0], ' ', $text);
            return;
        }

        if (preg_match('/\b(cewek|perempuan|wanita|putri|pr)\b/', $text, $match)) {
            $filters[] = ['field' => 'jenis_kelamin', 'operator' => '=', 'value' => 'P'];
            $text = str_replace($match[0], ' ', $text);
        }
    }

    protected function extractAge(string &$text, array &$filters): void
    {
        if (preg_match('/\b(?:umur|umurnya|usia|berusia)\s+(?:antara\s+)?(\d{1,2})\s*(?:sampai|-|hingga)\s*(\d{1,2})(?:\s*tahun)?/', $text, $match)) {
            $filters[] = ['field' => 'umur', 'operator' => 'between', 'value' => [(int) $match[1], (int) $match[2]]];
            $text = str_replace($match[0], ' ', $text);
            return;
        }

        $patterns = [
            '/\b(?:umur|umurnya|usia|berusia)\s*(?:minimal|min|paling sedikit|>=|lebih dari sama dengan)\s*(\d{1,2})(?:\s*tahun)?/' => '>=',
            '/\b(?:umur|umurnya|usia|berusia)\s*(\d{1,2})(?:\s*tahun)?\s*(?:ke atas|atau lebih)/' => '>=',
            '/\b(?:umur|umurnya|usia|berusia)\s*(?:maksimal|max|paling banyak|<=|kurang dari sama dengan)\s*(\d{1,2})(?:\s*tahun)?/' => '<=',
            '/\b(?:umur|umurnya|usia|berusia)\s*(?:di bawah|dibawah|kurang dari|<)\s*(\d{1,2})(?:\s*tahun)?/' => '<',
            '/\b(?:umur|umurnya|usia|berusia)\s*(?:di atas|diatas|lebih dari|>)\s*(\d{1,2})(?:\s*tahun)?/' => '>',
            '/\b(?:umur|umurnya|usia|berusia)\s*(\d{1,2})(?:\s*tahun)?/' => '=',
        ];

        foreach ($patterns as $pattern => $operator) {
            if (! preg_match($pattern, $text, $match)) {
                continue;
            }

            $filters[] = ['field' => 'umur', 'operator' => $operator, 'value' => (int) $match[1]];
            $text = str_replace($match[0], ' ', $text);
            return;
        }
    }

    protected function extractBodyMeasurement(string &$text, array &$filters, string $field, string $labelPattern): void
    {
        if (preg_match("/(?:{$labelPattern})\s+(\d{2,3})\s*(?:sampai|-|hingga)\s*(\d{2,3})/", $text, $match)) {
            $filters[] = ['field' => $field, 'operator' => 'between', 'value' => [(int) $match[1], (int) $match[2]]];
            $text = str_replace($match[0], ' ', $text);
            return;
        }

        $patterns = [
            "/(?:{$labelPattern})\s*(?:minimal|min|paling sedikit|>=|lebih dari sama dengan)\s*(\d{2,3})/" => '>=',
            "/(?:{$labelPattern})\s*(?:maksimal|max|paling banyak|<=|kurang dari sama dengan)\s*(\d{2,3})/" => '<=',
            "/(?:{$labelPattern})\s*(?:lebih dari|di atas|>)\s*(\d{2,3})/" => '>',
            "/(?:{$labelPattern})\s*(?:kurang dari|di bawah|<)\s*(\d{2,3})/" => '<',
            "/(?:{$labelPattern})\s*(\d{2,3})/" => '=',
        ];

        foreach ($patterns as $pattern => $operator) {
            if (! preg_match($pattern, $text, $match)) {
                continue;
            }

            $filters[] = ['field' => $field, 'operator' => $operator, 'value' => (int) $match[1]];
            $text = str_replace($match[0], ' ', $text);
            return;
        }
    }

    protected function extractAddressParts(string &$text, array &$filters): void
    {
        foreach (['desa', 'kecamatan', 'kabupaten', 'provinsi'] as $field) {
            if (! preg_match("/\b{$field}\s+(.+?)(?=\s+(?:desa|kecamatan|kabupaten|provinsi|status|kelas|tinggi|berat|umur|usia|umurnya|berusia|cowok|cewek|laki|perempuan)\b|$)/", $text, $match)) {
                continue;
            }

            $value = $this->cleanValue($match[1]);
            if ($value !== '') {
                $filters[] = ['field' => $field, 'operator' => 'contains', 'value' => Str::title($value)];
            }

            $text = str_replace($match[0], ' ', $text);
        }

        if (preg_match('/\b(?:tinggal di|tinggal|rumahnya di|rumah di|rumah|alamatnya di|alamat di|alamat|domisili di|domisili|dari|asal dari|asal|di)\s+(.+?)(?=\s+(?:status|kelas|tinggi|berat|umur|usia|umurnya|berusia|cowok|cewek|laki|perempuan)\b|$)/', $text, $match)) {
            $value = $this->cleanValue($match[1]);
            if ($value !== '') {
                $filters[] = ['field' => 'alamat', 'operator' => 'contains', 'value' => Str::title($value)];
            }

            $text = str_replace($match[0], ' ', $text);
        }
    }

    protected function extractClass(string &$text, array &$filters): void
    {
        if (! preg_match('/\bkelas\s+(.+?)(?=\s+(?:status|tinggi|berat|umur|usia|umurnya|berusia|cowok|cewek|laki|perempuan|desa|kecamatan|kabupaten|provinsi)\b|$)/', $text, $match)) {
            return;
        }

        $value = $this->cleanValue($match[1]);
        if ($value !== '') {
            $filters[] = ['field' => 'kelas', 'operator' => 'contains', 'value' => Str::title($value)];
        }

        $text = str_replace($match[0], ' ', $text);
    }

    protected function extractNameKeyword(string $text, array $filters): ?string
    {
        $hasNaturalSignal = collect($filters)->isNotEmpty()
            || Str::contains($text, ['tinggal', 'rumah', 'alamat', 'domisili', 'status', 'kelas', 'desa', 'kecamatan', 'kabupaten', 'provinsi', 'umur', 'usia', 'umurnya', 'berusia']);

        if (! $hasNaturalSignal) {
            return null;
        }

        $tokens = collect(explode(' ', $this->normalize($text)))
            ->reject(fn (string $token) => $token === '' || in_array($token, self::STOP_WORDS, true))
            ->values();

        if ($tokens->isEmpty()) {
            return null;
        }

        return Str::title($tokens->implode(' '));
    }

    protected function summarize(array $filters): string
    {
        $parts = collect($filters)->map(function (array $filter) {
            $label = $this->label($filter['field']);
            $value = $this->displayValue($filter['field'], $filter['value']);

            return match ($filter['operator']) {
                'contains' => "{$label} memuat {$value}",
                'between' => "{$label} {$value}",
                default => "{$label} {$filter['operator']} {$value}",
            };
        });

        return 'Menampilkan siswa dengan '.$parts->implode(', ');
    }

    protected function label(string $field): string
    {
        return [
            'nama' => 'nama',
            'nis' => 'NIS',
            'jenis_kelamin' => 'jenis kelamin',
            'tinggi_badan' => 'tinggi badan',
            'berat_badan' => 'berat badan',
            'alamat' => 'alamat',
            'desa' => 'desa',
            'kecamatan' => 'kecamatan',
            'kabupaten' => 'kabupaten',
            'provinsi' => 'provinsi',
            'status' => 'status',
            'kelas' => 'kelas',
            'id_kelas' => 'ID kelas',
            'tanggal_lahir' => 'tanggal lahir',
            'umur' => 'umur',
        ][$field] ?? $field;
    }

    protected function displayValue(string $field, mixed $value): string
    {
        if (is_array($value)) {
            return implode(' sampai ', $value);
        }

        if ($field === 'jenis_kelamin') {
            return $value === 'L' ? 'laki-laki' : 'perempuan';
        }

        if ($field === 'status' && $value === 'siswa') {
            return 'aktif';
        }

        return (string) $value;
    }

    protected function applyAgeWhere(Builder $query, string $operator, mixed $value): void
    {
        $today = Carbon::today();

        match ($operator) {
            '=' => $query->whereBetween('tgl_lahir', $this->birthDateRangeForExactAge((int) $value, $today)),
            'between' => $query->whereBetween('tgl_lahir', $this->birthDateRangeForAgeBetween((int) $value[0], (int) $value[1], $today)),
            '>=' => $query->where('tgl_lahir', '<=', $today->copy()->subYears((int) $value)->endOfDay()->toDateTimeString()),
            '>' => $query->where('tgl_lahir', '<', $today->copy()->subYears(((int) $value) + 1)->addDay()->startOfDay()->toDateTimeString()),
            '<=' => $query->where('tgl_lahir', '>=', $today->copy()->subYears(((int) $value) + 1)->addDay()->startOfDay()->toDateTimeString()),
            '<' => $query->where('tgl_lahir', '>', $today->copy()->subYears((int) $value)->endOfDay()->toDateTimeString()),
            default => null,
        };
    }

    protected function birthDateRangeForExactAge(int $age, CarbonInterface $today): array
    {
        return [
            $today->copy()->subYears($age + 1)->addDay()->startOfDay()->toDateTimeString(),
            $today->copy()->subYears($age)->endOfDay()->toDateTimeString(),
        ];
    }

    protected function birthDateRangeForAgeBetween(int $youngestAge, int $oldestAge, CarbonInterface $today): array
    {
        if ($youngestAge > $oldestAge) {
            [$youngestAge, $oldestAge] = [$oldestAge, $youngestAge];
        }

        return [
            $today->copy()->subYears($oldestAge + 1)->addDay()->startOfDay()->toDateTimeString(),
            $today->copy()->subYears($youngestAge)->endOfDay()->toDateTimeString(),
        ];
    }

    protected function validAgeValue(string $operator, mixed $value): bool
    {
        if ($operator === 'between') {
            return is_array($value)
                && count($value) === 2
                && $this->validAgeNumber($value[0])
                && $this->validAgeNumber($value[1]);
        }

        return $this->validAgeNumber($value);
    }

    protected function validAgeNumber(mixed $value): bool
    {
        return is_int($value) && $value >= 0 && $value <= 120;
    }

    protected function normalize(string $value): string
    {
        $value = Str::of($value)->lower()->ascii()->replaceMatches('/[^\pL\pN\s<>=-]+/u', ' ')->toString();

        return trim(preg_replace('/\s+/', ' ', $value) ?? '');
    }

    protected function cleanValue(string $value): string
    {
        $value = $this->correctKnownTypos($this->normalize($value));
        $tokens = collect(explode(' ', $value))
            ->reject(fn (string $token) => in_array($token, ['yang', 'ada', 'di', 'ke'], true))
            ->values();

        return trim($tokens->implode(' '));
    }

    protected function correctKnownTypos(string $value): string
    {
        $corrections = [
            'semarng' => 'semarang',
            'semrang' => 'semarang',
            'smarang' => 'semarang',
        ];

        foreach ($corrections as $typo => $correction) {
            $value = preg_replace("/\b{$typo}\b/", $correction, $value) ?? $value;
        }

        return $value;
    }

    protected function emptyResult(): array
    {
        return [
            'intent' => 'search_students',
            'mode' => 'empty',
            'keyword' => null,
            'filters' => [],
            'summary' => null,
            'notices' => [],
        ];
    }
}
