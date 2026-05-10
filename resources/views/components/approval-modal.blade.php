@props(['req'])

<!-- Modal Approve -->
<div class="modal fade" id="approveModal-{{ $req->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('asset-requests.approve', $req->id) }}" method="POST">
            @csrf @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Persetujuan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin menyetujui pengajuan <b>{{ $req->item_name }}</b> ({{ $req->quantity }} unit)?<br>
                    Barang akan otomatis ditambahkan ke Master Aset.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Setujui</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div class="modal fade" id="rejectModal-{{ $req->id }}" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('asset-requests.reject', $req->id) }}" method="POST">
            @csrf @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tolak Pengajuan</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Alasan Penolakan <span class="text-danger">*</span></label>
                        <textarea name="reject_reason" class="form-control" required placeholder="Tuliskan alasan mengapa ditolak..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Pengajuan</button>
                </div>
            </div>
        </form>
    </div>
</div>
