<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ReservationRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            // Step 1: Akun
            'nama_lengkap' => 'required|string',
            'tempat_lahir' => 'required|string',
            // tanggal_lahir must be a valid date and not in the future
            'tanggal_lahir' => 'required|date|before_or_equal:today',
            'jenis_kelamin' => 'required|in:L,P',

            // Step 2: Reservasi
            'cara_bayar' => 'required|in:UMUM,BPJS',
            'poli_id' => 'required|exists:clinics,id',
            'dokter_id' => 'required|exists:doctors,id',
            // allow selecting today as a valid reservation date
            'tanggal_reservasi' => 'required|date|after_or_equal:today',
            'no_bpjs' => 'required_if:cara_bayar,BPJS',
            'no_rujukan' => 'required_if:cara_bayar,BPJS',

            // Step 3: Data Diri
            'telepon' => 'required|string',
            'pendidikan' => 'required|string',
            'status' => 'required|string',
            'pekerjaan' => 'required|string',
            'agama' => 'required|string',
            'email' => 'nullable|email',
            'golongan_darah' => 'required|string',
            'kewarganegaraan' => 'required|string',
            'bahasa' => 'required',
            'suku' => 'required|string',

            // Step 4: Alamat
            'provinsi' => 'required|string',
            'kabupaten' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'alamat' => 'required|string',
            'rt' => 'required|string',
            'rw' => 'required|string',

            // Captcha
            'captcha' => 'required|captcha',
        ];

        // Conditional rule: require KTP only if age >= 18
        try {
            $dob = $this->input('tanggal_lahir');
            if ($dob) {
                $age = \Carbon\Carbon::parse($dob)->age;
            } else {
                $age = null;
            }
        } catch (\Throwable $e) {
            $age = null;
        }

        if ($age !== null && $age >= 18) {
            $rules['no_ktp'] = 'required|digits:16|unique:patients,no_ktp';
        } else {
            $rules['no_ktp'] = 'nullable|digits:16|unique:patients,no_ktp';
        }

        $hasKtp = !empty($this->input('no_ktp'));
        if ($hasKtp || ($age !== null && $age >= 18)) {
            $rules['email'] = 'required|email|unique:patients,email';
        } else {
            $rules['email'] = 'nullable|email|unique:patients,email';
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        // Sanitize BPJS fields: some JS widgets may submit arrays for these inputs.
        try {
            $input = $this->all();
            if (isset($input['no_bpjs']) && is_array($input['no_bpjs'])) {
                $input['no_bpjs'] = implode(',', $input['no_bpjs']);
            }
            if (isset($input['no_rujukan']) && is_array($input['no_rujukan'])) {
                $input['no_rujukan'] = implode(',', $input['no_rujukan']);
            }

            // Ensure cara_bayar comes through as scalar (select2 can return arrays)
            if (isset($input['cara_bayar']) && is_array($input['cara_bayar'])) {
                $input['cara_bayar'] = count($input['cara_bayar']) ? (string)$input['cara_bayar'][0] : null;
            }

            // Map frontend field `bahasa_keseharian` (used in the view) to the
            // request field `bahasa` expected by validation and model.
            if (isset($input['bahasa_keseharian']) && !isset($input['bahasa'])) {
                $input['bahasa'] = $input['bahasa_keseharian'];
            }

            $this->merge($input);
            // Temporary debug logging: record types/values for problematic fields to diagnose validation errors
            try {
                if (config('app.debug')) {
                    $debug = [
                        'cara_bayar' => isset($input['cara_bayar']) ? gettype($input['cara_bayar']) : null,
                        'no_bpjs' => isset($input['no_bpjs']) ? (is_array($input['no_bpjs']) ? 'array('.count($input['no_bpjs']).')' : (string)$input['no_bpjs']) : null,
                        'no_rujukan' => isset($input['no_rujukan']) ? (is_array($input['no_rujukan']) ? 'array('.count($input['no_rujukan']).')' : (string)$input['no_rujukan']) : null,
                        'all_keys' => array_keys($input),
                    ];
                    Log::debug('ReservationRequest.prepareForValidation debug', $debug);
                }
            } catch (\Exception $e) {
                // ignore logging errors
            }
        } catch (\Exception $e) {
            // ignore sanitization failures
        }
    }

    public function messages()
    {
        return [
            'captcha.*' => 'Kode captcha salah, silakan coba lagi.',
            'no_bpjs.required_if' => 'No Kartu BPJS wajib diisi jika cara bayar BPJS.',
            'no_rujukan.required_if' => 'No Rujukan wajib diisi jika cara bayar BPJS.',
            'bahasa.required' => 'Field bahasa harus dipilih.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            'tanggal_reservasi.after_or_equal' => 'Tanggal reservasi harus sama dengan atau setelah hari ini.',
        ];
    }
}
