@extends('layouts.main')

@section('title', 'Edit Jadwal')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Jadwal</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form id="myForm" action="{{ route('updateJadwal', $jadwal->id) }}" method="post" 
                        enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                    <div class="form-group row">
                                        <label for="nip" class="col-sm-2 col-form-label">Nama Dosen:</label>
                                        <div class="col-sm-10">
                                        <select class="form-control" id="nip" name="nip" required>
                                            <option disabled value="">Pilih Nama Dosen</option>
                                            @foreach ($dosen as $d)
                                                <option value="{{ $d->nip }}"
                                                    {{ $d->nip == $jadwal->nip ? 'selected' : '' }}>{{ $d->nama_dosen }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    </div>
                                    <div class="form-group row">
                                    <label for="Tempat" class="col-sm-2 col-form-label">Matkul:</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="jadwal" name="jadwal"
                                        value="{{ $jadwal->jadwal }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tanggal" class="col-sm-2 col-form-label">Tanggal:</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="tanggal" name="tanggal"
                                            value="{{ $jadwal->tanggal ?? date('Y-m-d') }}" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="waktu" class="col-sm-2 col-form-label">Waktu:</label>
                                    <div class="col-sm-5">
                                        <input type="time" class="form-control" id="waktu_mulai" name="waktu_mulai"
                                            value="{{ $jadwal->waktu_mulai }}" required>
                                    </div>
                                    <div class="col-sm-5">
                                        <input type="time" class="form-control" id="waktu_selesai" name="waktu_selesai"
                                            value="{{ $jadwal->waktu_selesai }}" required>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
