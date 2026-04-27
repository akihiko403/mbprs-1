<?php

namespace App\Http\Controllers;

use App\Models\BuildingCategory;
use App\Models\BuildingPermit;
use App\Models\SystemSetting;
use Illuminate\Support\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfCannotAccess('reports')) {
            return $redirect;
        }

        $filters = $request->only(['search', 'month', 'year', 'status', 'building_category_id']);

        return view('reports.index', [
            'title' => 'Reports',
            'subtitle' => 'Generate filtered permit reports and export records for monitoring and filing.',
            'records' => $this->reportRecords($filters),
            'filters' => $filters,
            'buildingCategories' => BuildingCategory::query()->orderBy('name')->get(),
            'months' => $this->reportMonths(),
            'years' => $this->reportYears(),
        ]);
    }

    public function print(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->redirectIfCannotAccess('reports')) {
            return $redirect;
        }

        $filters = $request->only(['search', 'month', 'year', 'status', 'building_category_id']);
        $systemSettings = SystemSetting::current();

        return view('reports.print', [
            'records' => $this->reportRecords($filters),
            'reportHeading' => 'Municipal Building Permits',
            'reportSubheading' => $systemSettings->system_subheader ?? 'Municipality of Lebak',
            'reportLogoUrl' => $systemSettings->system_logo_path
                ? asset('storage/'.$systemSettings->system_logo_path).'?v='.$systemSettings->updated_at?->timestamp
                : null,
        ]);
    }

    public function export(Request $request): BinaryFileResponse|RedirectResponse
    {
        if ($redirect = $this->redirectIfCannotAccess('reports')) {
            return $redirect;
        }

        $filters = $request->only(['search', 'month', 'year', 'status', 'building_category_id']);
        $records = $this->reportRecords($filters);
        $systemSettings = SystemSetting::current();
        $filePath = $this->buildReportWorkbookWithExcel(
            $records,
            'Municipal Building Permits',
            $systemSettings->system_subheader ?? 'Municipality of Lebak',
            $this->reportLogoFile($systemSettings),
        );

        return response()->download(
            $filePath,
            'report-'.now()->format('YmdHis').'.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    private function reportRecords(array $filters)
    {
        return $this->reportQuery($filters)
            ->with(['buildingType', 'buildingCategory', 'approver'])
            ->latest()
            ->get();
    }

    private function reportQuery(array $filters)
    {
        return BuildingPermit::query()->filter($filters);
    }

    private function reportMonths(): array
    {
        return [
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
    }

    private function reportYears(): array
    {
        $currentYear = (int) now()->format('Y');

        return array_combine(
            array_map('strval', range($currentYear, 2000)),
            array_map('strval', range($currentYear, 2000)),
        );
    }

    private function reportLogoFile(SystemSetting $systemSettings): ?array
    {
        if (! $systemSettings->system_logo_path || ! Storage::disk('public')->exists($systemSettings->system_logo_path)) {
            return null;
        }

        $absolutePath = public_path('storage/'.ltrim($systemSettings->system_logo_path, '/\\'));
        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
        $mimeType = Storage::disk('public')->mimeType($systemSettings->system_logo_path);

        if (! is_file($absolutePath) || ! in_array($extension, ['png', 'jpg', 'jpeg', 'gif'], true)) {
            return null;
        }

        return [
            'path' => $absolutePath,
            'extension' => $extension === 'jpg' ? 'jpeg' : $extension,
            'mime' => $mimeType ?: 'image/png',
        ];
    }

    private function buildReportWorkbookWithExcel(Collection $records, string $title, string $subtitle, ?array $logoFile): string
    {
        require_once app_path('Support/ZipArchivePolyfill.php');
        require_once base_path('vendor/mk-j/php_xlsxwriter/xlsxwriter.class.php');

        $tempDirectory = storage_path('app/temp');
        $outputPath = $tempDirectory.'/report-export-'.uniqid('', true).'.xlsx';

        if (! is_dir($tempDirectory)) {
            mkdir($tempDirectory, 0777, true);
        }

        $writer = new \XLSXWriter();
        $writer->setAuthor('MBPRS');
        $writer->setTempDir($tempDirectory);

        $sheetName = 'Reports';
        $headers = [
            'A' => 'string',
            'B' => 'string',
            'C' => 'string',
            'D' => 'string',
            'E' => 'string',
            'F' => 'string',
            'G' => 'string',
            'H' => 'string',
            'I' => 'string',
        ];

        $writer->writeSheetHeader($sheetName, $headers, true);
        $writer->writeSheetRow($sheetName, ['', $title, '', '', '', '', '', '', ''], [
            ['font' => 'Arial', 'font-size' => 16, 'font-style' => 'bold', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 16, 'font-style' => 'bold', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 16, 'font-style' => 'bold', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 16, 'font-style' => 'bold', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 16, 'font-style' => 'bold', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 16, 'font-style' => 'bold', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 16, 'font-style' => 'bold', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 16, 'font-style' => 'bold', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 16, 'font-style' => 'bold', 'halign' => 'center'],
        ]);
        $writer->writeSheetRow($sheetName, ['', $subtitle, '', '', '', '', '', '', ''], [
            ['font' => 'Arial', 'font-size' => 11, 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'halign' => 'center'],
        ]);
        $writer->writeSheetRow($sheetName, ['', '', '', '', '', '', '', '', '']);

        $writer->writeSheetRow($sheetName, ['Permit ID', 'Owner', 'Building Type', 'Building Category', 'Barangay', 'City/Municipality', 'Province', 'Status', 'Created'], [
            ['font' => 'Arial', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eff3f2', 'border' => 'left,right,top,bottom', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eff3f2', 'border' => 'left,right,top,bottom', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eff3f2', 'border' => 'left,right,top,bottom', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eff3f2', 'border' => 'left,right,top,bottom', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eff3f2', 'border' => 'left,right,top,bottom', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eff3f2', 'border' => 'left,right,top,bottom', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eff3f2', 'border' => 'left,right,top,bottom', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eff3f2', 'border' => 'left,right,top,bottom', 'halign' => 'center'],
            ['font' => 'Arial', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eff3f2', 'border' => 'left,right,top,bottom', 'halign' => 'center'],
        ]);

        if ($records->isEmpty()) {
            $writer->writeSheetRow($sheetName, ['No records matched the selected report filters.', '', '', '', '', '', '', '', ''], [
                ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom'],
                ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom'],
                ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom'],
                ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom'],
                ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom'],
                ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom'],
                ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom'],
                ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom'],
                ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom'],
            ]);
            $writer->markMergedCell($sheetName, 4, 0, 4, 8);
        } else {
            foreach ($records as $record) {
                $writer->writeSheetRow($sheetName, [
                    $record->permit_id,
                    $record->owner_full_name,
                    $record->buildingType?->name ?? '',
                    $record->buildingCategory?->name ?? '',
                    $record->barangay ?? '',
                    $record->city_municipality ?? '',
                    $record->province ?? '',
                    $record->status ?? '',
                    $record->created_at?->format('j-M-y') ?? '',
                ], [
                    ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom', 'valign' => 'center'],
                    ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom', 'valign' => 'center'],
                    ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom', 'valign' => 'center'],
                    ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom', 'valign' => 'center'],
                    ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom', 'valign' => 'center'],
                    ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom', 'valign' => 'center'],
                    ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom', 'valign' => 'center'],
                    ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom', 'valign' => 'center'],
                    ['font' => 'Arial', 'font-size' => 11, 'border' => 'left,right,top,bottom', 'valign' => 'center'],
                ]);
            }
        }

        $writer->markMergedCell($sheetName, 0, 1, 0, 8);
        $writer->markMergedCell($sheetName, 1, 1, 1, 8);
        $writer->writeToFile($outputPath);

        if (! file_exists($outputPath)) {
            throw new \RuntimeException('Excel export failed: workbook file was not created.');
        }

        return $outputPath;
    }
}
