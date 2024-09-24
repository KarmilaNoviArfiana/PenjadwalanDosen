<?php

namespace App\Http\Controllers;

use App\Helpers\JadwalService;
use Illuminate\Http\Request;
use App\Models\Dosen;
use App\Models\Jadwal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $dosen = $user->dosen;

        if ($dosen) {
            $dtJadwal = Jadwal::where('nip', $dosen->nip);
        } else {
            $dtJadwal = Jadwal::query();
        }

        if ($nip = $request->nip) {
            $dtJadwal->where('nip', $nip);
        }

        if ($search = $request->search) {
            $dtJadwal->whereHas('dosen', function ($query) use ($search) {
                $query->where('nama_dosen', 'like', "%$search%");
            });
        }

        $dtJadwal = $dtJadwal->orderBy('tanggal', 'desc')->paginate(5);
       
        if (!($dtJadwal instanceof \Illuminate\Pagination\LengthAwarePaginator)) {
            return redirect()->back()->with('error', 'Failed to fetch data.');
        }

        $dosen = Dosen::all();

        return view('jadwal', compact('dtJadwal', 'dosen', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $dosen = Dosen::all();
        return view('tambahJadwal', compact('dosen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $startDate = Carbon::parse($request->tanggal); // Mulai dari tanggal yang diberikan
        $endDate = $startDate->copy()->addMonths(6); // Akhirnya 6 bulan dari tanggal mulai

        // Loop setiap minggu sampai akhir tanggal
        DB::beginTransaction();

        while ($startDate->lessThanOrEqualTo($endDate)) {
            $punyaJadwalLain = JadwalService::checkTabrakan($request->nip, $startDate->format('Y-m-d'), $request->waktu_mulai, $request->waktu_selesai);
            if ($punyaJadwalLain) {
                DB::rollBack();
                return redirect()->back()->withInput()->with('error', 'Jadwal tabrakan pada tanggal ' . $startDate->format('d F Y'));
            }

            Jadwal::create([
                'nip' => $request->nip,
                'jadwal' => $request->jadwal,
                'tanggal' => $startDate->format('Y-m-d'), // Format tanggal sesuai kebutuhan
                'waktu_mulai' => $request->waktu_mulai,
                'waktu_selesai' => $request->waktu_selesai,
            ]);

            // Tambah satu minggu ke tanggal mulai
            $startDate->addWeek();
        }

        DB::commit();

        return redirect('jadwal')->with('success', 'Jadwal berhasil ditambahkan selama 6 bulan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $dosen = Dosen::all();

        return view('editJadwal', compact('jadwal', 'dosen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $jadwal->update([
            'nip' => $request->nip,
            'jadwal' => $request->jadwal,
            'tanggal' => $request->tanggal,
            'waktu_mulai' => $request->waktu_mulai,
            'waktu_selesai' => $request->waktu_selesai,
        ]);

        return redirect('jadwal');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect('jadwal');
    }

    public function search(Request $request)
    {
        $search = $request->search;

        $dtDosen = Dosen::where('nama', 'like', '%' . $search . '%')->get();
        $nipDosen = $dtDosen->pluck('nip')->toArray();

        $dtJadwal = Jadwal::whereIn('nip', $nipDosen)->paginate(5);

        return view('jadwal', compact('dtJadwal', 'dtDosen'));
    }
}
