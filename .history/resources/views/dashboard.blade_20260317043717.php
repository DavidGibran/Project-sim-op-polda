@extends('layouts.app')

@section('content')
  <!-- Dashboard Header -->
  <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <h2 class="text-title-md2 font-bold text-black dark:text-white">
      Dashboard
    </h2>

    <div class="flex items-center gap-3">
      <a href="{{ route('kendaraan.import') }}" 
         class="inline-flex items-center justify-center gap-2.5 rounded-lg bg-primary py-3 px-6 text-center font-medium text-white dark:text-black hover:bg-opacity-90 lg:px-8 xl:px-10">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
          <polyline points="17 8 12 3 7 8"></polyline>
          <line x1="12" y1="3" x2="12" y2="15"></line>
        </svg>
        Import Kendaraan
      </a>
    </div>
  </div>

  <div class="grid grid-cols-12 gap-4 md:gap-6">
    <!-- Row 1: Fleet Metrics (Full Width.) -->
    <div class="col-span-12">
      <x-dashboard.fleet-metrics 
        :totalKendaraan="$totalKendaraan"
        :kendaraanAktif="$kendaraanAktif"
        :kendaraanPerbaikan="$kendaraanPerbaikan"
        :penugasanAktif="$penugasanAktif"
        :totalR2="$totalR2"
        :totalR4="$totalR4"
        :siapDipakai="$siapDipakai"
        :sedangTugas="$sedangTugas"
        :perbaikanTerbaru="$perbaikanTerbaru"
      />
    </div>

    <!-- Row 2: Charts (Trend + Status) -->
    <!-- Left: Trend Chart (8 Columns) -->
    <div class="col-span-12 xl:col-span-8">
        <x-dashboard.fleet-trend-chart :trendData="$trendData" />
    </div>

    <!-- Right: Unused Vehicles Panel (4 Columns) -->
    <div class="col-span-12 xl:col-span-4">
        <x-dashboard.unused-vehicles 
            :kendaraanTidakDigunakan="$kendaraanTidakDigunakan"
        />
    </div>

    <!-- Row 3: Recent Assignments (Full Width) -->
    <div class="col-span-12">
        <x-dashboard.recent-assignments 
            :penugasanTerbaru="$penugasanTerbaru"
        />
    </div>

    <!-- Row 4: Recent Repairs (Full Width) -->
    <div class="col-span-12">
        <x-dashboard.recent-repairs 
            :perbaikanTerbaru="$perbaikanTerbaru"
        />
    </div>
  </div>
@endsection
