<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\MedicalReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MedicalReportController extends Controller
{
    public function patientIndex(): View
    {
        $patient = $this->authUser()->patient;
        $reports = MedicalReport::where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('patient.medical-reports.reports', compact('reports'));
    }

    public function upload(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240',
            'description' => 'nullable|string|max:500',
        ]);

        $patient = $this->authUser()->patient;

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $filePath = $file->store('medical-reports/' . $patient->id, 'public');

        MedicalReport::create([
            'patient_id' => $patient->id,
            'file_path' => $filePath,
            'original_name' => $originalName,
            'description' => $validated['description'],
        ]);

        return back()->with('success', 'تم رفع التقرير الطبي بنجاح');
    }

    public function download(MedicalReport $report): \Symfony\Component\HttpFoundation\StreamedResponse|RedirectResponse
    {
        if ($report->patient_id !== $this->authUser()->patient->id) {
            return back()->with('error', 'لا يمكنك الوصول إلى هذا الملف');
        }

        if (!Storage::disk('public')->exists($report->file_path)) {
            return back()->with('error', 'الملف غير موجود');
        }

        return response()->streamDownload(
            fn () => print(Storage::disk('public')->get($report->file_path)),
            $report->original_name,
            ['Content-Type' => 'application/octet-stream']
        );
    }

    public function doctorDownload(MedicalReport $report): \Symfony\Component\HttpFoundation\StreamedResponse|RedirectResponse
    {
        $doctor = $this->authUser()->doctor;

        $isDoctorPatient = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $report->patient_id)
            ->exists();

        if (!$isDoctorPatient) {
            return back()->with('error', 'لا يمكنك الوصول إلى هذا الملف');
        }

        if (!Storage::disk('public')->exists($report->file_path)) {
            return back()->with('error', 'الملف غير موجود');
        }

        return response()->streamDownload(
            fn () => print(Storage::disk('public')->get($report->file_path)),
            $report->original_name,
            ['Content-Type' => 'application/octet-stream']
        );
    }

    public function destroy(MedicalReport $report): RedirectResponse
    {
        if ($report->patient_id !== $this->authUser()->patient->id) {
            return back()->with('error', 'لا يمكنك حذف هذا الملف');
        }

        Storage::disk('public')->delete($report->file_path);
        $report->delete();

        return back()->with('success', 'تم حذف التقرير الطبي');
    }
}
