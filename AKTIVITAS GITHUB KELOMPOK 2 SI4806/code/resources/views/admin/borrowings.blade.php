@extends('layout')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
    <h1>Daftar Peminjaman Buku</h1>
</div>

@if(session('success'))
    <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
        {{ session('error') }}
    </div>
@endif

<div style="overflow-x: auto;">
    <table border="1" style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead style="background-color: #f8f9fa;">
            <tr>
                <th style="padding: 10px;">Peminjam</th>
                <th style="padding: 10px;">Buku</th>
                <th style="padding: 10px;">Tanggal Request</th>
                <th style="padding: 10px;">Status</th>
                <th style="padding: 10px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($borrowings as $loan)
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px;">
                    <strong>{{ $loan->user->name }}</strong><br>
                    <small style="color: gray;">{{ $loan->user->email }}</small>
                </td>
                <td style="padding: 10px;">{{ $loan->book->title }}</td>
                <td style="padding: 10px;">{{ \Carbon\Carbon::parse($loan->borrowed_at)->format('d M Y') }}</td>
                <td style="padding: 10px;">
                    @php
                        $colors = [
                            'pending' => ['bg' => '#ffc107', 'text' => '#000'],
                            'dipinjam' => ['bg' => '#17a2b8', 'text' => '#fff'],
                            'dikembalikan' => ['bg' => '#28a745', 'text' => '#fff'],
                            'ditolak' => ['bg' => '#dc3545', 'text' => '#fff'],
                        ];
                        $statusColor = $colors[$loan->status] ?? ['bg' => '#ccc', 'text' => '#000'];
                    @endphp
                    <span style="background-color: {{ $statusColor['bg'] }}; color: {{ $statusColor['text'] }}; padding: 5px 10px; border-radius: 15px; font-size: 0.8em; font-weight: bold;">
                        {{ strtoupper($loan->status) }}
                    </span>
                </td>
                <td style="padding: 10px; text-align: center;">
                    @if($loan->status == 'pending')
                        <div style="display: flex; gap: 5px; justify-content: center;">
                            {{-- Tombol Setuju --}}
                            <form action="{{ route('admin.borrowings.approve', $loan->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" style="background: #28a745; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                                    ✓ Setuju
                                </button>
                            </form>

                            {{-- Tombol Tolak --}}
                            <form action="{{ route('admin.borrowings.reject', $loan->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                                    ✗ Tolak
                                </button>
                            </form>
                        </div>
                    @else
                        <span style="color: gray; font-style: italic; font-size: 0.9em;">Selesai</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; padding: 20px;">Belum ada data peminjaman.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $borrowings->links() }}
    </div>
</div>
@endsection